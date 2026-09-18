<?php require_once('trava.php'); ?>
<?php
require_once('Encrypt.php');
$c = new C_Encrypt();

$array=array("t"=>$db['taijutsu'],"n"=>$db['ninjutsu'],"g"=>$db['genjutsu']);
rsort($array);
$array2=array("t"=>$db['taijutsu'],"n"=>$db['ninjutsu'],"g"=>$db['genjutsu']);
arsort($array2);
$tam=220;
require_once('funcoes.php');
?>
<?php
$max=250;
$src="_img/bars/bar.png";
$array=array("t"=>$db['taijutsu'],"n"=>$db['ninjutsu'],"g"=>$db['genjutsu']);
rsort($array);
$array2=array("t"=>$db['taijutsu'],"n"=>$db['ninjutsu'],"g"=>$db['genjutsu']);
arsort($array2);
?>
  <script>
function ExibiLista(){
if (document.getElementById("lista").style.display=="none"){
document.getElementById("lista").style.display="block";
document.getElementById("Click").innerHTML="Ocultar Medalhas";
}else{
document.getElementById("lista").style.display="none";
document.getElementById("Click").innerHTML="Exibir Medalhas";
}
}
</script>
<div class="box_top">Atributos de <?php echo ucfirst($_GET['view']); ?></div>
<?php if($db['tipodeconta']=='admin') {
  echo '<div class="box_middle" ><div class="apresentacao" style="width:400px; margin:auto; text-align:center "><img src="_img/star.png" width="" height="" alt="" border="0">Este usuario é Administrador.</div></div>';
} else {
?>
<div class="box_middle">Atributos de combate, nível e experiência de <?php echo ucfirst($_GET['view']); ?>.<div class="sep"></div>
	<?php
		if($db['renegado']=='sim'){
			$sqlx=mysql_query("SELECT id FROM usuarios WHERE renegado='sim' ORDER BY nivel DESC, yens_fat DESC, vitorias DESC, derrotas ASC LIMIT 1");
		  	$dbx=mysql_fetch_assoc($sqlx);
			if($dbx['id']==$db['id']) $nivel='Líder da Akatsuki'; else $nivel='Nukenin';
		} else {
        	$sqlx=mysql_query("SELECT id FROM usuarios WHERE vila=".$db['vila']." AND renegado='".$db['renegado']."' ORDER BY nivel DESC, yens_fat DESC, vitorias DESC, derrotas ASC LIMIT 1");
		  	$dbx=mysql_fetch_assoc($sqlx);
		  	if($dbx['id']==$db['id']){
		  		switch($db['vila']){
    case 1: $nivel='Hokage'; break;
					case 2: $nivel='Kazekage'; break;
					case 3: $nivel='Otokage'; break;
					case 4: $nivel='Líder da Vila da Chuva'; break;
					case 5: $nivel='Raikage'; break;
					case 6: $nivel='Mizukage'; break;
					case 8: $nivel='Tsuchikage'; break;
					case 9: $nivel='Amekage'; break;
					case 10: $nivel='Fumakage'; break;
					case 11: $nivel='Takikage'; break;
					case 12: $nivel='Administrador'; break;
					case 13: $nivel='Invasor'; break;

				}
		  	} else $nivel=rankNinja($db['nivel']);
		}
		?>
	<table width="100%" cellpadding="0" cellspacing="0"<?php if($dbx['id']==$db['id']){
		echo ' style="background:url(_img/kage/kage';
		if($db['renegado']=='sim') echo '4'; else
		switch($db['vila']){
   	case 1: echo '1'; break;
			case 2: echo '2'; break;
			case 3: echo '3'; break;
			case 4: echo '4'; break;
			case 5: echo '5'; break;
			case 6: echo '6'; break;
			case 8: echo '8'; break;
			case 9: echo '9'; break;
			case 10: echo '10'; break;
			case 11: echo '11'; break;
            case 99: echo '1'; break;
		}
		echo '.jpg) no-repeat right top;"'; } ?>>
		<tr style="background:url(_img/gradient2.jpg) repeat-y;">
        	<td width="20%" align="right" style="padding-right:10px;"><b>Registro:</b></td>
      <td colspan="2"><?php $reg=explode(' ',$db['reg']); $datareg=explode('-',$reg[0]); echo $datareg[2].'/'.$datareg[1].'/'.$datareg[0].', às '.$reg[1]; ?></td>
        </tr>
        <tr>
        	<td align="right" style="padding-right:10px;"><b>Personagem:</b></td>
          <td colspan="2"><?php fpersonagem($db['personagem']); ?></td>
        </tr>
        <tr style="background:url(_img/gradient2.jpg) repeat-y;">
        	<td width="20%" align="right" style="padding-right:10px;"><b>Vila:</b></td>
      <td colspan="2"><?php echo $txtvila; ?></td>
        </tr>
        <?php /*<tr>
        	<td align="right" style="padding-right:10px;"><b>Aluno:</b></td>
          <td colspan="2"><?php /*if($db['alunoid']=='') echo '-'; else echo '<a href="?p=view&view='.strtolower($db['alunoid']).'">'.$db['alunoid'].'</a>'; ?></td>
        </tr>
        <tr style="background:url(_img/gradient.jpg) repeat-y;">
        	<td width="20%" align="right" style="padding-right:10px;"><b>Sensei:</b></td>
      <td colspan="2"><?php /*if($db['senseiid']=='') echo '-'; else echo '<a href="?p=view&view='.strtolower($db['senseiid']).'">'.$db['senseiid'].'</a>'; ?></td>
        </tr>*/ ?>
        <tr>
        	<td align="right" style="padding-right:10px;"><b>Clã:</b></td>
          <td colspan="2"><?php if(($db['orgid']==0)or($db['orgid']==-1)) echo '-'; else echo '<a href="?p=vieworg&id='.strtolower($db['orgid']).'">'.$db['orgnome'].'</a>'; ?></td>
        </tr>
        <tr style="background:url(_img/gradient2.jpg) repeat-y;">
        	<td align="right" style="padding-right:10px;"><b>Nível:</b></td>
          <td colspan="2"><?php echo $nivel; ?><b> [<?php echo $db['nivel']; ?>]</b></td>
        </tr>
        <tr>
        	<td colspan="3"><div class="sep"></div></td>
        </tr>
        <tr>
        	<td align="right" style="padding-right:10px;"><b>Taijutsu:</b></td>
          <td><img src="_img/bars/bar_left.jpg" /><?php
			if($array[0]==$array2["t"]) echo '<img src="'.$src.'" width="'.($max*$array[0])/$array[0].'" height="22" />'; else
			if($array[1]==$array2["t"]) echo '<img src="'.$src.'" width="'.($max*$array[1])/$array[0].'" height="22" /">'; else
			if($array[2]==$array2["t"]) echo '<img src="'.$src.'" width="'.($max*$array[2])/$array[0].'" height="22" /">';
			?><img src="_img/bars/bar_right.jpg" />
    		</td>
            <td width="25%"><b>| <?php echo $db['taijutsu']; ?> |</b></td>
        </tr>
        <tr>
        	<td align="right" style="padding-right:10px;"><b>Ninjutsu:</b></td>
          <td><img src="_img/bars/bar_left.jpg" /><?php
			if($array[0]==$array2["n"]) echo '<img src="'.$src.'" width="'.($max*$array[0])/$array[0].'" height="22" />'; else
			if($array[1]==$array2["n"]) echo '<img src="'.$src.'" width="'.($max*$array[1])/$array[0].'" height="22" />'; else
			if($array[2]==$array2["n"]) echo '<img src="'.$src.'" width="'.($max*$array[2])/$array[0].'" height="22" />';
			?><img src="_img/bars/bar_right.jpg" />
            </td>
            <td><b>| <?php echo $db['ninjutsu']; ?> |</b></td>
        </tr>
        <tr>
        	<td align="right" style="padding-right:10px;"><b>Genjutsu:</b></td>
          <td><img src="_img/bars/bar_left.jpg" /><?php
			if($array[0]==$array2["g"]) echo '<img src="'.$src.'" width="'.($max*$array[0])/$array[0].'" height="22" />'; else
			if($array[1]==$array2["g"]) echo '<img src="'.$src.'" width="'.($max*$array[1])/$array[0].'" height="22" />'; else
			if($array[2]==$array2["g"]) echo '<img src="'.$src.'" width="'.($max*$array[2])/$array[0].'" height="22" />';
			?><img src="_img/bars/bar_right.jpg" />
          </td>
            <td><b>| <?php echo $db['genjutsu']; ?> |</b></td>
        </tr>
        <tr>
        	<td colspan="3"><div class="sep"></div></td>
        </tr>
        <tr>
        	<td align="right" style="padding-right:10px;"><b>Experiência:</b></td>
          <td height="22" style="background:url(_img/bars/empty_bar.jpg) no-repeat;"><?php if($db['exp']==0) echo '&nbsp;'; else { ?><img src="_img/bars/bar_left.png" /><img src="<?php echo $src; ?>" width="<?php echo (($db['exp']*$max)/$db['expmax']); ?>" height="22" /><img src="_img/bars/bar_right.png" /><?php } ?></td>
            <td><b>| <?php echo $db['exp']; ?> / <?php echo $db['expmax']; ?> |</b></td>
        </tr>
    </table>
    <?php if($db['id']<>$_SESSION['logado']){ ?>
    <div class="sep"></div>
    <div align="center">
    	<form method="post" action="?p=hunt" onsubmit="var b=this.querySelector('input[type=submit]'); if(b) { b.value='Carregando...'; b.disabled=true; }">
		<?php
        $sqlf=mysql_query("SELECT count(id) conta FROM amigos WHERE usuarioid=".$_SESSION['logado']." AND amigoid=".$db['id']);
		$dbf=mysql_fetch_assoc($sqlf);
        if($dbf['conta']==0){ ?><input type="button" class="botao" value="Buddy List" style="margin-right:15px;" onclick="location.href='?p=addfriend&id=<?php echo $_GET['view']; ?>'" />
		<?php } ?>
        <?php
        $sqlf=mysql_query("SELECT count(id) conta FROM book WHERE usuarioid=".$_SESSION['logado']." AND inimigoid=".$db['id']);
		$dbf=mysql_fetch_assoc($sqlf);
        if($dbf['conta']==0){ ?><input type="button" class="botao" value="Bingo Book" style="margin-right:15px;" onclick="location.href='?p=addbook&id=<?php echo $_GET['view']; ?>'" />
		<?php } ?>
        <input type="hidden" id="hunt_tipo" name="hunt_tipo" value="<?php echo $c->encode('1',$chaveuniversal); ?>" />
        <input type="hidden" id="hunt_1" name="hunt_1" value="<?php echo $_GET['view']; ?>" />
        <input type="button" class="botao" value="Mensagem" style="margin-right:15px;" onclick="location.href='?p=messages&destiny=<?php echo ucfirst($_GET['view']); ?>'" /> <input type="submit" id="subm" name="subm" class="botao" value="Atacar" />
        </form>
    </div>
    <?php } ?>
</div>
<div class="box_bottom"></div>
<div class="box_top">Estatísticas de <?php echo ucfirst($_GET['view']); ?></div>
<div class="box_middle">Estatísticas da conta de <?php echo ucfirst($_GET['view']); ?>.<div class="sep"></div>
	<div style="background:url(_img/stats.jpg) no-repeat right top;height:120px;">
	<table width="60%" cellpadding="0" cellspacing="0">
        <tr style="background:url(_img/gradient.jpg) right;">
        	<td style="padding-left:3px;"><b>Yens Faturados</b></td>
            <td><?php echo number_format($db['yens_fat'],2,',','.'); ?> yens</td>
        </tr>
        <tr>
        	<td style="padding-left:3px;"><b>Yens Perdidos</b></td>
            <td><?php echo number_format($db['yens_perd'],2,',','.'); ?> yens</td>
        </tr>
        <tr style="background:url(_img/gradient.jpg) right;">
        	<td style="padding-left:3px;"><b>Batalhas</b></td>
            <td><?php echo $db['batalhas']; ?> batalhas</td>
        </tr>
        <tr>
        	 <tr style="background:url(_img/gradient.jpg) right;">
        	<td style="padding-left:3px;"><b>Score</b></td>
            <td><?php echo $db['score']; ?> Pontos</td>
        </tr>
        	<td style="padding-left:3px;"><b>Vitórias</b></td>
            <td><?php echo $db['vitorias']; ?> vitórias</td>
        </tr>
        <tr style="background:url(_img/gradient.jpg) right;">
        	<td style="padding-left:3px;"><b>Derrotas</b></td>
            <td><?php echo $db['derrotas']; ?> derrotas</td>
        </tr>
        <tr>
        	<td style="padding-left:3px;"><b>Empates</b></td>
            <td><?php echo $db['empates']; ?> empates</td>
        </tr>
        <tr style="background:url(_img/gradient.jpg) right;">
        	<td style="padding-left:3px;"><b>Experiência Total</b></td>
            <td><?php echo $db['exptotal']; ?> pontos</td>
        </tr>
    </table>
    </div>
</div>
<div class="box_bottom"></div>
<div class="box_top">Apresentação de <?php echo ucfirst($_GET['view']); ?></div>
<div class="box_middle">
     <div align="center"><?php if(isset($_GET['report'])) echo '<div class="aviso">Obrigado por reportar este perfil.<br />Uma análise será feita na mensagem de apresentação.</div>'; else { ?><a href="?p=spam&amp;id=<?php echo($db['id']); ?>&amp;name=<?php echo ($db['usuario']); ?>">Reportar Perfil</a><br /><span class="sub2">Utilize este link para comunicar nossa equipe sobre o uso indevido desta função.</span><?php } ?></div>
    <div class="sep"></div>
	<div class="apresentacao" style="width:520px;"><?php if($db['config_apresentacao']=='') echo 'Nenhum texto de apresentação.'; else echo str_replace(array('<p>','</p>'),array('','<br />'),$db['config_apresentacao']); ?></div>
</div>
<div class="box_bottom"></div>
<div class="box_top">Medalhas Ninjas</div>
<div class="box_middle">
<div align="center"><a style="cursor:pointer;color:#3A81B1;" id="Click" onselectstart="return false" onclick="ExibiLista()">Exibir Medalhas</a></div>
<div  id="lista" style="display:none;">
<table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid transparent;text-align:center;">
<tbody>
               <?php
			$medalha =mysql_query("select * from medalhas where usuarioid='".$db['id']."' order by medalha asc, tipo desc");

			if (mysql_num_rows($medalha)<=0) {

				echo "<br/><center><b>" . $db['usuario'] . " não tem medalhas.</b></center><br/>";

			}else{

			$bronze = mysql_query("select * from medalhas where usuarioid='".$db['id']."' and tipo='1'");

			$prata = mysql_query("select * from medalhas where usuarioid='".$db['id']."' and tipo='2'");

			$ouro = mysql_query("select * from medalhas where usuarioid='".$db['id']."' and tipo='3'");



			echo "<p><table width=\"50%\"><tr>";

				echo "<th width=\"13%\" align=\"right\"><img src=\"_img/medalhas/prata.png\"> X " . mysql_num_rows($prata) . "</th>";

				echo "<th width=\"14%\" align=\"center\"><img src=\"_img/medalhas/medalha.png\"> X " . mysql_num_rows($ouro) . "</th>";

				echo "<th width=\"13%\" align=\"left\"><img src=\"_img/medalhas/bronze.png\"> X " . mysql_num_rows($bronze) . "</th>";

			echo "</tr></table></p>";



			echo "<table>";



				while($meda=mysql_fetch_assoc($medalha))

				{

					echo "<tr><td>";

					if ($meda['tipo'] == '1') {

						echo "<img src=\"_img/medalhas/bronze.png\">";

					} elseif ($meda['tipo'] == '2') {

						echo "<img src=\"_img/medalhas/prata.png\">";

					} else {

						echo "<img src=\"_img/medalhas/medalha.png\">";

					}



					echo "<th width=\"13%\" align=\"left\"</td><td><b>" . $meda['medalha'] . ":</b> " . $meda['descricao'] . "</td></tr>";

				}



			echo "";

			}
			?>

</tbody>
</table>
</div>
</div>



<div class="box_bottom"></div>
<?php if($db['doujutsu']>0) require_once('view_doujutsu.php'); ?>
<?php require_once('view_shop.php'); ?>
<?php if(($db['config_twitter']<>'')&&($db['config_oktwitter']=='sim')) require_once('view_twitter.php'); ?>
<?php
@mysql_free_result($sqlf);
}
?>
