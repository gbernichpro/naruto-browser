<?php
//sistema proibido copia ilegal sujeito a pagamento de indenização caso provado copia ilegal ou roubo sujeito a multa pela lei.Artigo

require_once('Encrypt.php');
$c=new C_Encrypt();
 //Codigo desenvolvido por Junior Rios Todos direitos estão reservados.
if(isset($_GET['utilizar'])){
	$id = antiinjection($_GET['utilizar']);
	$sqli=mysql_query("SELECT itemid, usuarioid FROM invcla WHERE id='".$id."'");
	$dbi=mysql_fetch_assoc($sqli);
	$sqlvc=mysql_query("SELECT valor,hp,categoria FROM table_clashop WHERE id=".$dbi['itemid']);
	$dbvc=mysql_fetch_assoc($sqlvc);
	if($dbvc['categoria']<>'hp'){ echo "<script>self.location='?p=home'</script>"; return; }
	if($dbi['usuarioid']<>$db['id']){ echo "<script>self.location='?p=home'</script>"; return; }
	$data=date('Y-m-d H:i:s');
	$sqlv=mysql_query("SELECT valor,hp,categoria FROM table_clashop WHERE id=".$dbi['itemid']);
	$dbv=mysql_fetch_assoc($sqlv);
	mysql_query("DELETE FROM invcla WHERE id=".$id);
	$energia=$db['energia'];
	$hp=$dbv['hp'];
	if($dbv['categoria']=='hp')
	{
	if($energia+$hp>=$db['energiamax']) $energia=$db['energiamax']; else $energia=$energia+$hp;
	mysql_query("UPDATE usuarios SET energia=$energia WHERE id=".$db['id']);
	}
  echo "<script>self.location='?p=invcla&msg=4&value=".($dbv['hp'])."'</script>";
}
  //sistema direitos reservador a junior_rios01@hotmail.com Msn / email / facebook .
if(isset($_GET['dropar'])){
	$id = antiinjection($_GET['dropar']);
	$sqli=mysql_query("SELECT itemid, usuarioid FROM invcla WHERE id='".$id."'");
	$dbi=mysql_fetch_assoc($sqli);
	$sqlvc=mysql_query("SELECT valor,hp,categoria FROM table_clashop WHERE id=".$dbi['itemid']);
	$dbvc=mysql_fetch_assoc($sqlvc);
	if($dbvc['categoria']<>'cred'){ echo "<script>self.location='?p=home'</script>"; return; }
	if($dbi['usuarioid']<>$db['id']){ echo "<script>self.location='?p=home'</script>"; return; }
	$data=date('Y-m-d H:i:s');
	$sqlv=mysql_query("SELECT valor,cred,categoria FROM table_clashop WHERE id=".$dbi['itemid']);
	$dbv=mysql_fetch_assoc($sqlv);
	mysql_query("DELETE FROM invcla WHERE id=".$id);
	if($dbv['categoria']=='cred')
	{
	mysql_query("UPDATE usuarios SET creditos=creditos+".($dbv['cred'])." WHERE id=".$db['id']);
	}
  echo "<script>self.location='?p=invcla&msg=4&value=".($dbv['cred'])."'</script>";
}
   //Codigo desenvolvido por Junior Rios Todos direitos estão reservados.

   //sistema direitos reservador a junior_rios01@hotmail.com Msn / email / facebook .
if(isset($_GET['yens'])){
	$iddoitem = antiinjection($_GET['yens']);
	$sqlitem=mysql_query("SELECT itemid, usuarioid FROM invcla WHERE id='".$iddoitem."'");
	$dbitem=mysql_fetch_assoc($sqlitem);
	$sqlvc=mysql_query("SELECT valor,hp,categoria FROM table_clashop WHERE id=".$dbitem['itemid']);
	$dbvc=mysql_fetch_assoc($sqlvc);
	if($dbvc['categoria']<>'yens'){ echo "<script>self.location='?p=home'</script>"; return; }
	if($dbitem['usuarioid']<>$db['id']){ echo "<script>self.location='?p=home'</script>"; return; }
	$datai=date('Y-m-d H:i:s');
	$sqlvitem=mysql_query("SELECT valor,yens,categoria FROM table_clashop WHERE id=".$dbitem['itemid']);
	$dbvitem=mysql_fetch_assoc($sqlvitem);
	mysql_query("DELETE FROM invcla WHERE id=".$iddoitem);
	if($dbvitem['categoria']=='yens')
	{
	mysql_query("UPDATE usuarios SET yens=yens+".($dbvitem['yens'])." WHERE id=".$db['id']);
	}
	else
	{	echo"erro";
	}
  echo "<script>self.location='?p=invcla&msg=4&value=".($dbvitem['yens'])."'</script>";
}
   //Codigo desenvolvido por Junior Rios Todos direitos estão reservados.

