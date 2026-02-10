<div align="left">    <div style="background: url(../_img/_detalhes/base2.PNG);width: 720px;height: 250px;">
<table cellpadding="0" cellspacing="0" width="712" height="230"><tbody><tr><td width="150">
<img width="142" style="" src="_img/_detalhes/msg/1.png"></td><td valign="top"><br><br><br>
<div style="margin-left: 45px;margin-top: 15px;height: 0px;">
<b id="title" style="font-family: impact;font-size: 20px;font-weight: normal;color: #ffffff;"  onmouseover="style.color='#F5F5F5'" onmouseout="color.color='#ffffff'">
&raquo; Asuma Sensei:</b></div><br>
<div style="margin-left: 35px;margin-top: 35px;font-family: arial;font-size: 12px;color: #fff;"><b>
Seja bem-vindo ao Naruto <?php echo NARUTO_NOME; ?>! Para que você possa se</br>
aventurar no mundo de Naruto, é necessário arrumar suas</br>
configurações no jogo, assim conseguirá mais segurança e</br>
diversão.</br>
</b>
</div></td></tr></tbody></table></div>
<?php
if(isset($_POST['pass_atual'])){
	$atual = antiinjection($_POST['pass_atual']);
	$nova = antiinjection($_POST['pass_nova']);
	$nova2 = antiinjection($_POST['pass_nova2']);
	if(($_POST['pass_atual']=='')or($_POST['pass_nova']=='')or($_POST['pass_nova2']=='')){ echo "<script>self.location='?p=config&type=pass'</script>"; return; }
	if(md5($atual)<>$db['senha']){ echo "<script>self.location='?p=config&type=pass&msg=1'</script>"; return; }
	if($nova<>$nova2){ echo "<script>self.location='?p=config&type=pass&msg=2'</script>"; return; }
	mysql_query("UPDATE usuarios SET senha='".md5($nova)."' WHERE id=".$db['id']);
	echo "<script>self.location='?p=config&type=pass&msg=3'</script>";
}
if(isset($_GET['msg'])){
	switch($_GET['msg']){
		case 1: $msg='Senha atual não confere com senha cadastrada no sistema.'; break;
		case 2: $msg='Nova senha digitada não confere com a confirmação.'; break;
		case 3: $msg='Senha alterada com sucesso!'; break;
	}
	echo '<div class="aviso">'.$msg.'</div><div class="sep"></div>';
}
?>
<fieldset><legend>Alterar Senha</legend>
	<form method="post" action="?p=config_pass&amp;en=ok" onsubmit="var b=this.querySelector('input[type=submit]'); if(b) { b.value='Carregando...'; b.disabled=true; }">
    	<span class="destaque">Senha Atual:</span><br />
        <input type="password" id="pass_atual" name="pass_atual" /><br />
        <span class="sub2">Digite a senha atual.</span><br /><div class="sep" style="width:180px;"></div>
        <span class="destaque">Nova Senha:</span><br />
        <input type="password" id="pass_nova" name="pass_nova" /><br />
        <span class="sub2">Digite a nova senha.</span><br /><div class="sep" style="width:180px;"></div>
        <span class="destaque">Confirmar Nova Senha:</span><br />
        <input type="password" id="pass_nova2" name="pass_nova2" /><br />
        <span class="sub2">Confirme a nova senha.</span>
        <div class="sep"></div>
        <div align="center"><input type="submit" id="subm" name="subm" class="botao" value="Alterar Senha" /></div>
    </form></div>
</fieldset>