<?php
$sqlp=mysql_query("Select count(u.id) conta, u.id,t.descricao,t.nome,t.imagem from usaveis u left outer join table_usaveis t ON u.itemid=t.id where u.usuarioid=".$db['id']." Group by u.itemid ORDER by u.itemid ASC");
$dbp=mysql_fetch_assoc($sqlp);
if(isset($_GET['sell'])){	$id = antiinjection($_GET['sell']);	$sqli = mysql_query("SELECT id, usuarioid,itemid FROM usaveis WHERE id='".$id."'");
	$dbi = mysql_fetch_assoc($sqli);	if($dbi['usuarioid']<>$db['id']){ echo "<script>self.location='?p=home'</script>"; return; }	$data=date('Y-m-d H:i:s');
	$sqlv = mysql_query("SELECT valor FROM table_usaveis WHERE id='".$dbi['itemid']."'");
	$dbv=mysql_fetch_assoc($sqlv);	mysql_query("DELETE FROM usaveis WHERE id='".antiinjection($id)."'");
	if(mysql_affected_rows()==0){ echo "<script>self.location='?p=home'</script>"; return; }	mysql_query("UPDATE usuarios SET yens=yens+".$dbv['valor']." WHERE id='".$db['id']."'");
    echo "<script>self.location='?p=parchments&msg=4&value=".$dbv['valor']."'</script>";}
	$juniorrios=mysql_query("SELECT * FROM usaveis WHERE usuarioid='".$db['id']."'");
	$junior=mysql_fetch_assoc($juniorrios);$dbh=mysql_query("SELECT valor FROM table_usaveis where id='".$junior['itemid']."'");
	$test= mysql_fetch_assoc($dbh);?><div class="box_top">Pergaminhos</div>
	<div class="box_middle"><div style="background: url(../_img/_detalhes/base2.PNG);width: 720px;height: 250px;">
<table cellpadding="0" cellspacing="0" width="712" height="230"><tbody><tr><td width="150">
<img width="142" style="" src="_img/_detalhes/msg/26.png"></td><td valign="top"><br><br><br>
<div style="margin-left: -345px;margin-top: 15px;height: 0px;">
<b id="title" style="font-family: impact;font-size: 20px;font-weight: normal;color: #ffffff;"  onmouseover="style.color='#F5F5F5'" onmouseout="color.color='#ffffff'">
&raquo; Seu iventario!</b></div><br>
<div style="margin-left: 0px;margin-top: 35px;font-family: arial;font-size: 12px;color: #fff;"><b>
Abaixo estão a quantidade de pergaminhos que você possui.</br>
</b>
</div></td></tr></tbody></table></div><div class="sep"></div>
<ul class="menu">


	<li><a href="#">Itens</a>

		<ul>
			<li><a href="?p=inventory" class="documents">Items</a></li>
			<li><a href="?p=parchments" class="documents">Pergaminhos</a></li>
		</ul>
	</li>
	<li><a href="#">Especiais</a>
       <ul>
	  <li><a href="?p=animais" class="documents"><span>Animais</span></a></li>
	   <li><a href="?p=selos" class="documents"><span>Selos</span></a></li>
       <li><a href="?p=portao" class="documents"><span>Portões</span></a></li>
     </ul>
	</li>

	<li><a href="#">Outros</a>
       <ul>
	  <li><a href="?p=bolsas" class="documents"><span>Mochila Ninja</span></a></li>
            
            </ul>
	</li>


</ul>	<div class="sep"></div>	<div style="background:url(_img/gradient.jpg) repeat-y;color:#FFFFAA;"><img src="_img/yens.png" width="14" height="14" /> <b>Meus Yens: <?php echo number_format($db['yens'],2,',','.'); ?> yens</b></div>	<table width="100%" cellpadding="0" cellspacing="1">
	<?php	if(isset($_GET['msg'])){		switch($_GET['msg']){			case 4: if(!isset($_GET['value'])) $value=0; else $value = antiinjection($_GET['value']); $msg = 'Item vendido com sucesso! Foram creditados '.number_format($value,2,',','.').' yens em sua conta.'; return;		}	echo '<div class="sep"></div><div class="aviso">'.$msg.'</div>';	}	?>
	<?php if(mysql_num_rows($sqlp)==0) echo '<tr><td><div class="sep"></div></td></tr><tr><td><div class="aviso">Nenhum pergaminho encontrado.</div></td></tr>'; else
	do{ ?>    <tr>    	<td colspan="2"><div class="sep"></div></td>    </tr>    <tr class="table_dados" style="background:#323232;" onmouseover="style.background='#2C2C2C'" onmouseout="style.background='#323232'">
 	<td align="center" width="140"><img src="_img/equipamentos/<?php echo $dbp['imagem']; ?>.jpg" /></td>        <td style="padding:5px;">        	<b><?php echo $dbp['nome']; ?></b><br />            <span class="sub2"><?php echo $dbp['descricao']; ?></span>            <br /><br />Quantidade: <b><?php echo $dbp['conta']; ?> pergaminho<?php if($dbp['conta']>1) echo 's'; ?></b>
	<br>Valor de venda: <?php echo $test['valor'];?><br>            <a href="?p=parchments&sell=<?php echo $dbp['id']; ?>">Vender</a>          </td>  	</tr>    <?php } while($dbp=mysql_fetch_assoc($sqlp)); ?>    </table></div></div><div class="box_bottom"></div>