<?php
require_once('trava.php');
require_once('Encrypt.php');
$c = new C_Encrypt();

if(isset($_POST['buy_id'])){
	$buy = antiinjection($_POST['buy_id']);
	vn($buy);
	if($buy<1){ echo "<script>self.location='?p=home'</script>"; return; }
	$category = antiinjection($_POST['buy_cat']);
	if(($category<>'portao')){
	$data=date('Y-m-d H:i:s');
	$usuario=$db['usuario'];
	$msg='Usuario tentou burlar o sistema de shop de portão do chakra mudando a categoria , o item não foi inserido na conta do usuario.';
	mysql_query("INSERT INTO log_bugs (data,usuario,msg)"."VALUES ('".$data."','".$usuario."','".$msg."')");
	echo "<script>self.location='?p=home'</script>"; return; }
	switch($category){
		case 'portao': $sqlb = mysql_query("SELECT valor, reqtai, vip FROM table_portoes WHERE id='".antiinjection($buy)."'"); return;
	}
	if(mysql_num_rows($sqlb)==0){ echo "<script>self.location='?p=home'</script>"; return; }
	$dbb = mysql_fetch_assoc($sqlb);
	if(($dbb['vip']=='sim')&&(date('Y-m-d H:i:s')>=$db['vip'])){ echo "<script>self.location='?p=home'</script>"; return; }
	$valor = $dbb['valor'];
	if(date('Y-m-d H:i:s')<$db['vip']) $valor = floor($valor*(0.8));
	if(($db['renegado']=='nao')&&($buy==1)){ echo "<script>self.location='?p=home'</script>"; return; }
	$page = antiinjection($_POST['buy_page']);
	$bloq = 0;
	if($category=='portao')
		if($db['nivel']<$dbb['reqnivel']) $bloq=9;

	if($bloq>0){ echo "<script>self.location='?p=portoes&category=".$page."&msg=".$bloq."'</script>"; return; }
	if($db['yens']<$valor){

	echo "<script>$.prompt('Yens insuficientes para realizar esta compra!.');</script>"; }
	else{
	$sqli = mysql_query("SELECT count(id) conta FROM portao WHERE usuarioid='".$db['id']."' AND itemid='".antiinjection($buy)."'");
    $dbi=mysql_fetch_assoc($sqli);
	if($dbi['conta']>0){ echo "<script>$.prompt('Você já possui este item no inventario!.');</script>"; }
	else
	{
    $sqlbuscavida=mysql_query("select * from table_portoes where id=$buy");
    $sqlbusca=mysql_fetch_assoc($sqlbuscavida);
    $expira=date('Y-m-d H:i:s',time()+($sqlbusca['vida']*24*60*60));
    mysql_query("UPDATE usuarios SET yens=yens-".$valor." WHERE id='".$db['id']."'");
	mysql_query("INSERT INTO portao (usuarioid,itemid,categoria,expira) VALUES (".$db['id'].",".$buy.",'".$category."','".$expira."')");
    echo "<script>$.prompt('Compra Realizada com sucesso visite seu inventario!.');</script>";
}
}
}
?>
<div class="box_top">Portoes do chakra</div>
<div class="box_middle"><div style="background: url(../_img/_detalhes/base2.PNG);width: 720px;height: 250px;">
<table cellpadding="0" cellspacing="0" width="712" height="230"><tbody><tr><td width="150">
<img width="142" style="" src="_img/_detalhes/msg/28.png"></td><td valign="top"><br><br><br>
<div style="margin-left: -345px;margin-top: 15px;height: 0px;">
<b id="title" style="font-family: impact;font-size: 20px;font-weight: normal;color: #ffffff;"  onmouseover="style.color='#F5F5F5'" onmouseout="color.color='#ffffff'">
&raquo; Portões do chakra!</b></div><br>
<div style="margin-left: 0px;margin-top: 35px;font-family: arial;font-size: 12px;color: #fff;"><b>
Consiste basicamente em um sistema circulatório de chakra, composto<br />
 por 8 portões que controlam o fluxo do chakra, para que este não<br />
 ultrapasse o limite que o corpo de um shinobi agüenta. É possível<br />
 abrir estes portões, mas isso acarreta em conseqüências sérias para<br />
 corpo do shinobi. O chamado “Lótus” desativa o controle dos portões<br />
 dando uma força inigualável ao usuário.<br />
</div></td></tr></tbody></table></div><div class="sep"></div>
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
			case 8: $msg='Você já possui este item em seu inventário.'; break;
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
            </ul>
	</li>

	<li><a href="#">Outros</a>
       <ul>
	  <li><a href="?p=shop&amp;category=bolsas" class="documents"><span>Mochila Ninja</span></a></li>
     <li><a href="?p=shops" class="documents"><span>Comercio</span></a></li>
            </ul>
	</li>


</ul>

    <div class="sep"></div>
    <?php
	if(!isset($_GET['category'])) require_once('shop_portao.php'); else
    if(isset($_GET['category'])){
		switch($_GET['category']){
			case 'portao': require_once('shop_portao.php'); return;
		}
	}
	?>
</div></div>
<div class="box_bottom"></div>