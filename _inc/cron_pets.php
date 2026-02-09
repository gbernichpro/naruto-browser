<?php require_once('../conexao.php'); ?>
<?php
$expira=date('Y-m-d H:i:s');
$sql=mysql_query("SELECT id, usuarioid FROM pets WHERE expira<='$expira'");
$db=mysql_fetch_assoc($sql);
$assunto='Contrato Finalizado';
$msg='Informamos que seu contrato de invocação terminou.<br />Se desejar, pode escolher outro contrato de invocação para preencher o espaço do Kuchiyose no Jutsu.';
$destino=$db['usuarioid'];
mysql_query("INSERT INTO mensagens (data, origem, destino, assunto, msg) VALUES ('$expira', 0, $destino, '$assunto', '$msg')");
mysql_query("DELETE FROM pets WHERE id=".$db['id']);
?>