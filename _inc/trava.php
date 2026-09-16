<?php
if((!isset($_COOKIE['logado']))or(!isset($_SESSION['logado']))){
	//setcookie('logado',1,time()-3600);
	unset($_SESSION['logado']);
unset($_SESSION['username']);
	echo "<script>self.location='index.php?p=login'</script>"; exit();
}
?>
