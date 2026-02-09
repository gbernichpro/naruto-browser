<?php require_once('trava.php'); ?>
<?php
if(isset($_GET['cancel'])){
	if($db['missao']>1000){
		mysql_query("UPDATE table_missoes SET membros=membros-1 WHERE id=".$db['missao']);
	}
	if($db['orgmissao']>0) mysql_query("UPDATE table_missoes SET membros=membros-1 WHERE id=".$db['orgmissao']);
	mysql_query("UPDATE usuarios SET missao=0, orgmissao=0 WHERE id=".$db['id']);
	$db['missao']=0;
}
?>
<?php require_once('verificar.php'); ?>
<?php
require_once('Encrypt.php');
$c = new C_Encrypt();

if(isset($_POST['mis_rank'])){
	if($db['orgmissao']>0){ echo "<script>self.location='?p=missions&msg=2'</script>"; return; }
	$rank=$c->decode($_POST['mis_rank'],$chaveuniversal);
	$tempo=$c->decode($_POST['mis_tempo'],$chaveuniversal);
	vn($tempo);
	if($tempo<=0){ echo "<script>self.location='?p=missions'</script>"; return; }
	switch($rank){
		case 'S': $nivelmin=40; $mis=905; break;
		case 'A': $nivelmin=30; $mis=904; break;
		case 'B': $nivelmin=20; $mis=903; break;
		case 'C': $nivelmin=10; $mis=902; break;
		case 'D': $nivelmin=0; $mis=901; break;
		//case 'V': $nivelmin=0; $mis=999; break;
	}
	/*if($rank<>'V'){
		if($tempo>=25){ echo "<script>self.location='?p=home'</script>"; return; }
	}*/
	if($db['nivel']<$nivelmin){ echo "<script>self.location='?p=missions&msg=1'</script>"; return; }
	$soma=mktime(date('H')+$tempo, date('i'), date('s'));
	$missaofim=date('Y-m-d H:i:s',$soma);
	mysql_query("UPDATE usuarios SET missao='".antiinjection($mis)."', missao_tempo='".antiinjection($tempo)."', missao_fim='".antiinjection($missaofim)."' WHERE id='".$db['id']."'");
	/*if($rank=='V')
		{ echo "<script>self.location='?p=logout'</script>"; return; }
	else
		{ echo "<script>self.location='?p=busymission'</script>"; return; }*/
	if($rank=='V'){ echo "<script>self.location='?p=home'</script>"; return; }
	echo "<script>self.location='?p=busymission'</script>"; return;
}
?>
<div class="box_top">Missoes</div>
<div class="box_middle"><div style="background: url(../_img/_detalhes/base2.PNG);width: 720px;height: 250px;">
<table cellpadding="0" cellspacing="0" width="712" height="230"><tbody><tr><td width="150">
<img width="142" style="" src="_img/_detalhes/msg/14.png"></td><td valign="top"><br><br><br>
<div style="margin-left: -345px;margin-top: 15px;height: 0px;">
<b id="title" style="font-family: impact;font-size: 20px;font-weight: normal;color: #ffffff;"  onmouseover="style.color='#F5F5F5'" onmouseout="color.color='#ffffff'">
&raquo; Missões!</b></div><br>
<div style="margin-left: 0px;margin-top: 35px;font-family: arial;font-size: 12px;color: #fff;"><b>
Realize missões para ganhar <b>Yens</b> e <b>Experiência</b>! Ao</br>
terminar a missão, o ninja ganhará 1 ponto de experiência para cada</br>
hora trabalhada na missão. As missões de rank superiores a D aparecerão</br>
apenas quando seu nível alcançar o mínimo para realizá-las. Utilize os</br>
links abaixo para navegar entre as missões. Você pode cancelar a missão que</br>
estiver realizando a qualquer momento (ao fazer isso, você não receberá recompensas).
</br>
</div></td></tr></tbody></table></div>
  <?php
  $msg='';
  if(isset($_GET['msg'])){
  	switch($_GET['msg']){
		case 1: $msg='Seu nível é muito baixo para realizar missões deste rank.'; break;
		case 2: $msg='Você já está inscrito para uma missão de clã.'; break;
	}
  }
  if(isset($_GET['cancel'])) $msg='Missão cancelada!';
  if($msg<>'') echo '<div class="sep"></div><div class="aviso">'.$msg.'</div>';
  ?>
  <div class="sep"></div>
       <ul class="menu">


	<li><a href="#">Missões</a>

		<ul>
			<li><a href="?p=missions" class="documents">Normais</a></li>
		</ul>
	</li>
	<li><a href="#">Tarefas</a>
       <ul>
	  <li><a href="?p=tarefas" class="documents"><span>Tarefas Ninjas</span></a></li>
            </ul>
	</li>

	<li><a href="#">Quests</a>
       <ul>
	  <li><a href="?p=quests" class="documents"><span>Quests</span></a></li>
            </ul>


</ul>
  <?php if(!isset($_GET['type'])) require_once('missions_n.php'); else
  switch($_GET['type']){
	case 'n': require_once('missions_n.php'); return;
	case 'v': require_once('missions_v.php'); break;
  }
  ?>
</div>
<div class="box_bottom"></div>