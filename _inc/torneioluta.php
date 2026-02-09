<?php require_once('trava.php'); ?>
<?php require_once('verificar.php'); ?>
<?php require_once('funcoes.php'); ?>
<?php
$idninja = antiinjection($_GET['id']);
$_SESSION['errobot']=0;
if(!isset($idninja)){ echo "<script>self.location='?p=home'</script>"; return; }
if($idninja==$db['id']){ echo "<script>self.location='?p=torneio&msg=1'</script>"; return; }
$sqli=mysql_query("SELECT u.id, u.usuario, u.yens, u.yens_fat, u.nivel, u.orgid, u.energia, u.score , u.energiamax,u.torneio_eliminado,u.torneio_score,u.torneio, u.taijutsu, u.ninjutsu, u.genjutsu, u.personagem, u.avatar, u.renegado, u.vila, u.doujutsu, u.doujutsu_nivel, u.doujutsu_exp, u.doujutsu_expmax, u.exp, u.expmax, u.vip, u.missao, u.loginip, u.tipo, o.nivel orgnivel , sum(i.taijutsu) cla_taijutsu, sum(i.ninjutsu) cla_ninjutsu, sum(i.genjutsu) cla_genjutsu FROM usuarios u LEFT OUTER JOIN organizacoes o ON u.orgid=o.id  LEFT OUTER JOIN clas_investimentos i ON u.orgid=i.orgid WHERE u.id='".$idninja."'");
$dbi=mysql_fetch_assoc($sqli);
$sqlv = mysql_query("SELECT data FROM gm_guerracla_relatorios WHERE usuarioid=".$db['id']." AND inimigoid=".$dbi['id']." ORDER BY id DESC LIMIT 1");
$dbv=@mysql_fetch_assoc($sqlv);
$sqlv=mysql_query("SELECT data FROM relatorios WHERE usuarioid=".$db['id']." AND inimigoid=".$dbi['id']." ORDER BY id DESC LIMIT 1");
$dbv=@mysql_fetch_assoc($sqlv);
if($dbi['torneio_eliminado']=='666'){ echo "<script>self.location='?p=torneio&msg=2'</script>"; return; }
if($dbi['torneio']=='nao'){ echo "<script>self.location='?p=torneio&msg=3'</script>"; return; }
if($db['torneio_eliminado']=='666'){ echo "<script>self.location='?p=torneio&msg=4'</script>"; return; }
if($db['torneio']=='nao'){ echo "<script>self.location='?p=torneio&msg=5'</script>"; return; }
$soma=mktime(date('H')-12, date('i'), date('s'));
$penalidade=date('Y-m-d H:i:s',$soma);
if($penalidade<$dbv['data']){ echo "<script>self.location='?p=hunt&msg=9'</script>"; return; }
$sqlv=mysql_query("SELECT data FROM relatorios WHERE usuarioid=".$dbi['id']." OR inimigoid=".$dbi['id']." ORDER BY id DESC LIMIT 1");
$dbv=@mysql_fetch_assoc($sqlv);
$soma=mktime(date('H'), date('i')-30, date('s'));
$penalidade=date('Y-m-d H:i:s',$soma);
if($dbi['tipo']=='player'){
	if($penalidade<$dbv['data']){ echo "<script>self.location='?p=hunt&msg=8'</script>"; return; }
}
if($dbi['missao']==999){ echo "<script>self.location='?p=hunt&msg=10'</script>"; return; }
require_once('verifica_nivelatk.php');
if(mysql_num_rows($sqli)==0){ echo "<script>self.location='?p=hunt&msg=1'</script>"; return; }
/*
if($dbi['tipodeconta']=='admin'){ echo "<script>self.location='?p=hunt&msg=22'</script>"; return; }
if($db ['tipodeconta']=='admin'){ echo "<script>self.location='?p=hunt&msg=20'</script>"; return; }
*/
//usuario 1
if($dbi['energia']<25){ echo "<script>self.location='?p=hunt&msg=2'</script>"; return; }
if($db['energia']<25){ echo "<script>self.location='?p=hunt&msg=13'</script>"; return; }
if($dbi['avatar']==0){ echo "<script>self.location='?p=hunt&msg=12'</script>"; return; }
if(($db['doujutsu']==2)&&($db['doujutsu_nivel']<10)){ $txtdoujutsu1='Byakugan'; $addtai1=round($db['taijutsu']*($db['doujutsu_nivel']/50)); } else $addtai1=0;
if(($db['doujutsu']==2)&&($db['doujutsu_nivel']>=10)){ $txtdoujutsu1='Byakugan'; $addtai1=round($db['taijutsu']*($db['doujutsu_nivel']/16)); } else $addtai1=0;
if(($db['doujutsu']==3)&&($db['doujutsu_nivel']<10)){ $txtdoujutsu1='Rinnegan'; $addnin1=round($db['ninjutsu']*($db['doujutsu_nivel']/50)); } else $addnin1=0;
if(($db['doujutsu']==3)&&($db['doujutsu_nivel']>=10)){ $txtdoujutsu1='Rinnegan'; $addnin1=round($db['ninjutsu']*($db['doujutsu_nivel']/16)); } else $addnin1=0;
if($db['doujutsu']==5){ $txtdoujutsu1='Fuumetsu_Mangekyou_Sharingan'; $addgen1=round($db['genjutsu']*($db['doujutsu_nivel']/20)); } else $addgen1=0;
if($db['doujutsu']==5){ $txtdoujutsu1='Fuumetsu_Mangekyou_Sharingan'; $addtai1=round($db['taijutsu']*($db['doujutsu_nivel']/20)); } else $addgen1=0;
if($db['doujutsu']==5){ $txtdoujutsu1='Fuumetsu_Mangekyou_Sharingan'; $addnin1=round($db['ninjutsu']*($db['doujutsu_nivel']/20)); } else $addgen1=0;
if($db['doujutsu']==1){ $txtdoujutsu1='Sharingan'; $addgendou=round($db['genjutsu']*($db['doujutsu_nivel']/50)); } else $addgendou=0;
if($db['doujutsu']==4){ $txtdoujutsu1='Mangekyou_Sharingan'; $addmange=round($db['genjutsu']*($db['doujutsu_nivel']/20)); } else $addmange=0;
$addgendou=$addgendou;
$addmange=$addmange;
//usuario 2
if(($dbi['doujutsu']==2)&&($dbi['doujutsu_nivel']<10)){ $txtdoujutsu2='Byakugan'; $addtai2=round($dbi['taijutsu']*($dbi['doujutsu_nivel']/50)); } else $addtai2=0;
if(($dbi['doujutsu']==2)&&($dbi['doujutsu_nivel']>=10)){ $txtdoujutsu2='Byakugan'; $addtai2=round($dbi['taijutsu']*($dbi['doujutsu_nivel']/16)); } else $addtai2=0;
if(($dbi['doujutsu']==3)&&($dbi['doujutsu_nivel']<10)){ $txtdoujutsu2='Rinnegan'; $addnin2=round($dbi['ninjutsu']*($dbi['doujutsu_nivel']/50)); } else $addnin2=0;
if(($dbi['doujutsu']==3)&&($dbi['doujutsu_nivel']>=10)){ $txtdoujutsu2='Rinnegan'; $addnin2=round($dbi['ninjutsu']*($dbi['doujutsu_nivel']/16)); } else $addnin2=0;
if($dbi['doujutsu']==5){ $txtdoujutsu2='Fuumetsu_Mangekyou_Sharingan'; $addgen2=round($dbi['genjutsu']*($dbi['doujutsu_nivel']/20)); } else $addgen2=0;
if($dbi['doujutsu']==5){ $txtdoujutsu2='Fuumetsu_Mangekyou_Sharingan'; $addtai2=round($dbi['taijutsu']*($dbi['doujutsu_nivel']/20)); } else $addgen2=0;
if($dbi['doujutsu']==5){ $txtdoujutsu2='Fuumetsu_Mangekyou_Sharingan'; $addnin2=round($dbi['ninjutsu']*($dbi['doujutsu_nivel']/20)); } else $addgen2=0;
if($dbi['doujutsu']==1){ $txtdoujutsu1='Sharingan'; $addgendou2=round($dbi['genjutsu']*($dbi['doujutsu_nivel']/50)); } else $addgendou2=0;
if($dbi['doujutsu']==4){ $txtdoujutsu1='Mangekyou_Sharingan'; $addmange2=round($dbi['genjutsu']*($dbi['doujutsu_nivel']/20)); } else $addmange2=0;
$addgendou2=$addgendou2;
$addmange2=$addmange2;

