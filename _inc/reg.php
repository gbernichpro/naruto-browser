<?php
require_once('Encrypt.php');
$c=new C_Encrypt();

if(isset($_SESSION['logado'])){ echo "<script>self.location='?p=home'</script>"; return; }
$sqlc=mysql_query("SELECT count(id) conta FROM usuarios");
$dbc=mysql_fetch_assoc($sqlc);
$vagas=45000;
if($dbc['conta']>=$vagas){ echo "<script>self.location='?p=login'</script>"; return; }
if(isset($_POST['reg_submit'])){
	$erro=0;
	if(@$_POST['reg_termos']=='') $erro=11;
	if(!isset($_POST['reg_termos'])) $erro=8;
	if($_POST['reg_senha']<>$_POST['reg_senha2']) $erro=7;
	if($_POST['reg_vila']=='') $erro=6;
	if($_POST['reg_personagem']=='') $erro=5;
	if($_POST['reg_email']=='') $erro=4;
	if($_POST['reg_senha2']=='') $erro=3;
	if($_POST['reg_senha']=='') $erro=2;
	if($_POST['reg_usuario']=='') $erro=1;
	$admin = "";
	$personagem=$c->decode($_POST['reg_personagem'],$chaveuniversal);
	$vila=$c->decode($_POST['reg_vila'],$chaveuniversal);
	$usuario=str_replace(array(' ','&nbsp;'),'_',$_POST['reg_usuario']);
	$usuario=str_replace(' ','_',$usuario);
	$usuario=ucfirst(strtolower(str_replace(array('/','^','[','-',']','+','$','(',')','?','\'','|','°','ª','#','@','.','?','!'),'',$usuario)));
	$pattern = "([_ _-_,_._>_`_´_<_~_^\/_?_°_\_:_;_§_|_!_¹_²_³_£_¢_¬_§_º_@_#_%_¨_&_*_+_{_}_*_])" ;
if(preg_match('/' . $pattern . '/', $_POST['reg_usuario']))
{
die("<script>self.location='?p=reg&erro=16'</script>");
}

	$sqlc=mysql_query("SELECT count(id) conta FROM usuarios WHERE usuario='".$usuario."'");
	$dbc=mysql_fetch_assoc($sqlc);
	if($dbc['conta']>0) $erro=12;
	$sqlc=mysql_query("SELECT count(id) conta FROM usuarios WHERE email='".$_POST['reg_email']."'");
	$dbc=mysql_fetch_assoc($sqlc);
	if($dbc['conta']>0) $erro=12;
	if($_POST['reg_nlink']<>''){
		$nlink=$_POST['reg_nlink'];
		$sqlv=mysql_query("SELECT count(id) conta FROM usuarios WHERE usuario='$nlink'");
		$dbv=mysql_fetch_assoc($sqlv);
		if($dbv['conta']==0) $erro=13;
	}
	$sqlv=mysql_query("SELECT count(id) conta FROM usuarios WHERE senha='".md5($_POST['reg_senha'])."'");
	$dbv=mysql_fetch_assoc($sqlv);
	//if($dbv['conta']>=5) $erro=15;
	if(isset($_POST['nlink'])) $link='&nlink='.$_POST['nlink']; else $link='';
	if($erro>0){ echo "<script>self.location='?p=reg&user=".$_POST['reg_usuario']."&mail=".$_POST['reg_email']."&char=".$personagem."&village=".$vila."&erro=".$erro.$link."'</script>"; return; }
	else {
	$novocodigo=rand(99999,99999999);
		if(isset($_POST['reg_akatsuki'])) $renegado='sim'; else $renegado='nao';
$atual=date('Y-m-d H:i:s');
$soma = mktime(date('H')+168, date('i'), date('s'));
$fim = date('Y-m-d H:i:s',$soma);
$vipadd=$fim;
		$usuario=ucfirst(strtolower(str_replace(array(' ','/','^','[','-',']','+','$','(',')','?','\'','|','°','ª','#','@','.','?','!'),'',$_POST['reg_usuario'])));
		mysql_query("INSERT INTO usuarios (usuario, status, senha, email, personagem, vila, renegado, hunt_restantes, reg, natureza1, natureza2, natureza3, ip, vip, vip_inicio, ativador, yens) VALUES ('".$usuario."','ativo','".md5($_POST['reg_senha'])."','".strtolower($_POST['reg_email'])."','".$personagem."',".$vila.",'".$renegado."',14,'".date('Y-m-d H:i:s')."','','','','".ip2long($_SERVER['REMOTE_ADDR'])."','".$vipadd."','".$atual."','".$novocodigo."','9000')") or die(mysql_error());

            $assunto = "Código de ativação Naruto";
            $messagem .= "<html>\n"; 
            $messagem .= "<body>\n";         
            $messagem .= "<table style=\"font-family: Arial,Helvetica,sans-serif; text-align: left;\">
	  <tbody><tr>
		<td height=\"183\" width=\"15\">&nbsp;</td>
		<td>&nbsp;</td>
		<td width=\"10\">&nbsp;</td>
	  </tr>
	  <tr>
		<td height=\"300\">&nbsp;</td>
		<td valign=\"top\"><p style=\"font-size: 12px; color:#5a5756;\">
		  Olá ".$_POST['usuario'].",<br>
		  <br>
		  Para prosseguir com seu cadastro você deve ativar a sua conta no Naruto, para ativar sua conta utilize seu codigo de ativação que está logo abaixo dessa mensagem.<br>
		  <br>

		  <br>
          <b>Codigo de ativação:</b> $novocodigo
          <br>
   <br><br>
		  Equipe Naruto </p>
	 </td>
		<td>&nbsp;</td>
	  </tr>
	</tbody></table>\n";
            $messagem .= "</body>\n"; 
            $messagem .= "</html>\n"; 
            
            $headers .= "MIME-Version: 1.0\n" ; 
            $headers .= "Content-Type: text/html; charset=\"iso-8859-1\"\n"; 
            $headers .= "X-Priority: 1 (Higuest)\n"; 
            $headers .= "X-MSMail-Priority: High\n"; 
            $headers .= "Importance: High\n"; 
            $headers .= "From: ";
            
            mail( $_POST['reg_email'], $assunto, $messagem, $headers );
//=============================================//



		echo "<script>self.location='?p=reg2'</script>"; return;
	}
}
?>
<?php
$txtnaruto='<b>Nome:</b> Uzumaki Naruto<br /><b>Vila:</b> Vila da Folha<br /><b>Classificação:</b> Gennin';
$txtsakura='<b>Nome:</b> Haruno Sakura<br /><b>Vila:</b> Vila da Folha<br /><b>Classificação:</b> Chuunin';
$txtsasuke='<b>Nome:</b> Uchiha Sasuke<br /><b>Vila:</b> Vila da Folha<br /><b>Classificação:</b> Nukenin';
$txtkakashi='<b>Nome:</b> Hatake Kakashi<br /><b>Vila:</b> Vila da Folha<br /><b>Classificação:</b> Jounnin';
$sqlc=mysql_query("SELECT count(id) conta FROM usuarios");
$dbc=mysql_fetch_assoc($sqlc);
?>
<div class="box_top">Registrar</div><div class="box_middle"><div style="background: url(../_img/_detalhes/base2.PNG);width: 720px;height: 250px;">
<table cellpadding="0" cellspacing="0" width="712" height="230"><tbody><tr><td width="150">
<img width="142" style="" src="_img/_detalhes/msg/1.png"></td><td valign="top"><br><br><br>
<div style="margin-left: -345px;margin-top: 15px;height: 0px;">
<b id="title" style="font-family: impact;font-size: 20px;font-weight: normal;color: #ffffff;"  onmouseover="style.color='#F5F5F5'" onmouseout="color.color='#ffffff'">
&raquo; Agora falta pouco!</b></div><br>
<div style="margin-left: 0px;margin-top: 35px;font-family: arial;font-size: 12px;color: #fff;"><b>
Seja bem-vindo ao Naruto <?php echo NARUTO_NOME; ?>! Para que você possa se aventurar no mundo de</br>
Naruto, é necessário criar uma conta de acesso. Para isso, basta preencher o</br>
formulário abaixo com os dados solicitados. Todos os campos solicitados são</br>
obrigatórios, sem exceção.</br>
</b>
</div></td></tr></tbody></table></div><br /><div class="sep"></div><div style="background:url(_img/gradient.jpg) repeat-y;padding-left:5px;">Neste momento, temos <b><?php echo $vagas-$dbc['conta']; ?> vagas</b> disponíveis para registro.<?php if(($vagas-$dbc['conta'])<10) echo ' Seja rápido!'; ?></div><div class="sep"></div>

