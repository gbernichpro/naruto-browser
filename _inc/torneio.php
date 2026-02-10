<?php include 'trava.php'; ?>
  <?php
	if($_GET['msg']==true){
		switch($_GET['msg']){
			case 1: $msg='<script>top.$.prompt("Você não pode atacar você mesmo!");</script>'; break;
			case 2: $msg='<script>top.$.prompt("Este usuário já foi eliminado do torneio!");</script>'; break;
		    case 3: $msg='<script>top.$.prompt("Este usuário não está no torneio!");</script>'; break;
		    case 4: $msg='<script>top.$.prompt("Você ja foi eliminado do torneio!");</script>'; break;
            case 5: $msg='<script>top.$.prompt("Você não está no torneio");</script>'; break;
		}
	echo $msg;
	}
	?>

<?php if ($ssj2=="fim"){ ?>
<script>
function ExibiLista(){
if (document.getElementById("lista").style.display=="none"){
document.getElementById("lista").style.display="block";
document.getElementById("Click").innerHTML="Ocultar Antigas Arenas";
}else{
document.getElementById("lista").style.display="none";
document.getElementById("Click").innerHTML="Exibir Antigas Arenas";
}
}
</script>
<?php } ?>
<div class="box_top">Arena</div>
<div class="box_middle">
<center>
<img src="_img/arena.png" onclick="proteje()"><div class="sep"></div>
Bem-Vindo(a), todos os ninjas inscritos de cada vila irão competir na <b>Arena</b>.<br>
A <b>Arena</b> só acaba quando restar um sobrevivente e não é aceito items para aprimorar o dano.
<br>
Cada ninja derrotado é <b>Eliminado</b> e o ultimo que resta é o <b>Vencedor</b> arena só acaba quando houver  1 sobrevivente ele será o vencedor.

