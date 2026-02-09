<?php
  require_once('conexao.php');
  $timestamp=time(); 
  $timeout=time()-900;
  if($db['timestamp']<$timeout) mysql_query("UPDATE usuarios SET timestamp='$timestamp' WHERE id=".$db['id']);
?> 