<form method="post" action="?p=reg" name="reg" id="reg" style="background:url(_img/reg.jpg) no-repeat right top;" onsubmit="subm.value='Carregando...';subm.disabled=true;">
<div align="left">
<input type="hidden" id="reg_submit" name="reg_submit" value="1" />
<input type="hidden" id="reg_nlink" name="reg_nlink" value="<?php if(isset($_GET['nlink'])) echo $_GET['nlink']; ?>" />
<fieldset>
	<legend>Dados da Conta</legend>
    <span class="destaque">Nome de Usuário:</span><br />
    <input type="text" id="reg_usuario" name="reg_usuario" maxlength="15" onfocus="className='input'" onblur="className=''" <?php if(isset($_GET['user'])) echo 'value="'.$_GET['user'].'"'; ?>/><br />
    <span class="sub2"><small>Minimo. 4 e Máximo. 20 caracteres. (letras e números).</small></span><br /><br />

    <span class="destaque">Senha:</span><br />
    <input type="password" id="reg_senha" name="reg_senha" maxlength="15" onfocus="className='input'" onblur="className=''" /><br />
    <span class="sub2"><small>Min. 8 e Máx. 20 caracteres.</small></span><br /><br />

    <span class="destaque">Confirmar Senha:</span><br />
    <input type="password" id="reg_senha2" name="reg_senha2" maxlength="15" onfocus="className='input'" onblur="className=''" /><br />
    <span class="sub2"><small>Repita novamente a senha.</small></span><br /><br />

    <span class="destaque">Email:</span><br />
    <input type="text" id="reg_email" name="reg_email" maxlength="250" onfocus="className='input'" onblur="className=''" <?php if(isset($_GET['mail'])) echo 'value="'.$_GET['mail'].'"'; ?>/><br />
    <span class="sub2"><small>Informe um e-mail válido! Você receberá o link de ativação neste e-mail.</small></span>
