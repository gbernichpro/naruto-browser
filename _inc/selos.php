<?php
require_once('Encrypt.php');
$c = new C_Encrypt();

if(isset($_GET['action'])){
	if(!isset($_GET['id'])){ echo "<script>self.location='?p=home'</script>"; return; }
	$id = antiinjection($_GET['id']);
	$sqli = mysql_query("SELECT usuarioid,categoria FROM selos WHERE id='".$id."'");
	$dbi = mysql_fetch_assoc($sqli);
	if($dbi['usuarioid']<>$db['id']){ echo "<script>self.location='?p=home'</script>"; return; }
	$categoria = antiinjection($dbi['categoria']);
	$act = antiinjection($_GET['action']);
	vn($id);
	mysql_query("UPDATE selos SET status='off' WHERE usuarioid=".$db['id']." AND categoria='".$categoria."'");
	if($act=='on') mysql_query("UPDATE selos SET status='on' WHERE id=".$id);
	if($act=='on') echo "<script>self.location='?p=selos&msg=2'</script>"; else echo "<script>self.location='?p=selos&msg=3'</script>";
}



$sqli = mysql_query("SELECT i.id,i.status,i.upgrade,t.categoria,t.descricao,t.taijutsu,t.ninjutsu,t.genjutsu,t.nome,t.imagem,t.valor,i.valorap,i.expira FROM selos i LEFT OUTER JOIN table_selos t ON i.itemid=t.id WHERE i.usuarioid=".$db['id']." AND venda='nao' ORDER BY status ASC");
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


</ul>


 <div class="sep"></div>
 <div style="background:url(_img/gradient.jpg) repeat-y;color:#FFFFAA;"><img src="_img/yens.png" width="14" height="14" /> <b>Meus Yens: <?php echo number_format($db['yens'],2,',','.'); ?> yens</b></div>
	<?php
	if(isset($_GET['msg'])){
		switch($_GET['msg']){
			case 1: $msg='Ramen utilizado com sucesso!<br />Sua energia foi regenerada em <b>'.$_GET['e'].' pontos.</b>'; break;
			case 2: $msg='Item equipado com sucesso!'; break;
			case 3: $msg='Item desequipado com sucesso!'; break;
			case 4: if(!isset($_GET['value'])) $value=0; else $value=$_GET['value']; $msg='Item vendido com sucesso! Foram creditados '.number_format($value,2,',','.').' yens em sua conta.'; return;
		    case 5: $msg='Yens insuficientes para este aprimoramento'; break;
		    case 6: $msg='Aprimoramento realizado com sucesso!'; break;
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
        	<b><?php echo $dbi['nome']; ?><?php if($dbi['upgrade']>0) echo ' +'.$dbi['upgrade']; ?></b><br />
            <span class="sub2"><?php echo $dbi['descricao']; ?></span><br />
            <b><?php if($dbi['taijutsu']>0) echo '<img src="_img/equipamentos/up.png" width="14" height="14" align="absmiddle" /> [+'.($dbi['taijutsu']+$dbi['upgrade']).'] em Taijutsu<br />'; ?>
            <?php if($dbi['ninjutsu']>0) echo '<img src="_img/equipamentos/up.png" width="14" height="14" align="absmiddle" /> [+'.($dbi['ninjutsu']+$dbi['upgrade']).'] em Ninjutsu<br />'; ?>
            <?php if($dbi['genjutsu']>0) echo '<img src="_img/equipamentos/up.png" width="14" height="14" align="absmiddle" /> [+'.($dbi['genjutsu']+$dbi['upgrade']).'] em Genjutsu<br />'; ?></b>
            <br />

              <?php
			$expira=$dbi['expira'];
			$ex=explode(' ',$expira);
			$data=explode('-',$ex[0]);
			$hora=explode(':',$ex[1]);
			?>
            <span><b>Expira em </b> <?php echo $data[2].'/'.$data[1].'/'.$data[0].', às '.$hora[0]; ?> horas</span><br>

          <a href="?p=selos&action=<?php if($dbi['status']=='off') echo 'on'; else echo 'off'; ?>&id=<?php echo $dbi['id']; ?>"><?php if($dbi['status']=='off') echo 'Ativar selo'; else echo 'Desativar Selo'; ?></a>
          </td>
  	</tr>
    <?php } while($dbi=mysql_fetch_assoc($sqli)); ?>
    </table>
      <?php if((mysql_num_rows($sqli)==0)){ ?>
    <div class="sep"></div>
    <div class="aviso">Você não possui nenhum selo ativado.<a href="?p=shopselos">Ir Comprar Selos</a></div>
    <?php } ?>
</div></div>
<div class="box_bottom"></div>
<?php
@mysql_free_result($sqli);
?>