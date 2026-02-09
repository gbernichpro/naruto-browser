<?php
function SomarData($data, $dias, $meses, $ano){
   $data = explode("/", $data);
   $newData = date("d/m/Y", mktime(0, 0, 0, $data[1] + $meses,
     $data[0] + $dias, $data[2] + $ano) );
   return $newData;
}

function addVip($usuarioid,$dias){
	$ex=explode(' ',date('Y-m-d H:i:s'));
	$data=explode('-',date('Y-m-d'));
	$novadata=SomarData($data[2].'/'.$data[1].'/'.$data[0],$dias,0,0);
	$data=explode('/',$novadata);
	$vipfim=$data[2].'-'.$data[1].'-'.$data[0].' '.$ex[1];
	$vipinicio=date('Y-m-d H:i:s');
	mysql_query("UPDATE usuarios SET creditos=creditos-7, vip_inicio='$vipinicio', vip='$vipfim', hunt_restantes=hunt_restantes+6 WHERE id=$usuarioid");
}

function somaVip($usuarioid,$inicial,$final){
	$ex=explode(' ',$final);
	$data=explode('-',$final);
	$dias=45;
	$novadata=SomarData($data[2].'/'.$data[1].'/'.$data[0],$dias,0,0);
	$data=explode('/',$novadata);
	$vipfim=$data[2].'-'.$data[1].'-'.$data[0].' '.$ex[1];
	$vipinicio=$inicial;
	mysql_query("UPDATE usuarios SET creditos=creditos-7, vip_inicio='$vipinicio', vip='$vipfim' WHERE id=$usuarioid");
}

/*if(isset($_GET['option'])){
	$yens=2000;
	if(($_GET['option']<1)or($_GET['option']>count($itens_valor))){ echo "<script>self.location='?p=credshop&msg=1'</script>"; return; }
	$ex=explode(' ',$itens_valor[($_GET['option']-1)]);
	$creditos=$ex[0];
	if($db['creditos']<$creditos){ echo "<script>self.location='?p=credshop&msg=2'</script>"; return; }
	switch($_GET['option']){
		case 2: mysql_query("UPDATE usuarios SET creditos=creditos-1, yens=yens+$yens, yens_fat=yens_fat+$yens WHERE id=".$db['id']); mail('credshop@narutohit.net','Compra no CredShop','Usuário '.$db['usuario'].' trocou 1 crédito por 1000 yens.'); return;
	}
	echo "<script>self.location='?p=credshop&msg=".($_GET['option']+2)."'</script>";
}*/
if(isset($_GET['option'])){
	$option = antiinjection($_GET['option']);
	$sqlc = mysql_query("SELECT * FROM credshop WHERE id=$option");
	$dbc = mysql_fetch_assoc($sqlc);
	if($db['creditos']<$dbc['valor']){ echo "<script>self.location='?p=credshop&msg=2'</script>"; return; }
	mysql_query("UPDATE usuarios SET creditos=creditos-1 WHERE id=".$db['id']);
	mail('sasuke_nab@hotmail.com','Compra no CredShop','Usuário '.$db['usuario'].' trocou '.$dbc['valor'].' créditos por '.$dbc['nome'].'.'); return;
	echo "<script>self.location='?p=credshop&msg=10'</script>";
}
?>
<script>
var httpshop = getHTTPObject();
function handleShop(){
	if(httpshop.readyState==4){
		resultado=httpshop.responseText;
		document.getElementById('div_shop').innerHTML=resultado;
	} else {
		resultado='<div align="center" style="padding-bottom:80px;"><img src="_img/skins/naruto/anim/kakashi2.gif" /><br />Carregando...</div>';
		document.getElementById('div_shop').innerHTML=resultado;
	}
}

function carregar(tipo){
	httpshop.open("GET","_ajax/ajax_credshop.php?type="+tipo, true);
	httpshop.onreadystatechange = handleShop;
	httpshop.send(null);
}
</script>
<div class="box_top">CredShop</div>
<div class="box_middle">Abaixo você encontra nosso CredShop, uma loja virtual onde poderá gastar os créditos adquiridos. Pense bem antes de comprar qualquer item, pois não é possível desfazer esta ação.<div class="sep"></div>
	<?php
	if(isset($_GET['msg'])){
		switch($_GET['msg']){
			case 1: $msg='Opção inválida.'; break;
			case 2: $msg='Créditos insuficientes.'; break;
			case 3: $msg='VIP adquirida com sucesso!'; break;
			case 4: $msg='Nome da conta alterado com sucesso!'; break;
			case 5: $msg='Vila alterada com sucesso!'; break;
			case 6: $msg='Personagem alterado com sucesso!'; break;
			case 7: $msg='Crédito foi trocado por 2.000,00 yens!'; break;
			case 8: $msg='Email da conta alterado com sucesso!'; break;
			case 9: $msg='Pergunta e Resposta Secreta alteradas com sucesso!'; break;
			case 10: $msg='Item comprado com sucesso!'; break;
		}
		echo '<div class="aviso">'.$msg.'</div><div class="sep"></div>';
	}
	?>
    <div align="center" style="background:url(_img/skins/<?php echo $db['config_skin']; ?>/gradient.jpg);height:20px;line-height:20px;"><a href="javascript:void(0);" onclick="carregar('especial');">Especiais</a> | <a href="javascript:void(0);" onclick="carregar('arma');">Armas</a> | <a href="javascript:void(0);" onclick="carregar('vestimenta');">Vestimentas</a> | <a href="javascript:void(0);" onclick="carregar('calcado');">Calçados</a> | <a href="javascript:void(0);" onclick="carregar('acessorio');">Acessórios</a> | <a href="javascript:void(0);" onclick="carregar('kuchiyose');">Kuchiyose</a></div><div class="sep"></div>
	<div style="padding-left:5px;background:url(_img/skins/<?php echo $db['config_skin']; ?>/gradient2.jpg) repeat-y;height:20px;line-height:20px;color:#FFFFAA;"><img src="_img/yens.png" width="14" height="14" align="absmiddle" /> <b>Meus Créditos:</b> <?php if($db['creditos']==0) echo 'Nenhum'; else echo $db['creditos']; ?> crédito<?php if($db['creditos']>1) echo 's'; ?></div>
	<div id="div_shop"><script>carregar('especial');</script></div>
</div>
<div class="box_bottom"></div>