<?php
if((!isset($_GET['id']))or(!isset($_GET['name']))){ echo "<script>self.location='?p=home'</script>"; return; }
if($_GET['id']==''){ echo "<script>self.location='?p=home'</script>"; return; }
if($_GET['name']==''){ echo "<script>self.location='?p=home'</script>"; return; }
$dbs = mysql_fetch_assoc(mysql_query("SELECT config_apresentacao FROM usuarios WHERE id='".antiinjection($_GET['id'])."'"));
$msg = antiinjection($dbs['config_apresentacao']);
mysql_query("INSERT INTO spam (usuarioid, usuario, informanteid, informante, mensagem) VALUES ('".antiinjection($_GET['id'])."', '".antiinjection($_GET['name'])."', ".$db['id'].", '".$db['usuario']."', '".$msg."')");
echo "<script>self.location='?p=view&view=".$_GET['name']."&report=true'</script>";

?>