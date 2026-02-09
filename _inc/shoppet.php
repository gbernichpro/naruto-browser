<?php
if(isset($_GET['buy'])){
	vn($_GET['buy']);
	$sqli=mysql_query("SELECT i.usuarioid,i.venda,i.valor,t.nome FROM animais i LEFT OUTER JOIN table_animais t ON i.itemid=t.id WHERE i.id=".antiinjection($_GET['buy']));
	if(mysql_num_rows($sqli)==0){ echo "<script>self.location='?p=shoppet&item=".$_GET['item']."&msg=1'</script>"; return; }
	$dbi=mysql_fetch_assoc($sqli);
	if($dbi['usuarioid']==$db['id']){ echo "<script>self.location='?p=shoppet&item=".$_GET['item']."&msg=3'</script>"; return; }
	if($dbi['venda']=='nao'){ echo "<script>self.location='?p=home'</script>"; return; }
	if($db['yens']<$dbi['valor']){ echo "<script>self.location='?p=shoppet&item=".$_GET['item']."&msg=2'</script>"; return; }
	mysql_query("UPDATE usuarios SET yens=yens-".$dbi['valor'].", compraloja=compraloja+1 WHERE id=".$db['id']);
	mysql_query("INSERT INTO vendaspets (usuarioid, valor) VALUES (".$dbi['usuarioid'].", ".$dbi['valor'].")");
	mysql_query("INSERT INTO mensagens (data, origem, destino, assunto, msg) VALUES ('".date('Y-m-d H:i:s')."', 0, ".$dbi['usuarioid'].", 'Pet Vendido!', 'Parabéns, o pet <b>".$dbi['nome']."</b>, que estava anunciado em sua loja, foi vendido para ".$db['usuario']." por <b>".number_format($dbi['valor'],2,',','.')." yens</b>. O valor ficará guardado em sua loja até que você faça um saque para sua reserva.')");
	mysql_query("UPDATE animais SET venda='nao', valor=0, usuarioid=".$db['id']." WHERE id=".$_GET['buy']);
    mysql_query("UPDATE usuarios SET yens=yens-".$dbi['valor']." WHERE id=".$db['id']);
	echo "<script>self.location='?p=shoppet&item=".$_GET['item']."&msg=4'</script>";
}
$reqtai=$db['taijutsu']+7;
$reqnin=$db['ninjutsu']+7;
$reqgen=$db['genjutsu']+7;
$sqls=mysql_query("SELECT id,nome,categoria FROM table_animais ORDER BY nome ASC");
$dbs=mysql_fetch_assoc($sqls);
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
	<b>Selecione o Item:</b><br />
    <form method="get" action="?p=shoppet">
    <input type="hidden" id="p" name="p" value="shoppet" />
	<select id="item" name="item">
    	<?php do{
		switch($dbs['categoria']){
			case 'animais': $categoria='animais'; break;

		}
		?>
        <option value="<?php echo $dbs['id']; ?>"<?php if((isset($_GET['item']))&&($_GET['item']==$dbs['id'])) echo ' selected="selected"'; ?>><?php echo $dbs['nome']; ?> [<?php echo $categoria; ?>]</option>
        <?php } while($dbs=mysql_fetch_assoc($sqls)); ?>
    </select>&nbsp;<input type="submit" class="botao" value="Pesquisar" /></form>
    <span class="sub2">Selecione o item que deseja pesquisar.</span>
    <?php if(isset($_GET['item'])){ ?>
    <?php
	vn($_GET['item']);
	$sqlv=mysql_query("SELECT * FROM table_animais WHERE id=".$_GET['item']);
	$dbv=mysql_fetch_assoc($sqlv);
	if(mysql_num_rows($sqlv)==0){ echo "<script>self.location='?p=home'</script>"; return; }
	$sqli=mysql_query("SELECT i.id,i.valor,i.usuarioid,i.upgrade,u.usuario,i.taijutsu,i.ninjutsu,i.genjutsu FROM animais i LEFT OUTER JOIN usuarios u ON i.usuarioid=u.id WHERE i.itemid=".$_GET['item']." AND i.venda='sim' ORDER BY i.id ASC");
	$dbi=mysql_fetch_assoc($sqli);
	?>
    <table width="100%" cellpadding="0" cellspacing="1">
    <tr>
    	<td colspan="3"><div class="sep"></div></td>
    </tr>
    <tr class="table_dados" style="background:#161616;" onmouseover="style.background='#310000'" onmouseout="style.background='#161616'">
    	<td align="center" width="140" valign="top"><img src="_img/equipamentos/<?php echo $dbv['imagem']; ?>.png" /></td>
        <td colspan="2" style="padding:5px;">
        	<b><?php echo $dbv['nome']; ?></b><br />
            <span class="sub2"><?php echo $dbv['descricao']; ?></span><br />
            <b>
            <br />
            <span style="font-size:14px;">Preço na loja: <b><?php echo number_format($dbv['valor'],2,',','.'); ?> yens</b></span>
          </td>
  	</tr>
    <?php if(mysql_num_rows($sqli)==0) echo '<tr><td colspan="2"><div class="sep"></div></td></tr><tr<tr><td colspan="2"><div class="aviso">Nenhum item encontrado.</div></td></tr>'; else do{ ?>
    <tr>
    	<td colspan="3"><div class="sep"></div></td>
    </tr>
    <tr class="table_dados" style="background:#161616;" onmouseover="style.background='#310000'" onmouseout="style.background='#161616'">
    	<td align="left" colspan="2"><a href="?p=view&view=<?php echo strtolower($dbi['usuario']); ?>"><?php echo $dbi['usuario']; ?></a> vendendo por <b><?php echo number_format($dbi['valor'],2,',','.'); ?> yens</b> [Tai +<?php echo $dbi['taijutsu']; ?>] [Nin +<?php echo $dbi['ninjutsu']; ?>] [Gen +<?php echo $dbi['genjutsu']; ?>]</td>
        <td align="center" width="30%"><?php if($dbi['usuarioid']<>$db['id']){ ?><a href="?p=shoppet<?php if(isset($_GET['item'])) echo '&item='.$_GET['item']; ?>&buy=<?php echo $dbi['id']; ?>">Comprar</a>  <?php } ?></td>
    </tr>
    <?php } while($dbi=mysql_fetch_assoc($sqli)); ?>
    </table>
    <?php } ?>
</div>
<div class="box_bottom"></div>
