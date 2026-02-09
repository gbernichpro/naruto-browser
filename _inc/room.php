<?php
$atual=date('Y-m-d H:i:s');

$pegagetid = antiinjection($_GET['id']);

if(isset($_GET['leave'])){
	$sqlv=mysql_query("SELECT id FROM salas WHERE usuarioid='".$db['id']."' AND fim>'".$atual."'");
	$dbv=mysql_fetch_assoc($sqlv);
	mysql_query("UPDATE salas SET usuarioid='0', fim='0000-00-00 00:00:00' WHERE id='".$dbv['id']."'");
}
if(!isset($_GET['id'])){ echo "<script>self.location='?p=school'</script>"; return; }
$sqlv=mysql_query("SELECT id FROM salas WHERE usuarioid='".$db['id']."' AND fim>'".$atual."' AND id<>'".$pegagetid."'");
$dbv=mysql_fetch_assoc($sqlv);
if(mysql_num_rows($sqlv)>0){ echo "<script>self.location='?p=school'</script>"; return; }
require_once('verificar_sala.php');
$soma=mktime(date('H'),date('i')+15,date('s'));
$fim=date('Y-m-d H:i:s',$soma);
if((($dbr['usuarioid']==$db['id'])&&($atual>$dbr['fim']))or(($dbr['usuarioid']<>$db['id'])&&($atual>$dbr['fim']))){
	mysql_query("UPDATE salas SET fim='".$fim."', usuarioid='".$db['id']."' WHERE id='".$pegagetid."'");
	$dbr['fim']=$fim;
}
?>
<?php
if($atual<$dbr['fim']){
	$fim=$dbr['fim'];
	$sqltempo=mysql_fetch_assoc(mysql_query("SELECT timediff('".$fim."','".$atual."') as fim"));
	$fim=$sqltempo['fim'];
	$msgconc='<b>Tempo Restante: <span id="sala_tempo" style="color:#FFFFFF">'.$fim.'</span></b>';
	$msg='<b>Tempo Restante: <span id="sala_tempo" style="color:#FFFFFF">'.$fim.'</span></b>';
} else $msgconc='<b>Tempo Restante: <span id="sala_tempo" style="color:#FFFFFF">'.$fim.'</span></b>';
?>
<script language="javascript" type="text/javascript">
var conc=0;
function calculafim(div,divtotal){
	if(conc==0){
	var navegador=navigator.appName;
	var tmp = document.getElementById(div).innerHTML.split(":");
	var s = tmp[2];
	var m = tmp[1];
	var h = tmp[0];
	s--;
	if (s < 00){ s = 59;	m--; }
	if (m < 00){ m = 59;	h--; };
	s = new String(s); if (s.length < 2) s = "0" + s;
	m = new String(m); if (m.length < 2) m = "0" + m;
	h = new String(h); if (h.length < 2) h = "0" + h;

	var temp = h + ":" + m + ":" + s;

	document.getElementById(div).innerHTML = temp;
	document.getElementById(div).value = temp;
	atualiza(div,divtotal);
	}
}
<?php if($atual<$dbr['fim']) echo "window.setInterval('calculafim(\"sala_tempo\",\"mensagem\")',1000);"; ?>
function atualiza(div,divtotal){
  	if((document.getElementById(div).value) < "00:00:01"){
  		self.location="?p=school";
  		conc=1;
	}
}
</script>
<div class="box_top">Sala <?php echo $pegagetid; ?></div>
<div class="box_middle"><div style="background: url(../_img/_detalhes/base2.PNG);width: 720px;height: 250px;">
<table cellpadding="0" cellspacing="0" width="712" height="230"><tbody><tr><td width="150">
<img width="142" style="" src="_img/_detalhes/msg/5.png"></td><td valign="top"><br><br><br>
<div style="margin-left: -345px;margin-top: 15px;height: 0px;">
<b id="title" style="font-family: impact;font-size: 20px;font-weight: normal;color: #ffffff;"  onmouseover="style.color='#F5F5F5'" onmouseout="color.color='#ffffff'">
&raquo; Sala Ninja</b></div><br>
<div style="margin-left: 0px;margin-top: 35px;font-family: arial;font-size: 12px;color: #fff;"><b>

