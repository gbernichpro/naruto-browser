<?php
if(isset($_GET['start'])){
	vn($_GET['start']);
	$sqlq=mysql_query("SELECT * FROM table_quests WHERE id='".antiinjection($_GET['start'])."'");
	if(mysql_num_rows($sqlq)==0){ echo "<script>self.location='?p=home'</script>"; return; }
	$dbq = mysql_fetch_assoc($sqlq);
	if($db['nivel']<$dbq['nivel']){ echo "<script>self.location='?p=quests&msg=1'</script>"; return; }
	if($db['quest']>0){ echo "<script>self.location='?p=quests&msg=2'</script>"; return; }
	mysql_query("UPDATE usuarios SET quest='".antiinjection($_GET['start'])."', quest_vitorias=0 WHERE id=".$db['id']);
	echo "<script>self.location='?p=quests&msg=3'</script>";
}
if(isset($_GET['receive'])){
	$sqlq = mysql_query("SELECT * FROM table_quests WHERE id=".$db['quest']);
	if(mysql_num_rows($sqlq)==0){ echo "<script>self.location='?p=home'</script>"; return; }
	$dbq = mysql_fetch_assoc($sqlq);
	if($db['quest_vitorias']<$dbq['vitorias']){ echo "<script>self.location='?p=quests&msg=5'</script>"; return; }
	mysql_query("UPDATE usuarios SET quest=0, quest_vitorias=0, yens=yens+".$dbq['yens'].", yens_fat=yens_fat+".$dbq['yens'].", exp=exp+".$dbq['exp'].", exptotal=exptotal+".$dbq['exp']." WHERE id=".$db['id']);
	echo "<script>self.location='?p=quests&msg=6'</script>";
}
if(isset($_GET['cancel'])){
	mysql_query("UPDATE usuarios SET quest=0, quest_vitorias=0 WHERE id=".$db['id']);
	echo "<script>self.location='?p=quests&msg=4'</script>";
}
?>
<?php if($db['quest']>0){ ?>
<?php
$dif=$db['nivel']+3;
$sqlq=mysql_query("SELECT * FROM table_quests WHERE id=".$db['quest']);
$dbq=mysql_fetch_assoc($sqlq);
?>
<div class="box_top">Quest em Andamento</div>
<div class="box_middle">Abaixo estão as informações atualizadas da quest em andamento.
	<?php
	if(isset($_GET['msg'])){
		switch($_GET['msg']){
			case 1: $msg='Nível insuficiente para realizar esta quest.'; break;
			case 2: $msg='Você já iniciou uma quest.'; break;
			case 3: $msg='Quest iniciada!'; break;
			case 4: $msg='Quest cancelada!'; break;
			case 5: $msg='Requisitos da quest ainda não estão completos!'; break;
			case 6: $msg='Parabéns, a quest em andamento foi finalizada com sucesso!'; break;
		}
	echo '<div class="sep"></div><div class="aviso">'.$msg.'</div>';
	}
	?>
    <table width="100%" cellpadding="0" cellspacing="1">
        <tr>
        	<td colspan="3"><div class="sep"></div></td>
        </tr>
    	<tr class="table_dados" style="background:#323232;" onmouseover="style.background='#2C2C2C'" onmouseout="style.background='#323232'">
        	<td width="220"><img src="_img/quests/<?php echo $dbq['id']; ?>.jpg" /></td>
            <td align="center"><b><?php echo $dbq['nome']; ?></b><br /><span class="sub2"><?php echo $dbq['descricao']; ?></span><br /><br /><b>Vitórias</b><br /><span class="sub2"><?php echo $db['quest_vitorias']; ?> vitórias<br /><?php $completo=(100*$db['quest_vitorias'])/$dbq['vitorias']; if($completo>100) $completo=100; echo $completo; ?>% completada</span></td>
            <td><b>Recompensas</b><br /><span class="sub2"><?php echo number_format($dbq['yens'],2,',','.'); ?> yens<br /><?php echo $dbq['exp']; ?> pontos de experiência</span><?php if($db['quest_vitorias']<$dbq['vitorias']){ ?><br /><br /><input type="button" class="botao" value="Cancelar Quest" onclick="javascript:location.href='?p=quests&cancel=true'" /><?php } ?><?php if($db['quest_vitorias']>=$dbq['vitorias']){ ?><br /><br /><input type="button" class="botao" value="Finalizar Quest" onclick="javascript:location.href='?p=quests&receive=true'" /><?php } ?></td>
        </tr>
    </table>
</div>
<div class="box_bottom"></div>
<?php } else { ?>
<?php
$dif=$db['nivel']+3;
$sqlq=mysql_query("SELECT * FROM table_quests WHERE nivel<=$dif ORDER BY id DESC");
$dbq=mysql_fetch_assoc($sqlq);
?>
<div class="box_top">Quests</div>
<div class="box_middle"><div style="background: url(../_img/_detalhes/base2.PNG);width: 720px;height: 250px;">
<table cellpadding="0" cellspacing="0" width="712" height="230"><tbody><tr><td width="150">
<img width="142" style="" src="_img/_detalhes/msg/20.png"></td><td valign="top"><br><br><br>
<div style="margin-left: -345px;margin-top: 15px;height: 0px;">
<b id="title" style="font-family: impact;font-size: 20px;font-weight: normal;color: #ffffff;"  onmouseover="style.color='#F5F5F5'" onmouseout="color.color='#ffffff'">
&raquo; Quests Ninjas!</b></div><br>
<div style="margin-left: 0px;margin-top: 35px;font-family: arial;font-size: 12px;color: #fff;"><b>
Ganhe bônus de experiência e yens realizando as quests abaixo.</br>
Cada ninja pode realizar apenas uma quest por vez. Complete os </br>
requisitos de cada quest e suba no ranking rapidamente!</br>
</br>
</div></td></tr></tbody></table></div>
<div class="sep"></div>
       <ul class="menu">


	<li><a href="?p=missions">Voltar</a>

</ul>
	<?php
	if(isset($_GET['msg'])){
		switch($_GET['msg']){
			case 1: $msg='Nível insuficiente para realizar esta quest.'; break;
			case 2: $msg='Você já iniciou uma quest.'; break;
			case 3: $msg='Quest iniciada!'; break;
			case 4: $msg='Quest cancelada!'; break;
			case 5: $msg='Requisitos da quest ainda não estão completos!'; break;
			case 6: $msg='Parabéns, a quest em andamento foi finalizada com sucesso!'; break;
		}
	echo '<div class="sep"></div><div class="aviso">'.$msg.'</div>';
	}
	?>
    <table width="100%" cellpadding="0" cellspacing="1">
    	<?php do{ ?>
        <tr>
        	<td colspan="3"><div class="sep"></div></td>
        </tr>
    	<tr class="table_dados" style="background:#323232;" onmouseover="style.background='#2C2C2C'" onmouseout="style.background='#323232'">
        	<td width="220"><img src="_img/quests/<?php echo $dbq['id']; ?>.jpg" /></td>
            <td align="center"><b><?php echo $dbq['nome']; ?></b><br /><span class="sub2"><?php echo $dbq['descricao']; ?></span><br /><br /><b>Nível Mínimo</b><br /><span class="sub2">Nível <?php echo $dbq['nivel']; ?></span></td>
            <td><b>Recompensas</b><br /><span class="sub2"><?php echo number_format($dbq['yens'],2,',','.'); ?> yens<br /><?php echo $dbq['exp']; ?> pontos de experiência</span><?php if($db['nivel']>=$dbq['nivel']){ ?><br /><br /><input type="button" class="botao" value="Iniciar Quest" onclick="javascript:location.href='?p=quests&start=<?php echo $dbq['id']; ?>'" /><?php } ?></td>
        </tr>
        <?php } while($dbq=mysql_fetch_assoc($sqlq)); ?>
    </table>
</div>
<div class="box_bottom"></div>
<?php } ?>