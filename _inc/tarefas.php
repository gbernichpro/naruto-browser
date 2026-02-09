<div class="box_top">Tarefas</div>
<div class="box_middle">
<div style="background: url(../_img/_detalhes/base2.PNG);width: 720px;height: 250px;">
<table cellpadding="0" cellspacing="0" width="712" height="230"><tbody><tr><td width="150">
<img width="142" style="" src="_img/_detalhes/msg/15.png"></td><td valign="top"><br><br><br>
<div style="margin-left: -345px;margin-top: 15px;height: 0px;">
<b id="title" style="font-family: impact;font-size: 20px;font-weight: normal;color: #ffffff;"  onmouseover="style.color='#F5F5F5'" onmouseout="color.color='#ffffff'">
&raquo; Tarefas Ninjas!</b></div><br>
<div style="margin-left: 0px;margin-top: 35px;font-family: arial;font-size: 12px;color: #fff;"><b>
Tarefas ninjas são como um extra para que o ninja se torne um</br>
poderoso hokage no Naruto a Lenda, asim que completar recebe um otimo</br> 
Premio,Boa Sorte.
</br>
</div></td></tr></tbody></table></div>
<div class="sep"></div>
       <ul class="menu">


	<li><a href="#">Vitorias</a>

		<ul>
			<li><a href="?p=tarefas&amp;category=vitorias" class="documents">Vitorias</a></li>
		</ul>
	</li>
	<li><a href="#">Nivel</a>
       <ul>
	  <li><a href="?p=tarefas&amp;category=nivel" class="documents"><span>Nivel</span></a></li>
            </ul>
	</li>

	<li><a href="#">Score</a>
       <ul>
	  <li><a href="?p=tarefas&amp;category=score" class="documents"><span>Score</span></a></li>
            </ul>
	<li><a href="?p=missions">Voltar</a>

</ul>
    <div class="sep"></div>
    <?php
	if(!isset($_GET['category'])) require_once('tarefas_vitorias.php'); else
    if(isset($_GET['category'])){
		switch($_GET['category']){
			case 'vitorias': require_once('tarefas_vitorias.php'); return;
			case 'nivel': require_once('tarefas_nivel.php'); break;
			case 'score': require_once('tarefas_score.php'); break;

		}
	}
	?>