<?php
/**
 * Biblioteca do instalador.
 *
 * Fica separada de _inc/ de proposito: o instalador NAO pode carregar
 * _inc/conexao.php, porque conexao.php abre a conexao com o banco e da die()
 * quando as credenciais ainda nao existem -- que e exatamente o estado em que
 * o instalador roda.
 *
 * Substitui o antigo _inc/db_init.php, que importava o dump a cada primeira
 * conexao e criava um admin com senha fixa 'admin'.
 */

require_once dirname(__DIR__) . '/_inc/env.php';





/**
 * O jogo ja foi instalado? Trava em disco OU variavel de ambiente.
 *
 * A variavel existe para o caso do volume nao ser persistente: em Coolify
 * voce marca INSTALLER_ENABLED=false depois de instalar e o instalador
 * fica fechado independente do arquivo.
 */
function naruto_is_locked() {
    if (naruto_env('INSTALLER_ENABLED') !== null && !naruto_env_bool('INSTALLER_ENABLED', true)) {
        return true;
    }
    return file_exists(naruto_lock_file());
}

/**
 * Grava a trava apos uma instalacao bem sucedida.
 */
function naruto_write_lock(array $info) {
    $payload = json_encode(
        $info + ['data' => date('c')],
        JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
    );
    return @file_put_contents(naruto_lock_file(), $payload) !== false;
}

/**
 * Se INSTALL_TOKEN estiver definido, o instalador so abre com ?token=<valor>.
 * Util quando voce precisa reabrir o instalador em um host publico.
 */
function naruto_token_ok() {
    $expected = naruto_env('INSTALL_TOKEN');
    if ($expected === null || $expected === '') {
        return true;
    }
    $given = isset($_REQUEST['token']) ? (string)$_REQUEST['token'] : '';
    return hash_equals((string)$expected, $given);
}

/**
 * Quebra um dump SQL em comandos.
 *
 * O explode(';') que existia em db_init.php quebra em qualquer ';' dentro de
 * string ou comentario. O dump atual por sorte nao tem nenhum, mas qualquer
 * apresentacao de jogador com ';' em um dump futuro derrubaria o import pela
 * metade, deixando o banco inconsistente sem avisar. Este parser respeita
 * aspas simples, aspas duplas, crases, escapes e comentarios -- e --,
 * # e blocos.
 *
 * @return string[] comandos sem o ';' final, ja trimados e sem vazios
 */
function naruto_split_sql($sql) {
    $statements = [];
    $buffer     = '';
    $len        = strlen($sql);
    $inString   = false;   // aspa que abriu a string atual, ou false
    $inLineCmt  = false;
    $inBlockCmt = false;

    for ($i = 0; $i < $len; $i++) {
        $c    = $sql[$i];
        $next = ($i + 1 < $len) ? $sql[$i + 1] : '';

        if ($inLineCmt) {
            if ($c === "\n") {
                $inLineCmt = false;
                $buffer .= $c;
            }
            continue;
        }

        if ($inBlockCmt) {
            if ($c === '*' && $next === '/') {
                $inBlockCmt = false;
                $i++;
            }
            continue;
        }

        if ($inString !== false) {
            $buffer .= $c;
            // Escape com barra invertida consome o proximo caractere.
            if ($c === '\\' && $inString !== '`') {
                if ($next !== '') {
                    $buffer .= $next;
                    $i++;
                }
                continue;
            }
            // Aspa duplicada ('' ou "") e a propria aspa, nao fecha a string.
            if ($c === $inString) {
                if ($next === $inString) {
                    $buffer .= $next;
                    $i++;
                    continue;
                }
                $inString = false;
            }
            continue;
        }

        // Fora de string: comentarios so comecam aqui.
        if ($c === '-' && $next === '-' && (($i + 2 >= $len) || $sql[$i + 2] === ' ' || $sql[$i + 2] === "\t" || $sql[$i + 2] === "\n" || $sql[$i + 2] === "\r")) {
            $inLineCmt = true;
            continue;
        }
        if ($c === '#') {
            $inLineCmt = true;
            continue;
        }
        if ($c === '/' && $next === '*') {
            $inBlockCmt = true;
            $i++;
            continue;
        }

        if ($c === "'" || $c === '"' || $c === '`') {
            $inString = $c;
            $buffer .= $c;
            continue;
        }

        if ($c === ';') {
            $stmt = trim($buffer);
            if ($stmt !== '') {
                $statements[] = $stmt;
            }
            $buffer = '';
            continue;
        }

        $buffer .= $c;
    }

    $stmt = trim($buffer);
    if ($stmt !== '') {
        $statements[] = $stmt;
    }

    return $statements;
}

