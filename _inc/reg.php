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
	if (!validate_csrf_token($_POST['csrf_token'] ?? '')) $erro=17;
	if ($erro===0 && !validate_turnstile($_POST['cf-turnstile-response'] ?? '')) $erro=18;
	if ($erro===0 && empty($_POST['reg_termos'])) $erro=8;
	if ($erro===0 && ($_POST['reg_senha'] ?? '') !== ($_POST['reg_senha2'] ?? '')) $erro=7;
	if ($erro===0 && empty($_POST['reg_vila'])) $erro=6;
	if ($erro===0 && empty($_POST['reg_personagem'])) $erro=5;
	if ($erro===0 && !filter_var($_POST['reg_email'] ?? '', FILTER_VALIDATE_EMAIL)) $erro=4;
	if ($erro===0 && empty($_POST['reg_senha2'])) $erro=3;
	if ($erro===0 && strlen($_POST['reg_senha'] ?? '') < 8) $erro=2;
	if ($erro===0 && strlen($_POST['reg_usuario'] ?? '') < 4) $erro=1;
	$admin = "";
	$personagem=$c->decode($_POST['reg_personagem'] ?? '',$chaveuniversal);
	$vila=$c->decode($_POST['reg_vila'] ?? '',$chaveuniversal);
	$usuario=str_replace(array(' ','&nbsp;'),'_',$_POST['reg_usuario'] ?? '');
	$usuario=str_replace(' ','_',$usuario);
	$usuario=ucfirst(strtolower(str_replace(array('/','^','[','-',']','+','$','(',')','?','\'','|','°','ª','#','@','.','?','!'),'',$usuario)));
	$pattern = "([_ _-_,_._>_`_´_<_~_^\/_?_°_\_:_;_§_|_!_¹_²_³_£_¢_¬_§_º_@_#_%_¨_&_*_+_{_}_*_])" ;
	if($erro===0 && preg_match('/' . $pattern . '/', $_POST['reg_usuario'] ?? '')) $erro=16;

	$redirecionarCadastro = static function ($codigo, $personagem, $vila) {
		$parametros = [
			'p' => 'reg',
			'user' => $_POST['reg_usuario'] ?? '',
			'mail' => $_POST['reg_email'] ?? '',
			'char' => $personagem,
			'village' => $vila,
			'erro' => $codigo,
		];
		if (!empty($_POST['reg_nlink'])) $parametros['nlink'] = $_POST['reg_nlink'];
		echo '<script>self.location=' . json_encode('?' . http_build_query($parametros)) . '</script>';
	};
	if($erro>0){ $redirecionarCadastro($erro, $personagem, $vila); return; }

	// Modernized counts and checks with Prepared Statements
	$stmt_c = mysqli_prepare($mysqli_link, "SELECT count(id) conta FROM usuarios WHERE usuario=?");
	mysqli_stmt_bind_param($stmt_c, "s", $usuario);
	mysqli_stmt_execute($stmt_c);
	$result_c = mysqli_stmt_get_result($stmt_c);
	$dbc = mysqli_fetch_assoc($result_c);
	if($dbc['conta']>0) $erro=12;

	$stmt_c2 = mysqli_prepare($mysqli_link, "SELECT count(id) conta FROM usuarios WHERE email=?");
	mysqli_stmt_bind_param($stmt_c2, "s", $_POST['reg_email']);
	mysqli_stmt_execute($stmt_c2);
	$result_c2 = mysqli_stmt_get_result($stmt_c2);
	$dbc = mysqli_fetch_assoc($result_c2);
	if($dbc['conta']>0) $erro=12;

	if($_POST['reg_nlink']<>''){
		$nlink=$_POST['reg_nlink'];
		$stmt_v = mysqli_prepare($mysqli_link, "SELECT count(id) conta FROM usuarios WHERE usuario=?");
		mysqli_stmt_bind_param($stmt_v, "s", $nlink);
		mysqli_stmt_execute($stmt_v);
		$result_v = mysqli_stmt_get_result($stmt_v);
		$dbv = mysqli_fetch_assoc($result_v);
		if($dbv['conta']==0) $erro=13;
	}

	if($erro>0){ $redirecionarCadastro($erro, $personagem, $vila); return; }
	else {
	    $novocodigo=rand(99999,99999999);
		if(isset($_POST['reg_akatsuki'])) $renegado='sim'; else $renegado='nao';
        $atual=date('Y-m-d H:i:s');
        $soma = mktime(date('H')+168, date('i'), date('s'));
        $fim = date('Y-m-d H:i:s',$soma);
        $vipadd=$fim;
		$usuario=ucfirst(strtolower(str_replace(array(' ','/','^','[','-',']','+','$','(',')','?','\'','|','°','ª','#','@','.','?','!'),'',$_POST['reg_usuario'])));
		
        // Secure Registration INSERT with Prepared Statements
        $senha_hash = password_hash($_POST['reg_senha'], PASSWORD_DEFAULT);
        
        $query = "INSERT INTO usuarios (
            usuario, status, senha, email, personagem, vila, renegado, hunt_restantes, reg, 
            natureza1, natureza2, natureza3, ip, vip, vip_inicio, ativador, yens,
            alunoid, senseiid, config_resposta, pessoal_nome, pessoal_sexo, pessoal_idade, pessoal_pais, pessoal_uf,
            pontos, tempo, creditos, creditosusados, pontoscla, pass, premiodiario, inwar_score, caiu, torneio_eliminado, torneio_score,
            hunt_fim, treino_fim, penalidade_fim, missao_tempo, missao_fim, treino_tempo, config_apresentacao, loginip
        ) VALUES (
            ?, 'ativo', ?, ?, ?, ?, ?, 14, ?, 
            '', '', '', ?, ?, ?, ?, '9000',
            '', '', '', '', '', 0, '', '',
            0, 0, 0, 0, 0, '', 0, 0, 0, 0, 0,
            ?, ?, ?, '0', ?, '0', '', ?
        )";
        
        $stmt_reg = mysqli_prepare($mysqli_link, $query);
        if (!$stmt_reg) {
            // Log error but show user friendly message if possible, or let custom handler catch if restored
             error_log("Erro no preparo do registro: " . mysqli_error($mysqli_link));
             die("Erro no sistema de registro. Contate o suporte. (Cód: PREPARE)");
        }
        
        $ip_long = ip2long($_SERVER['REMOTE_ADDR']);
        
        // Bind params: 
        // ... (previous params)
        // s (hunt_fim)
        // s (treino_fim)
        // s (penalidade_fim)
        // s (missao_fim)
        // s (loginip)
        
        // Total: 14 + 2 = 16 params provided
        $bind = mysqli_stmt_bind_param($stmt_reg, "ssssisssssisssss", $usuario, $senha_hash, $_POST['reg_email'], $personagem, $vila, $renegado, $atual, $_SERVER['REMOTE_ADDR'], $vipadd, $atual, $novocodigo, $atual, $atual, $atual, $atual, $_SERVER['REMOTE_ADDR']);
        
        if (!$bind) {
             error_log("Erro ao vincular parâmetros do registro: " . mysqli_stmt_error($stmt_reg));
             die("Erro no sistema de registro. Contate o suporte. (Cód: BIND)");
        }
        
        $exec = mysqli_stmt_execute($stmt_reg);
        if (!$exec) {
             error_log("Erro na execução do registro: " . mysqli_stmt_error($stmt_reg));
             die("Erro ao salvar dados: " . mysqli_stmt_error($stmt_reg));
        }

            $assunto = "Código de ativação Naruto";
            $messagem = "<html>\n"; 
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
		  Olá ".$_POST['reg_usuario'].",<br>
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
            
            send_mail_smtp( $_POST['reg_email'], $assunto, $messagem );
            // echo "Debug: Tentando enviar email...<br>";
             // send_mail_smtp( $_POST['reg_email'], $assunto, $messagem );
            // echo "Debug: Email ignorado para debug.<br>";

