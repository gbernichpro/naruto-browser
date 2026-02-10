<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

require_once('mysqli_shim.php');
@session_start();
require_once('mail.php');
require_once('security.php');
require_once('cache.php');
require_once('error_handler.php');
$mysql_banco=$_ENV['DB_NAME'];
$mysql_usuario=$_ENV['DB_USER'];
$mysql_senha=$_ENV['DB_PASS'];
$mysql_host=$_ENV['DB_HOST'];
$conexao=mysql_pconnect($mysql_host,$mysql_usuario,$mysql_senha);
mysql_select_db($mysql_banco);
mysql_query("SET NAMES 'utf8'");
error_reporting(E_ALL & ~E_NOTICE);
define("NARUTO_NOME", $_ENV['GAME_NAME'] ?? "Fight");
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