$sqli=mysql_query("SELECT i.id,i.status,i.upgrade,t.hp,t.cred,t.yens,t.categoria,t.descricao,t.taijutsu,t.ninjutsu,t.genjutsu,t.nome,t.imagem,t.valor FROM invcla i LEFT OUTER JOIN table_clashop t ON i.itemid=t.id WHERE i.usuarioid=".$db['id']." AND venda='nao' ORDER BY status ASC");
$dbi=mysql_fetch_assoc($sqli);
?>
<div class="box_top">Meu Inventario</div>
<div class="box_middle"><div style="background: url(../_img/_detalhes/base2.PNG);width: 720px;height: 250px;">
<table cellpadding="0" cellspacing="0" width="712" height="230"><tbody><tr><td width="150">
<img width="142" style="" src="_img/_detalhes/msg/14.png"></td><td valign="top"><br><br><br>
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
	 <div align="center">Menu:
 <select size="1" id="aaa">
<option selected value="#">Nenhum</option>
<option value="?p=inventory">Itens</option>
<option value="?p=parchments">Pergaminhos</option>
<option value="?p=bolsas">Mochilas</option>
<option value="?p=animais">Pets</option>
<option value="?p=selos">Selos</option>
<option value="?p=portao">Portões do chakra</option>
</select>
<input type="button" value="Filtrar" onclick="location=document.getElementById('aaa').value">
    <div class="sep"></div>
	<div style="background:url(_img/gradient.jpg) repeat-y;color:#FFFFAA;"><img src="_img/yens.png" width="14" height="14" /> <b>Meus Yens: <?php echo number_format($db['yens'],2,',','.'); ?> yens</b></div>
	<?php
	if(isset($_GET['msg'])){
		switch($_GET['msg']){
			case 1: $msg='Ramen utilizado com sucesso!<br />Sua energia foi regenerada em <b>'.$_GET['e'].' pontos.</b>'; break;
			case 2: $msg='Item equipado com sucesso!'; break;
			case 3: $msg='Item desequipado com sucesso!'; break;
			case 4: if(!isset($_GET['value'])) $value=0; else $value=$_GET['value']; $msg='Você utilizou um item de recuperação com sucesso!, foram recuperados '.$value.' de hp.'; break;
		    case 5: $msg='Senha incorreta impossivel vender item!'; break;
		    case 6: $msg='Sua energia está totalmente completa não pode utilizar o item no momento!'; break;
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
        	<b><?php echo $dbi['nome']; ?><?php if($dbi['upgrade']>0) echo ' +'.$dbp['upgrade']; ?></b><br />
            <span class="sub2"><?php echo $dbi['descricao']; ?></span><br />


             <?php
             if ($dbi['categoria']=='hp'){
             echo"
             <b>Este Item Regenera</b>:";


             echo "<font color='#00FF40'>".$dbi['hp']."</font> Pontos de energia.<br />";

                  }

                  else if ($dbi['categoria']=='cred'){
             echo"
             <b>Item para Dropar Creditos!</b>:";


             echo "<font color='#00FF40'>".$dbi['cred']."</font> Creditos.<br />";

                  }
                     else if ($dbi['categoria']=='yens'){
             echo"
             <b>Item da bonificação!</b>:";


             echo "<font color='#00FF40'>".$dbi['yens']."</font> yens.<br />";

                  }
                  ?>



         <br />
          <?php
          if($dbi['categoria']=='hp'){
          echo "<a href='?p=invcla&utilizar=".$dbi['id']."'>Utilizar item</a>";

              }
              else if($dbi['categoria']=='cred'){              echo "<a href='?p=invcla&dropar=".$dbi['id']."'>Utilizar item</a>";
              }
              else if($dbi['categoria']=='yens'){
              echo "<a href='?p=invcla&yens=".$dbi['id']."'>Utilizar item</a>";
              }
              else
              {              echo"erro você foi trollado por adm sasuke kkkkkkkkkkkkkkkkkkkkkk";
              }
              ?>
          </td>
  	</tr>
    <?php } while($dbi=mysql_fetch_assoc($sqli)); ?>
    </table>
    <?php if(mysql_num_rows($sqli)==0){ ?>
    <div class="sep"></div>
    <div class="aviso">Nenhum item em seu inventário.</div>
    <?php } ?>
</div>
<div class="box_bottom"></div>
<?php

@mysql_free_result($sqli);
 //Codigo desenvolvido por Junior Rios Todos direitos estão reservados.
?>