//=============================================//



		echo "<script>self.location='?p=reg2'</script>"; return;
        // echo "Debug: Fim do script. Redirecionamento pausado.<br>";
        // return;
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
<?php
if (isset($_GET['erro'])) {
    $mensagensCadastro = [
        1 => 'O nome de usuário deve ter pelo menos 4 caracteres.',
        2 => 'A senha deve ter pelo menos 8 caracteres.',
        3 => 'Confirme a senha.',
        4 => 'Informe um e-mail válido.',
        5 => 'Escolha um personagem.',
        6 => 'Escolha uma vila.',
        7 => 'As senhas digitadas não são iguais.',
        8 => 'Você precisa aceitar os termos para criar a conta.',
        11 => 'Você precisa aceitar os termos para criar a conta.',
        12 => 'Esse usuário ou e-mail já está cadastrado.',
        13 => 'O jogador indicado não foi encontrado.',
        16 => 'Use apenas letras e números no nome de usuário.',
        17 => 'A página expirou. Atualize e tente novamente.',
        18 => 'A verificação antirrobô não foi concluída.',
    ];
    $codigoErro = (int) $_GET['erro'];
    if (isset($mensagensCadastro[$codigoErro])) {
        echo '<div class="aviso">' . htmlspecialchars($mensagensCadastro[$codigoErro], ENT_QUOTES, 'UTF-8') . '</div><div class="sep"></div>';
    }
}
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

