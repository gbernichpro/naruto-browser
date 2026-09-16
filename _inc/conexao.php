<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
if (file_exists(dirname(__DIR__) . '/.env')) {
    $dotenv->load();
} else {
    $dotenv->safeLoad();
}

require_once('env.php');
require_once('mysqli_shim.php');
@session_start();
require_once('mail.php');
require_once('security.php');
require_once('cache.php');
require_once('error_handler.php');

// naruto_env() le tanto o .env (via phpdotenv) quanto as variaveis reais do
// processo. Ler so $_ENV quebra em Docker/Coolify, porque o variables_order
// padrao de producao nao copia o ambiente para $_ENV.
$mysql_banco   = naruto_env('DB_NAME');
$mysql_usuario = naruto_env('DB_USER');
$mysql_senha   = naruto_env('DB_PASS', '');
$mysql_host    = naruto_env('DB_HOST', 'localhost');
$mysql_porta   = naruto_env('DB_PORT');

if ($mysql_banco === null || $mysql_usuario === null) {
    naruto_sem_configuracao('O jogo ainda nao foi configurado: faltam DB_NAME e DB_USER.');
}

// O shim entende "host:porta" como a extensao mysql original fazia.
$alvo = $mysql_porta ? $mysql_host . ':' . $mysql_porta : $mysql_host;

$conexao = @mysql_pconnect($alvo, $mysql_usuario, $mysql_senha);
if (!$conexao) {
    die("Database Connection Failed. Check your DB credentials. " . mysqli_connect_error());
}
mysql_select_db($mysql_banco);
if (mysql_error()) {
    die("Database Selection Failed: " . mysql_error());
}
mysql_query("SET NAMES 'utf8'");

// O schema e de 2013 e grava datas zero ('0000-00-00') em colunas datetime
// NOT NULL, o que o modo estrito do MySQL 8 / MariaDB 10.5+ rejeita. Sem isso
// o cadastro e varias telas de batalha falham em servidor moderno.
// Defina DB_LEGACY_SQL_MODE=false depois de modernizar o schema.
if (naruto_env_bool('DB_LEGACY_SQL_MODE', true)) {
    @mysql_query("SET SESSION sql_mode = ''");
}

// Banco conectado mas vazio: manda para o instalador em vez de despejar
// erro de "table doesn't exist" em toda pagina.
$naruto_check = @mysql_query("SHOW TABLES LIKE 'usuarios'");
if (!$naruto_check || mysql_num_rows($naruto_check) === 0) {
    naruto_sem_configuracao('Conectei no banco `' . $mysql_banco . '`, mas ele nao tem as tabelas do jogo.');
}

/**
 * Encaminha para o instalador web quando o jogo ainda nao esta pronto.
 */
function naruto_sem_configuracao($motivo) {
    require_once NARUTO_ROOT . '/_inc/env.php';

    $temInstalador = is_file(NARUTO_ROOT . '/install/index.php')
        && !file_exists(naruto_lock_file())
        && naruto_env_bool('INSTALLER_ENABLED', true);

    $ehAjax = (isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
        || (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] !== 'GET');

    if ($temInstalador && !$ehAjax && !headers_sent()) {
        $base = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? '/'), '/');
        header('Location: ' . ($base === '' ? '' : $base) . '/install/');
        exit;
    }

    die(htmlspecialchars($motivo, ENT_QUOTES, 'UTF-8')
        . ' Abra /install/ para instalar, ou confira as variaveis DB_* do ambiente.');
}
// Espelha a decisao do index.php: em producao os erros vao para o log do
// servidor, nunca para a tela do jogador.
if (naruto_env_bool('APP_DEBUG', false)) {
    error_reporting(E_ALL);
    @ini_set('display_errors', '1');
} else {
    error_reporting(E_ALL & ~E_NOTICE);
    @ini_set('display_errors', '0');
}
define("NARUTO_NOME", naruto_env('GAME_NAME', naruto_env('NARUTO_NOME', "Fight")));
function antiinjection2($sql){
$sql = addslashes($sql);
$sql = str_replace("<","</",$sql);
$sql = str_replace(">","/>",$sql);
$sql = str_replace("select","",$sql);
$sql = str_replace("insert","",$sql);
$sql = str_replace("delete","",$sql);
$sql = str_replace("where","",$sql);
$sql = str_replace("drop table","",$sql);
$sql = str_replace("show tables","",$sql);
$sql = str_replace("truncate","",$sql);
$sql = str_replace("SELECT","",$sql);
$sql = str_replace("INSERT","",$sql);
$sql = str_replace("DELETE","",$sql);
$sql = str_replace("WHERE","",$sql);
$sql = str_replace("DROP TABLE","",$sql);
$sql = str_replace("SHOW TABLES","",$sql);
$sql = str_replace("TRUNCATE","",$sql);
$sql = str_replace("from","",$sql);
$sql = str_replace("FROM","",$sql);
return $sql;
}

function protecao2($sql){
$sql = str_replace("select","",$sql);
$sql = str_replace("insert","",$sql);
$sql = str_replace("delete","",$sql);
$sql = str_replace("where","",$sql);
$sql = str_replace("drop table","",$sql);
$sql = str_replace("show tables","",$sql);
$sql = str_replace("truncate","",$sql);
$sql = str_replace("SELECT","",$sql);
$sql = str_replace("INSERT","",$sql);
$sql = str_replace("DELETE","",$sql);
$sql = str_replace("WHERE","",$sql);
$sql = str_replace("DROP TABLE","",$sql);
$sql = str_replace("SHOW TABLES","",$sql);
$sql = str_replace("TRUNCATE","",$sql);
$sql = str_replace("from","",$sql);
$sql = str_replace("FROM","",$sql);
return $sql;
}

?>