<?php require_once('../conexao.php'); ?>
<?php
$expira=date('Y-m-d H:i:s');
$sql=mysql_query("SELECT id, usuarioid FROM selos WHERE expira<='$expira'");
$db=mysql_fetch_assoc($sql);
$assunto='Selo Amaldiçoado Encerrado';
$msg='Informamos que seu selo amaldiçoado terminou.<br />Se desejar, pode escolher outro selo para utilizar.';
$destino=$db['usuarioid'];
mysql_query("INSERT INTO mensagens (data, origem, destino, assunto, msg) VALUES ('$expira', 0, $destino, '$assunto', '$msg')");
mysql_query("DELETE FROM selos WHERE id=".$db['id']);
?>