<?php
$reqtai=$db['taijutsu']+7;
$reqnin=$db['ninjutsu']+7;
$reqgen=$db['genjutsu']+7;
$sqls=mysql_query("SELECT id,nome,categoria FROM table_itens Where categoria='vestimenta' ORDER BY nome ASC");
$dbs=mysql_fetch_assoc($sqls);
?>

<b>Selecione o Item:</b><br />
    <form method="get" action="?p=shops">
    <input type="hidden" id="p" name="p" value="shops" />
	<select id="item" name="item">
    	<?php do{
		switch($dbs['categoria']){
			case 'vestimenta': $categoria='Vestimenta'; break;

		}
		?>
        <option value="<?php echo $dbs['id']; ?>"<?php if((isset($_GET['item']))&&($_GET['item']==$dbs['id'])) echo ' selected="selected"'; ?>><?php echo $dbs['nome']; ?> [<?php echo $categoria; ?>]</option>
        <?php } while($dbs=mysql_fetch_assoc($sqls)); ?>
    </select>&nbsp;
    <input type="hidden" id="categoria" name="categoria" value="<?php echo $categoria;?>" />
    <input type="submit" class="botao" value="Pesquisar" /></form>
    <span class="sub2">Selecione o item que deseja pesquisar.</span>
    <?php if(isset($_GET['item'])){ ?>
    <?php
	vn($_GET['item']);
	$sqlv=mysql_query("SELECT * FROM table_itens WHERE id=".$_GET['item']);
	$dbv=mysql_fetch_assoc($sqlv);
	if(mysql_num_rows($sqlv)==0){ echo "<script>self.location='?p=home'</script>"; return; }
	$sqli=mysql_query("SELECT i.id,i.valor,i.usuarioid,i.upgrade,u.usuario FROM inventario i LEFT OUTER JOIN usuarios u ON i.usuarioid=u.id WHERE i.itemid=".$_GET['item']." AND i.venda='sim' ORDER BY i.id ASC");
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
            <b><?php if($dbv['taijutsu']>0) echo '<img src="_img/equipamentos/up.png" width="14" height="14" align="absmiddle" /> [+'.$dbv['taijutsu'].'] em Taijutsu<br />'; ?>
            <?php if($dbv['ninjutsu']>0) echo '<img src="_img/equipamentos/up.png" width="14" height="14" align="absmiddle" /> [+'.$dbv['ninjutsu'].'] em Ninjutsu<br />'; ?>
            <?php if($dbv['genjutsu']>0) echo '<img src="_img/equipamentos/up.png" width="14" height="14" align="absmiddle" /> [+'.$dbv['genjutsu'].'] em Genjutsu<br />'; ?></b>
            <br />
            <span style="font-size:14px;">Valor Normal: <b><?php echo number_format($dbv['valor'],2,',','.'); ?> yens</b></span>
          </td>
  	</tr>
    <?php if(mysql_num_rows($sqli)==0) echo '<tr><td colspan="2"><div class="sep"></div></td></tr><tr<tr><td colspan="2"><div class="aviso">Nenhum item encontrado.</div></td></tr>'; else do{ ?>
    <tr>
    	<td colspan="3"><div class="sep"></div></td>
    </tr>
    <tr class="table_dados" style="background:#161616;" onmouseover="style.background='#310000'" onmouseout="style.background='#161616'">
    	<td align="left" colspan="2"><a href="?p=view&view=<?php echo strtolower($dbi['usuario']); ?>"><?php echo $dbi['usuario']; ?></a> vendendo por <b><?php echo number_format($dbi['valor'],2,',','.'); ?> yens</b> [+<?php echo $dbi['upgrade']; ?>]</td>
        <td align="center" width="30%"><?php if($dbi['usuarioid']<>$db['id']){ ?><a href="?p=shops<?php if(isset($_GET['item'])) echo '&item='.$_GET['item']; ?>&buy=<?php echo $dbi['id']; ?>">Comprar</a> | <?php } ?><a href="?p=viewshop&shop=<?php echo strtolower($dbi['usuario']); ?>">Visitar Loja</a></td>
    </tr>
    <?php } while($dbi=mysql_fetch_assoc($sqli)); ?>
    </table>
    <?php } ?>