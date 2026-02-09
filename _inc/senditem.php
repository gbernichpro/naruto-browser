<?php require_once('trava.php'); ?>
 <?php

if(isset($_POST['usuario'])){
    if($db['pass']<>$_POST['senha']){echo "<script>self.location='?p=senditem&msg=5'</script>"; return;}
    if($db['creditos']<1){echo "<script>self.location='?p=senditem&msg=7'</script>";; return;}
    if(date('Y-m-d H:i:s')>=$db['vip']){echo "<script>self.location='?p=senditem&msg=12'</script>";; return;}
    if($db['nivel']<30){echo "<script>self.location='?p=senditem&msg=8'</script>";; return;}
    if($_POST['usuario']==$db['usuario']){echo "<script>self.location='?p=senditem&msg=10'</script>";; return;}
    $id=antiinjection($_POST['usuario']);
	$sqleee=mysql_query("SELECT * from inventario where usuarioid=".$db['id']."");
    $dbree=mysql_fetch_assoc($sqleee);
    if(mysql_num_rows($sqleee)<=0){echo "<script>self.location='?p=senditem&msg=2'</script>"; return;}
	$sqlee=mysql_query("SELECT i.*, t.* FROM inventario i LEFT OUTER JOIN table_itens t ON i.itemid=t.id WHERE t.nome='".antiinjection($_POST['enviaritem'])." i.usuarioid=".$db['id']."");
    $dbre=mysql_fetch_assoc($sqlee);
    $data=date('Y-m-d H:i:s');
    $teste=mysql_query("Select * from usuarios where usuario='".antiinjection($_POST['usuario'])."'");
    $test=mysql_fetch_assoc($teste);
    if(mysql_num_rows($teste)<=0){echo "<script>self.location='?p=senditem&msg=11'</script>"; return;}
    $iten=mysql_query("SELECT * FROM table_itens where nome='".antiinjection($_POST['enviaritem'])."'");
    $it=mysql_fetch_assoc($iten);
    $seg=mysql_query("SELECT * FROM inventario where usuarioid=".$db['id']." and itemid='".$it['id']."'");
    $it2=mysql_fetch_assoc($seg);
    if(mysql_num_rows($seg)<=0){ echo "<script>self.location='?p=senditem&msg=9'</script>"; return;}
    if(mysql_num_rows($iten)<=0){echo "<script>self.location='?p=home2'</script>"; return;}
    mysql_query("UPDATE usuarios set creditos=creditos-1 WHERE id='".$db['id']);
    mysql_query("DELETE FROM inventario WHERE usuarioid='".$db['id']."' and itemid=".$it['id']);
    mysql_query("INSERT INTO inventario (usuarioid,itemid,categoria,upgrade) VALUES (".$test['id'].",".$it['id'].",'".$it['categoria']."','".$it2['upgrade']."')");
	$expira=date('Y-m-d H:i:s');
	$assunto='Transferencia de items';
    $msg='Informamos que você acabou de transferir um item o item foi '.$it['nome'].'.<br />Transferencia concluida. foi enviado para o jogador '.$id.'.';
    $destino=$db['id'];
    mysql_query("INSERT INTO mensagens (data, origem, destino, assunto, msg) VALUES ('$expira', 0, $destino, '$assunto', '$msg')");
	$expira2=date('Y-m-d H:i:s');
	$assunto2='Transferencia de items';
    $msg2='Informamos que você acabou receber um item o item foi '.$it['nome'].'.<br />Transferencia concluida. foi enviado pelo  jogador '.$db['usuario'].'.';
    $destino2=$test['id'];
    mysql_query("INSERT INTO mensagens (data, origem, destino, assunto, msg) VALUES ('$expira2', 0, $destino2, '$assunto2', '$msg2')");
	mysql_query("INSERT INTO log_transferencias (usuarioid,data, assunto, msg) VALUES (".$db['id'].",'".date('Y-m-d H:i:s')."','".$db['usuario']."', 'Acabou de transferir o item <b>".$it['nome']."</b>, para ".$id." , foi enviado com sucesso</b>.')");
	if(mysql_affected_rows()==0){ echo "<script>self.location='?p=home'</script>"; return; }
	echo "<script>self.location='?p=senditem&msg=1'</script>";
}


