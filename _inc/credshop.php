<?php
require_once('trava.php');
require_once('Encrypt.php');
$c=new C_Encrypt();


if(isset($_POST['buy_id'])){
	$buy = antiinjection($_POST['buy_id']);
	vn($buy);
	if($buy<1){ echo "<script>self.location='?p=home'</script>"; return; }
	$category = antiinjection($_POST['buy_cat']);
	if(($category<>'arma')&&($category<>'vestimenta')&&($category<>'calcado')&&($category<>'bijuu')&&($category<>'acessorios')&&($category<>'animais')){
	$data=date('Y-m-d H:i:s');
	$usuario=$db['usuario'];
	$msg='Usuario tentou burlar o sistema de credshop mudando a categoria , o item não foi inserido na conta do usuario.';
	mysql_query("INSERT INTO log_bugs (data,usuario,msg)"."VALUES ('".$data."','".$usuario."','".$msg."')");
	echo "<script>self.location='?p=home'</script>"; return; }
	switch($category){
		case 'arma': $sqlb = mysql_query("SELECT nome,categoria,creditos,credshop  FROM table_itens WHERE id=$buy"); return;
		case 'vestimenta': $sqlb = mysql_query("SELECT nome,categoria,creditos,credshop  FROM table_itens WHERE id=$buy"); break;
		case 'calcado': $sqlb = mysql_query("SELECT nome,categoria,creditos,credshop  FROM table_itens WHERE id=$buy"); break;
        case 'bijuu': $sqlb = mysql_query("SELECT nome,categoria,creditos,credshop FROM table_itens WHERE id=$buy"); break;
	    case 'acessorios': $sqlb=mysql_query("SELECT nome,categoria,creditos,credshop FROM table_itens WHERE id=$buy"); break;
	    case 'animais': $sqlb=mysql_query("SELECT nome,creditos,taijutsu,ninjutsu,genjutsu, credshop='sim'  FROM table_animais WHERE id=$buy"); break;
	}
	if(mysql_num_rows($sqlb)==0){ echo "<script>self.location='?p=home'</script>"; return; }
	$dbb=mysql_fetch_assoc($sqlb);
	if(($dbb['vip']=='sim')&&(date('Y-m-d H:i:s')>=$db['vip'])){ echo "<script>self.location='?p=home'</script>"; return; }
	//if($dbb['credshop']<>'sim'){ echo "<script>self.location='?p=home'</script>"; return; }
	//if($category<>$dbb['categoria']){ echo "<script>self.location='?p=home'</script>"; return; }
	$valor = antiinjection($dbb['creditos']);
	if(date('Y-m-d H:i:s')<$db['vip']) $valor=floor($valor);
	if(($db['renegado']=='nao')&&($buy==1)){ echo "<script>self.location='?p=home'</script>"; return; }
	$page = antiinjection($_POST['buy_page']);
	$bloq = 0;
	if($category=='acessorios')
		;
	if($category=='arma')
		if($db['taijutsu']<$dbb['reqtai']) $bloq=3;
	if($category=='vestimenta')
		if($db['genjutsu']<$dbb['reqgen']) $bloq=6;
	if($category=='calcado'){
		if($db['taijutsu']<$dbb['reqtai']) $bloq=7;
		if($db['ninjutsu']<$dbb['reqtai']) $bloq=7;
		if($db['genjutsu']<$dbb['reqtai']) $bloq=7;
	if($category=='bijuu')
		if($db['genjutsu']<$dbb['reqgen']) $bloq=8;
	}
	if($bloq>0){ echo "<script>self.location='?p=credshop&category=".$page."&msg=".$bloq."'</script>"; return; }
	if($db['creditos']<$valor){ echo "<script>self.location='?p=credshop&category=".$page."&msg=1'</script>"; return; }
	$sqli=mysql_query("SELECT count(id) conta FROM inventario WHERE usuarioid=".$db['id']." AND itemid='".antiinjection($buy)."'");
    $dbi = mysql_fetch_assoc($sqli);
	if($dbi['conta']>0){ echo "<script>self.location='?p=credshop&category=".$page."&msg=8'</script>"; return; }
	$juca=mysql_query("SELECT count(id) conta FROM animais WHERE usuarioid=".$db['id']." AND itemid='".antiinjection($buy)."'");
    $juca2=mysql_fetch_assoc($juca);
	if($juca2['conta']>0){ echo "<script>self.location='?p=credshop&category=".$page."&msg=8'</script>"; return; }
    if($category=='animais'){
	mysql_query("INSERT INTO animais (usuarioid,itemid,categoria,taijutsu,ninjutsu,genjutsu) VALUES (".$db['id'].",".$buy.",'".$category."',".$dbb['taijutsu'].",".$dbb['ninjutsu'].",".$dbb['genjutsu'].")");
	}
	else {
	mysql_query("INSERT INTO inventario (usuarioid,itemid,categoria) VALUES (".$db['id'].",".$buy.",'".$category."')");
	}
    mysql_query("UPDATE usuarios SET creditos=creditos-".$valor." , creditosusados=creditosusados+".$valor."   WHERE id=".$db['id']);
	$data=date('Y-m-d H:i:s');
	$usuario=$db['id'];
	$ass=$db['usuario'];
	$msg='Usuario adiquirio um item no credshop o seguinte item '.$dbb['nome'].' por '.$dbb['creditos'].'creditos.';
	mysql_query("INSERT INTO log_creditos (usuarioid,data,assunto,msg)"."VALUES ('".$usuario."','".$data."','".$ass."','".$msg."')");
	echo "<script>self.location='?p=credshop&category=".$page."&msg=2'</script>";
}
?>
<div class="box_top">Credshop</div>
<div class="box_middle"><div style="background: url(../_img/_detalhes/base2.PNG);width: 720px;height: 250px;">
<table cellpadding="0" cellspacing="0" width="712" height="230"><tbody><tr><td width="150">
<img width="142" style="" src="_img/_detalhes/msg/58.png"></td><td valign="top"><br><br><br>
<div style="margin-left: -345px;margin-top: 15px;height: 0px;">
<b id="title" style="font-family: impact;font-size: 20px;font-weight: normal;color: #ffffff;"  onmouseover="style.color='#F5F5F5'" onmouseout="color.color='#ffffff'">
&raquo; CredShop!</b></div><br>
<div style="margin-left: 0px;margin-top: 35px;font-family: arial;font-size: 12px;color: #fff;"><b>
Abaixo você encontra nosso CredShop, uma loja virtual onde</br>
poderá gastar os créditos adquiridos. Pense bem antes de comprar</br>
qualquer item, pois não é possível desfazer esta ação.</br>
</br>
</div></td></tr></tbody></table></div><div class="sep"></div>
	<div style="background:url(_img/gradient.jpg) repeat-y;color:#FFFFAA;"><img src="_img/yens.png" width="14" height="14" /> <b>Meus Creditos: <?php echo number_format($db['creditos'],2,',','.'); ?> Creditos</b></div><div class="sep"></div>
		<div style="background:url(_img/gradient.jpg) repeat-y;color:#FFFFAA;"><img src="_img/yens.png" width="14" height="14" /> <b>Utilizados: <?php echo number_format($db['creditosusados'],2,',','.'); ?> Creditos</b></div><div class="sep"></div>
	<?php
	if(isset($_GET['msg'])){
		switch($_GET['msg']){
			case 1: $msg='Creditos insuficientes para comprar este item.'; break;
			case 2: $msg='Item comprado com sucesso! Visite seu <a href="?p=inventory">inventário</a> agora mesmo!'; break;
			case 3: $msg='Taijutsu insuficiente para comprar este item.'; break;
			case 4: $msg='Nível insuficiente para desbloquear este personagem.'; break;
			case 5: $msg='Personagem desbloqueado!'; break;
			case 6: $msg='Genjutsu insuficiente para comprar este item.'; break;
			case 7: $msg='Taijutsu, Ninjutsu ou Genjutsu insuficiente para comprar este item.'; break;
			case 8: $msg='Você já possui este item em seu inventário.'; break;
			case 9: $msg='Troca de Vila Realizada Com Sucesso.'; break;
			case 10: $msg='Creditos insuficientes.'; break;
			case 11: $msg='Energia Restaurada! Com Sucesso'; break;
			case 12: $msg='Pacote Comprado Com Sucesso'; break;
			case 13: $msg='Doujutsu Adiquirido Com sucesso'; break;
			case 14: $msg='Vip Adiquirido Com Sucesso'; break;
			case 15: $msg='Aguarde seu vip acabar para poder comprar novamente.'; break;
			case 16: $msg='Personagem adiquirido com sucesso'; break;
			case 17: $msg='Não é possivel colocar caracteres especiais no nome de usuario'; break;
		    case 18: $msg='Campo usuario vazio.'; break;
		    case 19: $msg='Troca de nick realizado com sucesso.'; break;
		    case 20: $msg='Nome de usuario já existe tente outro.'; break;
		    case 21: $msg='Minimo 4 caracteres!.'; break;
		    case 23: $msg='Erro esta vila não existe!.'; break;
		}
	echo '<div class="aviso">'.$msg.'</div><div class="sep"></div>';
	}
	?>


 <ul class="menu">


	<li><a href="#">Especiais</a>

		<ul>
			<li><a href="?p=credshop&amp;category=bijuu" class="documents">Bijuus</a></li>
			<li><a href="?p=credshop&amp;category=animais" class="documents">Animais</a></li>
			<li><a href="?p=credshop&amp;category=doujutsu" class="documents">Doujutsu</a></li>
		    <li><a href="?p=credshop&amp;category=rare" class="documents">Pedra Rara</a></li>
		    <li><a href="?p=credshopit" class="documents">Transferencias</a></li>
		</ul>

	</li>
	<li><a href="#">Equipamentos</a>
       <ul>
	  <li><a href="?p=credshop&amp;category=weapons" class="documents"><span>Armas</span></a></li>
                <li><a href="?p=credshop&amp;category=armors" class="documents"><span>Vestimentas</span></a></li>
                <li><a href="?p=credshop&amp;category=boots" class="documents"><span>Calçados</span></a></li>
                <li><a href="?p=credshop&amp;category=acessorios" class="documents"><span>Acessorios</span></a></li>
            </ul>
	</li>

	<li><a href="#">Personagem</a>
       <ul>
	  <li><a href="?p=credshop&amp;category=vilas" class="documents"><span>Vila</span></a></li>
                <li><a href="?p=credshop&amp;category=upgrade" class="documents"><span>Energia</span></a></li>
                <li><a href="?p=credshop&amp;category=yens" class="documents"><span>Yens</span></a></li>
                <li><a href="?p=credshop&amp;category=vip" class="documents"><span>Vip</span></a></li>
                <li><a href="?p=credshop&amp;category=nick" class="documents"><span>Usuario</span></a></li>
                <li><a href="?p=credshop&amp;category=chars" class="documents"><span>Personagems</span></a></li>
            </ul>
	</li>


</ul>
    <?php
	if(!isset($_GET['category'])) require_once('credshop_weapons.php'); else
    if(isset($_GET['category'])){
		switch($_GET['category']){
			case 'weapons': require_once('credshop_weapons.php'); return;
			case 'armors': require_once('credshop_armors.php'); break;
			case 'animais': require_once('credshop_animais.php'); break;
			case 'vilas': require_once('credshopvl.php'); break;
			case 'boots': require_once('credshop_boots.php'); break;
			case 'bijuu': require_once('credshop_bijuu.php'); break;
			case 'acessorios': require_once('credshop_acessorios.php'); break;
            case 'upgrade': require_once('credshopup.php'); break;
            case 'yens': require_once('credshopyn.php'); break;
            case 'doujutsu': require_once('credshopdou.php'); break;
            case 'vip': require_once('_inc/credshopvip.php'); break;
            case 'chars': require_once('_inc/credshopch.php'); break;
            case 'rare': require_once('_inc/rare.php'); break;
            case 'nick': require_once('_inc/nick.php'); break;

		}
	}
	?>
</div></div>
<div class="box_bottom"></div>