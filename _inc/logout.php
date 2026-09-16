<?php
mysql_query("UPDATE usuarios SET timestamp=0 WHERE id=".$_SESSION['logado']);
unset($_SESSION['logado']);
unset($_SESSION['username']);
unset($_SESSION['errobot']);
setcookie('logado',1,time()-3600);
setcookie('session_id',1,time()-3600);
if(isset($_GET['reason'])) $reason='&reason='.$_GET['reason']; else $reason='';
if(!isset($_GET['csrf_token']) || !validate_csrf_token($_GET['csrf_token'])) {
    echo "<script>self.location='?p=login".$reason."'</script>"; return;
}
echo "<script>self.location='?p=login".$reason."'</script>"; return;
?>