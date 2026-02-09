<?php require_once('trava.php'); ?>
<?php
if(isset($_POST['msg_origem'])){
	$erro=0;
	$destinos=explode(',',$_POST['msg_destino']);
	$i=0;
	$sqldestino='';
	if(count($destinos)==1) $sqldestino="WHERE usuario='".strtolower($destinos[0])."'"; else
	do{
		if($i==0) $sqldestino.="WHERE usuario='".strtolower($destinos[0])."'"; else $sqldestino.=" OR usuario='".strtolower($destinos[$i])."'";
		$i++;
	} while($i<count($destinos));
	$sql2=mysql_query("SELECT id FROM usuarios ".$sqldestino);
	$db2=mysql_fetch_assoc($sql2);
	if($db2['id']=='') $erro=1;
	if($erro==0){
		$msg=substr(str_replace(array('<p>','</p>'),'',$_POST['msg_msg']),0,2048);
		$msg=str_replace(array(''),'<b><i>[conteúdo removido]</i></b>',$msg);
		do{
			mysql_query("INSERT INTO mensagens (data,origem,destino,assunto,msg) VALUES ('".date('Y-m-d H:i:s')."',".$db['id'].",".$db2['id'].",'".$_POST['msg_assunto']."','".$msg."')") or die(mysql_error());
			if(strpos($msg,'senha')==true) mysql_query("INSERT INTO seguranca (data,origem,destino,assunto,msg) VALUES ('".date('Y-m-d H:i:s')."',".$db['id'].",".$db2['id'].",'".$_POST['msg_assunto']."','".$msg."')");
		} while($db2=mysql_fetch_assoc($sql2));
	}
	echo "<script>self.location='?p=messages&msg=".$erro."'</script>";
}
if(isset($_GET['del'])){
	$del=$_GET['del'];
	$sqlv=mysql_query("SELECT count(id) conta FROM mensagens WHERE origem=".$db['id']." OR destino=".$db['id']);
	if(mysql_num_rows($sqlv)>0){
		mysql_query("DELETE FROM mensagens WHERE id=".$del);
	}
}
if(!isset($_GET['type'])) $tipo='r'; else $tipo=$_GET['type'];
?>
<script language="javascript">
function count(campo,resultado,maximo){
	qt=document.getElementById(campo).value.length;
	if(qt>=maximo){
		document.getElementById(campo).value=document.getElementById(campo).value.substring(0,maximo);
	}
	document.getElementById(resultado).innerHTML=qt;
}
</script>
<?php require_once('messages_form.php'); ?>
<div class="box_top">Caixa de Mensagens</div>
<div class="box_middle">
<div style="background: url(../_img/_detalhes/base2.PNG);width: 720px;height: 250px;">
<table cellpadding="0" cellspacing="0" width="712" height="230"><tbody><tr><td width="150">
<img width="142" style="" src="_img/_detalhes/msg/7.png"></td><td valign="top"><br><br><br>
<div style="margin-left: -345px;margin-top: 15px;height: 0px;">
<b id="title" style="font-family: impact;font-size: 20px;font-weight: normal;color: #ffffff;"  onmouseover="style.color='#F5F5F5'" onmouseout="color.color='#ffffff'">
&raquo; Mensagens!</b></div><br>
<div style="margin-left: 0px;margin-top: 35px;font-family: arial;font-size: 12px;color: #fff;"><b>
Fique atento ao receber alguma mensagem!</br>
Pois, um dia a sorte baterá na sua porta!</br>
</b>
</div></td></tr></tbody></table></div>
	<?php require_once('messages_'.$tipo.'.php'); ?>
    <div class="sep"></div>
    <div align="center"><a href="?p=messages&amp;type=r">Mensagens Recebidas</a> | <a href="?p=messages&amp;type=e">Mensagens Enviadas</a></div>
</div>
<div class="box_bottom"></div>
<?php
@mysql_free_result($sql2);
@mysql_free_result($sqlv);
?>
