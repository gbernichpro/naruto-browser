<?php
$verificar=antiinjection($_POST['quantidade']);

require_once('trava.php');
if (ereg("^[0-9]+$",$_POST['quantidade']))
	{
        $dbg=mysql_query("SELECT * FROM transfer where usuarioid='".$db['id']."'");
        $dbi=mysql_fetch_assoc($dbg);
	    $sqlh=mysql_query("SELECT * FROM usuarios WHERE usuario='".$_POST['usuario']."'");
	    if(mysql_num_rows($sqlh)==0){ echo "<script>self.location='?p=credshopit&msg=2'</script>"; return; }
		$transf=$dbi['transferido'];
		$limite=$dbi['limite'];
		//aqui vai verificar até quanto ele pode tranferir exp: X-limite=500  Y-transferido=200  x-y=300 ele  só pode tranferir 300
		$podetransferir=($dbi['limite']-$dbi['transferido']);
		if($verificar>$podetransferir){echo "<script>self.location='?p=credshopit&msg=14'</script>"; return;};


		if($verificar>$dbi['limite']){ echo "<script>self.location='?p=credshopit&msg=11'</script>"; return; }
		if($db['nivel']<1){ echo "<script>self.location='?p=credshopit&msg=8'</script>"; return; }
		if($verificar==0){ echo "<script>self.location='?p=credshopit&msg=3'</script>"; return; }
		if($verificar<0){ echo "<script>self.location='?p=credshopit&msg=3'</script>"; return; }
		if($verificar>$db['yens']){ echo "<script>self.location='?p=credshopit&msg=3'</script>"; return; }
		$user2=$_POST['usuario'];
		$sql2=mysql_query("select id from usuarios where usuario='".$user2."'")or die ("Problema na conexão com  a tabela usuarios");
		$dbi2=mysql_fetch_assoc($sql2);
		$user=$_POST['usuario'];
		$valor=$verificar-($verificar*0.1);
		$valor2=ceil($valor);
		$expira=date('Y-m-d H:i:s');
		$assunto='Transferencia concluida';
        $msg='<b>Você enviou '.$verificar.' yens para '.$user.'.</b> <b><br><span style="color:#FF0000"> Atenção foi entregue '.$valor2.' yens para o '.$user.' devido a taxa de 10% de despesas de transferencias.</span></br></b> ';
        $destino=$db['id'];
		$user2=$dbi2['id'];
		$assunto2='Transferencia concluida';
        $msg2='<b>O Ninja '.$db['usuario'].' acabou de lhe doar '.$verificar.' de yens(s).</b><b><br><span style="color:#FF0000"> Você recebeu '.$valor2.' yens devido a taxa de 10% de despesas de transferencias.</span></b></br> ';
		$destino2=$user2;
		mysql_query("UPDATE usuarios SET yens=yens+".$valor2." WHERE usuario='".$user."'");
		mysql_query("UPDATE usuarios SET yens=yens-".$valor2."  WHERE id=".$db['id']);
		mysql_query("UPDATE transfer SET transferido=transferido+".$verificar."  WHERE usuarioid=".$db['id']);
        mysql_query("INSERT INTO mensagens (data, origem, destino, assunto, msg) VALUES ('$expira', 0, $destino, '$assunto', '$msg')");
        mysql_query("INSERT INTO mensagens (data, origem, destino, assunto, msg) VALUES ('$expira', 0, $destino2, '$assunto2', '$msg2')");


		echo "<script>self.location='?p=credshopit&msg=7'</script>";

	}
else
	{
		echo "<script>self.location='?p=credshopit&msg=6'</script>";
	}
?>