$sqlj=mysql_query("SELECT j.nivel, t.id, t.nome, t.forca, t.texto FROM jutsus j LEFT OUTER JOIN table_jutsus t ON j.jutsu=t.id WHERE j.usuarioid=".$db['id']." AND j.status='ativo' ORDER BY RAND()");
if(mysql_num_rows($sqlj)>0){
	$i=1;
	while($dbj=mysql_fetch_array($sqlj)){
		$idjutsu1[$i]=$dbj['id'];
		$nomejutsu1[$i]=$dbj['nome'];
		$forcajutsu1[$i]=$dbj['forca'];
		$niveljutsu1[$i]=$dbj['nivel'];
		$textojutsu1[$i]=$dbj['texto'];
		$i++;
	}
}

$sqlj2=mysql_query("SELECT j.nivel, t.id, t.nome, t.forca, t.texto FROM jutsus j LEFT OUTER JOIN table_jutsus t ON j.jutsu=t.id WHERE j.usuarioid=".$dbi['id']." AND j.status='ativo' ORDER BY RAND()");
if(mysql_num_rows($sqlj2)>0){
	$i=1;
	while($dbj2=mysql_fetch_array($sqlj2)){
		$idjutsu2[$i]=$dbj2['id'];
		$nomejutsu2[$i]=$dbj2['nome'];
		$forcajutsu2[$i]=$dbj2['forca'];
		$niveljutsu2[$i]=$dbj2['nivel'];
		$textojutsu2[$i]=$dbj2['texto'];
		$i++;
	}
}

$maxj1=mysql_num_rows($sqlj);
$maxj2=mysql_num_rows($sqlj2);
/*do{
	echo $dbj['nome'].'.......';
} while($dbj=mysql_fetch_assoc($sqlj));*/
$eu=mysql_fetch_assoc(mysql_query("SELECT * FROM usuarios WHERE id=".$db['id']));
$ele=mysql_fetch_assoc(mysql_query("SELECT * FROM usuarios WHERE id=".$dbi['id']));

