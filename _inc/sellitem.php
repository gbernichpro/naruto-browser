<?php
if(isset($_POST['id'])){
    $juniorgato=mysql_query("Select * from usuarios where id=".antiinjection($db['id']));
    $junior=@mysql_fetch_assoc($juniorgato);
    if($junior['pass']<>$_POST['senha']){echo "<script>self.location='?p=inventory&msg=5'</script>";; return;}
    $id=antiinjection($_POST['id']);
	$sqli=mysql_query("SELECT itemid, usuarioid FROM inventario WHERE id=".$id);
	$dbi=mysql_fetch_assoc($sqli);
	if($dbi['usuarioid']<>$db['id']){ echo "<script>self.location='?p=home'</script>"; return; }
	$data=date('Y-m-d H:i:s');
	$sqlv=mysql_query("SELECT nome,valor FROM table_itens WHERE id=".antiinjection($dbi['itemid']));
	$dbv=mysql_fetch_assoc($sqlv);
	mysql_query("DELETE FROM inventario WHERE id=".$id);
	if(mysql_affected_rows()==0){ echo "<script>self.location='?p=home'</script>"; return; }
	mysql_query("UPDATE usuarios SET yens=yens+".($dbv['valor']/2)." WHERE id=".$db['id']);
    $data=date('Y-m-d H:i:s');
	$usuario=$db['id'];
	$ass=$db['usuario'];
	$msg='Usuario acabou de vender um item para o jogo, item: '.$dbv['nome'].' por '.($dbv['valor']/2).'yens.';
	mysql_query("INSERT INTO log_vendas (usuarioid,data,assunto,msg)"."VALUES ('".$usuario."','".$data."','".$ass."','".$msg."')");
    echo "<script>self.location='?p=inventory&msg=4&value=".($dbv['valor']/2)."'</script>";
}
if(!isset($_GET['id'])){ echo "<script>self.location='?p=home'</script>"; return; }
$sqli=mysql_query("SELECT i.id,i.status,i.upgrade,i.usuarioid,t.categoria,t.descricao,t.taijutsu,t.ninjutsu,t.genjutsu,t.nome,t.imagem,t.valor FROM inventario i LEFT OUTER JOIN table_itens t ON i.itemid=t.id WHERE  i.id=".antiinjection($_GET['id'])." ORDER BY status ASC");
$dbi=mysql_fetch_assoc($sqli);
if(mysql_num_rows($sqli)==0){ echo "<script>self.location='?p=home'</script>"; return; }
if($dbi['usuarioid']<>$db['id']){ echo "<script>self.location='?p=home'</script>"; return; }

?>
<div class="box_top">Venda de item</div>
<div class="box_middle">Aki é onde você venderá seu item para o sistema ou seja você pode vender seu item para o jogo recebendo na hora mas lembre que será a metade do preço pago no comercio.<div class="sep"></div>


	<table width="100%" cellpading="0" cellspacing="1">
    <tr class="table_dados" style="background:#323232;" onmouseover="style.background='#2C2C2C'" onmouseout="style.background='#323232'">
    	<td align="center" width="140" valign="top"><img src="_img/equipamentos/<?php echo $dbi['imagem']; ?>.png" /></td>
        <td style="padding:5px;">
        	<b><?php echo $dbi['nome']; ?><?php if($dbi['upgrade']>0) echo ' +'.$dbi['upgrade']; ?></b><br />
            <span class="sub2"><?php echo $dbi['descricao']; ?></span><br />
            <b><?php if($dbi['taijutsu']>0) echo '<img src="_img/equipamentos/up.png" width="14" height="14" align="absmiddle" /> [+'.($dbi['taijutsu']+$dbi['upgrade']).'] em Taijutsu<br />'; ?>
            <?php if($dbi['ninjutsu']>0) echo '<img src="_img/equipamentos/up.png" width="14" height="14" align="absmiddle" /> [+'.($dbi['ninjutsu']+$dbi['upgrade']).'] em Ninjutsu<br />'; ?>
            <?php if($dbi['genjutsu']>0) echo '<img src="_img/equipamentos/up.png" width="14" height="14" align="absmiddle" /> [+'.($dbi['genjutsu']+$dbi['upgrade']).'] em Genjutsu<br />'; ?></b>
            <br />
             <b>Valor de Venda</b>:


             <?php echo number_format(($dbi['valor']/2),2,',','.'); ?> yens<br />
            <form method="post" action="?p=sellitem" onsubmit="subm.value='Carregando...';subm.disabled=true;">
            <input type="hidden" id="id" name="id" value="<?php echo $_GET['id']; ?>" />
        	<span class="sub2">Metade do preço do comercio.</span><br /><br />
       		<input type="password" id="senha" name="senha" maxlength="15" onfocus="className='input'" onblur="className=''" /><br />
            <span class="sub2">Digite sua senha de vendas.</span><br /><br />
            <input type="submit" id="subm" name="subm" class="botao" value="Vender item" /><br />
            </form>



          </td>
  	</tr>
    </table>
</div>
<div class="box_bottom"></div>