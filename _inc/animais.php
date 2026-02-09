<?php
require_once('Encrypt.php');
$c=new C_Encrypt();

if(isset($_GET['action'])){
	if(!isset($_GET['id'])){ echo "<script>self.location='?p=home'</script>"; return; }
	$id = antiinjection($_GET['id']);
	$sqli = mysql_query("SELECT usuarioid,categoria FROM animais WHERE id=".$id);
	$dbi = mysql_fetch_assoc($sqli);
	if($dbi['usuarioid']<>$db['id']){ echo "<script>self.location='?p=home'</script>"; return; }
	$categoria = antiinjection($dbi['categoria']);
	$act = antiinjection($_GET['action']);
	vn($id);
	mysql_query("UPDATE animais SET status='off' WHERE usuarioid=".$db['id']." AND categoria='".$categoria."'");
	if($act=='on') mysql_query("UPDATE animais SET status='on' WHERE id=".$id);
	if($act=='on') echo "<script>self.location='?p=animais&msg=2'</script>"; else echo "<script>self.location='?p=animais&msg=3'</script>";
}
if(isset($_GET['sell'])){
	$id = antiinjection($_GET['sell']);
	$sqli = mysql_query("SELECT itemid, usuarioid FROM animais WHERE id=".$id);
	$dbi = mysql_fetch_assoc($sqli);
	if($dbi['usuarioid']<>$db['id']){ echo "<script>self.location='?p=home'</script>"; return; }
	$data = date('Y-m-d H:i:s');
	$sqlv=mysql_query("SELECT valor FROM table_animais WHERE id=".$dbi['itemid']);
	$dbv=mysql_fetch_assoc($sqlv);
	mysql_query("DELETE FROM animais WHERE id=".$id);
	if(mysql_affected_rows()==0){ echo "<script>self.location='?p=home'</script>"; return; }
	mysql_query("UPDATE usuarios SET yens=yens+".($dbv['valor']/2)." WHERE id=".$db['id']);
    echo "<script>self.location='?p=animais&msg=4&value=".($dbv['valor']/2)."'</script>";
}


$sqli = mysql_query("SELECT i.id,i.status,i.upgrade,t.categoria,t.descricao,i.taijutsu,i.ninjutsu,i.genjutsu,t.maxtai,i.itemid,t.maxnin,t.maxgen,t.nome,t.imagem,t.valor FROM animais i LEFT OUTER JOIN table_animais t ON i.itemid=t.id WHERE i.usuarioid=".$db['id']." AND venda='nao' ORDER BY status ASC");
$dbi=mysql_fetch_assoc($sqli);



?>
<div class="box_top">Meu Inventario</div>
<div class="box_middle"><div style="background: url(../_img/_detalhes/base2.PNG);width: 720px;height: 250px;">
<table cellpadding="0" cellspacing="0" width="712" height="230"><tbody><tr><td width="150">
<img width="142" style="" src="_img/_detalhes/msg/1.png"></td><td valign="top"><br><br><br>
<div style="margin-left: -345px;margin-top: 15px;height: 0px;">
<b id="title" style="font-family: impact;font-size: 20px;font-weight: normal;color: #ffffff;"  onmouseover="style.color='#F5F5F5'" onmouseout="color.color='#ffffff'">
&raquo; Seu iventario!</b></div><br>
<div style="margin-left: 0px;margin-top: 35px;font-family: arial;font-size: 12px;color: #fff;"><b>
Abaixo está todo seu inventário. Estarão listados aqui todos os itens que existirem</br>
em sua conta, incluindo os equipamentos equipados e não-equipados. Lembre-se que ao</br>
equipar um certo tipo de item, qualquer outro que seja do mesmo tipo será </br>
automaticamente substituído. Você pode também vender o item para o comércio de sua</br>
vila, ou então anunciar em sua <a href="?p=myshop">própria loja</a>, para que outros jogadores o comprem.</br>
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
	  <li><a href="#">Minha loja </a>
       <ul>
	  <li><a href="?p=myshoppet" class="documents"><span>Animais</span></a></li>
     </ul>