$idrank=mysql_query("SELECT * FROM nal_torneio ORDER BY id DESC LIMIT 1");
for ($s=0;$s<mysql_num_rows($idrank);$s++){
$sei=mysql_fetch_assoc($idrank);
$idinwar=$sei['id'];
}
?>
<?php
switch($db['vila']){
	case 1: $vila='folha'; if($db['renegado']=='sim') $txtvila='Akatsuki (Vila da Folha)'; else $txtvila='Vila da Folha'; break;
	case 2: $vila='areia'; if($db['renegado']=='sim') $txtvila='Akatsuki (Vila da Areia)'; else $txtvila='Vila da Areia'; break;
	case 3: $vila='som'; if($db['renegado']=='sim') $txtvila='Akatsuki (Vila do Som)'; else $txtvila='Vila do Som'; break;
	case 4: $vila='chuva'; if($db['renegado']=='sim') $txtvila='Akatsuki (Vila da Chuva)'; else $txtvila='Vila da Chuva'; break;
	case 5: $vila='nuvem'; if($db['renegado']=='sim') $txtvila='Akatsuki (Vila da Nuvem)'; else $txtvila='Vila da Nuvem'; break;
	case 6: $vila='nevoa'; if($db['renegado']=='sim') $txtvila='Akatsuki (Vila da Névoa)'; else $txtvila='Vila da Névoa'; break;
	case 8: $vila='pedra'; if($db['renegado']=='sim') $txtvila='Akatsuki (Vila da Pedra)'; else $txtvila='Vila da Pedra'; break;
	case 99: $vila='folha'; $txtvila='Vila da Pedra'; break;
} ?>
<?php
switch($dbi['vila']){
	case 1: $vilai='folha'; if($dbi['renegado']=='sim') $txtvilai='Akatsuki (Vila da Folha)'; else $txtvilai='Vila da Folha'; break;
	case 2: $vilai='areia'; if($dbi['renegado']=='sim') $txtvilai='Akatsuki (Vila da Areia)'; else $txtvilai='Vila da Areia'; break;
	case 3: $vilai='som'; if($dbi['renegado']=='sim') $txtvilai='Akatsuki (Vila do Som)'; else $txtvilai='Vila do Som'; break;
	case 4: $vilai='chuva'; if($dbi['renegado']=='sim') $txtvilai='Akatsuki (Vila da Chuva)'; else $txtvilai='Vila da Chuva'; break;
	case 5: $vilai='nuvem'; if($dbi['renegado']=='sim') $txtvilai='Akatsuki (Vila da Nuvem)'; else $txtvilai='Vila da Nuvem'; break;
	case 6: $vilai='nevoa'; if($dbi['renegado']=='sim') $txtvilai='Akatsuki (Vila da Névoa)'; else $txtvilai='Vila da Névoa'; break;
	case 8: $vilai='pedra'; if($dbi['renegado']=='sim') $txtvilai='Akatsuki (Vila da Pedra)'; else $txtvilai='Vila da Pedra'; break;
	case 99: $vilai='folha'; $txtvilai='Vila da Pedra'; break;
} ?>
<?php
$tai=($db['taijutsu']+$addtai1+$addtai3+$addtai5+$adicionatai).','.($dbi['taijutsu']+$addtai2+$addtai4+$addtai6+$adicionatai2);
$nin=($db['ninjutsu']+$addnin1+$addnin3+$addnin5).','.($dbi['ninjutsu']+$addnin2+$addnin4+$addnin6);
$gen=($db['genjutsu']+$addgen1+$addgen3+$addgen5+$addgendou+$addmange).','.($dbi['genjutsu']+$addgen2+$addgen4+$addgen6+$addgendou2+$addmange2);
$ene=$db['energia'].' / '.$db['energiamax'].','.$dbi['energia'].' / '.$dbi['energiamax'];










