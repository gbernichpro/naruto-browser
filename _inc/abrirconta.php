<?php
$verifique=isset($_GET['abrir']);
$dbh=mysql_query("SELECT * FROM transfer where usuarioid='".$db['id']."'");
if(mysql_num_rows($dbh)>0){ echo "<script>self.location='?p=credshopit&msg=10'</script>"; return; }
//Aki é o codigo de cada item by juniorrios :P
if(isset($verifique)) {
 if($db['nivel']<15){
			echo "<script>self.location='?p=credshopit&msg=13'</script>"; return;
 }else
		mysql_query("INSERT INTO transfer (usuarioid,limite,transferido) VALUES (".$db['id'].",'50000','0')");
		mysql_query("UPDATE usuarios SET yens=yens-".$valor." WHERE id=".$db['id']);

			echo "<script>self.location='?p=credshopit&msg=12'</script>"; return;}
        //Termina aki!!!!!!!!!!!!!!

        ?>
