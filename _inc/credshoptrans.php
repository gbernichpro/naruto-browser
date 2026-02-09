<?php
$verificar=$_POST['quantidade'];
require_once('trava.php');
if(date('Y-m-d H:i:s')>$db['vip']){ echo "<script>self.location='?p=msgvip'</script>"; return; }
if (ereg("^[0-9]+$",$_POST['quantidade']))
	{
		$sqlh=mysql_query("SELECT * FROM usuarios WHERE usuario='".$_POST['usuario']."'");
		if(mysql_num_rows($sqlh)==0){ echo "<script>self.location='?p=credshopit&msg=2'</script>"; return; }
		if($verificar==0){ echo "<script>self.location='?p=credshopit&msg=3'</script>"; return; }
		if($verificar<0){ echo "<script>self.location='?p=credshopit&msg=3'</script>"; return; }
		if($verificar>$db['creditos']){ echo "<script>self.location='?p=credshopit&msg=3'</script>"; return; }
		$user2=$_POST['usuario'];
		$sql2=mysql_query("select id from usuarios where usuario='".$user2."'")or die ("Problema na conexão com  a tabela usuarios");
		$dbi2=mysql_fetch_assoc($sql2);
		$user=$_POST['usuario'];
		$expira=date('Y-m-d H:i:s');
		$assunto='Transferencia concluida';
        $msg='Voce enviou '.$verificar.' credito(s) para '.$user.' com sucesso!.';
        $destino=$db['id'];
		$user2=$dbi2['id'];
		$assunto2='Transferencia concluida';
        $msg2='O Ninja '.$db['usuario'].' acabou de lhe doar '.$_POST['quantidade'].' de credito(s)';
		$destino2=$user2;
		mysql_query("UPDATE usuarios SET creditos=creditos+".$verificar."   WHERE usuario='".$user."'");
		mysql_query("UPDATE usuarios SET creditos=creditos-".$verificar." , creditostransferidos=creditostransferidos+".$_POST['quantidade']." WHERE id=".$db['id']);
        mysql_query("INSERT INTO mensagens (data, origem, destino, assunto, msg) VALUES ('$expira', 0, $destino, '$assunto', '$msg')");
        mysql_query("INSERT INTO mensagens (data, origem, destino, assunto, msg) VALUES ('$expira', 0, $destino2, '$assunto2', '$msg2')");

		echo "<script>self.location='?p=credshopit&msg=5'</script>";

	}
else
	{
		echo "<script>self.location='?p=credshopit&msg=6'</script>";
	}
?>

