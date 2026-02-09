<?php
require_once('trava.php');
require_once('Encrypt.php');
$c = new C_Encrypt();
if($db['missao']>0){ echo "<script>self.location='?p=busymission'</script>"; return; }
if($db['orgid']==0){ echo "<script>self.location='?p=home'</script>"; return; }
 if(isset($_POST['buy_id2'])){
	$buy2 = antiinjection($_POST['buy_id2']);
	vn($buy2);
	if($buy2<1){ echo "<script>self.location='?p=home'</script>"; return; }
	$category2 = antiinjection($_POST['buy_cat2']);
	if(($category2<>'arma')&&($category2<>'vestimenta')&&($category<>'calcado')){ echo "<script>self.location='?p=home'</script>"; return; }
	switch($category2){
		case 'arma': $sqlb2 = mysql_query("SELECT valor, clashop='sim' FROM table_itens WHERE id='".antiinjection($buy2)."'"); return;
		case 'vestimenta': $sqlb2 = mysql_query("SELECT valor, clashop='sim' FROM table_itens WHERE id='".antiinjection($buy2)."'"); break;
	    case 'calcado': $sqlb = mysql_query("SELECT valor, clashop='sim' FROM table_itens WHERE id='".antiinjection($buy)."'"); break;
	}
	if(mysql_num_rows($sqlb2)==0){ echo "<script>self.location='?p=ho244me'</script>"; return; }
	$dbb2 = mysql_fetch_assoc($sqlb2);
	if(($dbb2['vip']=='sim')&&(date('Y-m-d H:i:s')>=$db['vip'])){ echo "<script>self.location='?p=home'</script>"; return; }
	$valor2 = $dbb2['valor'];
	if(($db['renegado']=='nao')&&($buy2==1)){ echo "<script>self.location='?p=home'</script>"; return; }
	$page2 = antiinjection($_POST['buy_page']);
	$bloq2 = 0;
	if($category2=='acessorios')
		;
	if($category2=='arma')
		if($db['taijutsu']<$dbb2['reqtai']) $bloq=3;
	if($category2=='vestimenta')
		if($db['genjutsu']<$dbb2['reqgen']) $bloq=6;
	if($category2=='calcado'){
		if($db['taijutsu']<$dbb2['reqtai']) $bloq=7;
		if($db['ninjutsu']<$dbb2['reqtai']) $bloq=7;
		if($db['genjutsu']<$dbb2['reqtai']) $bloq=7;
	if($category2=='bijuu')
		if($db['genjutsu']<$dbb2['reqgen']) $bloq=8;
	}
	if($bloq>0){ echo "<script>self.location='?p=cla_shop&category=".$page."&msg=".$bloq."'</script>"; return; }
	if($db['pontoscla']<$valor2){ echo "<script>self.location='?p=cla_shop&category=".$page."&msg=1'</script>"; return; }
	$sqli2 = mysql_query("SELECT count(id) conta FROM inventario WHERE usuarioid='".$db['id']."' AND itemid='".antiinjection($buy2)."'");
    $dbi2=mysql_fetch_assoc($sqli2);
	if($dbi['conta']>0){ echo "<script>self.location='?p=cla_shop&category=".$page2."&msg=8'</script>"; return; }
    mysql_query("UPDATE usuarios SET pontoscla=pontoscla-".$valor2." WHERE id='".$db['id']."'");
    mysql_query("INSERT INTO inventario (usuarioid,itemid,categoria) VALUES (".$db['id'].",".$buy2.",'".$category2."')");
	echo "<script>self.location='?p=cla_shop&category=".$page2."&msg=2'</script>";

}



