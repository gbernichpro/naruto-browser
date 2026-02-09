<?php require_once('../conexao.php'); ?>
<?php
$expira=date('Y-m-d H:i:s');
$sql=mysql_query("SELECT id,usuarioid FROM transfer WHERE transferido<=limite");
while($db=mysql_fetch_assoc($sql))
{
$assunto='Limite de transferencias de yens resetados.';
$msg='Informamos que você já pode realizar transferencias.<br />.';
$destino=$db['usuarioid'];
mysql_query("INSERT INTO mensagens (data, origem, destino, assunto, msg) VALUES ('$expira', 0, $destino, '$assunto', '$msg')");
mysql_query("UPDATE transfer SET transferido=0 WHERE usuarioid=".$db['usuarioid']);
}
?>