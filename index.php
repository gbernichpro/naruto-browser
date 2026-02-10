<?php
date_default_timezone_set("Brazil/East");
if(isset($_GET['allowgm'])) setcookie('allowgm',1,time()+900);
?>
<?php if(!isset($_COOKIE['allowgm'])) if(file_exists('manutencao.php')) require_once('manutencao.php'); ?>
<?php require_once('_inc/conexao.php'); ?>
<?php
function vn($numero){
	if(!is_numeric($numero)){
			echo "<script>self.location='?p=home'</script>"; return;
	}

}
$chaveuniversal='hgfdhgfd';
require_once('_inc/Encrypt.php');
$c=new C_Encrypt();

function anti_sql_injection ($str) {
    if (!is_numeric($str)) {
        $str= get_magic_quotes_gpc() ? stripslashes($str) : $str;
        $str= function_exists("mysql_real_escape_string") ? mysql_real_escape_string($str) : mysql_escape_string($str);
    }
    return $str;
}
?>
<?php
if((isset($_GET['p']))&&($_GET['p']=='logout')) require_once('_inc/logout.php');
if(isset($_POST['login_login'])){
	$erro=0;
    if(!validate_csrf_token($_POST['csrf_token'])) $erro=99; // Generic error for CSRF failure
    if($erro==0 && !validate_turnstile($_POST['cf-turnstile-response'])) $erro=98; // Turnstile failure
	if($erro==0 && $_POST['login_senha']=='') $erro=1;
	if($erro==0){
       // Prepared Statement for Login
       $stmt = mysqli_prepare($mysqli_link, "SELECT id,senha,avatar,missao,missao_fim,status FROM usuarios WHERE usuario=?");
       mysqli_stmt_bind_param($stmt, "s", $_POST['login_login']);
       mysqli_stmt_execute($stmt);
       $result = mysqli_stmt_get_result($stmt);
       
	   if(mysqli_num_rows($result)==0) $erro=2;
		if($erro==0){
			$db=mysqli_fetch_assoc($result);

            
            // Password Verification Logic
            $senha_digitada = $_POST['login_senha'];
            $senha_banco = $db['senha'];
            $login_ok = false;

            // 1. Check if it's a legacy MD5 hash
            if (md5(antiinjection2($senha_digitada)) === $senha_banco) {
                $login_ok = true;
                // Upgrade to password_hash
                $new_hash = password_hash($senha_digitada, PASSWORD_DEFAULT);
                $stmt_up = mysqli_prepare($mysqli_link, "UPDATE usuarios SET senha=? WHERE id=?");
                mysqli_stmt_bind_param($stmt_up, "si", $new_hash, $db['id']);
                mysqli_stmt_execute($stmt_up);

            } 
            // 2. Check if it's a modern hash
            elseif (password_verify($senha_digitada, $senha_banco)) {
                $login_ok = true;
                // Check if needs rehash (e.g. algorithm changed)
                if (password_needs_rehash($senha_banco, PASSWORD_DEFAULT)) {
                    $new_hash = password_hash($senha_digitada, PASSWORD_DEFAULT);
                    $stmt_re = mysqli_prepare($mysqli_link, "UPDATE usuarios SET senha=? WHERE id=?");
                    mysqli_stmt_bind_param($stmt_re, "si", $new_hash, $db['id']);
                    mysqli_stmt_execute($stmt_re);
                }

            }

            if (!$login_ok) $erro=2;

			//if($db['status']=='inativo') $erro=5;
			if($db['missao']==999){
				$atual=date('Y-m-d H:i:s');
				if($atual<$db['missao_fim']) $erro=4;
			}
			if($db['status']=='banido'){
				echo "<script>self.location='?p=banido'</script>"; return;
			}elseif ($db['status']=='inativo')
	{

               echo "<script>self.location='?p=login&erro=ativar'</script>"; return;

	}

            if($erro==0){
				//session_regenerate_id();
				$_SESSION['logado']=$db['id'];
				setcookie('logado',1,time()+900);
				mysql_query("UPDATE usuarios SET loginip='".ip2long($_SERVER['REMOTE_ADDR'])."' WHERE id=".$db['id']);
				//setcookie('session_id',session_id(),time()+900);
				if($db['avatar']==0){ echo "<script>self.location='?p=first'</script>"; return; }
				echo "<script>self.location='?p=home'</script>"; return;
			}
		
	
		}
	}
	if($erro>0){ if($erro==4) $data='&date='.$c->encode(str_replace(' ','_',$db['missao_fim']),$chaveuniversal); else $data=''; echo "<script>self.location='?p=login&erro=".$erro.$data."'</script>"; return; }
}
?>
<?php
if(isset($_SESSION['logado']) || isset($_COOKIE['logado'])){
	if(!isset($_SESSION['logado'])){ setcookie('logado',1,time()-3600); echo "<script>self.location='?p=login'</script>"; return; }
	
	setcookie('logado',1,time()+900);
	//setcookie('session_id',session_id(),time()+900);
	if((!isset($_GET['p']))or(isset($_GET['p']))&&($_GET['p']<>'attack')){
        if((isset($_GET['p']))&&($_GET['p']=='view')) {
            $stmt_u = mysqli_prepare($mysqli_link, "SELECT u.*,o.nome orgnome, o.nivel orgnivel FROM usuarios u LEFT OUTER JOIN organizacoes o ON u.orgid=o.id WHERE status<>'banido' AND u.usuario=?");
            mysqli_stmt_bind_param($stmt_u, "s", $_GET['view']);
        } elseif((isset($_GET['p']))&&($_GET['p']=='prepare')) {
            $stmt_u = mysqli_prepare($mysqli_link, "SELECT u.*,o.nome orgnome, o.nivel orgnivel FROM usuarios u LEFT OUTER JOIN organizacoes o ON u.orgid=o.id WHERE status<>'banido' AND u.id=?");
            mysqli_stmt_bind_param($stmt_u, "i", $_SESSION['prepare']);
        } else {
            $stmt_u = mysqli_prepare($mysqli_link, "SELECT u.*,o.nome orgnome, o.nivel orgnivel FROM usuarios u LEFT OUTER JOIN organizacoes o ON u.orgid=o.id WHERE status<>'banido' AND u.id=?");
            mysqli_stmt_bind_param($stmt_u, "i", $_SESSION['logado']);
        }
        
        mysqli_stmt_execute($stmt_u);
        $result_u = mysqli_stmt_get_result($stmt_u);
		$db = mysqli_fetch_assoc($result_u) or die(mysqli_error($mysqli_link));
		if((isset($_GET['p']))&&($_GET['p']=='view')&&(mysqli_num_rows($result_u)==0)){ echo "<script>self.location='?p=home'</script>"; return; }
	} else {
            $stmt_u = mysqli_prepare($mysqli_link, "SELECT u.id, u.status, u.usuario, u.yens, u.yensbanco, u.yens_fat, u.nivel, u.orgid, u.energia, u.energiamax, u.taijutsu, u.ninjutsu, u.genjutsu, u.personagem, u.avatar, u.renegado, u.vila, u.doujutsu, u.exp, u.expmax, u.doujutsu, u.doujutsu_nivel, u.doujutsu_exp, u.doujutsu_expmax, u.vip_inicio, u.vip, u.missao, u.hunt, u.treino, u.penalidade_fim, u.loginip, o.nivel orgnivel FROM usuarios u LEFT OUTER JOIN organizacoes o ON u.orgid=o.id WHERE u.id=?");
        mysqli_stmt_bind_param($stmt_u, "i", $_SESSION['logado']);
        mysqli_stmt_execute($stmt_u);
        $result_u = mysqli_stmt_get_result($stmt_u);
		$db = mysqli_fetch_assoc($result_u) or die(mysqli_error($mysqli_link));
		if($db['status']=='banido'){ echo "<script>self.location='?p=logout&ban=true'</script>"; return; }
	}
	if((isset($_GET['p']))&&($_GET['p']<>'first')&&($_GET['p']<>'view')&&($_GET['p']<>'prepare')&&($db['avatar']==0)){ echo "<script>self.location='?p=first'</script>"; return; }
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
<title>Naruto <?php echo NARUTO_NOME; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="_css/naruto.css" rel="stylesheet" type="text/css" />
 <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <link type="text/css" href="_css/menu.css" rel="stylesheet" />
	<link type="text/css" href="_css/menu3.css" rel="stylesheet" />
    <link type="text/css" href="_css/sidebar_refined.css" rel="stylesheet" />
    <link type="text/css" href="_css/modern_ui.css" rel="stylesheet" />
    <script type="text/javascript" src="jquery.js"></script>
    <script type="text/javascript" src="javascript/zebra_dialog.js"></script>
    <link rel="stylesheet" href="css/zebra_dialog.css" type="text/css">
    <script type="text/javascript">
        window.onTurnstileSuccess = function(token) {
            console.log("Turnstile Success! Token captured.");
            var headerToken = document.getElementById('header_turnstile_token');
            if (headerToken) {
                headerToken.value = token;
                console.log("Header token field updated.");
            }
        };

        function validateLoginForm() {
            var token = document.getElementById('header_turnstile_token').value;
            console.log("Submitting form. Token status: " + (token ? "Present" : "Missing"));
            if (!token) {
                alert("Por favor, aguarde a verificação Anti-Bot na barra lateral ser concluída.");
                return false;
            }
            return true;
        }
    </script>
<script type="text/javascript" src="_js/jquery-impromptu.4.0.min.js"></script>
<script type="text/javascript" src="_js/jquery-modal-1.0.pack.js"></script>
<script type='text/javascript' src='javascripts/jquery.tipsy.js'></script>
<link rel="stylesheet" href="stylesheets/tipsy.css" type="text/css" />
<?php if((isset($_GET['p']))&&($_GET['p']=='messages')or(isset($_GET['p']))&&($_GET['p']=='config')or(isset($_GET['p']))&&($_GET['p']=='configorg')){ ?><script type="text/javascript" src="_js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
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
<?php } ?>
<script language="javascript">
var xmlhttp;

function carregaAjax(div,geturl,carg)
{
xmlhttp=GetXmlHttpObject();
if (xmlhttp==null)
  {
  alert ("Browser does not support HTTP Request");
  return;
  }
var url=geturl;
xmlhttp.onreadystatechange=function(){
  if((xmlhttp.readyState==1)&&(carg=='s')) document.getElementById(div).innerHTML='<div class="aviso" style="margin-top:10px;margin-bottom:10px;"><img src="_js/loading.gif" /><br /><b>Carregando...</b></div>';
  if(xmlhttp.readyState==4) document.getElementById(div).innerHTML=xmlhttp.responseText;
}
xmlhttp.open("GET",url,true);
xmlhttp.send(null);
}

function GetXmlHttpObject()
{
if (window.XMLHttpRequest)
  {
  // code for IE7+, Firefox, Chrome, Opera, Safari
  return new XMLHttpRequest();
  }
if (window.ActiveXObject)
  {
  // code for IE6, IE5
  return new ActiveXObject("Microsoft.XMLHTTP");
  }
return null;
}
</script>
<?php if(isset($_COOKIE['logado'])){ ?>
<script type="text/javascript" src="_js/jquery-modal-1.0.pack.js"></script>
<?php } ?>
<link rel="shortcut icon" href="_img/favicon.gif" />



<style type="text/css">
<!--
body {
	background: url(template/background.png) fixed top center no-repeat #5D5D5D;
}
.style1 {color: #FFFFFF}
.Button_Login {
background:url(template/button_login.png);
width:91px;
height:30px;
border:0;
}
.Button_Logout {
background:url(template/button_logout.png);
width:91px;
height:30px;
border:0;
}
.Button_Painel {
background:url(template/button_painel.png);
width:91px;
height:30px;
border:0;
}
.Button_Login:hover {
background:url(template/button_login_hover.png);
}
.Button_Logout:hover {
background:url(template/button_logout_hover.png);
}
.Button_Painel:hover {
background:url(template/button_painel_hover.png);
}

-->
</style>
</head>
<script type="text/javascript">
function horas(){
	var now = new Date();
	var hours = String(now.getHours()).padStart(2, '0');
	var minutes = String(now.getMinutes()).padStart(2, '0');
	var seconds = String(now.getSeconds()).padStart(2, '0');
	var cdate="<b><font color='#ff0000' face='arial' size='2'>"+hours+":"+minutes+":"+seconds+" "+"</font></b>";
	if(document.getElementById('clock')) {
        document.getElementById('clock').innerHTML = cdate;
    }
	setTimeout(horas, 1000);
}
horas();
</script>


<body>
<script src="_js/wz/wz_tooltip.js" type="text/javascript" language="javascript"></script>

   <?php if(isset($_SESSION['logado'])){
?>


<?php 	} ?>


      
<?php
$sqlr = mysql_query("SELECT usuario FROM usuarios ");
$dbr=mysql_fetch_assoc($sqlr);?>

<div align="center">
<?php if(isset($_SESSION['logado'])){
     include '_inc/war_villa_status.php';
     include '_inc/torneio_status.php';
     include '_inc/medalhas_status.php';
} ?>
                      
      <table align="center"  cellpadding="0" cellspacing="0" width="1024">
        <!--DWLayoutTable-->

<?php if(isset($_GET['erro'])){
		$erro_msg = '';
		switch($_GET['erro']){
			case 'ban': $erro_msg = 'Esta conta esta banida!'; break;
		    case 1: $erro_msg = 'Digite uma senha valida!'; break;
			case 2: $erro_msg = 'Login ou senha erradas!'; break;
			case 3: $erro_msg = 'Senha digitada errada!'; break;
			case 4: 
				if(isset($_GET['date'])){ 
					$data_raw = $c->decode($_GET['date'], $chaveuniversal); 
					$ex = explode('_', $data_raw); 
					$dt = explode('-', $ex[0]); 
					$erro_msg = "Sua conta esta em periodo de ferias.<br />Nao e possivel realizar o login ate o dia <b>" . $dt[2] . "/" . $dt[1] . "/" . $dt[0] . ", as " . $ex[1] . " horas</b>!";
				} else {
					$erro_msg = "Sua conta esta em periodo de ferias.";
				}
				break;
		}
		if($erro_msg) {
			echo "<script>$(function(){ top.$.prompt(" . json_encode($erro_msg) . "); });</script>";
		}
	} ?>
    <?php if(isset($_GET['reason'])) echo '<div class="aviso">Você foi deslogado pois outro usuário acessou sua conta.</div><div class="sep"></div>'; ?>
    <?php if(isset($_GET['ban'])) echo '<div class="aviso">Conta banida.</div><div class="sep"></div>'; ?>
<script>
function sair(){
$conf=confirm('Você deseja sair?');
if($conf==true){
top.location='?p=logout';
}
}
function painel() {
window.location.href='?p=painel';
}
</script>
<?php if(!isset($_SESSION['logado'])) {
$random = rand(1,1);
 echo'
          
		  <td height="365" colspan="2" valign="top" class="modern-header"><form id="login" name="login" method="post" action="#" onsubmit="return validateLoginForm()">
                <input type="hidden" name="csrf_token" value="' . get_csrf_token() . '">
                <input type="hidden" name="cf-turnstile-response" id="header_turnstile_token">
                <div style="padding: 40px 0 0 50px;">
                    <div class="modern-login-container">
                        <span class="modern-label">Login:</span>
                        <input id="login_login" name="login_login" type="text" placeholder="Usuário" />
                        <span class="modern-label">Senha:</span>
                        <input id="login_senha" name="login_senha" type="password" placeholder="Senha" />
                        <input type="submit" name="Submit" value="Entrar" class="Button_Login modern-btn-login"/>
                    </div>
                </div>
            </form>
          </td>
        </tr>
';
}
else {
$random = rand(1,1);
$nickname = ucfirst($db['usuario']); 
$time = time();
$stmt_last = mysqli_prepare($mysqli_link, "UPDATE usuarios SET tempo=? WHERE id=?");
mysqli_stmt_bind_param($stmt_last, "ii", $time, $db['id']);
mysqli_stmt_execute($stmt_last);

$tempo = (time() - 180);
$sqlee = mysql_query("SELECT * FROM usuarios WHERE tempo>".$tempo."");
$ok = mysql_num_rows($sqlee);
$hr = date(" H ");
if($hr >= 06 && $hr<12) { $livre = 'Bom dia'; 
}
else if ($hr >= 12 && $hr <18 ) { $livre = 'Boa Tarde'; 
}
else { $livre = 'Boa Noite'; }
echo '<td height="365" colspan="2" valign="top" class="modern-header">
                <div style="padding: 40px 0 0 50px;">
                    <div class="modern-login-container" style="color: #fff; font-family: var(--font-heading);">
                        <div style="font-size: 16px;">
                            <span style="color: var(--primary-red); font-weight: bold;">'.$livre.',</span> '.$nickname.'
                        </div>
                        <div style="width: 1px; height: 30px; background: var(--border-subtle);"></div>
                        <div style="font-size: 14px;">
                            <span id="clock"></span><script>setTimeout("horas()",1000);</script>
                        </div>
                        <div style="width: 1px; height: 30px; background: var(--border-subtle);"></div>
                        <div style="font-size: 12px; color: var(--text-dim);">
                            <b style="color: #0f0;">'.$ok.'</b> Ninjas Online
                        </div>
                        <div style="width: 1px; height: 30px; background: var(--border-subtle);"></div>
                        <input type="button" value="Sair" class="Button_Logout modern-btn-login" style="background: #333;" onclick="location.href=\'?p=logout&csrf_token='.get_csrf_token().'\'" />
                    </div>
                </div>
            </td>
        </tr>'; }?>
        
        
        <tr>
          <td width="246" rowspan="3" valign="top" background="template/bg_menu.png"><img src="template/top_menu.png" width="246" height="140" />
            <table width="246" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td width="209"><img src="template/top_bg_home.png" width="246" height="14" /></td>
              </tr>
              <tr>
                <td height="18" valign="top" background="template/bg_home_menu.png"><table width="227" height="18" border="0" align="right" cellpadding="0" cellspacing="0">
                  <tr>
                                  
<td width="200" valign="top"><?php if(!isset($_SESSION['logado'])) require_once('_inc/menu_off.php'); else require_once('_inc/menu_on.php'); ?></td>                    <td width="16">&nbsp;</td>
                  </tr>

                </table></td>
              </tr>
              <tr>
                <td><img src="template/bottom_menu.png" width="246" height="73" /></td>
              </tr>
              <?php if(!isset($_SESSION['logado'])): ?>
              <tr>
                <td align="center" style="padding: 10px 0;">
                    <div style="background: rgba(0,0,0,0.5); padding: 5px; border-radius: 5px; border: 1px solid #444; width: 154px;">
                        <div style="color: #fff; font-size: 10px; margin-bottom: 5px; font-weight: bold;">VERIFICAÇÃO ANTI-BOT</div>
                        <div class="cf-turnstile" data-sitekey="<?php echo $_ENV['TURNSTILE_SITE_KEY']; ?>" data-size="compact" data-callback="onTurnstileSuccess"></div>
                    </div>
                </td>
              </tr>
              <?php endif; ?>
            </table>
          </td>
		  <?php
$sqla=mysql_query("SELECT * FROM settings where `name`='lottery_premio'");
$premio=mysql_fetch_assoc($sqla);
$sqlaa=mysql_query("SELECT * FROM settings where `name`='last_winner'");
$ultimo=mysql_fetch_assoc($sqlaa);
$sqlr1=mysql_query("SELECT usuario, nivel, vila FROM usuarios WHERE tipo='player' AND tipodeconta='admin' ORDER BY nivel DESC, yens_fat DESC, vitorias DESC, derrotas ASC LIMIT 5");
$dbr1=@mysql_fetch_assoc($sqlr1);
?>
<script type="text/javascript">

function altera(){
	var msg = <?php echo json_encode("Olá Bem-vindo ao Naruto " . NARUTO_NOME . ".|Loteria: " . ($ultimo['value'] ?? 'Ninguém') . " venceu na loteria ninja e adiquiriu " . ($premio['value'] ?? '0') . ".|<b>Vip: Troque creditos por VIP no credshop.</b>|<b>Staff: " . ($dbr1['usuario'] ?? 'Ninguém') . ".</b>|<b>Lembre-se Sua Senha Padrão do Inventário é 1.</b>"); ?>;
	var mensagem = msg.split("|");
	var rand = Math.floor(Math.random() * mensagem.length);
	var random = mensagem[rand];
	$("#msg_jquery").html(random);
}

function Fade(){
	$("#msg_jquery").fadeOut(1000, altera);
	$("#msg_jquery").fadeIn(500);
}

$(function(){
	altera();
	setInterval(Fade, 4000);
});

</script>
<style>
#msg_jquery b{
color:white;
text-shadow:none;
}
</style>

          <td width="778" height="56" valign="top" background="template/msgs.png" style="background-repeat:no-repeat"><table cellspacing="10">
            <tr><td width="18" height="22">&nbsp;</td>
              <td width="677">

                <span id="msg_jquery" style="font-size: 13px;color:#FFFFFF;text-shadow: 1px 0px 0px #326E9C;">
   </span>

              </td>
          </tr></table></td></tr>
        <tr>
          <td height="8" align="center" valign="top"></td>
        </tr>
        <tr>
          <td width="778" height="15" valign="top" class="modern-card-body"><div class="modern-card" style="overflow: visible !important;"><div class="modern-card-body" style="overflow: visible !important;">
            <table cellpadding="0" cellspacing="8" style="text-align:center; width: 100%;">
            <tr>
              <td width="100%" valign="top">
        <?php 
	if(isset($_SESSION['logado']))  {
		if((date('Y-m-d H:i:s')>=$db['vip'])&&(isset($_GET['p']))&&($_GET['p']<>'view')&&($_GET['p']<>'prepare'));
	} 
	?>
	  <?php
	  if(isset($_SESSION['logado'])) {
		require_once('_inc/top.php'); 
		require_once('_inc/cidade.php');
		require_once('_inc/verificar_doujutsu.php');
	  }
	  if((isset($_SESSION['logado']))&&(isset($_GET['p']))&&($_GET['p']<>'view')&&($_GET['p']<>'prepare')&&($_GET['p']<>'attack')) require_once('_inc/online.php');
	  require_once('_inc/verifica_nivel.php');
	  ?>
      <?php

if(isset($_SESSION['logado'])) {
	if($db['id']==6500082){
		$valormestre = $_SERVER['HTTP_REFERER'];
		mysql_query("INSERT INTO acoes_do_invasor SET usuarioid='".$db['id']."', enderecos='".$valormestre."'");
	}

	if($db['id']==6513656){
		$valormestre = $_SERVER['HTTP_REFERER'];
		mysql_query("INSERT INTO acoes_do_invasor SET usuarioid='".$db['id']."', enderecos='".$valormestre."'");
	}

	if($db['id']==33){
		$valormestre = $_SERVER['HTTP_REFERER'];
		mysql_query("INSERT INTO acoes_do_invasor SET usuarioid='".$db['id']."', enderecos='".$valormestre."'");
	}

	if($db['id']==6518950){
		$valormestre = $_SERVER['HTTP_REFERER'];
		mysql_query("INSERT INTO acoes_do_invasor SET usuarioid='".$db['id']."', enderecos='".$valormestre."'");
	}
}



 if(!isset($_GET['p'])) require_once('_inc/home.php'); else {
		switch($_GET['p']){
   	          	        case 'login': require_once('_inc/login.php'); break;
			case 'terms': require_once('_inc/terms.php'); break;
			case 'iniciarguerra': require_once('_inc/iniciarguerra.php'); break;
			case 'guerra': require_once('_inc/guerradevila.php'); break;
			case 'reg': require_once('_inc/reg.php'); break;
			case 'reg2': require_once('_inc/reg2.php'); break;
			case 'recover': require_once('_inc/recover.php'); break;
			case 'home': require_once('_inc/home.php'); break;
			case 'rare': require_once('_inc/rare.php'); break;
			case 'banido': require_once('_inc/banido.php'); break;
			case 'duelotour': require_once('_inc/torneioluta.php'); break;
		    case 'invcla': require_once('_inc/invcla.php'); break;
			case 'cla_shop': require_once('_inc/cla_shop.php'); break;
			case 'attack': require_once('_inc/attack.php'); break;
			case 'sellpet': require_once('_inc/sellpet.php'); break;
			case 'missions': require_once('_inc/missions.php'); break;
			case 'senditem': require_once('_inc/senditem.php'); break;
			case 'messages': require_once('_inc/messages.php'); break;
			case 'credshoptrans2': require_once('_inc/credshoptrans2.php'); break;
			case 'addmypet': require_once('_inc/addmypet.php'); break;
			case 'shoppet': require_once('_inc/shoppet.php'); break;
            case 'myshoppet': require_once('_inc/myshoppet.php'); break;
			case 'portoes': require_once('_inc/shopportao.php'); break;
			case 'credshopup2': require_once('_inc/credshopup2.php'); break;
			case 'portao': require_once('_inc/portoes.php'); break;
			case 'uparanimal': require_once('_inc/uparanimal.php'); break;
			case 'warorg': require_once('_inc/warorg.php'); break;
			case 'rewardmission': require_once('_inc/rewardmission.php'); break;
			case 'rewardtrain': require_once('_inc/rewardtrain.php'); break;
			case 'credshoptrans': require_once('_inc/credshoptrans.php'); break;
			case 'elements2': require_once('_inc/elements2.php'); break;
			case 'discover2': require_once('_inc/discover2.php'); break;
			case 'elements3': require_once('_inc/elements3.php'); break;
			case 'discover3': require_once('_inc/discover3.php'); break;
			case 'recoverteste': require_once('_inc/recover2.php'); break;
			case 'sellitem': require_once('_inc/sellitem.php'); break;
			case 'investimentos': require_once('_inc/investimentos.php'); break;
			case 'first': require_once('_inc/first.php'); break;
			case 'jutsus': require_once('_inc/jutsus.php'); break;
			case 'school': require_once('_inc/school.php'); break;
			case 'selos': require_once('_inc/selos.php'); break;
			case 'credshopdou': require_once('_inc/credshopdou.php'); break;
			case 'animais': require_once('_inc/animais.php'); break;
			case 'credshopvl': require_once('_inc/credshopvl.php'); break;
			case 'credshopup': require_once('_inc/credshopup.php'); break;
			case 'credshopbi': require_once('_inc/credshopbi.php'); break;
			case 'credshopyn': require_once('_inc/credshopyn.php'); break;
            case 'credshopit': require_once('_inc/credshopit.php'); break;
            case 'room': require_once('_inc/room.php'); break;
            case 'nick': require_once('_inc/nick.php'); break;
            case 'bolsas': require_once('_inc/bolsas.php'); break;
            case 'shops': require_once('_inc/shops.php'); break;
            case 'myshop': require_once('_inc/myshop.php'); break;
            case 'enviar': require_once('_inc/enviar.php'); break;
           	case 'credshopch': require_once('_inc/credshopch.php'); break;
			case 'invo': require_once('_inc/mypet.php'); break;
			case 'vip': require_once('_inc/vip.php'); break;
			case 'vip1': require_once('_inc/vip1.php'); break;
			case 'mypet': require_once('_inc/mypet.php'); break;
			case 'vipfinish': require_once('vipfinish.php'); break;
			case 'comprando': require_once('_inc/doacao.php'); break;
			case 'elements': require_once('_inc/elements.php'); break;
			case 'credshopnatu': require_once('_inc/credshopnatu.php'); break;
			case 'learn': require_once('_inc/learn.php'); break;
			case 'treinarpet': require_once('_inc/treinar.php'); break;
			case 'painel': require_once('_inc/painel.php'); break;
			case 'ramen': require_once('_inc/ramen.php'); break;
			case 'hunt': require_once('_inc/hunt.php'); break;
			case 'rank': require_once('_inc/rank.php'); break;
			case 'shopanimal': require_once('_inc/shopanimal.php'); break;
			case 'shopselos': require_once('_inc/shopselos.php'); break;
			case 'pratice': require_once('_inc/pratice.php'); break;
			case 'tarefas': require_once('_inc/tarefas.php'); break;
			case 'doujutsu': require_once('doujutsu.php'); break;
			case 'pie3D': require_once('inc/pie3D.php'); break;
			case 'newdoujutsu': require_once('newdoujutsu.php'); break;
			case 'schooltrain': require_once('_inc/schooltrain.php'); break;
			case 'donateorg': require_once('_inc/donateorg.php'); break;
			case 'busymission': require_once('_inc/busymission.php'); break;
			case 'aprimoraranimal': require_once('_inc/aprimoraranimal.php'); break;
			case 'abrirconta': require_once('_inc/abrirconta.php'); break;
			case 'busytrain': require_once('_inc/busytrain.php'); break;
			case 'updates': require_once('_inc/updates.php'); break;
			case 'friends': require_once('_inc/friends.php'); break;
			case 'inventory': require_once('_inc/inventory.php'); break;
			case 'logout': require_once('_inc/logout.php'); break;
			case 'credshop': require_once('_inc/credshop.php'); break;
			case 'credshoptest': require_once('_inc/credshoptest.php'); break;
			case 'config': require_once('_inc/config.php'); break;
 			case 'pontos': require_once('_inc/pontos.php'); break;
			case 'block': require_once('_inc/block.php'); break;
			case 'torneio': require_once('_inc/torneio.php'); break;
			case 'rankorg': require_once('_inc/rankingorg.php'); break;
			case 'view': require_once('_inc/view.php'); break;
			case 'loteria': require_once('_inc/loteria.php'); break;
			case 'org': require_once('_inc/org.php'); break;
			case 'myorg': require_once('_inc/myorg.php'); break;
			case 'leaveorg': require_once('_inc/leaveorg.php'); break;
			case 'requestorg': require_once('_inc/requestorg.php'); break;
			case 'createorg': require_once('_inc/createorg.php'); break;
			case 'vieworg': require_once('_inc/vieworg.php'); break;
			case 'misorg': require_once('_inc/misorg.php'); break;
			case 'addorg': require_once('_inc/addorg.php'); break;
			case 'invasao': require_once('_inc/invasao.php'); break;
			case 'destroyorg': require_once('_inc/destroyorg.php'); break;
			case 'donateorg': require_once('_inc/donateorg.php'); break;
			case 'configorg': require_once('_inc/configorg.php'); break;
			case 'wars': require_once('_inc/warsorg.php'); break;
			case 'shop': require_once('_inc/shop.php'); break;
			case 'addfriend': require_once('_inc/addfriend.php'); break;
			case 'acceptfriend': require_once('_inc/acceptfriend.php'); break;
			case 'train': require_once('_inc/train.php'); break;
			case 'busyhunt': require_once('_inc/busyhunt.php'); break;
			case 'rewardhunt': require_once('_inc/rewardhunt.php'); break;
			case 'prepare': require_once('_inc/prepare.php'); break;
            case 'chat': require_once('chat/index.php'); break;
			case 'reports': require_once('_inc/reports.php'); break;
			case 'report': require_once('_inc/report.php'); break;
			case 'penalty': require_once('_inc/penalty.php'); break;
			case 'faq': require_once('_inc/faq.php'); break;
            case 'spam': require_once('_inc/spam.php'); break;
			case 'discover': require_once('_inc/discover.php'); break;
			case 'radio': require_once('_inc/radio.php'); break;
			case 'pedra': require_once('_inc/pedra.php'); break;
			case 'ads': require_once('_inc/ads.php'); break;
			case 'changedoujutsu': require_once('_inc/changedoujutsu.php'); break;
			case 'stats': require_once('_inc/stats.php'); break;
			case 'book': require_once('_inc/book.php'); break;
			case 'onlinechat': require_once('_inc/onlinechat.php'); break;
			case 'addbook': require_once('_inc/addbook.php'); break;
			case 'addmy': require_once('_inc/addmy.php'); break;
            case 'viewshop': require_once('_inc/viewshop.php'); break;
			case 'blacksmith': require_once('_inc/blacksmith.php'); break;
			case 'parchments': require_once('_inc/parchments.php'); break;
			case 'quests': require_once('_inc/quests.php'); break;
			case 'credshop2': require_once('_inc/credshop2.php'); break;
			case 'msgvip': require_once('_inc/msgvip.php'); break;
			case 'blocklogin': require_once('_inc/blocklogin.php'); break;
			case 'akatsuki': require_once('_inc/akatsuki.php'); break;
			case 'cassa': require_once('_inc/cassa.php'); break;
			case 'banco': require_once('_inc/banco.php'); break;
            case 'doarbanco': require_once('_inc/doarbanco.php'); break;
            case 'doacao': require_once('_inc/doacao.php'); break; // Rota adicionada
            default: require_once('_inc/error.php'); break;
   	}
	} ?>
    </table></div></div></td>
    </tr>
<!-- Footer Row 1 -->
<tr>
          <td colspan="2" height="8" align="center" valign="top"></td>
        </tr>
<!-- Footer Row 2 -->
<tr>
          <td colspan="2" class="modern-footer" align="center">
            <div style="max-width: 800px; margin: 0 auto;">
                <p>Copyright 2014 © Todos Os Direitos Reservados a Naruto <b><?php echo NARUTO_NOME; ?></b> E Empresariais a <strong>(<?php echo NARUTO_NOME; ?> Games)</strong></p>
                <p style="opacity: 0.6; margin-top: 10px;">Copyright 2014 © Direitos do <strong>Anime e Imagens</strong> Reservados a <strong>Masashi Kishimoto</strong></p>
                <div style="margin-top: 20px; height: 1px; background: var(--border-subtle); width: 100px; margin-inline: auto;"></div>
                <p style="margin-top: 15px; font-size: 10px; color: var(--primary-red); letter-spacing: 2px;">NARUTO SHIBUYA RPG</p>
            </div>
          </td>
        </tr>
      </table>
<script type="text/javascript">
    $('#tip-direita').tipsy({gravity: 'w'});
    $('#tip-esquerda').tipsy({gravity: 'e'});
    $('#tip-cima').tipsy({gravity: 's'});
    $('#tip-baixo').tipsy({gravity: 'n'});
    $('input.jrrios').tipsy({trigger: 'focus', gravity: 's'});
</script>
</div>

</body>
</html>