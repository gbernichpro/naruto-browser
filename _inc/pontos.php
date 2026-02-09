<?php
if ($db['pontos'] > 0) {
	switch($_GET['add']) {
		case '0':
	if ($db['pontos'] > 0) {
	mysql_query("UPDATE usuarios SET pontos=pontos-1, taijutsu=taijutsu+1 WHERE id=".$db['id']);
	echo "<script>self.location='?p=pontos&msg=1'</script>";
	} else {
	echo "<script>self.location='?p=pontos&msg=2'</script>";
	}
  	break;
  	
  			case '1':
	if ($db['pontos'] > 0) {
	mysql_query("UPDATE usuarios SET pontos=pontos-1, ninjutsu=ninjutsu+1 WHERE id=".$db['id']);
	echo "<script>self.location='?p=pontos&msg=1'</script>";
	} else {
	echo "<script>self.location='?p=pontos&msg=2'</script>";
	}
  	break;

      		case '2':
	if ($db['pontos'] > 0) {
	mysql_query("UPDATE usuarios SET pontos=pontos-1, genjutsu=genjutsu+1 WHERE id=".$db['id']);
	echo "<script>self.location='?p=pontos&msg=1'</script>";
	} else {
	echo "<script>self.location='?p=pontos&msg=2'</script>";
	}
  	break;




}}

?>
<div class="box_top">Distribuir pontos</div>
<div class="box_middle">
<div style="background: url(../_img/_detalhes/base2.PNG);width: 720px;height: 250px;">
<table cellpadding="0" cellspacing="0" width="712" height="230"><tbody><tr><td width="150">
<img width="142" style="" src="_img/_detalhes/msg/39.png"></td><td valign="top"><br><br><br>
<div style="margin-left: -345px;margin-top: 15px;height: 0px;">
<b id="title" style="font-family: impact;font-size: 20px;font-weight: normal;color: #ffffff;"  onmouseover="style.color='#F5F5F5'" onmouseout="color.color='#ffffff'">
&raquo; Pontos Adquiridos!</b></div><br>
<div style="margin-left: 0px;margin-top: 35px;font-family: arial;font-size: 12px;color: #fff;"><b>
Darkangel distribua seus pontos com sabedoria, você recebe <b>1 ponto</b> para cada nível</br>
ganho, esse pontos nao podem ser reestabelecidos entao use-os com sabedoria.</br>
</b>
</div></td></tr></tbody></table></div>
<div class="sep"></div>
<center>
Voce possui <b><?=$db['pontos']?></b> pontos para serem distribuidos.
</center>
<div class="sep"></div>
<table border="0"cellspacing="0"  cellpadding="3" width="100%">
<tr   style="background: url('_img/gradient.jpg') repeat-y transparent;">
<td><img src="_img/taijutsu_icon.png" border="0"></td> <td width=69>Voce possui <td width=20><b><?=$db['taijutsu']?></b></td><td> pontos treinados em <b>taijutu</b></b></td><td><a href="?p=pontos&add=0">Adicionar</a><br /></td>
</tr>
<tr>
<td><img src="_img/ninjutsu_icon.png" border="0"></td> <td width=69>Voce possui <td width=20><b><?=$db['ninjutsu']?></b></td><td> pontos treinados em <b>ninjutsu</b></b></td><td><a href="?p=pontos&add=1">Adicionar</a><br /></td>
</tr>
<tr  style="background: url('_img/gradient.jpg') repeat-y transparent;">
<td><img src="_img/genjutsu_icon.png" border="0"></td> <td width=69>Voce possui <td width=20><b><?=$db['genjutsu']?></b></td><td> pontos treinados em <b>genjutsu</b></b></td><td><a href="?p=pontos&add=2">Adicionar</a><br /></td>
</tr>
</table>
 	<?php
	if(isset($_GET['msg'])){
		switch($_GET['msg']){
			case 1: $msg='Ponto adicionado com sucesso!'; break;
			case 2: $msg='Voce nao possui pontos para serem adicionados!'; break;

		}
	echo '<div class="sep"></div><div class="aviso">'.$msg.'</div><div class="sep"></div>';
	}
	?>

</div>
<div class="box_bottom"></div>
