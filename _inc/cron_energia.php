<?php require_once('conexao.php'); ?>
<?php
mysql_query("UPDATE usuarios SET energia=energia+5 WHERE energia<energiamax AND tipo='player'");
mysql_query("UPDATE usuarios SET energia=ENERGIAMAX WHERE energia>energiamax AND tipo='player'");
?>
