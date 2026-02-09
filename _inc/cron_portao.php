<?php require_once('../conexao.php'); ?>
<?php
$expira=date('Y-m-d H:i:s');
$sql=mysql_query("SELECT id, usuarioid FROM portao WHERE expira<='$expira'");
$db=mysql_fetch_assoc($sql);
$assunto='Portão do chakra acabou o efeito';
$msg='Informamos que seu portão terminou.<br />Se desejar, pode escolher outro selo para utilizar.';
$destino=$db['usuarioid'];
mysql_query("INSERT INTO mensagens (data, origem, destino, assunto, msg) VALUES ('$expira', 0, $destino, '$assunto', '$msg')");
mysql_query("DELETE FROM portao WHERE id=".$db['id']);
?>