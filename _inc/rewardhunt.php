<?php require_once('trava.php'); ?>
<audio autoplay="autoplay" hidden="true" controls="controls">
   <source src="_sons/vitoria.mp3" />
</audio>
<?php
$atual=date('Y-m-d H:i:s');
if($db['hunt']==0){ echo "<script>self.location='?p=home'</script>"; return; } else {
	if($atual<$db['hunt_fim']){ echo "<script>self.location='?p=busyhunt'</script>"; return; } else {
		$exp=rand(2,10);
		switch($exp){
			case 2: $yens=rand(160,240); return;
			case 3: $yens=rand(241,330); break;
			case 4: $yens=rand(331,420); break;
			case 5: $yens=rand(421,510); break;
			case 6: $yens=rand(511,600); break;
			case 7: $yens=rand(601,690); break;
			case 8: $yens=rand(691,780); break;
			case 9: $yens=rand(781,870); break;
			case 10: $yens=rand(871,960); break;
		}
		if(date('Y-m-d H:i:s')<$db['vip']) $bonus=rand(1,15); else $bonus=0;
		$exp=$exp+$bonus;
		mysql_query("UPDATE usuarios SET hunt=0, yens=yens+".$yens.", yens_fat=yens_fat+".$yens.", exp=exp+".$exp.", exptotal=exptotal+".$exp." WHERE id=".$db['id']);
		$exp=$exp-$bonus;
		$db['yens']=$db['yens']+$yens;
		$db['yens']=$db['yens_fat']+$yens;
		$db['exp']=$db['exp']+$exp+$bonus;
		$db['exptotal']=$db['exptotal']+$exp+$bonus;
	}
}
?>
<div class="box_top">Caca Finalizada</div>
<div class="box_middle">
<div style="background: url(../_img/_detalhes/base2.PNG);width: 720px;height: 250px;">
<table cellpadding="0" cellspacing="0" width="712" height="230"><tbody><tr><td width="150">
<img width="142" style="" src="_img/_detalhes/msg/36.png"></td><td valign="top"><br><br><br>
<div style="margin-left: -345px;margin-top: 15px;height: 0px;">
<b id="title" style="font-family: impact;font-size: 20px;font-weight: normal;color: #ffffff;"  onmouseover="style.color='#F5F5F5'" onmouseout="color.color='#ffffff'">
&raquo; Quest Completada com Sucesso!</b></div><br>
<div style="margin-left: 0px;margin-top: 35px;font-family: arial;font-size: 12px;color: #fff;"><b>
Parabéns por conseguir terminar esta caça.<br />
Abaixo estão suas recompensas.<br />
Clique <a href="?p=hunt">aqui</a> para voltar às caças.<br />
<?php if($yens>0){ ?>- <b><?php echo number_format($yens,2,',','.'); ?> yens</b><br /><?php } if($exp>0){ ?>- <b><?php echo $exp; ?> ponto<?php if($exp>1) echo 's'; ?> de Experiência</b><?php } ?><?php if($bonus>0){ ?><br />- <b><?php echo $bonus; ?> pontos de Experiência (Bônus)</b><?php } ?>
</div></td></tr></tbody></table></div>
</div>
<div class="box_bottom"></div>


<?php
if(date('Y-m-d H:i:s')<$db['vip']){
$chance=rand(1,100);
if(($chance>=90)){
if($chance>=90){
	$sqli=mysql_query("SELECT * FROM table_usaveis ORDER BY RAND() LIMIT 1");
	$dbi=mysql_fetch_assoc($sqli);
	mysql_query("INSERT INTO usaveis (usuarioid, itemid) VALUES (".$db['id'].", ".$dbi['id'].")");
}
?>
<div class="box_top">Item Encontrado!</div>
<div class="box_middle">Parabéns! Você encontrou este item enquanto realizava sua caça!<div class="sep"></div>
	<div class="box_middle">Você é vip! sua chance de achar pergaminhos é 10%!<div class="sep"></div>
	<table width="100%" cellpadding="0" cellspacing="1">
    <tr class="table_dados" style="background:#323232;" onmouseover="style.background='#2C2C2C'" onmouseout="style.background='#323232'">
    	<td align="center" width="140"><img src="_img/equipamentos/<?php echo $dbi['imagem']; ?>.jpg" /></td>
        <td style="padding:5px;">
        	<b><?php echo $dbi['nome']; ?></b><br />
            <span class="sub2"><?php echo $dbi['descricao']; ?></span>
        </td>
  	</tr>
    </table>
</div>

<div class="box_bottom">
 <?php }}?>

<?php
if(date('Y-m-d H:i:s')>$db['vip']){
$chance=rand(1,100);
if(($chance>=95)){
if($chance>=5){
	$sqli=mysql_query("SELECT * FROM table_usaveis ORDER BY RAND() LIMIT 1");
	$dbi=mysql_fetch_assoc($sqli);
	mysql_query("INSERT INTO usaveis (usuarioid, itemid) VALUES (".$db['id'].", ".$dbi['id'].")");
}
?>
<div class="box_top">Item Encontrado!</div>
<div class="box_middle">Parabéns! Você encontrou este item enquanto realizava sua caça!<div class="sep"></div>
	<table width="100%" cellpadding="0" cellspacing="1">
    <tr class="table_dados" style="background:#323232;" onmouseover="style.background='#2C2C2C'" onmouseout="style.background='#323232'">
    	<td align="center" width="140"><img src="_img/equipamentos/<?php echo $dbi['imagem']; ?>.jpg" /></td>
        <td style="padding:5px;">
        	<b><?php echo $dbi['nome']; ?></b><br />
            <span class="sub2"><?php echo $dbi['descricao']; ?></span>
        </td>
  	</tr>
    </table>
</div>

<div class="box_bottom"></div>

 <?php }}
?>