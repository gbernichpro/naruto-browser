<?php
require_once('_inc/mysqli_shim.php');
session_start();
$mysql_banco='naruto';
$mysql_usuario='root';
$mysql_senha='';
$mysql_host='localhost';
$conexao=mysql_pconnect($mysql_host,$mysql_usuario,$mysql_senha);
mysql_select_db($mysql_banco);
mysql_query("SET NAMES 'utf8'");

function antiinjection($sql){
$sql = addslashes($sql);
$sql = str_replace("'","",$sql);
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

function protecao($sql){
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