?>

<div class="box_top">Transferencia de items</div>
<div class="box_middle">Sistema de transferencia de items entre contas use com sabedoria otimo para quem vende items por creditos para não utilizar o comercio interno.caso utilize em multi conta será banido!<div class="sep"></div>
 <?php
	if(isset($_GET['msg'])){
		switch($_GET['msg']){
			case 1: $msg='<b>Item enviado com sucesso</b>'; break;
			case 2: $msg='<b>Você não possui nenhum item para enviar!'; break;
		    case 5: $msg='Senha incorreta impossivel enviar item!'; break;
		    case 6: $msg='Não existe nenhum usuario com o nick informado!'; break;
		    case 7: $msg='Creditos insuficientes para realizar esta transferencia!'; break;
		    case 8: $msg='Nivel insuficiente para realizar está transferencia!'; break;
		    case 9: $msg='Você não possue este item na sua conta!'; break;
		    case 10: $msg='Você não pode enviar items para sí mesmo!'; break;
		    case 11: $msg='Usuario não existe!'; break;
		    case 12: $msg='Você Não é um usuario vip!'; break;
		}
	echo '<div class="sep"></div><div class="aviso">'.$msg.'</div>';
	}
	?>
	 <?php
	 $sqleee=mysql_query("SELECT * from inventario where usuarioid=".$db['id']."");
    $dbree=mysql_fetch_assoc($sqleee);



 ?>
 <form method="POST" action="?p=senditem">

	<table border="0" width="100%">
	<?php if(mysql_num_rows($sqleee)<=0){echo "<div class='aviso'><b>Você Não Possui items para enviar</b></div>";}

	else{
	?>

<tr><td><b>Selecione um item para ser enviado:</b></td>  <td><select name="enviaritem" size="1">
<?php
$sqle=mysql_query("SELECT i.*, t.* FROM inventario i LEFT OUTER JOIN table_itens t ON i.itemid=t.id WHERE i.usuarioid=".$db['id']."");
$dbr=mysql_fetch_assoc($sqle);
?>
<?php do{ ?>
<option name="<?=$dbr['id']?>"><?=$dbr['nome']?></option>
<?php $i++; } while($dbr=mysql_fetch_assoc($sqle)); ?>
</select>



</td></tr>
<tr><td><b>Usuario Beneficiado:</b></td>  <td><input type="text"  value="Usuario" original-title="Digite aqui o nick do usuario a ser enviado o item." class="jrrios" name="usuario" size="20"></td></tr>
<tr><td><b>Senha:</b></td>  <td><input type="text" value="Digite Sua Senha" original-title="Digite aqui a senha extra da sua loja e venda de items etç." class="jrrios" name="senha" size="20"></td></tr>
 <?php } ?>
<td>* <small>Necessário No Minimo 1 Crédito(taxa).!</small> <?php if($db['creditos']>1){echo "<img src='_img/accept.png'>"; }else echo"<img src='_img/cross.png'>";?>
<br>*<small> Necessário Ser VIP Durante a  Transferência</small>.! <?php if(date('Y-m-d H:i:s')<$db['vip']){echo "<img src='_img/accept.png'>"; }else echo"<img src='_img/cross.png'>";?>
<br>*<small> Necessário Possuir Nivel maior que 30.! </small><?php if($db['nivel']>30){echo "<img src='_img/accept.png'>"; }else echo"<img src='_img/cross.png'>";?>
<br>*<small> Log de transferencia enviado a ambos os jogadores .! <img src='_img/accept.png'>
          </td></td>

  	</tr>
    </table>
   <div align="center"> <input class="botao" type="submit" name="trans" value="Enviar"></div>
</form>

</div>
<div class="box_bottom"></div>