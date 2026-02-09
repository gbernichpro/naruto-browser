<?php require_once('trava.php'); ?>
<?php
if($db['tipodeconta'] =='admin'){
}else{
echo "<script>self.location='?p=home&msgg=5'</script>";return;
}
$dat=date('Y-m-d H:i:s');
if ($_POST['loteria'])
{
    if ((!$_POST['preco']) or (!$_POST['creditos'])) {
echo "<script>self.location='?p=painel&msglot=2'</script>";
}else{

$iten=mysql_query("SELECT * FROM table_itens where nome='".antiinjection($_POST['premiolot'])."'");
$it=mysql_fetch_assoc($iten);
	$query = mysql_query("update `settings` set `value`='".antiinjection($_POST['preco'])."' where `name`='preco'");
    $query2 = mysql_query("update `settings` set `value`='".antiinjection($_POST['usuario'])."' where `name`='adm'");

 if($_POST['tipo']=='creditos'){
	$query = mysql_query("update `settings` set `value`='".antiinjection($_POST['creditos'])."' where `name`='vencedor'");
}elseif($_POST['tipo']=='item'){
	$query = mysql_query("update `settings` set `value`='".$it['id']."' where `name`='vencedor'");
}
	$query = mysql_query("update `settings` set `value`='t' where `name`='loteria'");
	$query = mysql_query("update `settings` set `value`='".antiinjection($_POST['termina'])."' where `name`='end_lotto'");
	$query = mysql_query("update `settings` set `value`='".antiinjection($_POST['tipo'])."' where `name`='tipo'");



echo "<script>self.location='?p=painel&msglot=1'</script>";
}}
if ($_POST['invasao'])
{

	$query = mysql_query("update `invasor` set `nome`
='".$_POST['nome']."',`hp`='".$_POST['hp']."',`premio`='".$_POST['premioinv']."',`reqgen`='".$_POST['reqgen']."', `exp`='".$_POST['exp']."',`expmax`='".$_POST['expmax']."',`data`='".$_POST['data']."',`abertopor`='".$_POST['usuario']."', `nivelmin`='".$_POST['nivelmin']."',`nivelmax`='".$_POST['nivelmax']."',`hpmaximo`='".$_POST['maxhp']."',`vitorias`='0',`status`='t'");
echo "<script>self.location='?p=painel&msginv=1'</script>";
}




if (isset($_POST['iniciargr'])){

$hora_cadastro=date('Y-m-d H:i:s');
$hour=time()+86400;
$hora_fim=date('Y-m-d H:i:s', $hour);
mysql_query("INSERT INTO nal_war SET premio=".antiinjection($_POST['premio'])." ,nivelmin=".antiinjection($_POST['nivelmin'])." , nivelmax=".antiinjection($_POST['nivelmax'])." , custo=".antiinjection($_POST['custo'])." ,    inicio='$hora_cadastro' , fim='$hora_fim' , status='inscricao'");
echo "<script>alert('Guerra ninja iniciada com sucesso ');</script>";
$novoid=mysql_insert_id();
$number=1;

while($number <= 11){

mysql_query("insert into nal_war_vilas set vilaid='.$number.' , warid='.$novoid.'");
$number++;
}
}


if (isset($_POST['iniciararena'])){

$hora_cadastro=date('Y-m-d H:i:s');
$hour=time()+7200;
$hora_fim=date('Y-m-d H:i:s', $hour);
mysql_query("INSERT INTO nal_torneio SET premio=".antiinjection($_POST['premio'])." ,nivelmin=".antiinjection($_POST['nivelmin'])." , nivelmax=".antiinjection($_POST['nivelmax'])." , custo=".antiinjection($_POST['custo'])." , exp=".antiinjection($_POST['exp'])." , inscricoes=".antiinjection($_POST['insc'])." ,    inicio='$hora_cadastro' , fim='$hora_fim' , status='inscricao'");
echo "<script>alert('Arena iniciada com sucesso');</script>";
$novoid=mysql_insert_id();
$number=1;
}


if(isset($_GET['act']) && $_GET['act'] == 'send_mail'){
	if(strlen(trim($_POST['assunto'])) >= 3 && strlen(trim($_POST['assunto'])) <= 20){
		if(strlen(trim($_POST['mensagem'])) >= 10 && strlen(trim($_POST['mensahem'])) <= 2048){
			$users = mysql_query("SELECT * FROM `usuarios` WHERE `status`='ativo'");
			while($row = mysql_fetch_assoc($users)){
				mysql_query("INSERT INTO `mensagens` (`data`,`origem`,`destino`,`assunto`,`msg`) VALUES (NOW(),'0','".$row['id']."','".$_POST['assunto']."','".$_POST['mensagem']."')");
			}
			echo "<script>alert('Mensagens enviadas com sucesso!');</script>";
		}
	}
}
?>
  <script type="text/javascript" src="_js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
tinyMCE.init({
	mode : "textareas",
	theme: "advanced",
	plugins: "emotions",
	theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,cut,copy,paste,|,undo,redo,|,link,unlink,image,|,emotions",
	theme_advanced_buttons2 : "",
	theme_advanced_buttons3 : "",
	theme_advanced_toolbar_location : "top",
	content_css:"_css/tiny.css",
	theme_advanced_statusbar_location : "bottom",
	theme_advanced_path : false
});
</script>
<div class="box_top">Fazer comunicado aos jogadores</div>
<div class="box_middle">
	<fieldset>
		<legend>Enviar Mensagem</legend>
		<form method="post" action="?p=painel&act=send_mail" onsubmit="subm.value='Carregando...';subm.disabled=true;">
			<span class="destaque">Assunto da Mensagem:</span><br />
			<input type="text" name="assunto" maxlength="60" onfocus="className='input'" onblur="className=''" /><br />
			<span class="sub2">Digite o assunto da mensagem.</span><br /><div class="sep"></div>
			<span class="destaque">Mensagem:</span>
			<textarea id="msg_msg" name="mensagem" style="width:100%;"></textarea>
			<span class="sub2">Mensagem a ser enviada. Apenas os primeiros 2048 caracteres serão válidos.</span>
			<div class="sep"></div>
			<div align="center"><input type="submit" id="subm" name="sub2" class="botao" value="Enviar Mensagem"></div>
		</form>
	</fieldset>
</div>
<div class="box_bottom"></div>

<div class="box_top">Invasao</div>
<div class="box_middle" style="text-align:left;">
	<?php
	if(isset($_GET['msginv'])){
		switch($_GET['msginv']){
			case 1: $msg='<b>Invasao iniciada</b>,nao mecha nas configuracoes enquanto a loteria estiver aberta'; break;
		}
	echo '<div class="sep"></div><div class="aviso">'.$msg.'</div>';
	}
	?>
<div class="sep"></div>
<form method="POST" action="?p=painel">
<table border="0" width="100%">
<tr>  <td width="400">
<table border="0" width="100%">
<tr><td><b>Nome:</b></td>  <td><input type="text" name="nome" size="20"></td></tr>
<tr><td><b>Hp:</b></td>  <td><input type="text" name="hp" size="20"></td></tr>
<tr><td><b>Hp Maximo:</b></td>  <td><input type="text" name="maxhp" size="20"></td></tr>
<tr><td><b>Premio inicial:</b></td>  <td><input type="text"  name="premioinv" size="20"></td></tr>
<tr><td><b>Genjutsu requerido:</b></td>  <td><input type="text"  name="reqgen" size="20"></td></tr>
<tr><td><b>Nivel minimo:</b></td>  <td><input type="text" value="1" name="nivelmin" size="20"></td></tr>
<tr><td><b>Nivel Maximo:</b></td>  <td><input type="text"  name="nivelmax" size="20"></td></tr>
<tr><td><b>Exp:</b></td>  <td><input type="text" value="1" name="exp" size="20"></td></tr>
<tr><td><b>Exp max:</b></td>  <td><input type="text"  name="expmax" size="20"></td></tr>
<input type="hidden" id="data" name="data" value="<?php echo $dat; ?>">
<input type="hidden" id="usuario" name="usuario" value="<?php echo $db['usuario']; ?>">
</table> </td><td>
<?php
$inv=mysql_query("SELECT * FROM invasor");
$inv=mysql_fetch_assoc($inv);
?>
<?php if($inv['status']=='t'){?>

<input class="botao" type="submit"  name="invasao" value="Iniciar">

<?php }else{?>
<b>Invasao ja iniciada</b>
<?php }?>



</td></tr> </table></form>


<div class="sep"></div>


</div>



<div class="box_bottom"></div>
<div class="box_top">Loteria <?php echo isset($_POST['premiolot']) ? $_POST['premiolot'] : ''; ?></div>
<div class="box_middle" style="text-align:left;">
	<?php
	if(isset($_GET['msglot'])){
		switch($_GET['msglot']){
			case 1: $msg='<b>Loteria iniciada</b>,nao mecha nas configuracoes enquanto a loteria estiver aberta'; break;
			case 2: $msg='<b>ERRO</b>,Por favor preencha todos os campos'; break;
   		}
	echo '<div class="sep"></div><div class="aviso">'.$msg.'</div>';
	}
	?>

<form method="POST" action="?p=painel">
<div class="sep"></div>
<center><b>Qual sera o tipo de premiacao da loteria?</b><br>
<table border="0" width="100%">
<tr>
  <td align="center"><label style="cursor:pointer;"><img src="_img/items.jpg" border="0" onmouseover="Tip('<div class=tooltip>Equipamento</div>');" onmouseout="UnTip()"><br><input name="tipo" value="item" type="radio" CHECKED></label></td>
  <td align="center"><label style="cursor:pointer;"><img src="_img/creditos.jpg" border="0" onmouseover="Tip('<div class=tooltip>Creditos</div>');" onmouseout="UnTip()"><br><input name="tipo" value="creditos" type="radio"></label></td>
</tr>

</table>

</center><div class="sep"></div><table border="0" width="100%">
<tr>  <td width="400">

<table border="0" width="100%">
<tr><td><b>Termina em:</b></td>  <td><select name="termina" size="1">
   <option value="<?php echo(time()+800);?>">12 minutos</option>
   <option value="<?php echo(time()+1800);?>">30 minutos</option>
   <option value="<?php echo(time()+3600);?>">1 hora</option>
   <option value="<?php echo(time()+7200);?>">2 horas</option>
   <option value="<?php echo(time()+18000);?>">5 horas</option>
   <option value="<?php echo(time()+36000);?>">10 horas</option>
</select></td></tr>
<tr><td><b>Premio:</b></td>  <td><select name="premiolot" size="1">
<?php
$sqle=mysql_query("SELECT * FROM table_itens");
$dbr=mysql_fetch_assoc($sqle);
?>
<?php do{ ?>
<option value="<?php echo $dbr['id']; ?>"><?php echo $dbr['nome']; ?></option>
<?php $i++; } while($dbr=mysql_fetch_assoc($sqle)); ?>
</select>
</td></tr>
<tr><td><b>Premio em creditos:</b></td>  <td><input type="text" name="creditos" size="20"></td></tr>
<tr><td><b>Preco por Ticket:</b></td>  <td><input type="text" value="300" name="preco" size="20"></td></tr>
<input type="hidden" id="usuario" name="usuario" value="<?php echo $db['usuario']; ?>">
</table> </td><td>

<?php
$lo=mysql_query("SELECT * FROM settings where name='end_lotto'");
$lot=mysql_fetch_assoc($lo);
?>
<?php if($lot['value']<time()){?>

<input class="botao" type="submit" name="loteria" value="Iniciar">

<?php }else{?>
<b>Loteria ja iniciada</b>
<?php }?>

</td></tr> </table></form>
<div class="sep"></div>
 </div>
<div class="box_bottom"></div>

<div class="box_top">Iniciar guerra de vilas e arena</div>
<div class="box_middle">
<?php
	if(isset($_GET['msgwar'])){
		switch($_GET['msgwar']){
			case 1: $msg='<b>Guerra iniciada com sucesso</b>,nao mecha nas configuracoes enquanto a guerra de vilas estiver aberta'; break;
		}
	echo '<div class="sep"></div><div class="aviso">'.$msg.'</div>';
	}
	?>
<ul>
<li>
<b>Inicio</b>, e <b>Fim</b> é o fim do <b>Status</b>, ao chegar na data marcada pelo <b>Fim</b><br>
altera para outro <b>Status</b>.

</li>
</ul>
<center>
<form method="post" action="?p=painel">
Premio:
<br>
<input type="text" name="premio">
<br>
Nivel minimo:
<br>
<input type="text" name="nivelmin">
<br>
Nivel maximo:
<br>
<input type="text" name="nivelmax">
<br>
Custo para inscrever:
<br>
<input type="text" name="custo">
<br>
<input type="submit" name="iniciargr" value="Iniciar Guerra de Vilas">
</form>
</center>


<ul>
<li>
<b>OBS</b>,
Nivel Maximo até 20 = Categoria iniciante,
Nivel Maximo até 50 = Categoria avancado,
Nivel Maximo até 200 = Categoria profissional,
Experiencia iniciar no valor definido exemplo 20 o primeiro ninja que morrer ganhará 20 o segundo 40 por ai vai cuidado pra não colocar demais
.

</li>
</ul>
<center>
<form method="post" action="?p=painel">
Premio(para o vencedor):
<br>
<input type="text" name="premio">
<br>
Nivel minimo:
<br>
<input type="text" name="nivelmin">
<br>
Nivelmax:
<br>
<input type="text" name="nivelmax">
<br>
Custo para inscrever:
<br>
<input type="text" name="custo">
<br>
<br>
Experiencia inicial:
<br>
<input type="text" name="exp">
<br>
Quantidade de inscrições:
<br>
<input type="text" name="insc">
<br>
<input type="submit" name="iniciararena" value="Iniciar Arena">
</form>
</center></div>
<div class="box_bottom"></div>

</div>
<?php
if ($_POST['banned']) {
 
  if(strlen($_POST['banirnome']) < 1 ){
   echo "<script>alert('Nome Invalido')</script><script>top.location = '?p=painel'</script>";
  die();
  }
 
if (!isset($message)){
$query = mysql_query("update `usuarios` set `status`='".$_POST['select']."' WHERE `usuario`='".$_POST['banirnome']."'");
echo "<script>alert('alterado status do usuário!')</script><script>top.location = '?p=painel'</script>";
   
  }
}
?>
<div class="box_bottom"></div>
<div class="box_top">Banir</div>
<div class="box_middle" style="text-align:left;">
 
 
  <br>
 <div class="sep"></div>
<table border="0" width="100%">
     <form method='post' >
   
   
   
        <tr>
      <td height='50'>Nome do usuario</font></td>
      <td>
<input type="text" name="banirnome" size="20" maxlength="14">      </font></td>
    </tr>
        <tr>
      <td height='28'>Status:</font></td>
      <td>
<SELECT name="select">
<option value="banido">banir</option>
<option value="ativo">Desbanir</option>
</SELECT>
 
    </tr>
      <tr>
        <td>&nbsp;</td>
      <td>
<input class="botao" type="submit"  name="banned" value="Confirmar">
      </font></td>
  </tr>
  </table>
 
  <div class="sep"></div>
 
 
<div class="box_bottom"></div>
 
 
</div>
 
 
<?php
if ($_POST['credito']) {
 
  if(strlen($_POST['userid']) < 1 ){
   echo "<script>alert('Nome Invalido')</script><script>top.location = '?p=painel'</script>";
  die();
  }
 
if (!isset($message)){
$query = mysql_query("update `usuarios` set `creditos`=creditos+'".$_POST['creditos']."' WHERE `usuario`='".$_POST['userid']."'");
echo "<script>alert('Credito Enviado!')</script><script>top.location = '?p=painel'</script>";
   
  }
}
?>
<script language='JavaScript'>
function SomenteNumero(e){
    var tecla=(window.event)?event.keyCode:e.which;  
    if((tecla>47 && tecla<58)) return true;
    else{
        if (tecla==8 || tecla==0) return true;
        else  return false;
    }
}
</script>
 
<div class="box_top">Creditos</div>
<div class="box_middle" style="text-align:left;">
  <br>
<table border="0" width="100%">
     <form method='post' >
   
   
   
        <tr>
      <td height='28'>Nome do usuario</font></td>
      <td>
<input type="text" name="userid" size="20" maxlength="14">      </font></td>
    </tr>
        <tr>
      <td height='28'>Credito:</font></td>
      <td>
<input type="text" name="creditos" size="20" maxlength="14" onkeypress='return SomenteNumero(event)'>      </font></td>
    </tr>
      <tr>
        <td>&nbsp;</td>
      <td>
<input class="botao" type="submit"  name="credito" value="Doar">
      </font></td>
    </tr>
  </table>
 
<div class="box_bottom"></div>
 
</div>
 
<?php
if ($_POST['yens']) {
 
  if(($_POST['nome']) < 1 ){
   echo "<script>alert('Nome Invalido')</script><script>top.location = '?p=painel'</script>";
  die();
  }
 
if (!isset($message)){
$query = mysql_query("update `usuarios` set `yens`=yens+'".$_POST['yens']."' , `usuario`='".$_POST['nome']."'");
echo "<script>alert('Yens Enviado!')</script><script>top.location = '?p=painel'</script>";
   
  }
}
?>
<script language='JavaScript'>
function SomenteNumero(e){
    var tecla=(window.event)?event.keyCode:e.which;  
    if((tecla>47 && tecla<58)) return true;
    else{
        if (tecla==8 || tecla==0) return true;
        else  return false;
    }
}
</script>
 
<div class="box_top">Yens</div>
<div class="box_middle" style="text-align:left;">
  <br>
<table border="0" width="100%">
     <form method='post' >
   
   
   
        <tr>
      <td height='28'>Nome do usuario</font></td>
      <td>
<input type="text" name="nome" size="20" maxlength="14">      </font></td>
    </tr>
        <tr>
      <td height='28'>Yens:</font></td>
      <td>
<input type="text" name="yens" size="20" maxlength="14" onkeypress='return SomenteNumero(event)'>      </font></td>
    </tr>
      <tr>
        <td>&nbsp;</td>
      <td>
<input class="botao" type="submit"  name="credito" value="Doar">
      </font></td>
    </tr>
  </table>
 
<div class="box_bottom"></div>
 
</div>
 
 
<?php
if ($_POST['vipa']) {
 
  if(strlen($_POST['nome']) < 1 ){
   echo "<script>alert('Precisa por o nome do usuario que ira receber')</script><script>top.location = '?p=painel'</script>";
  die();
  }
  if(strlen($_POST['vipdia']) < 14 ){
   echo "<script>alert('Data Invalida')</script><script>top.location = '?p=painel'</script>";
   die();
  }
 
if (!isset($message)){
$query = mysql_query("update `usuarios` set `vip`='".$_POST['vipdia']."' WHERE `usuario`='".$_POST['nome']."'");
echo "<script>alert('vip doado com sucesso!')</script><script>top.location = '?p=painel'</script>";
   
  }
}
?>
 
<div class="box_top">Vip</div>
<div class="box_middle" style="text-align:left;">
<?php echo '<font color="#FF0000">Vip Deve Ser No Formato<font color="#FF00F0"> ANO-MES-DIA  HORA:MINUTOS:SEGUNDOS = 2012-01-01 00:00:00</font></font>'; ?>
 
 
 
<table border="0" width="100%">
     <form method='post' >
   
   
    <tr>
      <td height='28'>Nome do usuario</font></td>
      <td>
<input type="text" name="nome" size="20" maxlength="14">      </font></td>
    </tr>
        <tr>
      <td height='28'>Vip</font></td>
      <td>
<input type="datetime" name="vipdia" size="20" maxlength="10000">      </font></td>
    </tr>
      <tr>
        <td>&nbsp;</td>
      <td>
<input class="botao" type="submit"  name="vipa" value="Iniciar">
      </font></td>
    </tr>
  </table>
 
<div class="box_bottom"></div>
  <div class="sep"></div>
 
</div>
<?php