if(isset($_POST['buy_id'])){
	$buy = antiinjection($_POST['buy_id']);
	vn($buy);
	if($buy<1){ echo "<script>self.location='?p=home'</script>"; return; }
	$category = antiinjection($_POST['buy_cat']);
	if(($category<>'hp')&&($category<>'cred')&&($category<>'yens')){ echo "<script>self.location='?p=home'</script>"; return; }
	switch($category){
		case 'hp': $sqlb = mysql_query("SELECT valor FROM table_clashop WHERE id='".antiinjection($buy)."'"); return;
		case 'cred': $sqlb = mysql_query("SELECT valor FROM table_clashop WHERE id='".antiinjection($buy)."'"); break;
		case 'yens': $sqlb = mysql_query("SELECT valor FROM table_clashop WHERE id='".antiinjection($buy)."'"); break;

	}
	if(mysql_num_rows($sqlb)==0){ echo "<script>self.location='?p=home'</script>"; return; }
	$dbb = mysql_fetch_assoc($sqlb);
	if(($dbb['vip']=='sim')&&(date('Y-m-d H:i:s')>=$db['vip'])){ echo "<script>self.location='?p=home'</script>"; return; }
	$valor = $dbb['valor'];
	$page = antiinjection($_POST['buy_page']);
	$bloq = 0;
	if($bloq>0){ echo "<script>self.location='?p=cla_shop&category=".$page."&msg=".$bloq."'</script>"; return; }
    if($db['pontoscla']<$valor){ echo "<script>self.location='?p=cla_shop&category=".$page."&msg=1'</script>"; return; }
	$sqli = mysql_query("SELECT count(id) conta FROM invcla WHERE usuarioid='".$db['id']."' AND itemid='".antiinjection($buy)."'");
    $dbi=mysql_fetch_assoc($sqli);
	if($dbi['conta']>0){ echo "<script>self.location='?p=cla_shop&category=".$page."&msg=8'</script>"; return; }
    mysql_query("UPDATE usuarios SET pontoscla=pontoscla-".$valor." WHERE id='".$db['id']."'");
	mysql_query("INSERT INTO invcla (usuarioid,itemid,categoria) VALUES (".$db['id'].",".$buy.",'".$category."')");
    echo "<script>self.location='?p=cla_shop&category=".$page."&msg=2'</script>";

}
?>
<div class="box_top">Comércio dos clãs</div>
<div class="box_middle">Bem-vindo ao comercio dos clãs das vilas aki poderá gastar os pontos obtidos através de missoes ou guerras ninjas, os items aki não são encontrados no jogo porém tem que obter muitos pontos para conseguir estes lindos items!<?php if(date('Y-m-d H:i:s')<$db['vip']) echo '<br /><b>OBS: Os itens estão com 20% de desconto pela sua VIP.</b>'; ?><div class="sep"></div>
	<div style="background:url(_img/gradient.jpg) repeat-y;color:#FFFFAA;"><img src="_img/yens.png" width="14" height="14" /> <b>Meus Pts: <?php echo number_format($db['pontoscla'],2,',','.'); ?> Pts</b></div><div class="sep"></div>
	<?php
	if(isset($_GET['msg'])){
		switch($_GET['msg']){
			case 1: $msg='Pontos insuficientes para comprar este item.'; break;
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
<div id="menu">
    <ul class="menu">
        <li><a href="#" class="parent" align="center"><span>Acessorios</span></a>
            <ul>

                    <ul>

                            <ul>

                            </ul>
                        </li>


                            <ul>

                            </ul>
                        </li>
                    </ul>
                </li>
                <li><a href="?p=cla_shop&amp;category=hp" class="documents"><span>Comsumiveis</span></a></li>
                <li><a href="?p=cla_shop&amp;category=creds" class="documents"><span>Creditos</span></a></li>
                <li><a href="?p=cla_shop&amp;category=yens" class="documents"><span>Yens</span></a></li>
            </ul>
        </li>
        <li><a href="#" class="parent"><span>Equipamentos</span></a>
            <ul>

                  <ul>

                    </ul>
                </li>

                    <ul>

                    </ul>
                </li>
                <li><a href="?p=cla_shop&amp;category=weapons" class="documents"><span>Armas</span></a></li>
                <li><a href="?p=cla_shop&amp;category=armors" class="documents"><span>Vestimentas</span></a></li>
                <li><a href="?p=cla_shop&amp;category=boots" class="documents"><span>Calçados</span></a></li>
            </ul>
        </li>

    </ul>
</div>

    <div class="sep"></div>
    <?php
	if(!isset($_GET['category'])) require_once('cla_shopweapons.php'); else
    if(isset($_GET['category'])){
		switch($_GET['category']){
			case 'hp': require_once('cla_shophp.php'); return;
			case 'weapons': require_once('cla_shopweapons.php'); break;
            case 'armors': require_once('cla_shoparmors.php'); break;
            case 'boots': require_once('cla_shopboots.php'); break;
            case 'creds': require_once('cla_shopcred.php'); break;
            case 'yens': require_once('cla_shopyens.php'); break;
		}
	}
	?>
</div>
<div class="box_bottom"></div>