?>
<div class="box_top"><?php echo $db['usuario']; ?> x <?php echo $dbi['usuario']; ?></div>
<div class="box_middle">
	<table width="100%" cellpadding="0" cellspacing="0">
    	<tr>
        	<td colspan="2" align="center"><b><?php echo $db['usuario']; ?></b></td>
            <td align="center">&nbsp;</td>
            <td colspan="2" align="center"><b><?php echo $dbi['usuario']; ?></b></td>
        </tr>
        <tr>
        	<td colspan="2" align="center"><div class="sep"></div></td>
            <td align="center"></td>
            <td colspan="2" align="center"><div class="sep"></div></td>
        </tr>
    	<tr>
        	<td colspan="2" align="center" width="42%" style="background:url(_img/personagens/no_avatar.jpg) no-repeat center #444444;"><img src="_img/personagens/<?php echo $db['personagem']; ?>/<?php echo $db['avatar']; ?>.jpg" onmouseover="Tip('<div class=tooltip><?php echo fpersonagem($db['personagem']); ?></div>')" onmouseout="UnTip()" /></td>
            <td align="center" width="16%"><img src="_img/versus.jpg" /></td>
            <td colspan="2" align="center" width="42%" style="background:url(_img/personagens/no_avatar.jpg) no-repeat center #444444;"><img src="_img/personagens/<?php echo $dbi['personagem']; ?>/<?php echo $dbi['avatar']; ?>.jpg"onmouseover="Tip('<div class=t wiooltip><?php echo fpersonagem($dbi['personagem']); ?></div>')" onmouseout="UnTip()" /></td>
        </tr>
        <tr>
        	<td colspan="2" bgcolor="#444444" align="center"><img src="_img/vilas/<?php if($db['renegado']=='sim') echo 'akatsuki_'; ?><?php echo $vila; ?>.jpg" onmouseover="Tip('<div class=tooltip><?php echo $txtvila; ?></div>');" onmouseout="UnTip()" /></td>
            <td align="center">&nbsp;</td>
            <td colspan="2" bgcolor="#444444" align="center"><img src="_img/vilas/<?php if($dbi['renegado']=='sim') echo 'akatsuki_'; ?><?php echo $vilai; ?>.jpg" onmouseover="Tip('<div class=tooltip><?php echo $txtvilai; ?></div>');" onmouseout="UnTip()" /></td>
        </tr>
        <tr>
        	<td colspan="2" align="center"><div class="sep"></div></td>
            <td align="center"></td>
            <td colspan="2" align="center"><div class="sep"></div></td>
        </tr>
        <tr style="height:17px;">
        	<td width="20%" align="right" style="background:#323232;padding-right:10px;">Nível:</td>
            <td width="22%" style="background:#323232;"><?php if($db['renegado']=='sim') echo 'Nukenin'; else rankNinja($db['nivel']); ?> <b>[Nível <?php echo $db['nivel']; ?>]</b></td>
            <td width="16%"></td>
            <td width="20%" align="right" style="background:#323232;padding-right:10px;">Nível:</td>
            <td width="22%" style="background:#323232;"><?php if($dbi['renegado']=='sim') echo 'Nukenin'; else rankNinja($dbi['nivel']); ?> <b>[Nível <?php echo $dbi['nivel']; ?>]</b></td>
        </tr>
        <tr style="height:17px;">
        	<td align="right" style="background:#2C2C2C;padding-right:10px;">Taijutsu:</td>

            <td style="background:#2C2C2C;"><b>[<?php echo $db['taijutsu']; ?>]</b> <?php if($addtai1>0) echo '+'.$addtai1; ?> <?php if($sharingan>0) echo '+'.$sharingan; ?> <?php if($addtai3>0) echo '+'.$addtai3; ?> <?php if($addtai5>0) echo '+'.$addtai5; ?></td>
            <td></td>

            <td align="right" style="background:#2C2C2C;padding-right:10px;">Taijutsu:</td>
            <td style="background:#2C2C2C;"><b>[<?php echo $dbi['taijutsu']; ?>]</b> <?php if($addtai2>0) echo '+'.$addtai2; ?> <?php if($addtai4>0) echo '+'.$addtai4; ?> <?php if($addtai6>0) echo '+'.$addtai6; ?></td>
        </tr>
        <tr style="height:17px;">
        	<td align="right" style="background:#323232;padding-right:10px;">Ninjutsu:</td>
            <td style="background:#323232;"><b>[<?php echo $db['ninjutsu']; ?>]</b> <?php if($addnin1>0) echo '+'.$addnin1; ?> <?php if($addnin3>0) echo '+'.$addnin3; ?> <?php if($addnin5>0) echo '+'.$addnin5; ?></td>
            <td></td>
            <td align="right" style="background:#323232;padding-right:10px;">Ninjutsu:</td>
            <td style="background:#323232;"><b>[<?php echo $dbi['ninjutsu']; ?>]</b> <?php if($addnin2>0) echo '+'.$addnin2; ?> <?php if($addnin4>0) echo '+'.$addnin4; ?> <?php if($addnin6>0) echo '+'.$addnin6; ?></td>
        </tr>
        <tr style="height:17px;">
        	<td align="right" style="background:#2C2C2C;padding-right:10px;">Genjutsu:</td>
            <td style="background:#2C2C2C;"><b>[<?php echo $db['genjutsu']; ?>]</b> <?php if($addgen1>0) echo '+'.$addgen1; ?> <?php if($addmange>0) echo '+'.$addmange; ?> <?php if($addgendou>0) echo '+'.$addgendou; ?> <?php if($addgen3>0) echo '+'.$addgen3; ?> <?php if($addgen5>0) echo '+'.$addgen5; ?></td>
            <td></td>
            <td align="right" style="background:#2C2C2C;padding-right:10px;">Genjutsu:</td>
            <td style="background:#2C2C2C;"><b>[<?php echo $dbi['genjutsu']; ?>]</b> <?php if($addgen2>0) echo '+'.$addgen2; ?> <?php if($addmange2>0) echo '+'.$addmange2; ?> <?php if($addgendou2>0) echo '+'.$addgendou2; ?>  <?php if($addgen4>0) echo '+'.$addgen4; ?> <?php if($addgen6>0) echo '+'.$addgen6; ?></td>
        </tr>
        <tr style="height:17px;">
        	<td align="right" style="background:#323232;padding-right:10px;">Experiência:</td>
            <td style="background:#323232;"><b>[<?php echo $db['exp']; ?> / <?php echo $db['expmax']; ?>]</b></td>
            <td></td>
            <td align="right" style="background:#323232;padding-right:10px;">Experiência:</td>
            <td style="background:#323232;"><b>[<?php echo $dbi['exp']; ?> / <?php echo $dbi['expmax']; ?>]</b></td>
        </tr>
        <tr style="height:17px;">
        	<td align="right" style="background:#2C2C2C;padding-right:10px;">Energia:</td>
            <td style="background:#2C2C2C;"><b>[<?php echo $db['energia']; ?> / <?php echo $db['energiamax']; ?>]</b></td>
            <td></td>
            <td align="right" style="background:#2C2C2C;padding-right:10px;">Energia:</td>
            <td style="background:#2C2C2C;"><b>[<?php echo $dbi['energia']; ?> / <?php echo $dbi['energiamax']; ?>]</b></td>
        </tr>
        <tr>
        	<td colspan="2" align="center"><div class="sep"></div></td>
            <td align="center"></td>
            <td colspan="2" align="center"><div class="sep"></div></td>
        </tr>

            <td colspan="2" align="center"><div class="sep"></div></td>
            <td align="center"></td>
            <td colspan="2" align="center"><div class="sep"></div></td>
        </tr>
        <tr>
        	<td colspan="2" bgcolor="#323232" style="text-align:center"><?php if($db['doujutsu']==0) echo 'Nenhum doujutsu.'; else { ?><img src="_img/doujutsus/<?php echo strtolower($txtdoujutsu1); ?>.jpg" onmouseover="Tip('<div class=tooltip><b><?php echo $txtdoujutsu1; ?></b><br />Nível <?php echo $db['doujutsu_nivel']; ?></div>')" onmouseout="UnTip()" width="200" /><?php } ?></td>
            <td></td>
            <td colspan="2" bgcolor="#323232" style="text-align:center"><?php if($dbi['doujutsu']==0) echo 'Nenhum doujutsu.'; else { ?><img src="_img/doujutsus/<?php echo strtolower($txtdoujutsu2); ?>.jpg" onmouseover="Tip('<div class=tooltip><b><?php echo $txtdoujutsu2; ?></b><br />Nível <?php echo $dbi['doujutsu_nivel']; ?></div>')" onmouseout="UnTip()" width="200" /><?php } ?></td>
        </tr>
        <tr>
        	<td colspan="2" align="center"><div class="sep"></div></td>
            <td align="center"></td>
            <td colspan="2" align="center"><div class="sep"></div></td>
        </tr>
    </table>
</div>
<div class="box_bottom"></div>

