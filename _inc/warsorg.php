<?php
$sqlv=mysql_query("SELECT posicao FROM membros WHERE usuarioid=".$db['id']." AND orgid=".$db['orgid']." AND status='sim'");
$dbv=mysql_fetch_assoc($sqlv);
if($dbv['posicao']==3){ echo "<script>self.location='?p=myorg'</script>"; return; }
$sqlc=mysql_query("SELECT * FROM organizacoes WHERE id=".$db['orgid']);
$dbc=mysql_fetch_assoc($sqlc);
//if($dbc['liderid']<>$db['id']){ echo "<script>self.location='?p=myorg&msg=6'</script>"; return; }
if(isset($_POST['org'])){
	$logo = antiinjection($_POST['org_logo']);
	$minimo = antiinjection($_POST['org_nivel']);
	vn($minimo);
	if($minimo<0){ echo "<script>self.location='?p=home'</script>"; return; }
$desc=$_POST['org_desc'];
mysql_query("UPDATE organizacoes SET descricao='$desc', minimo='$minimo', logo='$logo' WHERE id=".$dbc['id']);
echo "<script>self.location='?p=configorg&msg=1'</script>";
}
?>
<div class="box_top">Guerras</div>
<div class="box_middle"><div align="center"><a href="?p=myorg">Pagina do clã</a> | 
<a href="?p=warsorg">Guerras</a> | 
<a href="?p=wardecorg">Declarações de guerras</a> | 
</div><div class="sep"></div>
Desde tempos antigos as guerras vem sendo cada vez mais constantes no mundo ninja,proporcionando aos vencedores inumeras premiações.
<br>Abaixo segue o relatorio das últimas guerras em que seu clã participou.
   <div class="sep"></div>
</div>
<div class="box_bottom"></div>

<div class="box_top">Últimas guerras</div>
<div class="box_middle">







 <?
$sqlo = mysql_query("SELECT * FROM clas_guerras WHERE (clan_2=".$db['orgid']." or clan_1=".$db['orgid'].") ORDER BY inicio DESC");
if(mysql_num_rows($sqlo)==0) echo '<table width="100%" cellspacing="0"><tr id="barra3"><td colspan="8"><center><b><div class="aviso">Nenhum relatório de guerra.</div></div></tr></table>';
while($dbo = mysql_fetch_array($sqlo))
{
if($db['orgid']==$dbo['clan_1']){
$claninimigo=$dbo['clan_2'];
}elseif($db['orgid']==$dbo['clan_2']){
$claninimigo=$dbo['clan_1'];
}
//*********************************************************************************//
	$sql1=mysql_query("SELECT * FROM organizacoes WHERE id='".$claninimigo."'");
	$clan_inimigo=mysql_fetch_assoc($sql1);
/*================================================================*/
	$sql2=mysql_query("SELECT * FROM organizacoes WHERE id='".$db['orgid']."'");
	$clan=mysql_fetch_assoc($sql2);
//*********************************************************************************//
	?>
	<table width="100%" cellspacing="0">
			<tr class="table_dados" style="background:#323232;">
				<td><b><?=$clan['nome']?></b><br><?php if($clan['logo']<>'') echo '<img src="'.$clan['logo'].'" height="55" width="89" />'; else echo '<span class="sub2">Sem Imagem</span>'; ?></td>
				<td><b style='color:#440000;'><?=$dbo['status']?></b>-<h1><br>X</h1>-</td>
				<td><b><?=$clan_inimigo['nome']?></b><br><?php if($clan_inimigo['logo']<>'') echo '<img src="'.$clan_inimigo['logo'].'" height="55" width="89" />'; else echo '<span class="sub2">Sem Imagem</span>'; ?></td>
			</tr>
	</table>
<div class="aviso">
Data de inicio da guerra:<?=$dbo['inicio']?>
</div>
 <div class='sep'></div>
	<?

}
	

 ?>





</div>
<div class="box_bottom"></div>