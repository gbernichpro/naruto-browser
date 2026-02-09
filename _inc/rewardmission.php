<?php require_once('trava.php'); ?>
<audio loop="loop" autoplay="autoplay" hidden="true" controls="controls">
   <source src="_sons/vitoria.mp3" />
</audio>
<?php
$sqlr = mysql_query("SELECT yens, exp, logo, membros FROM table_missoes WHERE id='".$db['missao']."'");
$dbr = mysql_fetch_assoc($sqlr);
$yens = $dbr['yens'];
$exp = $dbr['exp'];
$atual = date('Y-m-d H:i:s');
if($db['missao']==0){ echo "<script>self.location='?p=home'</script>"; return; } else {
	if($atual<$db['missao_fim']){ echo "<script>self.location='?p=busymission'</script>"; return; } else {
		if($db['missao']>=1000){
			$sqlr = mysql_query("SELECT yens, exp, logo, membros FROM table_missoes WHERE id='".$db['missao']."'");
			$dbr = mysql_fetch_assoc($sqlr);
			mysql_query("UPDATE table_missoes SET membros=membros-1 WHERE id='".$db['missao']."'");
			mysql_query("UPDATE organizacoes SET exp=exp+".$dbr['logo']." WHERE id='".$db['orgid']."'");
        mysql_query("UPDATE membros SET missoes=missoes+1 WHERE usuarioid='".$db['id']."'");
   mysql_query("UPDATE membros SET missoes=missoes+1 WHERE usuarioid='".$db['id']."'");
            $yens = $dbr['yens'];
			$exp = $dbr['exp'];
		} else
		if($db['missao']>900){
			switch($db['missao']){
				case 901: $yens = $db['missao_tempo']*500; break;
				case 902: $yens = $db['missao_tempo']*1000; break;
				case 903: $yens = $db['missao_tempo']*1500; break;
				case 904: $yens = $db['missao_tempo']*2500; break;
				case 905: $yens = $db['missao_tempo']*5000; break;
				case 999: $yens = $db['missao_tempo']*100; break;
			}
			$exp = $db['missao_tempo'];
		}
		mysql_query("UPDATE usuarios SET missao=0, yens=yens+".$yens.", yens_fat=yens_fat+".$yens.", exp=exp+".$exp.", exptotal=exptotal+".$exp." WHERE id=".$db['id']);
		mysql_query("INSERT INTO verificador (usuarioid, hora_missao) VALUES (".$db['id'].", '".date('Y-m-d H:i:s')."')");
		mysql_query("UPDATE organizacoes SET exp=exp+".$dbr['logo']." WHERE id=".$db['orgid']);
		$db['yens'] = $db['yens']+$yens;
		$db['exp'] = $db['exp']+$exp;
		$db['exptotal'] = $db['exptotal']+$exp;
	}
}
?>
<div id="newlvl">
</div>
<div class="box_top">Missão Finalizada</div>
<div class="box_middle"><div class="aviso">Parabéns por conseguir terminar esta missão. Como recompensa, estamos lhe dando <b><?php echo number_format($yens,2,',','.'); ?> yens</b>. Além disso, você adquiriu <b><?php echo $exp; ?> ponto<?php if($exp>1) echo 's'; ?> de experiência</b>.<?php if(isset($dbr['logo'])) echo ' Seu clã também recebeu <b>'.$dbr['logo'].' pontos</b> de reputação pelo término da missão.'; ?>
    </div>
</div>
<div class="box_bottom"></div>

<?php
@mysql_free_result($sqlm);
?>
