<?php
if(isset($_GET['cancel'])){
	mysql_query("UPDATE usuarios SET treino='0', treino_fim='0000-00-00 00:00:00' WHERE id='".$db['id']."'");
	$db['treino']=0;
	$db['treino_fim']='0000-00-00 00:00:00';
}
?>
<?php require_once('verificar.php'); ?>
<?php
$sqls = mysql_query("SELECT s.*,u.usuario FROM salas s LEFT OUTER JOIN usuarios u ON s.usuarioid=u.id ORDER BY id ASC");
$dbs = mysql_fetch_assoc($sqls);
?>
<?php
$atual = date('Y-m-d H:i:s');
$sqlv=mysql_query("SELECT * FROM salas WHERE usuarioid='".$db['id']."' AND fim>'".$atual."'");
$dbv=mysql_fetch_assoc($sqlv);
if(mysql_num_rows($sqlv)==0){
?>
<div class="box_top">Escola Ninja</div>
<div class="box_middle"><div style="background: url(../_img/_detalhes/base2.PNG);width: 720px;height: 250px;">
<table cellpadding="0" cellspacing="0" width="712" height="230"><tbody><tr><td width="150">
<img width="142" style="" src="_img/_detalhes/msg/18.png"></td><td valign="top"><br><br><br>
<div style="margin-left: -345px;margin-top: 15px;height: 0px;">
<b id="title" style="font-family: impact;font-size: 20px;font-weight: normal;color: #ffffff;"  onmouseover="style.color='#F5F5F5'" onmouseout="color.color='#ffffff'">
&raquo; Escola Ninja</b></div><br>
<div style="margin-left: 0px;margin-top: 35px;font-family: arial;font-size: 12px;color: #fff;"><b>
Bem-vindo à Escola Ninja! Aqui você poderá aprender novos jutsus, descobrir a</br>
natureza do seu chakra, praticar jutsus, entre outros. Escolha uma das salas</br>
disponíveis abaixo para usar. Haverá um sensei a sua espera!</br>
<b>OBS: Você só poderá ficar na sala em um tempo máximo de 15 minutos.
</br>
</b>
</div></td></tr></tbody></table></div><div class="sep"></div>
	<div align="center">
    <div class="aviso"><?php if(isset($_GET['cancel'])) echo 'Treino cancelado!<div class="sep"></div>'; ?><b>Salas disponíveis: <span id="disp"></span> de <?php echo mysql_num_rows($sqls); ?></b></div>
    <div class="sep"></div>
    <table width="100%" border="0" cellpadding="0" cellspacing="1">
      <?php $i=1; $d=0; do{ if(($dbs['usuarioid']==0)or($atual>$dbs['fim'])) $d++; ?>
      <tr class="table_dados" style="background:#323232;" onmouseover="style.background='#2C2C2C'" onmouseout="style.background='#323232'">
        <td>Sala <?php echo $i; ?></td>
        <td><?php if(($dbs['usuarioid']==0)or($atual>$dbs['fim'])) echo 'Ninguém'; else echo '<a href="?p=view&view='.$dbs['usuario'].'">'.$dbs['usuario'].'</a>'; ?></td>
        <td><?php if(($dbs['usuarioid']==0)or($atual>$dbs['fim'])) echo '<span style="color:#33CC00">Disponível</a>'; else echo '<span style="color:#FF9900">Ocupada</span>'; ?></td>
        <td><?php if(($dbs['usuarioid']==0)or($atual>$dbs['fim'])){ ?><a href="?p=room&amp;id=<?php echo $dbs['id']; ?>">Entrar</a><?php } ?></td>
      </tr>
      <?php $i++; } while($dbs=mysql_fetch_assoc($sqls)); ?>
    </table>
    </div>
    <script>document.getElementById('disp').innerHTML='<?php echo $d; ?>';</script>
</div>
<div class="box_bottom"></div>
<?php } else { ?>
<?php
$atual=date('Y-m-d H:i:s');
if($dbv['usuarioid']>0){
	if($atual<$dbv['fim']){
		$fim=$dbv['fim'];
		$sqltempo=mysql_fetch_assoc(mysql_query("SELECT timediff('".$fim."','".$atual."') as fim"));
		$fim=$sqltempo['fim'];
		$msg='Você já está com uma sala reservada, e ainda tem <b><span id="sala_tempo" style="color:#FFFFFF">'.$fim.'</span></b> restando.';
	}
}
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
<?php if($atual<$dbv['fim']) echo "window.setInterval('calculafim(\"sala_tempo\",\"mensagem\")',1000);"; ?>
function atualiza(div,divtotal){
  	if((document.getElementById(div).value) < "00:00:01"){
  		self.location="?p=school";
  		conc=1;
	}
}
</script>
<div class="box_top">Escola Ninja</div>
<div class="box_middle"><div class="aviso" id="mensagem"><?php
	if($atual<$dbv['fim'])
		echo $msg; 
	else
		echo $msgconc;
	?></div>
    <div class="sep"></div>
    <div align="center">Você pode retornar para a sala ou cancelar seu aprendizado a qualquer momento, utilizando os botões abaixo.<div class="sep"></div><input type="button" class="botao" value="Retornar para a Sala" onclick="location.href='?p=room&id=<?php echo $dbv['id']; ?>'" />&nbsp;<input type="button" class="botao" value="Cancelar" onclick="self.location='?p=room&leave=true'" /></div>
</div>
<div class="box_bottom"></div>
<?php } ?>
<?php
@mysql_free_result($sqls);
@mysql_free_result($sqlv);
?>
