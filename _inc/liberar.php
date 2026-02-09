<div class="box_top">Contato</div>
<div class="box_middle">

<?php
 include "conexao.php";


 $id = antiinjection($_GET['id']);
 $sql= mysql_query("UPDATE usuarios SET status='ativo' where id='$id'");


 echo "Conta ativada com you morer";


?>

<div class="box_bottom"></div>