<br><br>
<?php if ($ssj2=="fim"){ ?>
<a style="cursor:pointer;color:#3A81B1;" id="Click" onselectstart="return false" onclick="ExibiLista()">Exibir Antigas Arenas</a>
<br>
<div  id="lista" style="display:none;">
<table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #3A81B1;text-align:center;">
<tbody>
<tr bgcolor="#1E1E1E" style="font-weight:bold;">
<td>#</td>
<td>Vencedor</td>
<td>Score</td>
<td>Premio</td>
</tr>
<?php
$rankingsql=mysql_query("SELECT * FROM nal_torneio ORDER BY id DESC LIMIT 10");
for ($t=0;$t<mysql_num_rows($rankingsql);$t++){
$ranking=mysql_fetch_assoc($rankingsql);
$pesqui=mysql_query("select * from usuarios where id='".$ranking['vencedor']."'");
$pesquiven=mysql_fetch_assoc($pesqui);
$venc=$pesquiven['usuario'];
$pontos=$ranking['scorevenc'];
?>
<tr>
<td><?php echo $ranking['id']; ?>°</td>
<td><?php echo $venc; ?></td>
<td><?php echo $pontos; ?></td>
<td><?php echo number_format($ranking['premio'],2,',','.'); ?></td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
<?php } ?>
<div class="sep"></div>
<?php if ($ssj2=="inscricao"){ ?>
<?php
$juniorrios=mysql_query("SELECT * from nal_torneio WHERE status='inscricao'");
$jrrios=mysql_fetch_array($juniorrios);
$nivel1=$jrrios['nivelmin'];
$nivel2=$jrrios['nivelmax'];
$valor=$jrrios['custo'];
if ($_POST['Inscrever-se']){
if ($ssj2=="inscricao"){
if ($_SESSION['logado']==true){
if($db['yens']<$valor){ echo "<script>self.location='?p=torneio&msgg=3'</script>"; return; }
if($db['nivel']<$nivel1){ echo "<script>self.location='?p=torneio&msgg=1'</script>"; return; }
if($db['nivel']>$nivel2){ echo "<script>self.location='?p=torneio&msgg=2'</script>"; return; }
if($insc<=0){ echo "<script>self.location='?p=torneio&msgg=4'</script>"; return; }
if ($db['torneio']=="nao"){
mysql_query("UPDATE usuarios SET torneio='sim',yens=yens-".$valor." WHERE id=".$_SESSION['logado']);
mysql_query("update nal_torneio set inscricoes=inscricoes-1 where id=".$idinwar2);
$msg="Inscrito na <b>Arena</b>. Aguarde o inicio para iniciar os combates <b>Torneio!</b>";
}else{
$msg="Você já esta inscrito no <b>Torneio</b>.";
}
}else{
echo "<script>top.location='/?p=login';</script>";
}
}else{
echo "<script>top.location='/?p=torneio';</script>";
}
}
if ($_POST['Inscrever-se']==true){
echo "<div class='aviso'>".$msg."</div>";
}
$count2=mysql_query("SELECT count(torneio) as total FROM usuarios WHERE torneio = 'sim'");
$ninjas2=mysql_fetch_array($count2);
$countinscritos=$ninjas2['total'];

?>
  <?php
	if(isset($_GET['msgg'])){
		switch($_GET['msgg']){
			case 1: $msgg='<script>top.$.prompt("Seu nivel é menor que o nivel minimo impossivel realizar esta inscrição");</script>'; break;
			case 2: $msgg='<script>top.$.prompt("Seu nivel é maior que o nivel maximo impossivel realizar esta inscrição");</script>'; break;
			case 3: $msgg='<script>top.$.prompt("Yens insuficientes para se inscrever");</script>'; break;
			case 4: $msgg='<script>top.$.prompt("As Inscrições se encerraram tente da proxima vez");</script>'; break;
		}
	echo $msgg;
	}
	?>
Está aberta as inscrições para a <b>Arena</b> do <b>Naruto</b>.<br>
Inscreva-se <b>já</b>, <b>Todos X Todos</b> O Vencedor Ganhará um premio em yens e bastante experiencia!!<br>
Inscritos: <?php echo $countinscritos; ?>
<div class='sep'></div>
<div style="background:url(_img/gradient.jpg) repeat-y;color:#FFFFAA;"><img src="_img/yens.png" width="14" height="14" /> <b>Meus Yens: <?php echo number_format($db['yens'],2,',','.'); ?> yens</b></div><div class="sep"></div>
<table width="100%" cellpadding="0" cellspacing="0">
<tr height=20% >
<td align=right>
<small>Inscrições Restantes: <?php echo $insc; ?><br></small>
</td>
<td align=right>
<small>Inscritos:<?php echo $countinscritos; ?></small>
</td>
</tr>
</table>
<div align="left"> <strong>Nivel Minimo</strong>: <small><?php echo $jrrios['nivelmin']; ?></small> </div>
<div align="left"> <strong>Nivel Máximo<strong>:  <small><?php echo $jrrios['nivelmax']; ?></small></div>
<div align="left"> <strong>Taxa de inscrição<strong>:  <small><?php echo number_format($jrrios['custo'],2,',','.'); ?> yens</small></div>
<div align="left"> <strong>Premio<strong>:  <small><?php echo number_format($jrrios['premio'],2,',','.'); ?> yens</small></div>
<div align="left"> <strong>Experiencia inicial<strong>:  <small><?php echo $jrrios['exp']; ?></small></div>
<div align="left"> <strong>Final da inscrição</strong>: <font color="#FFFFFF"><small><?php echo $jrrios['fim']; ?></small></font> </div>
<?php
if ($db['torneio']=="sim"){echo"
<div class='sep'></div>
Você já inscrito na arena aguarde até o fim das inscrições.

";
}
elseif($insc<=0){	echo"Inscrições encerradas tente da proxima vez";
	}else{?>
<div class="sep"></div>
<form method="post" id="Form-Inscrever" action="?p=torneio&amp;en=ok" onsubmit="var b=this.querySelector('input[type=submit]'); if(b) { b.value='Carregando...'; b.disabled=true; }">

<input type="hidden" id="p" name="Inscrever-se" value="rank" />
<input type="submit" id="subm" style="cursor:pointer;" class="button" name="Inscrever-se" value="Inscrever-Se">
</form>

<?php }} if ($ssj2=="aberto"){ ?>
<div align="center"><h1 style="color:#FFFF00;text-shadow:1px 0px #49A7FF, -1px 0px 5px #000AFF;"><?php echo $idinwar2; ?>° Arena Naruto</h1></div>

<?php

$count2=mysql_query("SELECT count(torneio) as total FROM usuarios WHERE torneio = 'sim'");
$ninjas2=mysql_fetch_array($count2);
$countinscritos=$ninjas2['total'];

$count22=mysql_query("SELECT count(torneio) as total FROM usuarios WHERE torneio = 'sim' and torneio_eliminado=666");
$ninjas22=mysql_fetch_array($count22);
$countinscritos2=$ninjas22['total'];

$count222=mysql_query("SELECT count(torneio) as total FROM usuarios WHERE torneio = 'sim' and torneio_eliminado=0");
$ninjas222=mysql_fetch_array($count222);
$countinscritos22=$ninjas222['total'];


$array=array('1' => 'Iniciante','2' => 'Avançado','3' => 'Profissional');

if($nivelmax<20){
$tour=$array[1];
}
elseif($nivelmax<50){$tour=$array[2];
}
elseif($nivelmax<200){$tour=$array[3];
}
else{$tour="";
}


?>

  <b><i>Categoria</i></b>    <small><?php echo $tour;?></small>
 <div class="sep"></div>
<table width="100%" cellpadding="0" cellspacing="0">
<tr height=20%>
<td align=center>
<div align="left"><b><strong>Usuários Inscritos</strong></b> : <?php echo $countinscritos;?><br>
</td>
<td align=right>
<small>Premio:<?php echo number_format($premioinwar2,2,',','.'); ?> yens</small>
</td>
<tr height=30% >
<td align=right>
<small><h2>Nivel<?php $tour;?>: <?php echo $nivelmin;?> até <?php echo $nivelmax;?> </h2></small>
</td>
</tr>
</tr>
<tr height=70% >
<td valign=top>
<br><br><strong>Usuários Eliminados</strong>: <?php echo $countinscritos2;?><br>
</td>
<td valign=bottom align=right background="./test.jpg">
<br><br><strong>Usuários Restantes</strong>:<?php echo $countinscritos22;?>
</td>
</tr>
</table>







 <div class="sep"></div>
<?php if ($_GET['vila']==false){ ?>

<?php
if ($_POST['pesquisar']){
if ($_POST['pesquisa']!="7"){
$procura="renegado='nao' and vila='".$_POST['pesquisa']."' and";
}else{
if ($_POST['pesquisa']!="0"){
$procura="renegado='sim' and";
}else{
$procura="";
}
}
}
?>
<center>
<form method="post" action="?p=torneio">
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
<td align="left">Status</td>
<td>&nbsp;</td>
<td width="80">&nbsp;</td>
</tr>
<tr>
<td colspan="9"><div class="sep"></div></td>
</tr>
<?php
$pr=$_POST['pesquisa'];
if($pr==0){
$idrank=mysql_query("SELECT * FROM usuarios where torneio='sim' ORDER BY torneio_score DESC");
}
else{
$idrank=mysql_query("SELECT * FROM usuarios where ".$procura." torneio='sim' ORDER BY torneio_score DESC");
}
for ($s=0;$s<mysql_num_rows($idrank);$s++){
$seis=mysql_fetch_assoc($idrank);
$i++;
$posicao=$i;
?>
<tr class="table_dados" height="20">
<td><?php echo $posicao;?>º</td>
<td align="left">&nbsp;<a href="?p=duelotour&id=<?php echo $seis['id']; ?>"><?php echo $seis['usuario']; ?></a></td>
<td align="left"><b>[X]</b></td>
<td><img src="_img/rank/<?php echo $seis['vila']; ?>.png"></td>
<td><b>[<?php echo $seis['nivel']; ?>]</b></td>
<td><?php echo $seis['torneio_score']; ?></td>
<td align="left"><?php

  if ($seis['torneio_eliminado'] > 0){
			echo "<b></b> <font color=\"red\">Eliminado</font>";
			}
			else{
			echo "<b></b> <font color=\"green\"><b>Ativo</b></font>";
            }
 ?></td>
<td width="18"><img src="_img/<?php $timeout=time()-900; if($seis['timestamp']>=$timeout){ echo 'online'; }else{ echo 'offline'; } ?>.png"></td>
<td align="right"><img src="_img/rank/<?php echo $seis['personagem']; ?>.jpg"></td>
</tr>
<?php } ?>
<tr>
<td colspan="9"><div class="sep"></div></td>
</tr>
</tbody>
</table>
<?php }?>
<?php } if ($ssj2=="fim"){ ?>
<?php
$pesquisar=mysql_query("select * from usuarios where id='".$vencedorinwar2."'");
$pesquise=mysql_fetch_assoc($pesquisar);
     ?>
<h1 style="color:#3A81B1;text-shadow:1px 0px #49A7FF, -1px 0px 5px #000AFF;"><?php echo $idinwar2; ?>° Arena Finalizada!</h1>
<div class="sep"></div>
<b style="color:#3A81B1;">Campeão:</b><a href="?p=view&view=<?php echo $pesquise['usuario']; ?>"><?php echo $pesquise['usuario']; ?></a>


<?php


	echo "<fieldset style='background:#444444;'><legend><b>A Arena Ninja Está Fechado</b></legend>\n";
	echo "<table>";
	echo "<tr>";
	echo "<td>
<a href=\"?p=view&view=".$pesquise['usuario']."\"><img src=\"_img/personagens/" . $pesquise['personagem'] . "/" . $pesquise['avatar'] . ".jpg\" width=\"80\" height=\"80\" border=\"0\" /></a>

    </td><td>";

	echo "<table>";
	echo "<tr>";
	echo "<td><b>Ultimo ganhador:</b></td>";
	echo "<td>" . $pesquise['usuario'] . "</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td><b>Premio recebido:</b></td>";
	echo "<td>" . $ranking['premio'] . "</td>";
	echo "</tr>";
	echo "</tr>";
	echo "</table>";


	echo "</td>";
	echo "</tr>";
	echo "</table>";


	echo "</fieldset> <br>";
	echo"<div class=aviso>A Arena já encerrou</div>";




?>



<?php } ?>
</div>
<div class="box_bottom"></div>