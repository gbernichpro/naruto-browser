<?php
require_once('trava.php');
require_once('Encrypt.php');
$c = new C_Encrypt();

if(isset($_POST['char_id'])){
	$buy = $c->decode($_POST['char_id'],$chaveuniversal);
	$nivel = $c->decode($_POST['char_nivel'],$chaveuniversal);
	vn($buy); vn($nivel);
	$personagem = $c->decode($_POST['char_char'],$chaveuniversal);
	if($db['nivel']<$nivel){ echo "<script>self.location='?p=shop&category=characters&msg=4'</script>"; return; }
	mysql_query("UPDATE personagens SET ".$personagem."=1 WHERE usuarioid='".antiinjection($db['id'])."'");
    echo "<script>self.location='?p=home&msggg=1'</script>";
}

if(isset($_POST['buy_id'])){
	$buy = antiinjection($_POST['buy_id']);
	vn($buy);
	if($buy<1){ echo "<script>self.location='?p=home'</script>"; return; }
	$category = antiinjection($_POST['buy_cat']);
	if(($category<>'arma')&&($category<>'vestimenta')&&($category<>'calcado')&&($category<>'bijuu')&&($category<>'acessorios')&&($category<>'bolsas')){
	$data=date('Y-m-d H:i:s');
	$usuario=$db['usuario'];
	$msg='Usuario tentou burlar o sistema do Shop normal mudando a categoria , o item não foi inserido na conta do usuario.';
	mysql_query("INSERT INTO log_bugs (data,usuario,msg)"."VALUES ('".$data."','".$usuario."','".$msg."')");
	echo "<script>self.location='?p=home'</script>"; return; }
	switch($category){
		case 'arma': $sqlb = mysql_query("SELECT valor, reqtai,reqnivel, vip,categoria,credshop FROM table_itens WHERE id='".antiinjection($buy)."'"); return;
		case 'vestimenta': $sqlb = mysql_query("SELECT valor, reqgen,reqnivel, vip,categoria,credshop FROM table_itens WHERE id='".antiinjection($buy)."'"); break;
		case 'calcado': $sqlb = mysql_query("SELECT valor, reqtai, vip,categoria,credshop FROM table_itens WHERE id='".antiinjection($buy)."'"); break;
        case 'bijuu': $sqlb = mysql_query("SELECT valor, reqnin,reqnivel, vip,categoria,credshop FROM table_itens WHERE id='".antiinjection($buy)."'"); break;
        case 'acessorios': $sqlb = mysql_query("SELECT valor, reqnivel, vip,categoria,credshop FROM table_itens WHERE id='".antiinjection($buy)."'"); break;
        case 'bolsas': $sqlb = mysql_query("SELECT valor,categoria,credshop FROM table_bolsas WHERE id='".antiinjection($buy)."'"); break;
	}
	if(mysql_num_rows($sqlb)==0){ echo "<script>self.location='?p=home'</script>"; return; }
	$dbb = mysql_fetch_assoc($sqlb);
	if(($dbb['vip']=='sim')&&(date('Y-m-d H:i:s')>=$db['vip'])){ echo "<script>self.location='?p=home'</script>"; return; }
	if($category<>$dbb['categoria']){ echo "<script>self.location='?p=home'</script>"; return; }
	$valor = $dbb['valor'];
	if($category=='bolsas')
	{
	$valor = floor($valor);
	}
	elseif(date('Y-m-d H:i:s')<$db['vip']) $valor = floor($valor*(0.8));
	if(($db['renegado']=='nao')&&($buy==1)){ echo "<script>self.location='?p=home'</script>"; return; }
	$page = antiinjection($_POST['buy_page']);
	$bloq = 0;
	if($category=='acessorios')
		if($db['nivel']<$dbb['reqnivel']) $bloq=9;
	if($category=='bolsas');
	if($category=='arma')
		if($db['nivel']<$dbb['reqnivel']) $bloq=9;
	if($category=='vestimenta')
		if($db['nivel']<$dbb['reqnivel']) $bloq=9;
	if($category=='calcado'){
		if($db['taijutsu']<$dbb['reqtai']) $bloq=7;
		if($db['ninjutsu']<$dbb['reqtai']) $bloq=7;
		if($db['genjutsu']<$dbb['reqtai']) $bloq=7;
	if($category=='bijuu')
		if($db['nivel']<$dbb['reqnivel']) $bloq=9;
	}
	if($bloq>0){ echo "<script>self.location='?p=shop&category=".$page."&msg=".$bloq."'</script>"; return; }
	if($db['yens']<$valor){ echo "<script>self.location='?p=shop&category=".$page."&msg=1'</script>"; return; }
	if($category<>'bolsas'){
	$sqli = mysql_query("SELECT count(id) conta FROM inventario WHERE usuarioid='".$db['id']."' AND itemid='".antiinjection($buy)."'");
    $dbi=mysql_fetch_assoc($sqli);
	if($dbi['conta']>0){ echo "<script>self.location='?p=shop&category=".$page."&msg=8'</script>"; return; }
	}
	else
	{
	$sqli2=mysql_query("SELECT count(id) conta FROM bolsas WHERE usuarioid='".$db['id']."' AND itemid='".antiinjection($buy)."'");
    $dbi2=mysql_fetch_assoc($sqli2);
	if($dbi2['conta']>0){ echo "<script>self.location='?p=shop&category=".$page."&msg=8'</script>"; return; }
    }
    mysql_query("UPDATE usuarios SET yens=yens-".$valor." WHERE id='".$db['id']."'");
	if($category=='bolsas'){
    mysql_query("INSERT INTO bolsas (usuarioid,itemid,categoria) VALUES (".$db['id'].",".$buy.",'".$category."')");
	echo "<script>self.location='?p=shop&category=".$page."&msg=2'</script>";
	 }else{
	mysql_query("INSERT INTO inventario (usuarioid,itemid,categoria) VALUES (".$db['id'].",".$buy.",'".$category."')");
	echo "<script>self.location='?p=shop&category=".$page."&msg=2'</script>";
	}
}
?>
<div class="box_top">Comercio</div>
<div class="box_middle">
<div style="background: url(../_img/_detalhes/base2.PNG);width: 720px;height: 250px;">
<table cellpadding="0" cellspacing="0" width="712" height="230"><tbody><tr><td width="150">
<img width="142" style="" src="_img/_detalhes/msg/28.png"></td><td valign="top"><br><br><br>
<div style="margin-left: -345px;margin-top: 15px;height: 0px;">
<b id="title" style="font-family: impact;font-size: 20px;font-weight: normal;color: #ffffff;"  onmouseover="style.color='#F5F5F5'" onmouseout="color.color='#ffffff'">
&raquo; Elemento do Chakra Aberto!</b></div><br>
<div style="margin-left: 0px;margin-top: 35px;font-family: arial;font-size: 12px;color: #fff;"><b>
Bem-vindo ao centro comercial da vila. Temos tudo que você</br>
precisa para sua jornada no mundo ninja! Selecione uma das</br>
categorias abaixo e boas compras!<?php if(date('Y-m-d H:i:s')<$db['vip']) echo '<br /><b>OBS: Os itens estão com 20% de desconto pela sua VIP.</b>'; ?></br>
</div></td></tr></tbody></table></div>
<div class="sep"></div>
	<div style="background:url(_img/gradient.jpg) repeat-y;color:#FFFFAA;"><img src="_img/yens.png" width="14" height="14" /> <b>Meus Yens: <?php echo number_format($db['yens'],2,',','.'); ?> yens</b></div><div class="sep"></div>
	<?php
	if(isset($_GET['msg'])){
		switch($_GET['msg']){
			case 1: $msg='Yens insuficientes para comprar este item.'; break;
			case 2: $msg='Item comprado com sucesso! Visite seu <a href="?p=inventory">inventário</a> agora mesmo!'; break;
			case 3: $msg='Taijutsu insuficiente para comprar este item.'; break;
			case 4: $msg='Nível insuficiente para desbloquear este personagem.'; break;
			case 5: $msg='Personagem desbloqueado!'; break;
			case 6: $msg='Genjutsu insuficiente para comprar este item.'; break;
			case 7: $msg='Taijutsu, Ninjutsu ou Genjutsu insuficiente para comprar este item.'; break;
			case 8: $msg='<script>top.$.prompt("Você ja possui este item no seu inventario!");</script>'; break;
			case 9: $msg='Nível insuficiente para adiquirir este item.'; break;
		}
	echo '<div class="aviso">'.$msg.'</div><div class="sep"></div>';
	}
	?>
 <ul class="menu">


	<li><a href="#">Items</a>

		<ul>
			<li><a href="?p=shop&amp;category=weapons" class="documents">Armas</a></li>
			<li><a href="?p=shop&amp;category=armors" class="documents">Vestimentas</a></li>
			<li><a href="?p=shop&amp;category=boots" class="documents">Calçados</a></li>
			<li><a href="?p=shop&amp;category=acessorios" class="documents"><span>Acessorios</span></a></li>
		</ul>
	</li>
	<li><a href="#">Especiais</a>
       <ul>
	  <li><a href="?p=shop&amp;category=bijuu" class="documents"><span>Bijuus</span></a></li>
	  <li><a href="?p=shopselos" class="documents"><span>Selos</span></a></li>
     <li><a href="?p=portoes" class="documents"><span>Portões</span></a></li>
     <li><a href="?p=shopanimal" class="documents"><span>Animais</span></a></li>
            </ul>
	</li>

	<li><a href="#">Outros</a>
       <ul>
	  <li><a href="?p=shop&amp;category=bolsas" class="documents"><span>Mochila Ninja</span></a></li>

            </ul>
	</li>

	<li><a href="#">Comercio</a>
       <ul>
	 <li><a href="?p=shops" class="documents"><span>Items/Bijju</span></a></li>
     <li><a href="?p=shoppet" class="documents"><span>Animais</span></a></li>
            </ul>
	</li>

</ul>
    <div class="sep"></div>
    <?php
	if(!isset($_GET['category'])) require_once('shop_weapons.php'); else
    if(isset($_GET['category'])){
		switch($_GET['category']){
			case 'weapons': require_once('shop_weapons.php'); return;
			case 'armors': require_once('shop_armors.php'); break;
			case 'characters': require_once('shop_characters.php'); break;
			case 'boots': require_once('shop_boots.php'); break;
			case 'bijuu': require_once('shop_bijuu.php'); break;
			case 'acessorios': require_once('shop_acessorios.php'); break;
			case 'bolsas': require_once('shop_bolsas.php'); break;
		}
	}
	?>
</div>
<div class="box_bottom"></div>