/**
 * Desliga as excecoes do mysqli para o trecho de codigo chamador.
 *
 * A partir do PHP 8.1 o mysqli lanca mysqli_sql_exception por padrao. Tanto o
 * instalador quanto o importador dependem de mysqli_query() devolver false
 * para conseguir listar TODOS os comandos que falharam em vez de morrer no
 * primeiro. Devolve o modo anterior para quem quiser restaurar.
 */
function naruto_mysqli_quiet() {
    $anterior = null;
    if (function_exists('mysqli_report')) {
        $anterior = mysqli_report(MYSQLI_REPORT_OFF);
    }
    return $anterior;
}

/**
 * Caminho do schema unico do projeto.
 */
function naruto_schema_file() {
    return NARUTO_ROOT . '/install/schema.sql';
}

/**
 * Importa o schema em uma conexao mysqli ja aberta e com banco selecionado.
 *
 * @param mysqli   $link
 * @param string   $file
 * @param string[] $errors  preenchido por referencia com os comandos que falharam
 * @return int  quantidade de comandos executados com sucesso
 */
function naruto_import_sql($link, $file, array &$errors = []) {
    naruto_mysqli_quiet();

    $sql = @file_get_contents($file);
    if ($sql === false) {
        $errors[] = "Nao foi possivel ler o arquivo de schema: {$file}";
        return 0;
    }

    // O dump e de 2013 e insere datas zero ('0000-00-00') em colunas datetime
    // NOT NULL. O sql_mode padrao do MySQL 8 / MariaDB 10.5+ rejeita isso em
    // modo estrito, entao o import roda com o modo relaxado da sessao.
    @mysqli_query($link, "SET SESSION sql_mode = ''");
    @mysqli_query($link, "SET SESSION foreign_key_checks = 0");

    $ok = 0;
    foreach (naruto_split_sql($sql) as $stmt) {
        if (mysqli_query($link, $stmt)) {
            $ok++;
        } else {
            $errors[] = mysqli_error($link) . ' | comando: ' . substr(preg_replace('/\s+/', ' ', $stmt), 0, 160);
        }
    }

    @mysqli_query($link, "SET SESSION foreign_key_checks = 1");
    return $ok;
}

/**
 * Lista as tabelas do banco selecionado.
 */
function naruto_list_tables($link) {
    $tables = [];
    $res = @mysqli_query($link, 'SHOW TABLES');
    if ($res) {
        while ($row = mysqli_fetch_row($res)) {
            $tables[] = $row[0];
        }
    }
    return $tables;
}

/**
 * Cria a conta de administrador.
 *
 * Nao usa a lista de colunas fixa que existia em db_init.php: aquela lista
 * quebra se o schema mudar. Aqui as colunas sao conferidas contra o que a
 * tabela realmente tem antes de montar o INSERT.
 */
