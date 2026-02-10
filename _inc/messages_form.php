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
<div class="box_top">Enviar Mensagem</div>
<div class="box_middle" style="display:<?php if((isset($_GET['destiny']))or(isset($_GET['subject']))) echo 'none'; else echo 'block'; ?>;">
	<div style="border:1px dotted #999999;padding:5px;text-align:center;" id="div1">
    <a href="#" onclick="document.getElementById('div1').style.display='none';document.getElementById('div2').style.display='block';">Clique aqui para enviar uma mensagem.</a>
    <?php if(isset($_GET['msg'])){
		switch($_GET['msg']){
			case 0: $msg='Mensagem enviada com sucesso!'; break;
			case 1: $msg='Usuário não encontrado.'; break;
		}
	echo '<div class="sep"></div><div class="aviso">'.$msg.'</div>'; } ?>
    </div>
</div>
<div id="div2" class="box_middle" style="display:<?php if((isset($_GET['destiny']))or(isset($_GET['subject']))) echo 'block'; else echo 'none'; ?>;">
<div style="background: url(../_img/_detalhes/base2.PNG);width: 720px;height: 250px;">
<table cellpadding="0" cellspacing="0" width="712" height="230"><tbody><tr><td width="150">
<img width="142" style="" src="_img/_detalhes/msg/7.png"></td><td valign="top"><br><br><br>
<div style="margin-left: -345px;margin-top: 15px;height: 0px;">
<b id="title" style="font-family: impact;font-size: 20px;font-weight: normal;color: #ffffff;"  onmouseover="style.color='#F5F5F5'" onmouseout="color.color='#ffffff'">
&raquo; Enviar mensagem!</b></div><br>
<div style="margin-left: 0px;margin-top: 35px;font-family: arial;font-size: 12px;color: #fff;"><b>
Mande mensagens para seus amigos e outros jogadores!</br>
Também é possível mandar mensagens para os administradores!</br>
</b>
</div></td></tr></tbody></table></div>
<div class="sep"></div>
	<?php if(isset($_GET['msg'])){
		switch($_GET['msg']){
			case 0: $msg='Mensagem enviada com sucesso!'; break;
			case 1: $msg='Usuário não encontrado.'; break;
		}
	echo '<div class="aviso">'.$msg.'</div><div class="sep"></div>'; } ?>
    <fieldset><legend>Enviar Mensagem</legend>
    	<form method="post" id="Form-Enviar-Mensagem" action="?p=messages&amp;en=ok" onsubmit="var b=this.querySelector('input[type=submit]'); if(b) { b.value='Carregando...'; b.disabled=true; }">
       	  <input type="hidden" id="msg_origem" name="msg_origem" value="<?php echo $db['usuario']; ?>" />
            <span class="destaque">Destino(s) da Mensagem:</span><br />
            <input type="text" id="msg_destino" name="msg_destino" maxlength="159" onfocus="className='input'" onblur="className=''" <?php if(isset($_GET['destiny'])) echo 'value="'.$_GET['destiny'].'"'; ?>/><br />
            <span class="sub2">Digite o nome dos usuários que receberão sua mensagem (para mais de um usuário, separe por vírgula - máximo de 10 usuários por mensagem).</span><br /><div class="sep"></div>
            <span class="destaque">Assunto da Mensagem:</span><br />
            <input type="text" id="msg_assunto" name="msg_assunto" maxlength="60" onfocus="className='input'" onblur="className=''" <?php if(isset($_GET['subject'])) echo 'value="'.$_GET['subject'].'"'; ?>/><br />
            <span class="sub2">Digite o assunto da mensagem.</span><br /><div class="sep"></div>
            <span class="destaque">Mensagem:</span>
            <textarea id="msg_msg" name="msg_msg" style="width:100%;"></textarea>
            <span class="sub2">Mensagem a ser enviada. Apenas os primeiros 2048 caracteres serão válidos.</span>
            <div class="sep"></div>
            <div align="center"><input type="submit" id="subm" name="sub2" class="botao" value="Enviar Mensagem"></div>
        </form>
    </fieldset>
</div>
<div class="box_bottom"></div>