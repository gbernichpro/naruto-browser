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
if(isset($_POST['conn'])){
	if(isset($_POST['conn_view'])) $view='sim'; else $view='nao';
	if(isset($_POST['conn_ok'])) $ok='sim'; else $ok='nao';
	if(isset($_POST['conn_atu'])) $atu='sim'; else $atu='nao';
	mysql_query("UPDATE usuarios SET config_atualizacoes='".antiinjection($atu)."' WHERE id=".$db['id']);
	echo "<script>self.location='?p=config&type=conn&msg=1'</script>";
}
if(isset($_GET['msg'])){
	switch($_GET['msg']){
		case 1: $msg='Configurações atualizadas com sucesso!'; break;
	}
	echo '<div class="aviso">'.$msg.'</div><div class="sep"></div>';
}
?>
<script>
function showDiv(box,id)  
{ 
 var elm = document.getElementById(id);
 elm.style.display = box.checked? "block":"none" 
}
</script>
<form method="post" action="?p=config&amp;type=conn" style="background:url(_img/config_conn.jpg) no-repeat right top;" onsubmit="subm.value='Carregando...';subm.disabled=true;">
<input type="hidden" id="conn" name="conn" value="1" />
<fieldset><legend>Atualizações</legend>
    <input type="checkbox" id="conn_atu" name="conn_atu" <?php if($db['config_atualizacoes']=='sim') echo 'checked="true"'; ?>/> Desejo enviar minhas atualizações aos meus amigos.<br /><span class="sub2">Marque esta opção para permitir o envio de atualizações à seus amigos.</span>
	    <div class="sep"></div>
    <div align="center"><input type="submit" id="subm" name="subm" class="botao" value="Salvar Alterações" /></div>
</fieldset>
</form></div>