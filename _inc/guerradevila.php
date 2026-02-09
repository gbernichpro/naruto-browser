<?php include 'trava.php'; ?>
<?php if ($ssj=="fim"){ ?>
<script>
function ExibiLista(){
if (document.getElementById("lista").style.display=="none"){
document.getElementById("lista").style.display="block";
document.getElementById("Click").innerHTML="Ocultar antigas guerras de vila";
}else{
document.getElementById("lista").style.display="none";
document.getElementById("Click").innerHTML="Exibir antigas guerras de vila";
}
}
</script>
<?php } ?>
<div class="box_top">GUERRA DE VILA </div>
<div class="box_middle">
<center>
<img src="_img/guerra.jpg" onclick="proteje()"><div class="sep"></div>
Bem-Vindo(a), todos os ninjas inscritos de cada vila irão competir na <b>Guerra De Vilas</b>.<br>
A <b>Guerra Ninja</b> tem duraçao de 7 dias.
<br>
no final do periodo, a vila que estiver com mais <b>Score</b> se consagra a vencedora e os membros da vila vencedora que fizerão parte da <b>Guerra de Vilas</b> vão receber um premio em yens.
<?php

$juniorrios=mysql_query("SELECT * from nal_war WHERE status='aberto'");
$jrrios=mysql_fetch_array($juniorrios);

if($jrrios['status']=='aberto'){
echo"<br><font color='#FFFFFF'><b>Final da Guerra de vilas :</b></font><font color='#00FF00'><small> ".$jrrios['fim']."</small></font>";

}