function naruto_create_admin($link, $usuario, $senha, $email, &$erro = null) {
    $cols = [];
    $res = @mysqli_query($link, 'SHOW COLUMNS FROM `usuarios`');
    if (!$res) {
        $erro = 'Tabela `usuarios` nao existe: ' . mysqli_error($link);
        return false;
    }
    while ($row = mysqli_fetch_assoc($res)) {
        $cols[$row['Field']] = true;
    }

    $valores = [
        'usuario'        => $usuario,
        'senha'          => password_hash($senha, PASSWORD_DEFAULT),
        'email'          => $email,
        'personagem'     => 'naruto',
        'vila'           => 1,
        'reg'            => date('Y-m-d H:i:s'),
        'tipodeconta'    => 'admin',
        'status'         => 'ativo',
        'vip_inicio'     => '1970-01-01 00:00:00',
        'vip'            => '1970-01-01 00:00:00',
        'hunt_fim'       => '1970-01-01 00:00:00',
        'missao_fim'     => '1970-01-01 00:00:00',
        'treino_fim'     => '1970-01-01 00:00:00',
        'penalidade'     => '1970-01-01 00:00:00',
        'penalidade_fim' => '1970-01-01 00:00:00',
        'missao_tempo'   => 0,
        'treino_tempo'   => 0,
        'loginip'        => '127.0.0.1',
        'ip'             => '127.0.0.1',
    ];

    $campos = [];
    $marks  = [];
    $tipos  = '';
    $args   = [];
    foreach ($valores as $campo => $valor) {
        if (!isset($cols[$campo])) {
            continue;
        }
        $campos[] = "`{$campo}`";
        $marks[]  = '?';
        $tipos   .= is_int($valor) ? 'i' : 's';
        $args[]   = $valor;
    }

    $sql  = 'INSERT INTO `usuarios` (' . implode(', ', $campos) . ') VALUES (' . implode(', ', $marks) . ')';
    $stmt = mysqli_prepare($link, $sql);
    if (!$stmt) {
        $erro = mysqli_error($link);
        return false;
    }
    mysqli_stmt_bind_param($stmt, $tipos, ...$args);
    if (!mysqli_stmt_execute($stmt)) {
        $erro = mysqli_stmt_error($stmt);
        mysqli_stmt_close($stmt);
        return false;
    }
    mysqli_stmt_close($stmt);
    return true;
}

/**
 * Monta o conteudo de um arquivo .env a partir dos valores coletados.
 */
function naruto_render_env(array $v) {
    $q = function ($s) {
        // Aspas duplas quando houver espaco ou caractere que o parser do
        // dotenv trata de forma especial.
        $s = (string)$s;
        if ($s === '' || preg_match('/[\s"#\'\\\\$]/', $s)) {
            return '"' . str_replace(['\\', '"'], ['\\\\', '\\"'], $s) . '"';
        }
        return $s;
    };

    $linhas = [
        '# Gerado por install/index.php em ' . date('Y-m-d H:i:s'),
        '',
        '# Banco de dados',
        'DB_HOST=' . $q($v['DB_HOST']),
        'DB_PORT=' . $q($v['DB_PORT']),
        'DB_USER=' . $q($v['DB_USER']),
        'DB_PASS=' . $q($v['DB_PASS']),
        'DB_NAME=' . $q($v['DB_NAME']),
        '',
        '# Identidade do jogo',
        'GAME_NAME=' . $q($v['GAME_NAME']),
        'NARUTO_NOME=' . $q($v['NARUTO_NOME']),
        '',
        '# E-mail (PHPMailer) - preencha para ativar recuperacao de senha',
        'MAIL_FROM_NAME=' . $q($v['MAIL_FROM_NAME']),
        'MAIL_FROM_ADDRESS=' . $q($v['MAIL_FROM_ADDRESS']),
        'SMTP_HOST=' . $q($v['SMTP_HOST']),
        'SMTP_PORT=' . $q($v['SMTP_PORT']),
        'SMTP_USER=' . $q($v['SMTP_USER']),
        'SMTP_PASS=' . $q($v['SMTP_PASS']),
        'SMTP_AUTH=' . $q($v['SMTP_AUTH']),
        'SMTP_SECURE=' . $q($v['SMTP_SECURE']),
        '',
        '# Cloudflare Turnstile (captcha do cadastro)',
        'TURNSTILE_SITE_KEY=' . $q($v['TURNSTILE_SITE_KEY']),
        'TURNSTILE_SECRET_KEY=' . $q($v['TURNSTILE_SECRET_KEY']),
        '',
        '# Webhook opcional para notificacao de erros',
        'DISCORD_WEBHOOK_URL=' . $q($v['DISCORD_WEBHOOK_URL']),
        '',
        '# Fecha o instalador. Deixe false depois de instalar.',
        'INSTALLER_ENABLED=false',
        '',
    ];

    return implode("\n", $linhas);
}
