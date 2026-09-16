<?php
/**
 * Instalador web do Naruto MMORPG.
 *
 * Abra http://SEU_HOST/install/ , preencha os dados do MySQL e ele cria o
 * banco, importa as 76 tabelas de install/schema.sql, cria a conta de
 * administrador e grava o .env.
 *
 * Depois de instalar ele se tranca sozinho (_cache/installed.lock). Para
 * reabrir: apague a trava, ou defina INSTALLER_ENABLED=true no ambiente.
 */

require_once __DIR__ . '/lib.php';

@ini_set('display_errors', '1');
error_reporting(E_ALL);
@set_time_limit(300);
naruto_mysqli_quiet();
session_start();

// O .env pode nao existir ainda; carrega so se der, para pre-preencher o form.
if (is_file(NARUTO_ROOT . '/vendor/autoload.php')) {
    require_once NARUTO_ROOT . '/vendor/autoload.php';
    if (class_exists('Dotenv\Dotenv')) {
        try {
            Dotenv\Dotenv::createImmutable(NARUTO_ROOT)->safeLoad();
        } catch (Throwable $e) {
            // .env malformado nao pode impedir a reinstalacao
        }
    }
}

function h($s) {
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

function post($k, $default = '') {
    return isset($_POST[$k]) ? trim((string)$_POST[$k]) : $default;
}

if (empty($_SESSION['install_csrf'])) {
    $_SESSION['install_csrf'] = bin2hex(random_bytes(32));
}

// ---------------------------------------------------------------------------
// Portao de entrada
// ---------------------------------------------------------------------------
$bloqueado = null;
if (!naruto_token_ok()) {
    $bloqueado = 'Token invalido. Este instalador exige ?token=... porque a variavel INSTALL_TOKEN esta definida no ambiente.';
} elseif (naruto_is_locked()) {
    $bloqueado = 'O jogo ja foi instalado. Para reinstalar, apague o arquivo ' . h(naruto_lock_file())
        . ' ou defina INSTALLER_ENABLED=true nas variaveis de ambiente.';
}

// ---------------------------------------------------------------------------
// Checagem de requisitos
// ---------------------------------------------------------------------------
function checar_requisitos() {
    $req = [];

    $req[] = [
        'nome'  => 'PHP 8.0 ou superior',
        'ok'    => version_compare(PHP_VERSION, '8.0.0', '>='),
        'valor' => PHP_VERSION,
    ];

    foreach (['mysqli' => 'obrigatoria', 'curl' => 'Turnstile e webhook', 'mbstring' => 'PHPMailer', 'openssl' => 'SMTP com TLS', 'json' => 'obrigatoria'] as $ext => $para) {
        $req[] = [
            'nome'  => "Extensao {$ext}",
            'ok'    => extension_loaded($ext),
            'valor' => extension_loaded($ext) ? 'carregada' : "faltando ({$para})",
        ];
    }

    $req[] = [
        'nome'  => 'Dependencias do Composer (vendor/)',
        'ok'    => is_file(NARUTO_ROOT . '/vendor/autoload.php'),
        'valor' => is_file(NARUTO_ROOT . '/vendor/autoload.php') ? 'presente' : 'rode: composer install',
    ];

    $req[] = [
        'nome'  => 'Schema install/schema.sql',
        'ok'    => is_readable(naruto_schema_file()),
        'valor' => is_readable(naruto_schema_file())
            ? number_format(filesize(naruto_schema_file()) / 1024, 0, ',', '.') . ' KB'
            : 'nao encontrado',
    ];

    foreach (['_cache', 'reports', 'uploads'] as $dir) {
        $caminho = NARUTO_ROOT . '/' . $dir;
        if (!is_dir($caminho)) {
            @mkdir($caminho, 0775, true);
        }
        $ok = is_dir($caminho) && is_writable($caminho);
        $req[] = [
            'nome'     => "Pasta {$dir}/ gravavel",
            'ok'       => $ok,
            'valor'    => $ok ? 'ok' : 'sem permissao de escrita',
            'opcional' => ($dir !== '_cache'),
        ];
    }

    $envOk = is_writable(NARUTO_ROOT) || is_writable(NARUTO_ROOT . '/.env');
    $req[] = [
        'nome'     => 'Gravar .env na raiz',
        'ok'       => $envOk,
        'valor'    => $envOk ? 'ok' : 'raiz somente leitura (use variaveis de ambiente)',
        'opcional' => true,
    ];

    return $req;
}

$requisitos = checar_requisitos();
$bloqueiaReq = false;
foreach ($requisitos as $r) {
    if (!$r['ok'] && empty($r['opcional'])) {
        $bloqueiaReq = true;
    }
}

// ---------------------------------------------------------------------------
// Execucao
// ---------------------------------------------------------------------------
$erros      = [];
$avisos     = [];
$sucesso    = false;
$resultado  = [];
$precisaWipe = false;
$envConteudo = null;
$envGravado  = false;

$valores = [
    'db_host'      => post('db_host', naruto_env('DB_HOST', 'localhost')),
    'db_port'      => post('db_port', naruto_env('DB_PORT', '3306')),
    'db_user'      => post('db_user', naruto_env('DB_USER', 'root')),
    'db_name'      => post('db_name', naruto_env('DB_NAME', 'naruto')),
    'admin_user'   => post('admin_user', 'admin'),
    'admin_email'  => post('admin_email', naruto_env('MAIL_FROM_ADDRESS', '')),
    'game_name'    => post('game_name', naruto_env('GAME_NAME', 'Fight')),
];

// Em GET os dois vem marcados; em POST preservam o que o usuario escolheu.
$ehPost      = ($_SERVER['REQUEST_METHOD'] === 'POST');
$criarBanco  = $ehPost ? isset($_POST['criar_banco']) : true;
$salvarEnv   = $ehPost ? isset($_POST['salvar_env'])  : true;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$bloqueado) {
    if (!hash_equals($_SESSION['install_csrf'], post('csrf'))) {
        $erros[] = 'Sessao expirada. Recarregue a pagina e tente de novo.';
    } elseif ($bloqueiaReq) {
        $erros[] = 'Corrija os requisitos obrigatorios antes de instalar.';
    } else {
        $dbHost  = $valores['db_host'];
        $dbPort  = (int)($valores['db_port'] ?: 3306);
        $dbUser  = $valores['db_user'];
        $dbPass  = (string)post('db_pass');
        $dbName  = $valores['db_name'];
        $admUser = $valores['admin_user'];
        $admMail = $valores['admin_email'];
        $admPass = (string)post('admin_pass');
        $admPass2 = (string)post('admin_pass2');

        if ($dbHost === '' || $dbUser === '' || $dbName === '') {
            $erros[] = 'Host, usuario e nome do banco sao obrigatorios.';
        }
        if (!preg_match('/^[A-Za-z0-9_]+$/', $dbName)) {
            $erros[] = 'O nome do banco so pode conter letras, numeros e underline.';
        }
        if ($admUser === '' || !preg_match('/^[A-Za-z0-9_]{3,15}$/', $admUser)) {
            $erros[] = 'O usuario admin deve ter de 3 a 15 caracteres (letras, numeros e underline).';
        }
        if (strlen($admPass) < 8) {
            $erros[] = 'A senha do admin precisa ter pelo menos 8 caracteres.';
        }
        if ($admPass !== $admPass2) {
            $erros[] = 'As duas senhas do admin nao conferem.';
        }
        if ($admMail === '' || !filter_var($admMail, FILTER_VALIDATE_EMAIL)) {
            $erros[] = 'Informe um e-mail valido para a conta de administrador.';
        }

        if (!$erros) {
            $link = @mysqli_connect($dbHost, $dbUser, $dbPass, '', $dbPort);
            if (!$link) {
                $erros[] = 'Nao consegui conectar no MySQL: ' . mysqli_connect_error();
            } else {
                $resultado['conexao'] = mysqli_get_server_info($link);

                $dbEscapado = str_replace('`', '', $dbName);
                if (isset($_POST['criar_banco'])) {
                    @mysqli_query($link, "CREATE DATABASE IF NOT EXISTS `{$dbEscapado}` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
                }

                if (!@mysqli_select_db($link, $dbName)) {
                    $erros[] = "O banco `{$dbName}` nao existe e nao consegui criar. "
                        . 'Crie manualmente ou marque "criar o banco se nao existir". Erro: ' . mysqli_error($link);
                } else {
                    $tabelas = naruto_list_tables($link);

                    if ($tabelas && !isset($_POST['confirm_wipe'])) {
                        $precisaWipe = true;
                        $erros[] = 'O banco `' . $dbName . '` ja tem ' . count($tabelas) . ' tabela(s). '
                            . 'O schema comeca com DROP TABLE em todas as 76 tabelas do jogo: importar aqui APAGA os dados existentes. '
                            . 'Marque a confirmacao abaixo se for isso mesmo que voce quer.';
                    } else {
                        mysqli_set_charset($link, 'utf8');
                        $importErros = [];
                        $ok = naruto_import_sql($link, naruto_schema_file(), $importErros);

                        $resultado['comandos'] = $ok;
                        $resultado['tabelas']  = count(naruto_list_tables($link));

                        if ($importErros) {
                            foreach (array_slice($importErros, 0, 10) as $e) {
                                $erros[] = 'SQL: ' . $e;
                            }
                            if (count($importErros) > 10) {
                                $erros[] = '... e mais ' . (count($importErros) - 10) . ' erro(s).';
                            }
                        }

                        if (!$erros) {
                            $admErro = null;
                            if (!naruto_create_admin($link, $admUser, $admPass, $admMail, $admErro)) {
                                $erros[] = 'Tabelas importadas, mas falhou ao criar o admin: ' . $admErro;
                            } else {
                                $resultado['admin'] = $admUser;
                                $sucesso = true;
                            }
                        }

                        if ($sucesso) {
                            $envConteudo = naruto_render_env([
                                'DB_HOST'              => $dbHost,
                                'DB_PORT'              => $dbPort,
                                'DB_USER'              => $dbUser,
                                'DB_PASS'              => $dbPass,
                                'DB_NAME'              => $dbName,
                                'GAME_NAME'            => $valores['game_name'] ?: 'Fight',
                                'NARUTO_NOME'          => $valores['game_name'] ?: 'Fight',
                                'MAIL_FROM_NAME'       => naruto_env('MAIL_FROM_NAME', 'Naruto RPG'),
                                'MAIL_FROM_ADDRESS'    => naruto_env('MAIL_FROM_ADDRESS', $admMail),
                                'SMTP_HOST'            => naruto_env('SMTP_HOST', ''),
                                'SMTP_PORT'            => naruto_env('SMTP_PORT', '587'),
                                'SMTP_USER'            => naruto_env('SMTP_USER', ''),
                                'SMTP_PASS'            => naruto_env('SMTP_PASS', ''),
                                'SMTP_AUTH'            => naruto_env('SMTP_AUTH', 'true'),
                                'SMTP_SECURE'          => naruto_env('SMTP_SECURE', 'tls'),
                                'TURNSTILE_SITE_KEY'   => naruto_env('TURNSTILE_SITE_KEY', ''),
                                'TURNSTILE_SECRET_KEY' => naruto_env('TURNSTILE_SECRET_KEY', ''),
                                'DISCORD_WEBHOOK_URL'  => naruto_env('DISCORD_WEBHOOK_URL', ''),
                            ]);

                            if (isset($_POST['salvar_env'])) {
                                $envGravado = @file_put_contents(NARUTO_ROOT . '/.env', $envConteudo) !== false;
                                if ($envGravado) {
                                    @chmod(NARUTO_ROOT . '/.env', 0640);
                                } else {
                                    $avisos[] = 'Nao consegui gravar o .env (raiz sem permissao de escrita). '
                                        . 'Copie o bloco abaixo para as variaveis de ambiente do seu painel.';
                                }
                            } else {
                                $avisos[] = 'Voce optou por nao gravar o .env. Configure as variaveis abaixo no seu painel.';
                            }

                            if (!naruto_write_lock(['admin' => $admUser, 'banco' => $dbName, 'tabelas' => $resultado['tabelas']])) {
                                $avisos[] = 'Instalacao concluida, mas nao consegui gravar a trava em ' . naruto_lock_file()
                                    . '. Defina INSTALLER_ENABLED=false no ambiente para fechar o instalador.';
                            }
                        }
                    }
                }
                mysqli_close($link);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>Instalador &middot; Naruto MMORPG</title>
<style>
  :root{
    --bg:#15161a; --card:#1e2027; --card2:#24262f; --line:#33363f;
    --fg:#e8e8ea; --muted:#9a9ca6; --accent:#e8792b; --ok:#4caf50;
    --err:#e05656; --warn:#e0a020;
  }
  *{box-sizing:border-box}
  body{margin:0;padding:32px 16px;background:var(--bg);color:var(--fg);
       font:15px/1.55 -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Arial,sans-serif}
  .wrap{max-width:820px;margin:0 auto}
  h1{font-size:26px;margin:0 0 4px}
  h2{font-size:17px;margin:0 0 14px;padding-bottom:8px;border-bottom:1px solid var(--line)}
  .sub{color:var(--muted);margin:0 0 26px}
  .card{background:var(--card);border:1px solid var(--line);border-radius:10px;
        padding:22px;margin-bottom:18px}
  table{width:100%;border-collapse:collapse}
  td{padding:7px 0;border-bottom:1px solid var(--line);font-size:14px}
  td:last-child{text-align:right;color:var(--muted)}
  tr:last-child td{border-bottom:0}
  .badge{display:inline-block;min-width:58px;text-align:center;padding:2px 8px;
         border-radius:4px;font-size:12px;font-weight:600}
  .b-ok{background:rgba(76,175,80,.15);color:var(--ok)}
  .b-err{background:rgba(224,86,86,.15);color:var(--err)}
  .b-opt{background:rgba(224,160,32,.15);color:var(--warn)}
  label{display:block;font-size:13px;color:var(--muted);margin:14px 0 5px}
  input[type=text],input[type=password],input[type=email],input[type=number]{
    width:100%;padding:10px 12px;background:var(--card2);border:1px solid var(--line);
    border-radius:6px;color:var(--fg);font-size:14px}
  input:focus{outline:none;border-color:var(--accent)}
  .row{display:flex;gap:14px}
  .row>div{flex:1}
  .check{display:flex;align-items:flex-start;gap:9px;margin-top:16px;font-size:13px;color:var(--fg)}
  .check input{margin-top:3px}
  button{margin-top:22px;width:100%;padding:13px;background:var(--accent);border:0;
         border-radius:6px;color:#fff;font-size:15px;font-weight:600;cursor:pointer}
  button:hover{filter:brightness(1.1)}
  .msg{padding:13px 15px;border-radius:7px;margin-bottom:12px;font-size:14px}
  .msg-err{background:rgba(224,86,86,.1);border:1px solid rgba(224,86,86,.35)}
  .msg-warn{background:rgba(224,160,32,.1);border:1px solid rgba(224,160,32,.35)}
  .msg-ok{background:rgba(76,175,80,.1);border:1px solid rgba(76,175,80,.35)}
  pre{background:#101116;border:1px solid var(--line);border-radius:7px;padding:15px;
      overflow:auto;font-size:12.5px;color:#cfd2d8}
  code{background:#101116;padding:2px 6px;border-radius:4px;font-size:13px}
  a{color:var(--accent)}
  .danger{border-color:rgba(224,86,86,.5)}
  ol{padding-left:20px} ol li{margin-bottom:8px}
</style>
</head>
<body>
<div class="wrap">

  <h1>🍥 Instalador do Naruto MMORPG</h1>
  <p class="sub">Configura o banco, importa as 76 tabelas e cria a conta de administrador.</p>

<?php if ($bloqueado): ?>
  <div class="card">
    <h2>Instalador fechado</h2>
    <div class="msg msg-warn"><?php echo $bloqueado; ?></div>
    <p><a href="../">Ir para o jogo &rarr;</a></p>
  </div>

<?php elseif ($sucesso): ?>
  <div class="card">
    <h2>Instalacao concluida</h2>
    <div class="msg msg-ok">
      <strong><?php echo (int)$resultado['tabelas']; ?> tabelas</strong> criadas
      (<?php echo (int)$resultado['comandos']; ?> comandos SQL executados) e a conta
      <strong><?php echo h($resultado['admin']); ?></strong> foi criada como administrador.
    </div>
    <?php foreach ($avisos as $a): ?>
      <div class="msg msg-warn"><?php echo h($a); ?></div>
    <?php endforeach; ?>

    <?php if ($envGravado): ?>
      <p>O arquivo <code>.env</code> foi gravado na raiz do projeto.</p>
    <?php else: ?>
      <p>Configure estas variaveis de ambiente (Coolify, painel da hospedagem ou arquivo <code>.env</code>):</p>
      <pre><?php echo h($envConteudo); ?></pre>
    <?php endif; ?>

    <h2 style="margin-top:26px">Ultimos passos</h2>
    <ol>
      <li>Defina <code>INSTALLER_ENABLED=false</code> no ambiente, ou apague a pasta <code>install/</code> do servidor.</li>
      <li>Entre no jogo com o usuario que voce acabou de criar e troque a senha.</li>
      <li>Se for usar recuperacao de senha, preencha as variaveis <code>SMTP_*</code>.</li>
    </ol>
    <p><a href="../">Ir para o jogo &rarr;</a></p>
  </div>

<?php else: ?>

  <div class="card">
    <h2>Requisitos do servidor</h2>
    <table>
      <?php foreach ($requisitos as $r): ?>
      <tr>
        <td>
          <?php if ($r['ok']): ?>
            <span class="badge b-ok">OK</span>
          <?php elseif (!empty($r['opcional'])): ?>
            <span class="badge b-opt">AVISO</span>
          <?php else: ?>
            <span class="badge b-err">FALTA</span>
          <?php endif; ?>
          &nbsp;<?php echo h($r['nome']); ?>
        </td>
        <td><?php echo h($r['valor']); ?></td>
      </tr>
      <?php endforeach; ?>
    </table>
    <?php if ($bloqueiaReq): ?>
      <div class="msg msg-err" style="margin-top:16px">
        Resolva os itens marcados como <strong>FALTA</strong> antes de continuar.
        Itens em <strong>AVISO</strong> nao impedem a instalacao (uploads e relatorios
        ficam indisponiveis ate as pastas serem gravaveis).
      </div>
    <?php endif; ?>
  </div>

  <form method="post" class="card<?php echo $precisaWipe ? ' danger' : ''; ?>">
    <h2>Banco de dados</h2>

    <?php foreach ($erros as $e): ?>
      <div class="msg msg-err"><?php echo h($e); ?></div>
    <?php endforeach; ?>

    <input type="hidden" name="csrf" value="<?php echo h($_SESSION['install_csrf']); ?>">
    <?php if (isset($_REQUEST['token'])): ?>
      <input type="hidden" name="token" value="<?php echo h($_REQUEST['token']); ?>">
    <?php endif; ?>

    <div class="row">
      <div style="flex:3">
        <label for="db_host">Host do MySQL</label>
        <input type="text" id="db_host" name="db_host" value="<?php echo h($valores['db_host']); ?>"
               placeholder="localhost" required>
      </div>
      <div>
        <label for="db_port">Porta</label>
        <input type="number" id="db_port" name="db_port" value="<?php echo h($valores['db_port']); ?>">
      </div>
    </div>

    <div class="row">
      <div>
        <label for="db_user">Usuario</label>
        <input type="text" id="db_user" name="db_user" value="<?php echo h($valores['db_user']); ?>" required>
      </div>
      <div>
        <label for="db_pass">Senha</label>
        <input type="password" id="db_pass" name="db_pass" autocomplete="new-password">
      </div>
    </div>

    <label for="db_name">Nome do banco</label>
    <input type="text" id="db_name" name="db_name" value="<?php echo h($valores['db_name']); ?>" required>

    <div class="check">
      <input type="checkbox" id="criar_banco" name="criar_banco"<?php echo $criarBanco ? ' checked' : ''; ?>>
      <label for="criar_banco" style="margin:0;color:var(--fg)">
        Criar o banco se ele ainda nao existir
      </label>
    </div>

    <?php if ($precisaWipe): ?>
    <div class="check">
      <input type="checkbox" id="confirm_wipe" name="confirm_wipe" required>
      <label for="confirm_wipe" style="margin:0;color:var(--err)">
        <strong>Entendo que isso apaga todas as tabelas do jogo nesse banco</strong>
        e quero importar o schema mesmo assim.
      </label>
    </div>
    <?php endif; ?>

    <h2 style="margin-top:30px">Conta de administrador</h2>
    <div class="row">
      <div>
        <label for="admin_user">Usuario</label>
        <input type="text" id="admin_user" name="admin_user" value="<?php echo h($valores['admin_user']); ?>"
               maxlength="15" required>
      </div>
      <div>
        <label for="admin_email">E-mail</label>
        <input type="email" id="admin_email" name="admin_email" value="<?php echo h($valores['admin_email']); ?>" required>
      </div>
    </div>
    <div class="row">
      <div>
        <label for="admin_pass">Senha (minimo 8 caracteres)</label>
        <input type="password" id="admin_pass" name="admin_pass" autocomplete="new-password" required>
      </div>
      <div>
        <label for="admin_pass2">Repita a senha</label>
        <input type="password" id="admin_pass2" name="admin_pass2" autocomplete="new-password" required>
      </div>
    </div>

    <h2 style="margin-top:30px">Jogo</h2>
    <label for="game_name">Nome do servidor (aparece no titulo e nas mensagens)</label>
    <input type="text" id="game_name" name="game_name" value="<?php echo h($valores['game_name']); ?>">

    <div class="check">
      <input type="checkbox" id="salvar_env" name="salvar_env"<?php echo $salvarEnv ? ' checked' : ''; ?>>
      <label for="salvar_env" style="margin:0;color:var(--fg)">
        Gravar o arquivo <code>.env</code> na raiz
        <span style="color:var(--muted)">(desmarque em Docker/Coolify, onde as variaveis vem do painel)</span>
      </label>
    </div>

    <button type="submit">Instalar agora</button>
  </form>

<?php endif; ?>

</div>
</body>
</html>
