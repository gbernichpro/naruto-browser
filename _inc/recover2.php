<?php
include("trava.php");
if(isset($_POST['rec_email'])){
	$erro=0;
	if($_POST['rec_email']=='') $erro=1;
	if($erro==0){
		$sqlr=mysql_query("SELECT id, pass, config_pergunta, config_resposta FROM usuarios WHERE email='".$_POST['rec_email']."'");
		$dbr=mysql_fetch_assoc($sqlr);
		if($dbr['pass']=='') $erro=3;
		if($erro==0){
			$senha=$dbr['pass'];
			$mensagem='<div align="center">br /><br /><b>Mensagem Importante</b><br />Você solicitou sua senha do minha loja e .<br />Sua senha é '.$senha.' .<br /><br /></b><br /><span style="font-size:10px;">Caso você não tenha feito a solicitação, apenas ignore este email.</span><br /><br /><b><span style="color:#CC0000">A equipe Naruto lhe deseja um bom jogo!</span></b><br />Atenciosamente, equipe Naruto.</div>';
			$assunto='Solicitar Nova Senha';
			$remetente='';
			$headers = implode ( "\n",array ( "From: $remetente","Subject: ".$assunto,"Return-Path: $remetente","MIME-Version: 1.0","X-Priority: 3","Content-Type: text/html" ) );
			send_mail_smtp($_POST['rec_email'],'',$mensagem);
		}
	}
	echo "<script>self.location='?p=recoverteste&msg=".$erro."'</script>"; return;
}
?>
<div class="box_top">Recuperar Senha (minha loja e venda de items)</div>
<div class="box_middle">Basta colocar seu email abaixo que será enviado para caixa de entrada ou lixo eletronico o email com a senha.<div class="sep"></div>
    <?php if(isset($_GET['msg'])){
		switch($_GET['msg']){
			case 0: $msg='Sua senha foi enviada para o email informado!'; break;
			case 1: $msg='Digite um <b>EMAIL</b> válido.'; break;
			case 2: $msg='Digite um <b>NOME DE USUÁRIO</b> válido.'; break;
			case 3: $msg='Nenhum registro encontrado com os dados informados.'; break;
			case 4: $msg='Pergunta ou resposta secreta não confere.'; break;
		}
	echo '<div class="aviso">'.$msg.'</div><div class="sep"></div>'; } ?>
    <fieldset><legend>Formulário de Recuperação de Senha</legend>
    <form method="post" action="?p=recoverteste" style="background:url(_img/recover.jpg) no-repeat right top;" onsubmit="rec_botao.value='Processando...';rec_botao.disabled=true;">
    	<div class="destaque">Email:</div>
        <input type="text" id="rec_email" name="rec_email" onfocus="className='input'" onblur="className=''" /><br />
         <div align="center"><input type="submit" class="botao" name="rec_botao" value="Recuperar Senha" /></div>

    </form>
    </fieldset>
</div>
<div class="box_bottom"></div>
<?php
@mysql_free_result($sqlr);
?>