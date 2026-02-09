<?php require_once('trava.php'); ?>
<?php
if(!isset($_GET['id'])){ echo "<script>self.location='?p=school'</script>"; return; }
if($db['nivel']<60){ echo "<script>self.location='?p=room&id=".$_GET['id']."&msg=8'</script>"; return; }
if($db['natureza3']<>''){ echo "<script>self.location='?p=room&id=".$_GET['id']."&msg=9'</script>"; return; }

if(!isset($_GET['id'])){ echo "<script>self.location='?p=school'</script>"; return; }
require_once('verificar_sala.php');
$atual = date('Y-m-d H:i:s');
$fim = $dbr['fim'];
if($atual<$dbr['fim']){
	$sqltempo=mysql_fetch_assoc(mysql_query("SELECT timediff('$fim','$atual') as fim"));
	$fim=$sqltempo['fim'];
	$msgconc='<b>Tempo Restante: <span id="sala_tempo" style="color:#FFFFFF">'.$fim.'</span></b>';
	$msg='<b>Tempo Restante: <span id="sala_tempo" style="color:#FFFFFF">'.$fim.'</span></b>';
} else { echo "<script>self.location='?p=school'</script>"; return; }
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
<div class="box_top">3° Natureza do Chakra</div>
<div class="box_middle">
<div style="background: url(../_img/_detalhes/base2.PNG);width: 720px;height: 250px;">
<table cellpadding="0" cellspacing="0" width="712" height="230"><tbody><tr><td width="150">
<img width="142" style="" src="_img/_detalhes/msg/5.png"></td><td valign="top"><br><br><br>
<div style="margin-left: -345px;margin-top: 15px;height: 0px;">
<b id="title" style="font-family: impact;font-size: 20px;font-weight: normal;color: #ffffff;"  onmouseover="style.color='#F5F5F5'" onmouseout="color.color='#ffffff'">
&raquo; 3° Natureza do Chakra!</b></div><br>
<div style="margin-left: 0px;margin-top: 35px;font-family: arial;font-size: 12px;color: #fff;"><b>

Acredito que você está pronto para dominar a 3° natureza do seu chakra.</br>
Como vamos descobrir a 3° natureza de seu chakra, utilizaremos</br>
um método muito simples , que é através de papéis sensíveis ao chakra.</br>
Basta você concentrar sua energia que o papel irá reagir,</br>
 e então saberemos os tipos de jutsus que você poderá usar!</br>
</br>
</b>
</div></td></tr></tbody></table></div>
<div class="sep"></div>
	<div class="aviso" id="mensagem">
    <?php
	if(isset($_GET['msg'])){
		switch($_GET['msg']){
			case 1: $errmsg='Você não está pronto para controlar a natureza do seu chakra.<br />Volte quando estiver no nível 7.'; break;
			case 2: $errmsg='Parabéns! Você aprendeu um novo jutsu!<br />Utilize nossa área de treinamento para aperfeiçoá-lo assim que desejar.'; break;
			case 3: $errmsg='Você não está pronto para treinar sua linhagem avançada.<br />Volte quando estiver no nível 5.'; break;
			case 4: $errmsg='Seu doujutsu já foi liberado.<br />O aprimoramento de seu doujtsu depende da utilização.'; break;
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
    <div align="center"><img src="_img/jutsus/papel_chakra.jpg" /><div class="sep"></div><span class="sub2">Este método é muito eficiente, e lhe ajudará a descobrir a 3° natureza de seu chakra.<br />Assim que estiver pronto...<div class="sep"></div><input type="button" class="botao" onclick="location.href='?p=discover3&id=<?php echo $_GET['id']; ?>'" value="Concentrar Chakra" /></span></div>
</div>
<div class="box_bottom"></div>
