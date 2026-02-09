<?php
	require_once('conexao.php');
	$chaveuniversal='hgfdhgfd';
	require_once('Encrypt.php');
	$c=new C_Encrypt();
$jogadorid=$_POST['jogador'];
$jogadorid=$c->decode($jogadorid,$chaveuniversal);
$tempo = time();
$calc = $tempo + 86400;
$rand = rand(1,5);
$sql=mysql_query("SELECT * FROM usuarios WHERE id=".$jogadorid);
$db = mysql_fetch_assoc($sql);
if(mysql_num_rows($sql)==0)
	{
			echo "<script>self.location='?p=login'</script>";	
	}
else
	{
	if ($db['premiodiario'] > $tempo)
		{
			;
		}else{

	switch($rand)
		{
			case 1: mysql_query("update usuarios set yens=yens+1000 , yens_fat=yens_fat+1000 where id=".$jogadorid); echo "<script>top.$.prompt('Parabéns! Você recebeu 1.000,00 yens no seu prêmio diário!');</script>"; break;
			case 2: mysql_query("update usuarios set creditos=creditos+1 where id=".$jogadorid); echo "<script>top.$.prompt('Parabéns! Você recebeu 1 de creditos no seu prêmio diário!');</script>"; break;
			case 3: mysql_query("update usuarios set yens=yens+3000 , yens_fat=yens_fat+3000 where id=".$jogadorid); echo "<script>top.$.prompt('Parabéns! Você recebeu 3.000,00 yens no seu prêmio diário!');</script>"; break;
			case 4: mysql_query("insert into ramen (usuarioid, ramenid)"."VALUES ('".$jogadorid."', '5')");  echo "<script>top.$.prompt('Parabéns! Você conseguio um RAMEN no seu prêmio diário!');</script>"; break;
			case 5: echo "<script>top.$.prompt('Infelizmente você não teve sorte hoje não obteve nenhum premio.');</script>"; break;
		}
	mysql_query("update usuarios set premiodiario=".$calc." where id=".$jogadorid);
	}
	}
?>