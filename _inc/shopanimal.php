<?php
require_once('trava.php');
require_once('Encrypt.php');
$c=new C_Encrypt();

if(isset($_POST['buy_id'])){
	$buy=antiinjection($_POST['buy_id']);
	vn($buy);
	if($buy<1){ echo "<script>self.location='?p=home'</script>"; return; }
	$category=$_POST['buy_cat'];
	if($category<>'animais'){	$data=date('Y-m-d H:i:s');
	$usuario=$db['usuario'];
	$msg='Usuario tentou burlar o sistema de shop de animais mudando a categoria , o item não foi inserido na conta do usuario.';
	mysql_query("INSERT INTO log_bugs (data,usuario,msg)"."VALUES ('".$data."','".$usuario."','".$msg."')");
	 echo "<script>self.location='?p=home'</script>"; return; }
	switch($category){
        case 'animais': $sqlb=mysql_query("SELECT * FROM table_animais WHERE id=$buy"); return;
	}
	if(mysql_num_rows($sqlb)==0){ echo "<script>self.location='?p=home'</script>"; return; }
	$dbb=mysql_fetch_assoc($sqlb);
	if(($dbb['vip']=='sim')&&(date('Y-m-d H:i:s')>=$db['vip'])){ echo "<script>self.location='?p=home'</script>"; return; }
	if($category<>$dbb['categoria']){echo "<script>self.location='?p=homdase'</script>"; return; }
	$valor=$dbb['valor'];
	if(date('Y-m-d H:i:s')<$db['vip']) $valor=floor($valor*(0.8));
	//if(($db['renegado']=='nao')&&($buy==1)){ echo "<script>self.location='?p=home'</script>"; return; }
	$page=$_POST['buy_page'];
	$bloq=0;
		if($category=='animais')
		if($db['nivel']<$dbb['reqnivel']) $bloq=9;
	if($bloq>0){ echo "<script>self.location='?p=shopanimal&category=".$page."&msg=".$bloq."'</script>"; return; }
	if($db['yens']<$valor){ echo "<script>self.location='?p=shopanimal&category=".$page."&msg=1'</script>"; return; }
	$sqli=mysql_query("SELECT count(id) conta FROM animais WHERE usuarioid=".$db['id']." AND itemid=".$buy);
    $dbi=mysql_fetch_assoc($sqli);
	if($dbi['conta']>0){ echo "<script>self.location='?p=shopanimal&category=".$page."&msg=8'</script>"; return; }
    mysql_query("INSERT into animais (usuarioid,itemid,categoria,taijutsu,ninjutsu,genjutsu) values (".$db['id'].",".$buy.",'".$category."','1','1','1')");
    mysql_query("UPDATE usuarios SET yens=yens-".$valor." WHERE id=".$db['id']);
	echo "<script>self.location='?p=shopanimal&category=".$page."&msg=2'</script>";

}
?>
<div class="box_top">Kuchiyose no Jutsu</div>
<div class="box_middle">Um bom ninja se difere dos outros pelos jutsus que ele utiliza nas batalhas, principalmente quando se trata de invocações. O Kuchiyose no Jutsu é a técnica utilizada para esta finalidade, e somente ninjas experientes podem invocar estas criaturas. <?php if(date('Y-m-d H:i:s')<$db['vip']) echo '<br /><b>OBS: Os itens estão com 20% de desconto pela sua VIP.</b>'; ?><div class="sep"></div>
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
			case 10: $msg='Você já possui um pacto formado.'; break ;
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
	if(!isset($_GET['category'])) require_once('shopanimal_animais.php'); else
    if(isset($_GET['category'])){
		switch($_GET['category']){
			case 'animais': require_once('shopanimal_animais.php'); return;
		}
	}
	?>
</div></div>
<div class="box_bottom"></div>
