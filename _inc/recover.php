<?php
if(isset($_POST['rec_usuario'])){
	$erro=0;
	if($_POST['rec_email']=='') $erro=1;
	if($_POST['rec_usuario']=='') $erro=2;
	if($erro==0){
		$sqlr = mysql_query("SELECT id, senha, config_pergunta, config_resposta FROM usuarios WHERE usuario='".antiinjection2($_POST['rec_usuario'])."' AND email='".antiinjection2($_POST['rec_email'])."'");
		$dbr = mysql_fetch_assoc($sqlr);
		if($dbr['senha']=='') $erro=3;

		if($erro==0){
			$link='?newpass.php?user='.strtolower($_POST['rec_usuario']).'&token='.md5($dbr['id']);

            $assunto = "Solicitação de nova senha Naruto";
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
<div align=\"center\"><br /><br /><b>Mensagem Importante</b><br />Você solicitou uma nova senha para sua conta.<br />
			Utilize o link abaixo caso queira realmente uma nova senha.<br /><br /><b><a href=\"".$link."\">".$link."</a></b><br /><span style=\"font-size:10px;\">Caso você não tenha feito a solicitação, apenas ignore este email.</span><br />
			<br /><b><span style=\"color:#CC0000\">A equipe Naruto lhe deseja um bom jogo!</span></b><br /><br />
			<br />Atenciosamente, equipe Naruto.</div>
          <br>
   <br><br>
		  Equipe Naruto</p>
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
            
            mail( $_POST['rec_email'], $assunto, $messagem, $headers );	
		}
	}
	echo "<script>self.location='?p=recover&msg=".$erro."'</script>"; return;
}
?>
<div class="box_top">Recuperar Senha</div>
<div class="box_middle">
<div style="background: url(../_img/_detalhes/base2.PNG);width: 720px;height: 250px;">
<table cellpadding="0" cellspacing="0" width="712" height="230"><tbody><tr><td width="150">
<img width="142" style="" src="_img/_detalhes/msg/6.png"></td><td valign="top"><br><br><br>
<div style="margin-left: -345px;margin-top: 15px;height: 0px;">
<b id="title" style="font-family: impact;font-size: 20px;font-weight: normal;color: #ffffff;"  onmouseover="style.color='#F5F5F5'" onmouseout="color.color='#ffffff'">
&raquo; Recuperar Senha!</b></div><br>
<div style="margin-left: 0px;margin-top: 35px;font-family: arial;font-size: 12px;color: #fff;"><b>
Devido à criptografia usada em nosso sistema, não é possível lhe enviar a senha</br>
que você utilizava. Mas podemos criar uma nova senha! Para isso, preencha os</br>
dois campos abaixo com seu nome de usuário e email utilizado no momento do registro.</br>
É possível que nossa mensagem seja transferida para o lixo eletrônico de sua</br>
caixa de mensagens.</br>
</b>
</div></td></tr></tbody></table></div>
<div class="sep"></div>

    <?php if(isset($_GET['msg'])){
		switch($_GET['msg']){
			case 0: echo "<script>top.$.prompt('Sua senha foi enviada para o email informado!');</script>"; break;
			case 1: echo "<script>top.$.prompt('Digite um email válido!');</script>"; break;
			case 2: echo "<script>top.$.prompt('Digite um nome de usuário válido!');</script>"; break;
			case 3: echo "<script>top.$.prompt('Nenhum registro encontrado com os dados informados!');</script>"; break;

		}
	 } ?>
    <fieldset><legend>Formulário de Recuperação de Senha</legend>
    <form method="post" action="?p=recover" style="background:url(_img/recover.jpg) no-repeat right top;" onsubmit="rec_botao.value='Processando...';rec_botao.disabled=true;">
    	<div class="destaque">Nome do Usuário:</div>
    	<input type="text" id="rec_usuario" name="rec_usuario" onfocus="className='input'" onblur="className=''" /><br />
        <span class="sub2">Digite o nome de usuário (login) da sua conta.</span><br /><br />
        <div class="destaque">Email:</div>
        <input type="text" id="rec_email" name="rec_email" onfocus="className='input'" onblur="className=''" /><br />
        <span class="sub2">Digite o endereço de email cadastrado no sistema.</span><br /><br />
       
        <div class="sep"></div>
        <div align="center"><input type="submit" class="botao" name="rec_botao" value="Recuperar Senha" /></div>
    </form>
    </fieldset>
</div>
<div class="box_bottom"></div>
<?php
@mysql_free_result($sqlr);
?>