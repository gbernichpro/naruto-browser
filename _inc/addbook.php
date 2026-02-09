<?php
if(!isset($_GET['id'])){ echo "<script>self.location='?p=home'</script>"; return; }
$id = antiinjection($_GET['id']);
$sqlf=mysql_query("SELECT id FROM usuarios WHERE usuario='".$id."'");
$dbf=mysql_fetch_assoc($sqlf);
$sqlc=mysql_query("SELECT count(id) conta FROM book WHERE usuarioid=".$db['id']." AND inimigoid=".$dbf['id']);
$dbc=mysql_fetch_assoc($sqlc);
if($dbc['conta']>0){ echo "<script>self.location='?p=home'</script>"; return; }
mysql_query("INSERT INTO book (usuarioid,inimigoid) VALUES (".$db['id'].",".$dbf['id'].")");
echo "<script>self.location='?p=book&msg=1'</script>";
@mysql_free_result($sqlf);
?>