<?php
$sqlr = mysql_query("SELECT usuarioid, fim FROM salas WHERE id='".antiinjection($_GET['id'])."'");
$dbr=mysql_fetch_assoc($sqlr);
if(($dbr['usuarioid']<>$db['id'])&&($atual<$dbr['fim'])){ echo "<script>self.location='?p=school'</script>"; return; }
@mysql_free_result($sqlr);
?>