<form method="post" action="?p=reg&amp;en=ok" onsubmit="var b=this.querySelector('input[type=submit]'); if(b) { b.value='Carregando...'; b.disabled=true; }">
<?php csrf_input(); ?>
<div align="left">

<input type="hidden" id="reg_submit" name="reg_submit" value="1" />
<input type="hidden" id="reg_nlink" name="reg_nlink" value="<?php if(isset($_GET['nlink'])) echo htmlspecialchars($_GET['nlink'], ENT_QUOTES, 'UTF-8'); ?>" />
<fieldset>
	<legend>Dados da Conta</legend>
    <span class="destaque">Nome de Usuário:</span><br />
    <input type="text" id="reg_usuario" name="reg_usuario" maxlength="15" onfocus="className='input'" onblur="className=''" <?php if(isset($_GET['user'])) echo 'value="'.htmlspecialchars($_GET['user'], ENT_QUOTES, 'UTF-8').'"'; ?>/><br />
    <span class="sub2"><small>Minimo. 4 e Máximo. 20 caracteres. (letras e números).</small></span><br /><br />

    <span class="destaque">Senha:</span><br />
    <input type="password" id="reg_senha" name="reg_senha" maxlength="15" onfocus="className='input'" onblur="className=''" /><br />
    <span class="sub2"><small>Min. 8 e Máx. 20 caracteres.</small></span><br /><br />

    <span class="destaque">Confirmar Senha:</span><br />
    <input type="password" id="reg_senha2" name="reg_senha2" maxlength="15" onfocus="className='input'" onblur="className=''" /><br />
    <span class="sub2"><small>Repita novamente a senha.</small></span><br /><br />

    <span class="destaque">Email:</span><br />
    <input type="text" id="reg_email" name="reg_email" maxlength="250" onfocus="className='input'" onblur="className=''" <?php if(isset($_GET['mail'])) echo 'value="'.htmlspecialchars($_GET['mail'], ENT_QUOTES, 'UTF-8').'"'; ?>/><br />
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
    <div align="center">
        <?php if (naruto_turnstile_enabled()): ?>
            <div class="cf-turnstile" data-sitekey="<?php echo htmlspecialchars(naruto_env('TURNSTILE_SITE_KEY', ''), ENT_QUOTES, 'UTF-8'); ?>" data-size="compact"></div>
        <?php endif; ?>
        <input type="submit" class="botao" id="subm" name="subm" value="Registrar" />
    </div>
</fieldset>
</form>
</div></div>
<div class="box_bottom"></div>
<?php
@mysql_free_result($sqlc);
?>