<div class="box_top">Relatório Detalhado do Combate</div>
<div class="box_middle">
	<table width="100%" cellpadding="0" cellspacing="0">
    	<?php



		$relatoriofinal='';
		$db['taijutsu']=($db['taijutsu']+$addtai1+$addtai3+$addtai5+$adicionatai);
		$db['ninjutsu']=($db['ninjutsu']+$addnin1+$addnin3+$addnin5);
		$db['genjutsu']=($db['genjutsu']+$addgen1+$addgen3+$addgen5+$addgendou+$addmange);
		$dbi['taijutsu']=($dbi['taijutsu']+$addtai2+$addtai4+$addtai6+$adicionatai2);
		$dbi['ninjutsu']=($dbi['ninjutsu']+$addnin2+$addnin4+$addnin6);
		$dbi['genjutsu']=($dbi['genjutsu']+$addgen2+$addgen4+$addgen6+$addgendou2+$addmange2);
		$i=1;
		$jutsu1=1;
		$jutsu2=1;
		$perdido1=0;
		$dano1=0;
		do{
		?>
        <tr>
        	<td colspan="2"><div class="sep"></div></td>
        </tr>
        <?php
		if($i%2){
			if($jutsu1<=$maxj1){
				$icone='jutsus/'.$idjutsu1[$jutsu1].'.jpg';
				$dano=danojutsu($db['ninjutsu'],$dbi['genjutsu'],($forcajutsu1[$jutsu1]+(floor(($niveljutsu1[$jutsu1]-1)*5))));
				if($dano==1) $msgdano='que perdeu <b>'.floor($dano).' ponto de energia</b>.';
				else if($dano==0) $msgdano='que conseguiu desviar-se.';
				else $msgdano='que perdeu <b>'.floor($dano).' pontos de energia</b>.';
				$msg=str_replace('$player1','<b>'.$db['usuario'].'</b>',$textojutsu1[$jutsu1]);
				$msg=str_replace('$player2','<b>'.$dbi['usuario'].'</b>',$msg);
				$msg=str_replace('$jutsu',$nomejutsu1[$jutsu1],$msg);
				$msg=str_replace('$dano',$msgdano,$msg);
				$jutsu1++;
			} else {
				$icone='';
               $dano=dano($db['taijutsu'],$dbi['genjutsu']);
				if($dano>0){
					$msg='<b>'.$db['usuario'].'</b> atacou '.$dbi['usuario'];
					if(rand(1,3)==1){
						if($arma1==1) $msg.=' com sua arma.'; else $msg.=' com danos físicos.';
					} else $msg.=' com danos físicos.';
					$msg.='<br />'.$dbi['usuario'].' perdeu <b>'.floor($dano).' pontos de energia</b>.';
				} else {
					$msg='<b>'.$db['usuario'].'</b> atacou '.$dbi['usuario'].', mas o inimigo desviou.';
				}
			}
			$dano1=$dano1+floor($dano);
			$dbi['energia']=$dbi['energia']-floor($dano);
		} else {
			if($jutsu2<=$maxj2){
				$icone='jutsus/'.$idjutsu2[$jutsu2].'.jpg';
				$dano=danojutsu($dbi['ninjutsu'],$db['genjutsu'],($forcajutsu2[$jutsu2]+(floor(($niveljutsu2[$jutsu2]-1)*5))));
				if($dano==1) $msgdano='que perdeu <b>'.floor($dano).' ponto de energia</b>.';
				else if($dano==0) $msgdano='que conseguiu desviar-se.';
				else $msgdano='que perdeu <b>'.floor($dano).' pontos de energia</b>.';
				$msg=str_replace('$player1','<b>'.$dbi['usuario'].'</b>',$textojutsu2[$jutsu2]);
				$msg=str_replace('$player2','<b>'.$db['usuario'].'</b>',$msg);
				$msg=str_replace('$jutsu',$nomejutsu2[$jutsu2],$msg);
				$msg=str_replace('$dano',$msgdano,$msg);
				$jutsu2++;
			} else {
				$icone='';
				$dano=dano($dbi['taijutsu'],$db['genjutsu']);
				if($dano>0){
					$msg='<b>'.$dbi['usuario'].'</b> atacou '.$db['usuario'];
					if(rand(1,3)==1){
						if($arma2==1) $msg.=' com sua arma.'; else $msg.=' com danos físicos.';
					} else $msg.=' com danos físicos.';
					$msg.='<br />'.$db['usuario'].' perdeu <b>'.floor($dano).' pontos de energia</b>.';
				} else {
					$msg='<b>'.$dbi['usuario'].'</b> atacou '.$db['usuario'].', mas o inimigo desviou.';
				}
			}
			$perdido1=$perdido1+floor($dano);
			$db['energia']=$db['energia']-floor($dano);
		}
		?>
    	<tr>
        	<td <?php if($icone=='') echo 'colspan="2" '; ?>style="padding-left:5px;padding-right:15px;<?php if($i%2) echo 'background:url(_img/gradient.jpg) repeat-y;'; ?>"><?php echo $msg; $relatoriofinal.=$msg.'<div class="sep"></div>'; ?></td>
            <?php if($icone<>''){ ?><td><img src="_img/<?php echo $icone; ?>" /></td><?php } ?>
        </tr>
        <?php
        $i++;
		} while(($db['energia']>15)and($dbi['energia']>15)); ?>
    </table>
</div>
<div class="box_bottom"></div>