</fieldset>
<fieldset>

 <script language="JavaScript" type="text/javascript">
/*<![CDATA[*/
var Lstt;

function Cvila(obj){
 if (Lstt) Lstt.className='vila';
 obj.className='vila-s';
 Lstt=obj;
}
/*]]>*/
</script>

<style type="text/css">

.vila{
    cursor:pointer;    opacity:0.55;	-moz-opacity: 0.55;	filter: alpha(opacity=55);
}
.vila-s{


}
</style>

	<legend>Personagem</legend>
    <span class="sub2">Escolha um personagem entre os 4 abaixo. Durante sua evolução no jogo, novos personagens são liberados para uso. A troca de personagem pode ser feita uma vez por dia.</span><div class="sep"></div>
    <div align="center">
    <table>
   	  <tr>
       	  <td width="116" height="65" align="center"><img  class="vila"  src="_img/personagens/reg_naruto.jpg" onclick="document.getElementById('reg_personagem1').checked=true;Cvila(this);" onmouseover="Tip('<div class=tooltip><?php echo $txtnaruto; ?></div>')" onmouseout="UnTip()" /></td>
          	<td width="116" align="center"><img class="vila"  src="_img/personagens/reg_sakura.jpg" onclick="document.getElementById('reg_personagem2').checked=true;Cvila(this);" onmouseover="Tip('<div class=tooltip><?php echo $txtsakura; ?></div>')" onmouseout="UnTip()" /></td>
          <td width="116" align="center"><img class="vila"  src="_img/personagens/reg_sasuke.jpg" onclick="document.getElementById('reg_personagem3').checked=true;Cvila(this);" onmouseover="Tip('<div class=tooltip><?php echo $txtsasuke; ?></div>')" onmouseout="UnTip()" /></td>
            <td width="116" align="center"><img class="vila" src="_img/personagens/reg_kakashi.jpg" onclick="document.getElementById('reg_personagem4').checked=true;Cvila(this);" onmouseover="Tip('<div class=tooltip><?php echo $txtkakashi; ?></div>')" onmouseout="UnTip()" /></td>
        </tr>
        <tr>
        	<td align="center"><input type="radio" id="reg_personagem1" name="reg_personagem" value="<?php echo $c->encode('naruto',$chaveuniversal); ?>" <?php if((!isset($_GET['char']))or(isset($_GET['char']))&&($_GET['char']=='naruto')) echo 'checked="checked"'; ?>/></td>
            <td align="center"><input type="radio" id="reg_personagem2" name="reg_personagem" value="<?php echo $c->encode('sakura',$chaveuniversal); ?>" <?php if((isset($_GET['char']))&&($_GET['char']=='sakura')) echo 'checked="checked"'; ?>/></td>
            <td align="center"><input type="radio" id="reg_personagem3" name="reg_personagem" value="<?php echo $c->encode('sasuke',$chaveuniversal); ?>" <?php if((isset($_GET['char']))&&($_GET['char']=='sasuke')) echo 'checked="checked"'; ?>/></td>
          <td align="center"><input type="radio" id="reg_personagem4" name="reg_personagem" value="<?php echo $c->encode('kakashi',$chaveuniversal); ?>" <?php if((isset($_GET['char']))&&($_GET['char']=='kakashi')) echo 'checked="checked"'; ?>/></td>
        </tr>
    </table>
    </div>
