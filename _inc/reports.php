<?php
if((!isset($_GET['type']))or(isset($_GET['type']))&&($_GET['type']=='a')) $type=1; else $type=2;
?>
<div class="box_top">Relatorios de Ninjas</div>
	<div class="box_middle">
	<div style="background: url(../_img/_detalhes/base2.PNG);width: 720px;height: 250px;">
<table cellpadding="0" cellspacing="0" width="712" height="230"><tbody><tr><td width="150">
<img width="142" style="" src="_img/_detalhes/msg/4.png"></td><td valign="top"><br><br><br>
<div style="margin-left: -345px;margin-top: 15px;height: 0px;">
<b id="title" style="font-family: impact;font-size: 20px;font-weight: normal;color: #ffffff;"  onmouseover="style.color='#F5F5F5'" onmouseout="color.color='#ffffff'">
&raquo; Relatórios Ninjas!</b></div><br>
<div style="margin-left: 0px;margin-top: 35px;font-family: arial;font-size: 12px;color: #fff;"><b>
Opa! que emocionante, não ?</br>
Alem de ter batalhas emocionantes, eles também ficam gravadas aqui!</br>
Abaixo estão seus relatórios das suas batalhas que ocorreram durante o jogo.</br>
</b>
</div></td></tr></tbody></table></div>
	<?php require_once('reports'.$type.'.php'); ?>
    <div class="sep"></div>
    <div align="center"><a href="?p=reports&amp;type=a">Relatórios de Ataque</a> | <a href="?p=reports&amp;type=d">Relatórios de Defesa</a></div>
    </div>
<div class="box_bottom"></div>
<?php
@mysql_free_result($sqlr);
?>