<div class="box_top">Resultado do Combate</div>
<div class="box_middle">
	<?php
	$a=0;
	if($dbi['energia']<15) $a=1; else
	if($db['energia']<15) $a=2; else
	if($dano1>$perdido1) $a=1; else
	if($perdido1>$dano1) $a=2; else
	$a=3;
	if(date('Y-m-d')<='2010-04-04'){
		if(date('Y-m-d H:i:s')<$db['vip']){ $mpen=3; $spen=5; } else { $mpen=10; $spen=0; }
	} else {
		if(date('Y-m-d H:i:s')<$db['vip']){ $mpen=2; $spen=0; } else { $mpen=2; $spen=0; }
	}
	if($a==1){
		$vencedorid=$db['id'];
		$vencedor=$db['usuario'];
		$perdedor=$dbi['usuario'];
		if(date('Y-m-d H:i:s')<$db['vip'])
			$yens=floor($dbi['yens']*0.15);
		else
			$yens=floor($dbi['yens']*0.1);
		if($db['nivel']>$dbi['nivel']) $exp_1=1; else
		if($db['nivel']==$dbi['nivel']) $exp_1=2; else
		if($db['nivel']<$dbi['nivel']) $exp_1=3;
		$exp_2=4-($exp_1);
		if(($db['energia']-$perdido1)<0) $energia_1=0; else $energia_1=$db['energia']-$perdido1;
		if(($dbi['energia']-$dano1)<0) $energia_2=0; else $energia_2=$dbi['energia']-$dano1;
		if($db['doujutsu']>0) $expd_1=$exp_1; else $expd_1=0;
		if($dbi['doujutsu']>0) $expd_2=$exp_2; else $expd_2=0;
		//if(date('Y-m-d H:i:s')<$db['vip']) $pen=5; else $pen=10;
		$soma=mktime(date('H'), date('i')+($mpen), date('s')+($spen));
		$penalidade=date('Y-m-d H:i:s',$soma);
		if($db['energia']<0) $db['energia']=0;
		if($dbi['energia']<0) $dbi['energia']=0;
		if($dbi['score']<0) $dbi['score']=0;
		if($db['score']<0) $dbi['score']=0;
		mysql_query("UPDATE usuarios SET yens=yens+$yens, yens_fat=yens_fat+$yens, exp=exp+$exp_1, exptotal=exptotal+$exp_1, penalidade_fim='$penalidade', vitorias=vitorias+1, score=score+1, quest_vitorias=quest_vitorias+1, batalhas=batalhas+1, doujutsu_exp=doujutsu_exp+$expd_1, energia=".$db['energia']." WHERE id=".$db['id']);
        mysql_query("UPDATE usuarios SET yens=yens-$yens, yens_perd=yens_perd+$yens, exp=exp+$exp_2, exptotal=exptotal+$exp_2, derrotas=derrotas+1, score=score-1, batalhas=batalhas+1, doujutsu_exp=doujutsu_exp+$expd_2, energia=".$dbi['energia']." WHERE id=".$dbi['id']);

       	// GUERRA DE VILA //
		if ($eu['torneio'] == "sim" and $ele['torneio'] == "sim" and $ssj2=="aberto"){
		mysql_query("update usuarios set torneio_score=torneio_score+1 where id=".$eu['id']);
		mysql_query("update usuarios set torneio_score=torneio_score-1 where id=".$ele['id']);
        $torneio_exp=$expe;
        $sqlpnc=mysql_query("SELECT * FROM usuarios WHERE torneio = 'sim' and torneio_eliminado='666'");

        $countpnc=mysql_num_rows($sqlpnc);

        $mult=$countpnc+1;

        $newtourexp=ceil(($torneio_exp) * ($mult));

        $msg='Você foi o '.$mult.'° eliminado da arena por '.$eu['usuario'].' e você recebeu '.$newtourexp.' de experiencia.';
        mysql_query("INSERT INTO mensagens (data,origem,destino,assunto,msg) VALUES ('".date('Y-m-d H:i:s')."','0',".$ele['id'].",'Você foi eliminado do torneio','".$msg."')") or die(mysql_error());
        mysql_query("UPDATE usuarios SET torneio_eliminado='666' , exp=exp+'$newtourexp' ,exptotal=exptotal+'$newtourexp' WHERE id='".$ele['id']."'");

         //inicio code bucetenha
$count222=mysql_query("SELECT count(torneio) as total FROM usuarios WHERE torneio = 'sim' and torneio_eliminado=0");
$ninjas222=mysql_fetch_array($count222);
$countinscritos22=$ninjas222['total'];

if($countinscritos22<3){
 //dar premio ao  vencedor
$sqlTeste=mysql_query("SELECT * FROM usuarios where torneio='sim' and torneio_eliminado='0' ORDER BY torneio_score DESC");
$i++;
while($i < $var=mysql_fetch_assoc($sqlTeste)){

if($var['id']==$eu['id']){







$idinwar666=$var['id'];
$core=$var['torneio_score'];
$idpradar=$var['id'];
$arena_exp=$expe;
//exp para o top1
$sqlexp=mysql_query("SELECT * FROM usuarios WHERE torneio = 'sim' and torneio_eliminado='666'");
$countpncexp=mysql_num_rows($sqlexp);
$multiplica=$countpncexp+1;
$novo=ceil(($arena_exp) * ($multiplica));
mysql_query("UPDATE usuarios SET exp=exp+'$novo' ,exptotal=exptotal+'$novo' WHERE id='".$idpradar."'");
//fim
mysql_query("update usuarios set yens=yens+'$premioinwar2' , yens_fat=yens_fat+'$premioinwar2' where id=".$var['id']);
$msg='Parabêns você foi campeão da  '.$idinwar2.'° Arena do naruto a lenda , Você recebeu um premio de'.$premioinwar2.' yens pela conquista e '.$novo.' de experiencia.(Junto com uma medalha de ouro)';
mysql_query("INSERT INTO mensagens (data,origem,destino,assunto,msg) VALUES ('".date('Y-m-d H:i:s')."','0',".$var['id'].",'Campeão do ".$idinwar2."° Torneio','".$msg."')") or die(mysql_error());
//inserir medalha
mysql_query("INSERT INTO medalhas (usuarioid,medalha,descricao,tipo) Values ('".$var['id']."','Arena','Foi campeão da ".$idinwar2."° Arena do Naruto a lenda','3')");
mysql_query("update usuarios set torneio_score=0,torneio_eliminado=0 ,torneio='nao' where torneio='sim'");
mysql_query("update nal_torneio set vencedor='$idinwar666', scorevenc='$core' where id=".$idinwar2);
mysql_query("update nal_torneio set status='fim' where id=".$idinwar2);
}
$i++;
}
}                   //fim da bcta


		}
		// FIM //


	   }else
	if($a==2){
		$vencedorid=$dbi['id'];
		$vencedor=$dbi['usuario'];
		$perdedor=$db['usuario'];
		if(date('Y-m-d H:i:s')<$dbi['vip'])
			$yens=floor($db['yens']*0.15);
		else
			$yens=floor($db['yens']*0.1);
		if($db['nivel']>$dbi['nivel']) $exp_1=1; else
		if($db['nivel']==$dbi['nivel']) $exp_1=2; else
		if($db['nivel']<$dbi['nivel']) $exp_1=3;
		$exp_2=4-$exp_1;
		if(($db['energia']-$perdido1)<0) $energia_1=0; else $energia_1=$db['energia']-$perdido1;
		if(($dbi['energia']-$dano1)<0) $energia_2=0; else $energia_2=$dbi['energia']-$dano1;
		if($db['doujutsu']>0) $expd_1=$exp_1; else $expd_1=0;
		if($dbi['doujutsu']>0) $expd_2=$exp_2; else $expd_2=0;
		//if(date('Y-m-d H:i:s')<$db['vip']) $pen=5; else $pen=10;
		$soma=mktime(date('H'), date('i')+($mpen), date('s')+($spen));
		$penalidade=date('Y-m-d H:i:s',$soma);
		if($db['energia']<0) $db['energia']=0;
		if($dbi['energia']<0) $dbi['energia']=0;
		mysql_query("UPDATE usuarios SET yens=yens-$yens, yens_perd=yens_perd+$yens, exp=exp+$exp_1, exptotal=exptotal+$exp_1, penalidade_fim='$penalidade', derrotas=derrotas+1, score=score-1, batalhas=batalhas+1, doujutsu_exp=doujutsu_exp+$expd_1, energia=".$db['energia']." WHERE id=".$db['id']);
		mysql_query("UPDATE usuarios SET yens=yens+$yens, yens_fat=yens_fat+$yens, exp=exp+$exp_2, exptotal=exptotal+$exp_2, vitorias=vitorias+1, score=score+1, quest_vitorias=quest_vitorias+1, batalhas=batalhas+1, doujutsu_exp=doujutsu_exp+$expd_2, energia=".$dbi['energia']." WHERE id=".$dbi['id']);

		// GUERRA DE VILA //
		if ($eu['torneio'] == "sim" and $ele['torneio'] == "sim" and $ssj2=="aberto"){
        $torneio_exp=$expe;
        $sqlpnc=mysql_query("SELECT * FROM usuarios WHERE torneio = 'sim' and torneio_eliminado='666'");

        $countpnc=mysql_num_rows($sqlpnc);

        $mult=$countpnc+1;

        $newtourexp=ceil(($torneio_exp) * ($mult));

        $msg='Você foi o '.$mult.'° eliminado da arena por '.$ele['usuario'].' e você recebeu '.$newtourexp.' de experiencia.';
        mysql_query("INSERT INTO mensagens (data,origem,destino,assunto,msg) VALUES ('".date('Y-m-d H:i:s')."','0',".$eu['id'].",'Você foi eliminado do torneio','".$msg."')") or die(mysql_error());
        mysql_query("UPDATE usuarios SET torneio_eliminado='666' , exp=exp+'$newtourexp' ,exptotal=exptotal+'$newtourexp' WHERE id='".$eu['id']."'");
        mysql_query("update usuarios set torneio_score=torneio_score-1 where id=".$eu['id']);
        mysql_query("update usuarios set torneio_score=torneio_score+1 where id=".$ele['id']);

	   //inicio code bucetenha
$count222=mysql_query("SELECT count(torneio) as total FROM usuarios WHERE torneio = 'sim' and torneio_eliminado=0");
$ninjas222=mysql_fetch_array($count222);
$countinscritos22=$ninjas222['total'];

if($countinscritos22<3){
 //dar premio ao  vencedor
$sqlTeste=mysql_query("SELECT * FROM usuarios where torneio='sim' and torneio_eliminado='0' ORDER BY torneio_score DESC");
$i++;
while($i < $var=mysql_fetch_assoc($sqlTeste)){

if($var['id']==$ele['id']){







$idinwar666=$var['id'];
$core=$var['torneio_score'];
$idpradar=$var['id'];
$arena_exp=$expe;
//exp para o top1
$sqlexp=mysql_query("SELECT * FROM usuarios WHERE torneio = 'sim' and torneio_eliminado='666'");
$countpncexp=mysql_num_rows($sqlexp);
$multiplica=$countpncexp+1;
$novo=ceil(($arena_exp) * ($multiplica));
mysql_query("UPDATE usuarios SET exp=exp+'$novo' ,exptotal=exptotal+'$novo' WHERE id='".$idpradar."'");
//fim
mysql_query("update usuarios set yens=yens+'$premioinwar2' , yens_fat=yens_fat+'$premioinwar2' where id=".$var['id']);
$msg='Parabêns você foi campeão da  '.$idinwar2.'° Arena do naruto a lenda , Você recebeu um premio de'.$premioinwar2.' yens pela conquista e '.$novo.' de experiencia.(Junto com uma medalha de ouro)';
mysql_query("INSERT INTO mensagens (data,origem,destino,assunto,msg) VALUES ('".date('Y-m-d H:i:s')."','0',".$var['id'].",'Campeão do ".$idinwar2."° Torneio','".$msg."')") or die(mysql_error());
//inserir medalha
mysql_query("INSERT INTO medalhas (usuarioid,medalha,descricao,tipo) Values ('".$var['id']."','Arena','Foi campeão da ".$idinwar2."° Arena do Naruto a lenda','3')");
mysql_query("update usuarios set torneio_score=0,torneio_eliminado=0 ,torneio='nao' where torneio='sim'");
mysql_query("update nal_torneio set vencedor='$idinwar666', scorevenc='$core' where id=".$idinwar2);
mysql_query("update nal_torneio set status='fim' where id=".$idinwar2);
}
$i++;
}
}                   //fim da bcta

  //

















		}
		// FIM //



	} else
	if($a==3){
		$vencedorid=0;
		$vencedor='Empate';
		$perdedor='Empate';
		$yens=0;
		$exp_1=1;
		$exp_2=1;
		if(($db['energia']-$perdido1)<0) $energia_1=0; else $energia_1=$db['energia']-$perdido1;
		if(($dbi['energia']-$dano1)<0) $energia_2=0; else $energia_2=$dbi['energia']-$dano1;
		if($db['doujutsu']>0) $expd_1=$exp_1; else $expd_1=0;
		if($dbi['doujutsu']>0) $expd_2=$exp_2; else $expd_2=0;
		//if(date('Y-m-d H:i:s')<$db['vip']) $pen=5; else $pen=10;
		$soma=mktime(date('H'), date('i')+($mpen), date('s')+($spen));
		$penalidade=date('Y-m-d H:i:s',$soma);
		if($db['energia']<0) $db['energia']=0;
		if($dbi['energia']<0) $dbi['energia']=0;

		mysql_query("UPDATE usuarios SET exp=exp+$exp_1, exptotal=exptotal+$exp_1, penalidade_fim='$penalidade', empates=empates+1, batalhas=batalhas+1, doujutsu_exp=doujutsu_exp+$expd_1, energia=".$db['energia']." WHERE id=".$db['id']);
		mysql_query("UPDATE usuarios SET exp=exp+$exp_2, exptotal=exptotal+$exp_2, empates=empates+1, batalhas=batalhas+1, doujutsu_exp=doujutsu_exp+$expd_2, energia=".$dbi['energia']." WHERE id=".$dbi['id']);

	}
	$exp=$exp_1.','.(4-$exp_2);
	$danos=$dano1.','.$perdido1;
	$reldoujutsu=$db['doujutsu'].','.$dbi['doujutsu'];
	$nivel=$db['nivel'].','.$dbi['nivel'];
	if($db['loginip']==$dbi['loginip']) $ip='sim'; else $ip='nao';
	if($dbi['tipo']=='player'){
		mysql_query("INSERT INTO gm_guerracla_relatorios (data, usuarioid, inimigoid, vencedor, exp, yens, taijutsu, ninjutsu, genjutsu, energia, equips1, equips2, equips3, equips4, equips5, equips6, equips7, equips8, danos, nivel, doujutsu, ip) VALUES ('".date('Y-m-d H:i:s')."', ".$db['id'].", ".$dbi['id'].", $vencedorid, '$exp', $yens, '$tai', '$nin', '$gen', '$ene', '$equips1', '$equips2', '$equips3', '$equips4', '$equips5', '$equips6', '$equips7', '$equips8', '$danos', '$nivel', '$reldoujutsu', '$ip')");
		$relid=mysql_insert_id();
		$fp=@fopen('reports/r'.$relid.'.txt','w');
		@fwrite($fp,$relatoriofinal);
		@fclose($fp);
	}
	$sqlw=mysql_query("SELECT id FROM verificador WHERE status='off' AND usuarioid=".$dbi['id']);
	$dbw=mysql_fetch_assoc($sqlw);
	if(mysql_num_rows($sqlw)>0){
		mysql_query("UPDATE verificador SET status='on', yens=".$yens.", hora_ataque='".date('Y-m-d H:i:s')."', inimigoid=".$db['id'].", hunt=".$_SESSION['hunt']." WHERE id=".$dbw['id']);
	}
	if($dbi['tipo']=='bot'){
		mysql_query("UPDATE usuarios SET penalidade='0000-00-00 00:00:00' WHERE id=".$dbi['id']);
	}
	mysql_query("UPDATE book SET ultimo='".date('Y-m-d H:i:s')."', yens=".$yens.", hoje=1 WHERE usuarioid=".$db['id']." AND inimigoid=".$dbi['id']);
	?>
	<table width="100%" cellpadding="0" cellspacing="0">
    	<tr style="background:url(_img/gradient.jpg) repeat-y;">
        	<td width="25%"><b><?php echo $db['usuario']; ?></b></td>
            <td>causou <?php echo $dano1; ?> pontos de dano</td>
            <td>perdeu <?php echo $perdido1; ?> pontos de energia</td>
        </tr>
        <tr>
        	<td colspan="3"><div class="sep"></div></td>
        </tr>
        <tr style="background:url(_img/gradient.jpg) repeat-y;">
        	<td><b><?php echo $dbi['usuario']; ?></b></td>
            <td>causou <?php echo $perdido1; ?> pontos de dano</td>
            <td>perdeu <?php echo $dano1; ?> pontos de energia</td>
        </tr>
        <tr>
        	<td colspan="3"><div class="sep"></div></td>
        </tr>
        <tr>
        	<td colspan="3"><div class="aviso"><b><?php echo $vencedor; ?></b> venceu o combate.<br /><span class="sub2"><?php echo $vencedor; ?> recebeu <?php echo number_format($yens,2,',','.'); ?> yen<?php if($yens>1) echo 's'; ?> e adquiriu <?php echo $exp_1; ?> ponto<?php if($exp_1>1) echo 's'; ?> de experiência;<br /><?php echo $perdedor; ?> apenas adquiriu <?php echo $exp_2; ?> ponto<?php if($exp_2>1) echo 's'; ?> de experiência.<?php /*<br /><div id="atk" style="text-align:left;"><a href="javascript:carregaAjax('atk','search_atk.php?value=<?php echo base64_encode($yens); ?>&id=<?php echo base64_encode($db['id']); ?>&name=<?php echo base64_encode($db['usuario']); ?>','n');">Postar em minhas atualizações.</a></div>*/ ?></span></div></td>
        </tr>
    </table>
</div>
<div class="box_bottom"></div>
<?php
@mysql_free_result($sqli);
@mysql_free_result($sqlc);
@mysql_free_result($sqlc2);
@mysql_free_result($sqlj);
@mysql_free_result($sqlj2);
?>
