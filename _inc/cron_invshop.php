<?php require_once('../conexao.php'); ?>
<?php
$expira=date('Y-m-d H:i:s');
$sql=mysql_query("SELECT id, usuarioid FROM inv_invasao WHERE expira<='$expira'");
$db=mysql_fetch_assoc($sql);
$assunto='Iten da invasão encerrado';
$msg='Informamos algums de seus items da invasão expirou.<br />Se desejar, pode escolher outro para utilizar.';
$destino=$db['usuarioid'];
mysql_query("INSERT INTO mensagens (data, origem, destino, assunto, msg) VALUES ('$expira', 0, $destino, '$assunto', '$msg')");
mysql_query("DELETE FROM inv_invasao WHERE id=".$db['id']);
?>