?>
<br><br>
<?php if ($ssj=="fim"){ ?>
<a style="cursor:pointer;color:#3A81B1;" id="Click" onselectstart="return false" onclick="ExibiLista()">Exibir antigas guerras de vila</a>
<br>
<div  id="lista" style="display:none;">
<table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #3A81B1;text-align:center;">
<tbody>
<tr bgcolor="#1E1E1E" style="font-weight:bold;">
<td>#</td>
<td>Vila Vencedora</td>
<td>Score</td>
<td>Premio</td>
</tr>
<?php
$rankingsql=mysql_query("SELECT * FROM nal_war ORDER BY id DESC LIMIT 10");
for ($t=0;$t<mysql_num_rows($rankingsql);$t++){
$ranking=mysql_fetch_assoc($rankingsql);
$nal_vila=mysql_fetch_assoc(mysql_query("select * from nal_war_vilas where vilaid=".$ranking['vencedor']." and warid=".$ranking['id']));
					switch($ranking['vencedor']){
					case 1: $txtvila='Vila da Folha'; break;
					case 2: $txtvila='Vila da Areia'; break;
					case 3: $txtvila='Vila do Som'; break;
					case 4: $txtvila='Vila da Chuva'; break;
					case 5: $txtvila='Vila da Nuvem'; break;
					case 6: $txtvila='Vila da Névoa'; break;
					case 8: $txtvila='Vila da Pedra'; break;
					case 9: $txtvila='Vila da Cachoeira'; break;
					case 10: $txtvila='Vila da Neve'; break;
					case 11: $txtvila='Vila da Grama'; break;
					case 7: $txtvila='Akatsuki'; break;
											}

?>
<tr>
<td><?php echo $ranking['id']; ?>°</td>
<td><?php echo $txtvila; ?></td>
<td><?php echo $nal_vila['score']; ?></td>
<td><?php echo number_format($ranking['premio'],2,',','.'); ?></td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
<?php } ?>
<div class="sep"></div>
<?php if ($ssj=="inscricao"){ ?>
<?php
$juniorrios=mysql_query("SELECT * from nal_war WHERE status='inscricao'");
$jrrios=mysql_fetch_array($juniorrios);
$nivel1=$jrrios['nivelmin'];
$nivel2=$jrrios['nivelmax'];
$valor=$jrrios['custo'];
if ($_POST['Inscrever-se']){
if ($ssj=="inscricao"){
if ($_SESSION['logado']==true){
if($db['yens']<$valor){ echo "<script>self.location='?p=guerra&msg=3'</script>"; return; }
if($db['nivel']<$nivel1){ echo "<script>self.location='?p=guerra&msg=1'</script>"; return; }
if($db['nivel']>$nivel2){ echo "<script>self.location='?p=guerra&msg=2'</script>"; return; }
if ($db['inwar']=="nao"){
mysql_query("UPDATE usuarios SET inwar='sim',yens=yens-".$valor." WHERE id=".$_SESSION['logado']);
$msg="Inscrito na <b>Guerra De Vilas</b>. Você agora esta participando da <b>Guerra de Vila!</b>";
}else{
$msg="Você já esta inscrito na <b>Guerra De Vilas</b>.";
}
}else{
echo "<script>top.location='/?p=login';</script>";
}
}else{
echo "<script>top.location='/?p=guerra';</script>";
}
}
if ($_POST['Inscrever-se']==true){
echo "<div class='aviso'>".$msg."</div>";
}
$count2=mysql_query("SELECT count(inwar) as total FROM usuarios WHERE inwar = 'sim'");
$ninjas2=mysql_fetch_array($count2);
$countinscritos=$ninjas2['total'];

?>
  <?php
	if(isset($_GET['msg'])){
		switch($_GET['msg']){
			case 1: $msg='Seu nivel é menor que o nivel minimo, impossivel realizar a inscrição.'; break;
			case 2: $msg='Seu nivel é maior que o nivel máximo, impossivel realizar a inscrição.!'; break;
			case 2: $msg='Yens insuficientes para realizar a inscrição na guerra de vilas.!'; break;
		}
	echo '<div class="aviso">'.$msg.'</div><div class="sep"></div>';
	}
	?>
Está aberta as inscrições para a <b>Guerra de Vila</b> do <b>Naruto</b>.<br>
Inscreva-se <b>já</b>, a <b>Vila</b> vencedora ira premiar a todos os seus ninjas participantes!!<br>
Inscritos: <?php echo $countinscritos; ?>
<div class='sep'></div>
<div style="background:url(_img/gradient.jpg) repeat-y;color:#FFFFAA;"><img src="_img/yens.png" width="14" height="14" /> <b>Meus Yens: <?php echo number_format($db['yens'],2,',','.'); ?> yens</b></div><div class="sep"></div>
<div align="left"> <strong>Nivel Minimo</strong>: <small><?php echo $jrrios['nivelmin']; ?></small> </div>
<div align="left"> <strong>Nivel Máximo<strong>:  <small><?php echo $jrrios['nivelmax']; ?></small></div>
<div align="left"> <strong>Taxa de inscrição<strong>:  <small><?php echo number_format($jrrios['custo'],2,',','.'); ?> yens</small></div>
<?php if ($db['inwar']=="sim"){echo"
<div class='sep'></div>
Você já inscrito na guerra de vilas aguarde até o fim das inscrições.

";
}
else{?>
<div class="sep"></div>
<form method="post" id="Form-Inscrição-Guerra-Vila" onsubmit="subm.value='Carregando...';subm.disabled=true;">

<input type="hidden" id="p" name="Inscrever-se" value="rank" />
<input type="submit" id="subm" style="cursor:pointer;" class="button" name="Inscrever-se" value="Inscrever-Se">
</form>

<?php }} if ($ssj=="aberto"){ ?>
<a href="?p=guerra">Inicio</a> | <a href="?p=guerra&vila=ninjas">Ninjas Na Guerra</a>| <a href="?p=guerra&vila=top3">Top 3</a><div class="sep"></div>


<?php if ($_GET['vila']=="top3"){ ?>


 <table width="100%" cellpadding="0" cellspacing="0">
<tbody>
<tr class="table_titulo">
<td width="25">#</td>
<td align="left" width="90">Ninja</td>
<td>Org</td>
<td>Vila</td>
<td>Nível</td>
<td>Score</td>
<td align="left">Vila Score</td>
<td>&nbsp;</td>
<td width="80">&nbsp;</td>
</tr>
<tr>
<td colspan="9"><div class="sep"></div></td>
</tr>
<?php
$idrank=mysql_query("SELECT * FROM usuarios where inwar='sim' ORDER BY inwar_score DESC LIMIT 3");
for ($s=0;$s<mysql_num_rows($idrank);$s++){
$seis=mysql_fetch_assoc($idrank);
$juniorsistemas=mysql_query("select * from organizacoes where id='".$seis['id']."'");
$dbor=mysql_fetch_assoc($juniorsistemas);
if($dbor['sigla']==''){
$sigla="X";
}else{
$sigla=$dbor['sigla'];
}
if ($seis['renegado']=="nao"){
$var=$seis['vila'];
}else{
$var=7;
}
$vila_s=mysql_fetch_assoc(mysql_query("select * from nal_war_vilas where vilaid='$var' and warid=".$idinwar));
$posicao=$s+1
?>
<tr class="table_dados" height="20">
<td><?php echo $posicao++;?>°</td>
<td align="left">&nbsp;<a href="?p=view&amp;view=<?php echo $seis['usuario']; ?>"><?php echo $seis['usuario']; ?></a></td>
<td align="left"><b><?php echo $sigla;?></b></td>
<td><img src="_img/rank/<?php echo $seis['vila']; ?>.png"></td>
<td><b>[<?php echo $seis['nivel']; ?>]</b></td>
<td><?php echo $seis['inwar_score']; ?></td>
<td align="left"><?php echo $vila_s['score'];  ?></td>
<td width="18"><img src="_img/<?php $timeout=time()-900; if($seis['timestamp']>=$timeout){ echo 'online'; }else{ echo 'offline'; } ?>.png"></td>
<td align="right"><img src="_img/rank/<?php echo $seis['personagem']; ?>.jpg"></td>
</tr>
<?php } ?>
<tr>
<td colspan="9"><div class="sep"></div></td>
</tr>
</tbody>
</table>
<?php } ?>




<?php if ($_GET['vila']=="ninjas"){ ?>

<?php
if ($_POST['pesquisar']){
if ($_POST['pesquisa']!="7"){
$procura="renegado='nao' and vila='".$_POST['pesquisa']."' and";
}else{
if ($_POST['pesquisa']!="0"){
$procura="renegado='sim' and";
}else{
$procura=" ";
}
}
}
?>
<center>
<form method="post">
Vila:
<select name="pesquisa">
<option value="0" selected="selected">Geral</option>
<option value="1">Vila da Folha</option>
<option value="2">Vila da Areia</option>
<option value="3">Vila do Som</option>
<option value="4">Vila da Chuva</option>
<option value="5">Vila da Nuvem</option>
<option value="6">Vila da Névoa</option>
<option value="8">Vila da Pedra</option>
<option value="9">Vila da Cachoeira</option>
<option value="10">Vila da Neve</option>
<option value="11">Vila da Grama</option>
<option value="7">Akatsuki</option>
</select>
<br>
<input type="submit" name="pesquisar" value="Procurar">
</form>
</center>
<table width="100%" cellpadding="0" cellspacing="0">
<tbody>
<tr class="table_titulo">
<td width="25">#</td>
<td align="left" width="90">Ninja</td>
<td>Org</td>
<td>Vila</td>
<td>Nível</td>
<td>Score</td>
<td align="left">Vila Score</td>
<td>&nbsp;</td>
<td width="80">&nbsp;</td>
</tr>
<tr>
<td colspan="9"><div class="sep"></div></td>
</tr>
<?php
$idrank=mysql_query("SELECT * FROM usuarios where ".$procura." inwar='sim' ORDER BY inwar_score DESC");
for ($s=0;$s<mysql_num_rows($idrank);$s++){
$seis=mysql_fetch_assoc($idrank);
if ($seis['renegado']=="nao"){
$var=$seis['vila'];
}else{
$var=7;
}
$vila_s=mysql_fetch_assoc(mysql_query("select * from nal_war_vilas where vilaid='$var' and warid=".$idinwar));
?>
<tr class="table_dados" height="20">
<td>1º</td>
<td align="left">&nbsp;<a href="?p=view&amp;view=<?php echo $seis['usuario']; ?>"><?php echo $seis['usuario']; ?></a></td>
<td align="left"><b>[X]</b></td>
<td><img src="_img/rank/<?php echo $seis['vila']; ?>.png"></td>
<td><b>[<?php echo $seis['nivel']; ?>]</b></td>
<td><?php echo $seis['inwar_score']; ?></td>
<td align="left"><?php echo $vila_s['score'];  ?></td>
<td width="18"><img src="_img/<?php $timeout=time()-900; if($seis['timestamp']>=$timeout){ echo 'online'; }else{ echo 'offline'; } ?>.png"></td>
<td align="right"><img src="_img/rank/<?php echo $seis['personagem']; ?>.jpg"></td>
</tr>
<?php } ?>
<tr>
<td colspan="9"><div class="sep"></div></td>
</tr>
</tbody>
</table>
<?php } if($_GET['vila']==false){ ?>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tbody>

<tr bgcolor="#1E1E1E">
<td>#</td>
<td>Vila</td>
<td>Logo</td>
<td>Score</td>
<td>Ninjas</td>
</tr>
<tr><td colspan="9">
<div class="sep"></div>
</td>
</tr>
<?php
$idrank=mysql_query("SELECT * FROM nal_war ORDER BY id DESC LIMIT 1");
for ($s=0;$s<mysql_num_rows($idrank);$s++){
$sei=mysql_fetch_assoc($idrank);
$Ranksql = mysql_query("SELECT * FROM nal_war_vilas WHERE warid=".$sei['id']." ORDER BY score DESC");
}
for($i=0;$i<mysql_num_rows($Ranksql);$i++) {
$y=$i+1;
$Rank_vilas = mysql_fetch_assoc($Ranksql);
$vila=mysql_fetch_assoc(mysql_query("SELECT * FROM usuarios WHERE vila=".$Rank_vilas['vilaid']." and inwar='sim'"));
					switch($Rank_vilas['vilaid']){
					case 1: $txtvila='Vila da Folha'; break;
					case 2: $txtvila='Vila da Areia'; break;
					case 3: $txtvila='Vila do Som'; break;
					case 4: $txtvila='Vila da Chuva'; break;
					case 5: $txtvila='Vila da Nuvem'; break;
					case 6: $txtvila='Vila da Névoa'; break;
					case 8: $txtvila='Vila da Pedra'; break;
					case 9: $txtvila='Vila da Cachoeira'; break;
					case 10: $txtvila='Vila da Neve'; break;
					case 11: $txtvila='Vila da Grama'; break;
					case 7: $txtvila='Akatsuki'; break;
											}
if ($Rank_vilas['vilaid']!="7"){
$exibi="vila='".$Rank_vilas['vilaid']."' and inwar='sim' and renegado='nao'";
}else{
$exibi="inwar='sim' and renegado='sim'";
}
$count=mysql_query("SELECT count(vila) as total FROM usuarios WHERE ".$exibi."");
$ninjas=mysql_fetch_array($count);
$countninjas=$ninjas['total'];
?>
<tr>
<td><?php echo $y; ?>°</td>
<td><?php echo $txtvila;  ?></td>
<td><img src="_img/rank/<?php echo $Rank_vilas['vilaid']; ?>.png"></td>
<td><?php echo $Rank_vilas['score']; ?></td>
<td><?php echo $countninjas; ?></td>
</tr>
</tr>
<tr>
<td colspan="9">
<div class="sep"></div>
</td>
</tr>
<?php } echo "</tbody></table>"; } ?>
<?php } if ($ssj=="fim"){ ?>
<?php
	switch($vencedorinwar){
					case 1: $txtvila='Vila da Folha'; break;
					case 2: $txtvila='Vila da Areia'; break;
					case 3: $txtvila='Vila do Som'; break;
					case 4: $txtvila='Vila da Chuva'; break;
					case 5: $txtvila='Vila da Nuvem'; break;
					case 6: $txtvila='Vila da Névoa'; break;
					case 8: $txtvila='Vila da Pedra'; break;
					case 9: $txtvila='Vila da Cachoeira'; break;
					case 10: $txtvila='Vila da Neve'; break;
					case 11: $txtvila='Vila da Grama'; break;
					case 7: $txtvila='Akatsuki'; break;
											}
?>
<h1 style="color:#3A81B1;text-shadow:1px 0px #49A7FF, -1px 0px 5px #000AFF;"><?php echo $idinwar; ?>° Guerra de Vilas Finalizada!</h1>
<div class="sep"></div>
<b style="color:#3A81B1;">Vila campeã:</b> <?php echo $txtvila; ?>
<?php } ?>
</div>
<div class="box_bottom"></div>