</ul>
    <div class="sep"></div>
 <div style="background:url(_img/gradient.jpg) repeat-y;color:#FFFFAA;"><img src="_img/yens.png" width="14" height="14" /> <b>Meus Yens: <?php echo number_format($db['yens'],2,',','.'); ?> yens</b></div>
	  <div class="sep"></div>


	<?php
	if(isset($_GET['msg'])){
		switch($_GET['msg']){
			case 1: $msg='Ramen utilizado com sucesso!<br />Sua energia foi regenerada em <b>'.$_GET['e'].' pontos.</b>'; break;
			case 2: $msg='Item equipado com sucesso!'; break;
			case 3: $msg='Item desequipado com sucesso!'; break;
			case 4: if(!isset($_GET['value'])) $value=0; else $value=$_GET['value']; $msg='Item vendido com sucesso! Foram creditados '.number_format($value,2,',','.').' yens em sua conta.'; return;
		    case 5: $msg='Yens insuficientes para este aprimoramento'; break;
		    case 6: $msg='Aprimoramento realizado com sucesso!'; break;
		    case 7: $msg='Atributos maximos alcançados para este animal.Visite a loja e adiquira outro'; break;
		}
	echo '<div class="sep"></div><div class="aviso">'.$msg.'</div>';
	}
	?>
           <table width="100%" cellpadding="0" cellspacing="1">

    <?php if(mysql_num_rows($sqli)>0) do{ ?>
    <tr>
    	<td colspan="2"><div class="sep"></div></td>
    </tr>
    <tr class="table_dados" style="background:#323232;" onmouseover="style.background='#2C2C2C'" onmouseout="style.background='#323232'">
    	<td align="center" width="140"><img src="_img/equipamentos/<?php echo $dbi['imagem']; ?>.png" /></td>
        <td style="padding:5px;">
        	<b><?php echo $dbi['nome']; ?></b><br />
            <span class="sub2"><?php echo $dbi['descricao']; ?></span><br />
            Status do seu animal lendario<br>
            <b><?php if($dbi['taijutsu']>0) echo '<img src="_img/equipamentos/up.png" width="14" height="14" align="absmiddle" /> [+'.$dbi['taijutsu'].'] em Taijutsu<br />'; ?>
            <?php if($dbi['ninjutsu']>0) echo '<img src="_img/equipamentos/up.png" width="14" height="14" align="absmiddle" /> [+'.$dbi['ninjutsu'].'] em Ninjutsu<br />'; ?>
            <?php if($dbi['genjutsu']>0) echo '<img src="_img/equipamentos/up.png" width="14" height="14" align="absmiddle" /> [+'.$dbi['genjutsu'].'] em Genjutsu<br />'; ?></b>
            <br />
            <b>Valor de Venda</b>:


             <?php echo number_format(($dbi['valor']/2),2,',','.'); ?> yens<br />








</div>



              </span>



          <a href="?p=animais&action=<?php if($dbi['status']=='off') echo 'on'; else echo 'off'; ?>&id=<?php echo $dbi['id']; ?>"><?php if($dbi['status']=='off') echo 'Equipar'; else echo 'Retirar'; ?></a> | <a href="?p=sellpet&id=<?php echo $dbi['id']; ?>">Vender</a> |  <a href="?p=treinarpet&id=<?php echo $dbi['id']; ?>">Treinar Animal</a>| <a href="?p=addmypet&id=<?php echo $dbi['id']; ?>">Anunciar em Loja de pets</a>
          </td>
  	</tr>
    <?php } while($dbi=mysql_fetch_assoc($sqli)); ?>
    </table>
      <?php if((mysql_num_rows($sqli)==0)){ ?>
    <div class="sep"></div>
    <div class="aviso">Você não possui nenhum animal lendario.</a></div>
    <?php } ?>
</div></div></div>
<div class="box_bottom"></div>
<?php
@mysql_free_result($sqli);
?>