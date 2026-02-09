<?php
if(isset($_GET['buy'])){
	vn($_GET['buy']);
	$sqli = mysql_query("SELECT i.usuarioid,i.venda,i.valor,t.nome FROM inventario i LEFT OUTER JOIN table_itens t ON i.itemid=t.id WHERE i.id='".antiinjection($_GET['buy'])."'");
	if(mysql_num_rows($sqli)==0){ echo "<script>self.location='?p=shops&item=".$_GET['item']."&msg=1'</script>"; return; }
	$dbi = mysql_fetch_assoc($sqli);
	if($dbi['usuarioid']==$db['id']){ echo "<script>self.location='?p=shops&item=".$_GET['item']."&msg=3'</script>"; return; }
	if($dbi['venda']=='nao'){ echo "<script>self.location='?p=home'</script>"; return; }
	if($db['yens']<$dbi['valor']){ echo "<script>self.location='?p=shops&item=".$_GET['item']."&msg=2'</script>"; return; }
	mysql_query("UPDATE usuarios SET yens=yens-".$dbi['valor'].", compraloja=compraloja+1 WHERE id=".$db['id']);
	mysql_query("INSERT INTO vendas (usuarioid, valor) VALUES (".$dbi['usuarioid'].", ".$dbi['valor'].")");
	mysql_query("INSERT INTO mensagens (data, origem, destino, assunto, msg) VALUES ('".date('Y-m-d H:i:s')."', 0, ".$dbi['usuarioid'].", 'Item Vendido!', 'Parabéns, o item <b>".$dbi['nome']."</b>, que estava anunciado em sua loja, foi vendido para ".$db['usuario']."  por <b>".number_format($dbi['valor'],2,',','.')." yens</b>. O valor ficará guardado em sua loja até que você faça um saque para sua reserva.')");
	mysql_query("UPDATE inventario SET venda='nao', valor=0, usuarioid='".$db['id']."' WHERE id='".$_GET['buy']."'");
    mysql_query("UPDATE usuarios SET yens=yens-".$dbi['valor']." WHERE id='".$db['id']."'");
	echo "<script>self.location='?p=shops&item=".$_GET['item']."&msg=4'</script>";
}

?>
<div class="box_top">Mercado</div>
<div class="box_middle">
<div style="background: url(../_img/_detalhes/base2.PNG);width: 720px;height: 250px;">
<table cellpadding="0" cellspacing="0" width="712" height="230"><tbody><tr><td width="150">
<img width="142" style="" src="_img/_detalhes/msg/28.png"></td><td valign="top"><br><br><br>
<div style="margin-left: -345px;margin-top: 15px;height: 0px;">
<b id="title" style="font-family: impact;font-size: 20px;font-weight: normal;color: #ffffff;"  onmouseover="style.color='#F5F5F5'" onmouseout="color.color='#ffffff'">
&raquo; Mercado Ninja!</b></div><br>
<div style="margin-left: 0px;margin-top: 35px;font-family: arial;font-size: 12px;color: #fff;"><b>
Bem-vindo ao comércio interno do jogo. Aqui você encontrará as</br>
lojas criadas pelos próprios ninjas. Normalmente, os itens</br>
 vendidos aqui são mais baratos do que os itens vendidos no comércio</br>
 da vila. Selecione o item que deseja visualizar, e boas compras!<br />
</div></td></tr></tbody></table></div>
<div class="sep"></div>
	<?php
	if(isset($_GET['msg'])){
		switch($_GET['msg']){
			case 1: $msg='Este item já foi comprado por outra pessoa.'; break;
			case 2: $msg='Yens insuficientes para comprar este item.'; break;
			case 3: $msg='Você não pode comprar um item que já lhe pertence.'; break;
			case 4: $msg='Item comprado com sucesso!'; break;
		}
	echo '<div class="aviso">'.$msg.'</div><div class="sep"></div>';
	}
	?>
<ul class="menu">


	<li><a href="#">Items</a>

		<ul>
			<li><a href="?p=shops&amp;category=armas" class="documents">Armas</a></li>
			<li><a href="?p=shops&amp;category=vesti" class="documents">Vestimentas</a></li>
			<li><a href="?p=shops&amp;category=calcado" class="documents">Calçados</a></li>
			<li><a href="?p=shops&amp;category=acessorios" class="documents"><span>Acessorios</span></a></li>
				  <li><a href="?p=shops&amp;category=bijju" class="documents"><span>Bijuus</span></a></li>
		</ul>
	</li>
	<li><a href="#">Comercio</a>
       <ul>
	 <li><a href="?p=shop" class="documents"><span>Voltar</span></a></li>
     <li><a href="?p=shoppet" class="documents"><span>Animais</span></a></li>
            </ul>
	</li>

</ul>
    <div class="sep"></div>
      <?php
	if(!isset($_GET['category'])) require_once('shops_shops.php'); else
    if(isset($_GET['category'])){
		switch($_GET['category']){
			case 'armas': require_once('shops_weapons.php'); return;
            case 'vesti': require_once('shops_vestis.php'); break;
            case 'calcado': require_once('shops_calcado.php'); break;
            case 'bijju': require_once('shops_bijjus.php'); break;
            case 'acessorios': require_once('shops_acess.php'); break;
		}
	}
	?>


</div>
<div class="box_bottom"></div>