</fieldset>
<fieldset>
	<legend>Vila</legend>
    <span class="sub2">Escolha uma vila para representar. Jogadores da mesma vila n&atilde;o podem se enfrentar. Qualquer jogador poder&aacute; se tornar um Akatsuki.</span><div class="sep"></div>
    <div align="center">
    <table>
   	  <tr>
        	<td width="10" height="65" align="center"><img width="45"  src="_img/vilas/reg_folha.jpg" onclick="document.getElementById('reg_vila1').checked=true" onmouseover="Tip('<div class=tooltip>Vila da Folha</div>')" onmouseout="UnTip()" /></td>
          	<td width="10" align="center"><img width="45" src="_img/vilas/reg_areia.jpg" onclick="document.getElementById('reg_vila2').checked=true" onmouseover="Tip('<div class=tooltip>Vila da Areia</div>')" onmouseout="UnTip()" /></td>
            <td width="10" align="center"><img width="45"  src="_img/vilas/reg_som.jpg" onclick="document.getElementById('reg_vila3').checked=true" onmouseover="Tip('<div class=tooltip>Vila do Som</div>')" onmouseout="UnTip()" /></td>
            <td width="10" align="center"><img width="45"  src="_img/vilas/reg_chuva.jpg" onclick="document.getElementById('reg_vila4').checked=true" onmouseover="Tip('<div class=tooltip>Vila da Chuva</div>')" onmouseout="UnTip()" /></td>
            <td width="10" align="center"><img width="45"  src="_img/vilas/reg_nuvem.jpg" onclick="document.getElementById('reg_vila5').checked=true" onmouseover="Tip('<div class=tooltip>Vila da Nuvem</div>')" onmouseout="UnTip()" /></td>
            <td width="10" align="center"><img width="45"  src="_img/vilas/reg_nevoa.jpg" onclick="document.getElementById('reg_vila6').checked=true" onmouseover="Tip('<div class=tooltip>Vila da N&eacute;voa</div>')" onmouseout="UnTip()" /></td>
            <td width="10" align="center"><img width="45"  src="_img/vilas/reg_pedra.jpg" onclick="document.getElementById('reg_vila8').checked=true" onmouseover="Tip('<div class=tooltip>Vila da Pedra</div>')" onmouseout="UnTip()" /></td>
            <td width="10" align="center"><img width="45"  src="_img/vilas/reg_cachoeira.jpg" onclick="document.getElementById('reg_vila9').checked=true" onmouseover="Tip('<div class=tooltip>Vila da Cachoeira</div>')" onmouseout="UnTip()" /></td>
            <td width="10" align="center"><img width="45"  src="_img/vilas/reg_neve.jpg" onclick="document.getElementById('reg_vila10').checked=true" onmouseover="Tip('<div class=tooltip>Vila da Neve</div>')" onmouseout="UnTip()" /></td>
            <td width="10" align="center"><img width="45"  src="_img/vilas/reg_grama.jpg" onclick="document.getElementById('reg_vila11').checked=true" onmouseover="Tip('<div class=tooltip>Vila da Grama</div>')" onmouseout="UnTip()" /></td>


        </tr>


        <tr>
        	<td align="center"><input type="radio" id="reg_vila1" name="reg_vila" value="<?php echo $c->encode('1',$chaveuniversal); ?>" <?php if((!isset($_GET['village']))or(isset($_GET['village']))&&($_GET['village']==1)) echo 'checked="checked"'; ?>/></td>
            <td align="center"><input type="radio" id="reg_vila2" name="reg_vila" value="<?php echo $c->encode('2',$chaveuniversal); ?>" <?php if((isset($_GET['village']))&&($_GET['village']==2)) echo 'checked="checked"'; ?>/></td>
            <td align="center"><input type="radio" id="reg_vila3" name="reg_vila" value="<?php echo $c->encode('3',$chaveuniversal); ?>" <?php if((isset($_GET['village']))&&($_GET['village']==3)) echo 'checked="checked"'; ?>/></td>
            <td align="center"><input type="radio" id="reg_vila4" name="reg_vila" value="<?php echo $c->encode('4',$chaveuniversal); ?>" <?php if((isset($_GET['village']))&&($_GET['village']==4)) echo 'checked="checked"'; ?>/></td>
            <td align="center"><input type="radio" id="reg_vila5" name="reg_vila" value="<?php echo $c->encode('5',$chaveuniversal); ?>" <?php if((isset($_GET['village']))&&($_GET['village']==5)) echo 'checked="checked"'; ?>/></td>
            <td align="center"><input type="radio" id="reg_vila6" name="reg_vila" value="<?php echo $c->encode('6',$chaveuniversal); ?>" <?php if((isset($_GET['village']))&&($_GET['village']==6)) echo 'checked="checked"'; ?>/></td>
            <td align="center"><input type="radio" id="reg_vila8" name="reg_vila" value="<?php echo $c->encode('8',$chaveuniversal); ?>" <?php if((isset($_GET['village']))&&($_GET['village']==8)) echo 'checked="checked"'; ?>/></td>
            <td align="center"><input type="radio" id="reg_vila9" name="reg_vila" value="<?php echo $c->encode('9',$chaveuniversal); ?>" <?php if((isset($_GET['village']))&&($_GET['village']==9)) echo 'checked="checked"'; ?>/></td>
            <td align="center"><input type="radio" id="reg_vila10" name="reg_vila" value="<?php echo $c->encode('10',$chaveuniversal); ?>" <?php if((isset($_GET['village']))&&($_GET['village']==10)) echo 'checked="checked"'; ?>/></td>
            <td align="center"><input type="radio" id="reg_vila11" name="reg_vila" value="<?php echo $c->encode('11',$chaveuniversal); ?>" <?php if((isset($_GET['village']))&&($_GET['village']==11)) echo 'checked="checked"'; ?>/></td>

        </tr>

    </table>
    </div>
    <div class="sep"></div>
    <input type="checkbox" id="reg_akatsuki" name="reg_akatsuki" /> Desejo iniciar o jogo como sendo um ninja renegado (membro da Akatsuki).
</fieldset>
<fieldset>
	<legend>Termos e Condi&ccedil;&otilde;es</legend>
    <input type="checkbox" id="reg_termos" name="reg_termos" /> Declaro que <b>li</b> e <b>aceito</b> os termos propostos, e que estou ciente das regras do jogo.
    <div class="sep"></div>
    <div align="center"><input type="submit" class="botao" id="subm" name="subm" value="Registrar" /></div>
</fieldset>
</form>
</div></div>
<div class="box_bottom"></div>
<?php
@mysql_free_result($sqlc);
?>