Bem-vindo à minha sala! Serei seu professor pelos próximos 15 minutos.</br>
Escolha abaixo o que você deseja aprender, que eu tentarei lhe ensinar.</br>
Fique de olho no tempo, não irei tolerar 1 segundo a mais!</br>
</br>
</b>
</div></td></tr></tbody></table></div><div class="sep"></div>
	<div class="aviso" id="mensagem">
    <?php
	if(isset($_GET['msg'])){
		switch($_GET['msg']){
			case 1: $errmsg='Você não está pronto para controlar a natureza do seu chakra.<br />Volte quando estiver no nível 12.'; break;
			case 2: $errmsg='Parabéns! Você aprendeu um novo jutsu!<br />Utilize nossa área de treinamento para aperfeiçoá-lo assim que desejar.'; break;
			case 3: $errmsg='Você não está pronto para treinar sua linhagem avançada.<br />Volte quando estiver no nível 5.'; break;
			case 4: $errmsg='Seu doujutsu já foi liberado.<br />O aprimoramento de seu doujtsu depende da utilização.'; break;
			case 5: $errmsg='Você já controla uma natureza do chakra. Volte quando estiver no nível 40.'; break;
		    case 6: $errmsg='Você já controla uma 2° natureza do chakra. Volte quando estiver no nível 60.'; break;
		    case 7: $errmsg='Você não está pronto para controlar a 2° natureza do seu chakra.<br />Volte quando estiver no nível 40 acima.'; break;
		    case 8: $errmsg='Você não está pronto para controlar a 3° natureza do seu chakra.<br />Volte quando estiver no nível 60 acima.'; break;
		    case 9: $errmsg='Você já controla uma 3° natureza do chakra.'; break;
		    case 10: $errmsg='Você tentou controlar o a mesma natureza que a primeira!Falhou seu chakra tente novamente.'; break;
		    case 11: $errmsg='Você tentou controlar o a mesma natureza que a segunda!Falhou seu chakra tente novamente.'; break;
		}
	echo $errmsg.'<div class="sep"></div>';
	}
	?>
    <b>
	<?php
	if($atual<$dbr['fim'])
		echo $msg;
	else
		echo $msgconc;
	?>
    </b></div><div class="sep"></div>
    <div align="center">
    <table width="100%" cellpadding="0" cellspacing="1">
  	  <tr>
            <?php
            if($db['nivel']>=60){
            echo "<td align='center'><a href='?p=elements3&amp;id=".$_GET['id']."'><img src='_img/school/chakra3.png' border='0' /></a></td>";
          	 }
          	else if($db['nivel']>=40)
          	{
              echo "<td align='center'><a href='?p=elements2&amp;id=".$_GET['id']."'><img src='_img/school/chakra2.png' border='0' /></a></td>";
          		}
          		else if ($db['nivel']>=12){          			echo "<td align='center'><a href='?p=elements&amp;id=".$_GET['id']."'><img src='_img/school/chakra.jpg' border='0' /></a></td>";
          		}

          		?>


          	<td align="center"><a href="?p=learn&amp;id=<?php echo $_GET['id']; ?>"><img src="_img/school/jutsu.jpg" border="0" /></a></td>
            <td align="center"><a href="?p=schooltrain&amp;id=<?php echo $_GET['id']; ?>"><img src="_img/school/treino.jpg" border="0" /></a></td>
      </tr>
    </table>
    <div class="sep"></div>
    <div align="center"><input type="button" class="botao" value="Sair da Sala" onclick="location.href='?p=room&leave=true'" /></div>
  </div>
</div>
<div class="box_bottom"></div>
<?php
@mysql_free_result($sqlv);
?>