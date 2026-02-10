<?php require_once('trava.php'); ?>
<?php require_once('verificar.php'); ?>
<?php require_once('funcoes.php'); ?>
<?php
if(!isset($_GET['bot'])){ echo "<script>self.location='?p=home'</script>"; exit(); }
if(!isset($_SESSION['errobot'])) $_SESSION['errobot']=0;
if($_GET['bot']<>$_SESSION['bot']){
	$_SESSION['errobot']=$_SESSION['errobot']+1;
	if($_SESSION['errobot']>=2){ echo "<script>self.location='?p=logout'</script>"; exit(); }
	echo "<script>self.location='?p=prepare&msg=1'</script>"; exit();
}
$_SESSION['errobot']=0;
if(!isset($_SESSION['prepare'])){ echo "<script>self.location='?p=home'</script>"; exit(); }
$sqli=mysql_query("SELECT u.id, u.usuario, u.yens, u.yens_fat, u.nivel, u.orgid, u.energia, u.score ,u.torneio,u.torneio_eliminado, u.energiamax, u.taijutsu, u.ninjutsu, u.genjutsu, u.personagem, u.avatar, u.renegado, u.vila, u.doujutsu, u.doujutsu_nivel, u.doujutsu_exp, u.doujutsu_expmax, u.exp, u.expmax, u.vip, u.missao, u.loginip, u.tipo, o.nivel orgnivel , sum(i.taijutsu) cla_taijutsu, sum(i.ninjutsu) cla_ninjutsu, sum(i.genjutsu) cla_genjutsu FROM usuarios u LEFT OUTER JOIN organizacoes o ON u.orgid=o.id  LEFT OUTER JOIN clas_investimentos i ON u.orgid=i.orgid WHERE u.id=".$_SESSION['prepare']);
$dbi=mysql_fetch_assoc($sqli);
if(($dbi['torneio']=='sim')&&($dbi['torneio_eliminado']==0)){ echo "<script>self.location='?p=hunt&msg=18'</script>"; exit(); }
$sqlgrupos=mysql_query("SELECT u.id, u.usuario, u.yens, u.yens_fat, u.nivel, u.orgid, u.energia, u.score , u.energiamax, u.taijutsu, u.ninjutsu, u.genjutsu, u.personagem, u.avatar, u.renegado, u.vila, u.doujutsu, u.doujutsu_nivel, u.doujutsu_exp, u.doujutsu_expmax, u.exp, u.expmax, u.vip, u.missao, u.loginip, u.tipo, o.nivel orgnivel , sum(i.taijutsu) cla_taijutsu, sum(i.ninjutsu) cla_ninjutsu, sum(i.genjutsu) cla_genjutsu FROM usuarios u LEFT OUTER JOIN organizacoes o ON u.orgid=o.id  LEFT OUTER JOIN clas_investimentos i ON u.orgid=i.orgid WHERE u.id=".$db['id']."");
$dbg=mysql_fetch_assoc($sqlgrupos);
$sqlv=mysql_query("SELECT data FROM relatorios WHERE usuarioid=".$db['id']." AND inimigoid=".$dbi['id']." ORDER BY id DESC LIMIT 1");
$dbv=@mysql_fetch_assoc($sqlv);
$soma=mktime(date('H')-12, date('i'), date('s'));
$penalidade=date('Y-m-d H:i:s',$soma);
if($penalidade<$dbv['data']){ echo "<script>self.location='?p=hunt&msg=9'</script>"; exit(); }
$sqlv=mysql_query("SELECT data FROM relatorios WHERE usuarioid=".$dbi['id']." OR inimigoid=".$dbi['id']." ORDER BY id DESC LIMIT 1");
$dbv=@mysql_fetch_assoc($sqlv);
$soma=mktime(date('H'), date('i')-30, date('s'));
$penalidade=date('Y-m-d H:i:s',$soma);
if($dbi['tipo']=='player'){
	if($penalidade<$dbv['data']){ echo "<script>self.location='?p=hunt&msg=8'</script>"; exit(); }
}
if($dbi['missao']==999){ echo "<script>self.location='?p=hunt&msg=10'</script>"; exit(); }
require_once('verifica_nivelatk.php');
if(mysql_num_rows($sqli)==0){ echo "<script>self.location='?p=hunt&msg=1'</script>"; exit(); }
if($dbi['tipodeconta']=='admin'){ echo "<script>self.location='?p=hunt&msg=22'</script>"; exit(); }
if($db ['tipodeconta']=='admin'){ echo "<script>self.location='?p=hunt&msg=20'</script>"; exit(); }
if($dbi['energia']<25){ echo "<script>self.location='?p=hunt&msg=2'</script>"; exit(); }
if($db['energia']<25){ echo "<script>self.location='?p=hunt&msg=13'</script>"; exit(); }
if($dbi['avatar']==0){ echo "<script>self.location='?p=hunt&msg=12'</script>"; exit(); }
$sqls=mysql_query("SELECT i.id, i.upgrade, t.id itemid, t.nome, t.taijutsu, t.ninjutsu, t.genjutsu, t.imagem, t.categoria FROM inventario i LEFT OUTER JOIN table_itens t ON i.itemid=t.id WHERE i.usuarioid=".$db['id']." AND i.status='on'");
$sqlss=mysql_query("SELECT i.id, i.upgrade, t.id itemid, t.nome, i.taijutsu, i.ninjutsu, i.genjutsu, t.imagem, t.categoria FROM animais i LEFT OUTER JOIN table_animais t ON i.itemid=t.id WHERE i.usuarioid=".$db['id']." AND i.status='on'");
$sqlsss=mysql_query("SELECT i.id, i.upgrade, t.id itemid, t.nome, t.taijutsu, t.ninjutsu, t.genjutsu, t.imagem, t.categoria FROM selos i LEFT OUTER JOIN table_selos t ON i.itemid=t.id WHERE i.usuarioid=".$db['id']." AND i.status='on'");
$portoes=mysql_query("SELECT i.id, i.upgrade, t.id itemid, t.nome, t.porcentagem, t.ninjutsu, t.genjutsu, t.imagem, t.categoria FROM portao i LEFT OUTER JOIN table_portoes t ON i.itemid=t.id WHERE i.usuarioid=".$db['id']." AND i.status='on'");
if(($db['doujutsu']==2)&&($db['doujutsu_nivel']<10)){ $txtdoujutsu1='Byakugan'; $addtai1=round($db['taijutsu']*($db['doujutsu_nivel']/50)); } else $addtai1=0;
if(($db['doujutsu']==2)&&($db['doujutsu_nivel']>=10)){ $txtdoujutsu1='Byakugan'; $addtai1=round($db['taijutsu']*($db['doujutsu_nivel']/16)); } else $addtai1=0;
if(($db['doujutsu']==3)&&($db['doujutsu_nivel']<10)){ $txtdoujutsu1='Rinnegan'; $addnin1=round($db['ninjutsu']*($db['doujutsu_nivel']/50)); } else $addnin1=0;
if(($db['doujutsu']==3)&&($db['doujutsu_nivel']>=10)){ $txtdoujutsu1='Rinnegan'; $addnin1=round($db['ninjutsu']*($db['doujutsu_nivel']/16)); } else $addnin1=0;
if($db['doujutsu']==5){ $txtdoujutsu1='Fuumetsu_Mangekyou_Sharingan'; $addgen1=round($db['genjutsu']*($db['doujutsu_nivel']/20)); } else $addgen1=0;
if($db['doujutsu']==5){ $txtdoujutsu1='Fuumetsu_Mangekyou_Sharingan'; $addtai1=round($db['taijutsu']*($db['doujutsu_nivel']/20)); } else $addgen1=0;
if($db['doujutsu']==5){ $txtdoujutsu1='Fuumetsu_Mangekyou_Sharingan'; $addnin1=round($db['ninjutsu']*($db['doujutsu_nivel']/20)); } else $addgen1=0;
if($db['doujutsu']==1){ $txtdoujutsu1='Sharingan'; $addgendou=round($db['genjutsu']*($db['doujutsu_nivel']/50)); } else $addgendou=0;
if($db['doujutsu']==4){ $txtdoujutsu1='Mangekyou_Sharingan'; $addmange=round($db['genjutsu']*($db['doujutsu_nivel']/20)); } else $addmange=0;
if($db['orgnivel']>0){

	$addtai1=$addtai1+$db['orgnivel']+$dbg['cla_taijutsu'];
	$addnin1=$addnin1+$db['orgnivel']+$dbg['cla_ninjutsu'];
	$addgen1=$addgen1+$db['orgnivel']+$dbg['cla_genjutsu'];;
}
$addgendou=$addgendou;
$addmange=$addmange;
$equips1='';
$equips2='';
while($dbs=mysql_fetch_assoc($sqls)){
	if($equips1=='') $equips1=$dbs['itemid']; else $equips1.=','.$dbs['itemid'];
	if($equips1<>'') substr($equips1,0,strlen($equips1)-1);
	$addtai1=$addtai1+$dbs['taijutsu']+$dbs['upgrade'];
	$addnin1=$addnin1+$dbs['ninjutsu']+$dbs['upgrade'];
	$addgen1=$addgen1+$dbs['genjutsu']+$dbs['upgrade'];
}
$equips3='';
$equips4='';
while($dbss=mysql_fetch_assoc($sqlss)){
	if($equips3=='') $equips3=$dbss['itemid']; else $equips3.=','.$dbss['itemid'];
	if($equips3<>'') substr($equips3,0,strlen($equips3)-1);
	$addtai3=$addtai3+$dbss['taijutsu']+$dbss['upgrade'];
	$addnin3=$addnin3+$dbss['ninjutsu']+$dbss['upgrade'];
	$addgen3=$addgen3+$dbss['genjutsu']+$dbss['upgrade'];
}
$equips5='';
$equips6='';
while($dbsss=mysql_fetch_assoc($sqlsss)){
	if($equips5=='') $equips5=$dbsss['itemid']; else $equips5.=','.$dbsss['itemid'];
	if($equips5<>'') substr($equips5,0,strlen($equips5)-1);
	$addtai5=$addtai5+$dbsss['taijutsu']+$dbsss['upgrade'];
	$addnin5=$addnin5+$dbsss['ninjutsu']+$dbsss['upgrade'];
	$addgen5=$addgen5+$dbsss['genjutsu']+$dbsss['upgrade'];
}
$equips7='';
$equips8='';
while($dbportao=mysql_fetch_assoc($portoes)){
	if($equips7=='') $equips7=$dbportao['itemid']; else $equips7.=','.$dbportao['itemid'];
	if($equips7<>'') substr($equips7,0,strlen($equips7)-1);
	$percentual = $dbportao['porcentagem'] / 100;
    $taijutsufinal=$percentual*$db['taijutsu'];
    $taifin=ceil($taijutsufinal);
    $adicionatai=$taifin;
	}
$sqls2=mysql_query("SELECT i.id, i.upgrade, t.id itemid, t.nome, t.taijutsu, t.ninjutsu, t.genjutsu, t.imagem, t.categoria FROM inventario i LEFT OUTER JOIN table_itens t ON i.itemid=t.id WHERE i.usuarioid=".$dbi['id']." AND i.status='on'");
$sqls2s=mysql_query("SELECT i.id, i.upgrade, t.id itemid, t.nome, i.taijutsu, i.ninjutsu, i.genjutsu, t.imagem, t.categoria FROM animais i LEFT OUTER JOIN table_animais t ON i.itemid=t.id WHERE i.usuarioid=".$dbi['id']." AND i.status='on'");
$sqls2ss=mysql_query("SELECT i.id, i.upgrade, t.id itemid, t.nome, t.taijutsu, t.ninjutsu, t.genjutsu, t.imagem, t.categoria FROM selos i LEFT OUTER JOIN table_selos t ON i.itemid=t.id WHERE i.usuarioid=".$dbi['id']." AND i.status='on'");
$portoes2=mysql_query("SELECT i.id, i.upgrade, t.id itemid, t.nome, t.porcentagem, t.ninjutsu, t.genjutsu, t.imagem, t.categoria FROM portao i LEFT OUTER JOIN table_portoes t ON i.itemid=t.id WHERE i.usuarioid=".$dbi['id']." AND i.status='on'");
if(($dbi['doujutsu']==2)&&($dbi['doujutsu_nivel']<10)){ $txtdoujutsu2='Byakugan'; $addtai2=round($dbi['taijutsu']*($dbi['doujutsu_nivel']/50)); } else $addtai2=0;
if(($dbi['doujutsu']==2)&&($dbi['doujutsu_nivel']>=10)){ $txtdoujutsu2='Byakugan'; $addtai2=round($dbi['taijutsu']*($dbi['doujutsu_nivel']/16)); } else $addtai2=0;
if(($dbi['doujutsu']==3)&&($dbi['doujutsu_nivel']<10)){ $txtdoujutsu2='Rinnegan'; $addnin2=round($dbi['ninjutsu']*($dbi['doujutsu_nivel']/50)); } else $addnin2=0;
if(($dbi['doujutsu']==3)&&($dbi['doujutsu_nivel']>=10)){ $txtdoujutsu2='Rinnegan'; $addnin2=round($dbi['ninjutsu']*($dbi['doujutsu_nivel']/16)); } else $addnin2=0;
if($dbi['doujutsu']==5){ $txtdoujutsu2='Fuumetsu_Mangekyou_Sharingan'; $addgen2=round($dbi['genjutsu']*($dbi['doujutsu_nivel']/20)); } else $addgen2=0;
if($dbi['doujutsu']==5){ $txtdoujutsu2='Fuumetsu_Mangekyou_Sharingan'; $addtai2=round($dbi['taijutsu']*($dbi['doujutsu_nivel']/20)); } else $addgen2=0;
if($dbi['doujutsu']==5){ $txtdoujutsu2='Fuumetsu_Mangekyou_Sharingan'; $addnin2=round($dbi['ninjutsu']*($dbi['doujutsu_nivel']/20)); } else $addgen2=0;
if($dbi['doujutsu']==1){ $txtdoujutsu1='Sharingan'; $addgendou2=round($dbi['genjutsu']*($dbi['doujutsu_nivel']/50)); } else $addgendou2=0;
if($dbi['doujutsu']==4){ $txtdoujutsu1='Mangekyou_Sharingan'; $addmange2=round($dbi['genjutsu']*($dbi['doujutsu_nivel']/20)); } else $addmange2=0;
if($dbi['orgnivel']>0){
	$addtai2=$addtai2+$dbi['orgnivel']+$dbi['cla_taijutsu'];
	$addnin2=$addnin2+$dbi['orgnivel']+$dbi['cla_ninjutsu'];
	$addgen2=$addgen2+$dbi['orgnivel']+$dbi['cla_genjutsu'];;
}
$addgendou2=$addgendou2;
$addmange2=$addmange2;
while($dbs2=mysql_fetch_assoc($sqls2)){
	if($equips2=='') $equips2=$dbs2['itemid']; else $equips2.=','.$dbs2['itemid'];
	if($equips2<>'') substr($equips2,0,strlen($equips2)-1);
	$addtai2=$addtai2+$dbs2['taijutsu']+$dbs2['upgrade'];
	$addnin2=$addnin2+$dbs2['ninjutsu']+$dbs2['upgrade'];
	$addgen2=$addgen2+$dbs2['genjutsu']+$dbs2['upgrade'];
}
while($dbs2s=mysql_fetch_assoc($sqls2s)){
	if($equips4=='') $equips4=$dbs2s['itemid']; else $equips4.=','.$dbs2s['itemid'];
	if($equips4<>'') substr($equips4,0,strlen($equips4)-1);
	$addtai4=$addtai4+$dbs2s['taijutsu']+$dbs2s['upgrade'];
	$addnin4=$addnin4+$dbs2s['ninjutsu']+$dbs2s['upgrade'];
	$addgen4=$addgen4+$dbs2s['genjutsu']+$dbs2s['upgrade'];
}
while($dbs2ss=mysql_fetch_assoc($sqls2ss)){
	if($equips6=='') $equips6=$dbs2ss['itemid']; else $equips6.=','.$dbs2ss['itemid'];
	if($equips6<>'') substr($equips6,0,strlen($equips6)-1);
	$addtai6=$addtai6+$dbs2ss['taijutsu']+$dbs2ss['upgrade'];
	$addnin6=$addnin6+$dbs2ss['ninjutsu']+$dbs2ss['upgrade'];
	$addgen6=$addgen6+$dbs2ss['genjutsu']+$dbs2ss['upgrade'];
}
while($dbportao2=mysql_fetch_assoc($portoes2)){
	if($equips8=='') $equips8=$dbportao2['itemid']; else $equips8.=','.$dbportao['itemid'];
	if($equips8<>'') substr($equips8,0,strlen($equips8)-1);
	$percentual2 = $dbportao2['porcentagem'] / 100;
    $taijutsufinal2=$percentual2*$dbi['taijutsu'];
    $taifin2=ceil($taijutsufinal2);
    $adicionatai2=$taifin2;
	}
if(mysql_num_rows($sqls)>0) mysql_data_seek($sqls,0); if(mysql_num_rows($sqls2)>0) mysql_data_seek($sqls2,0);
if(mysql_num_rows($sqlss)>0) mysql_data_seek($sqlss,0); if(mysql_num_rows($sqls2s)>0) mysql_data_seek($sqls2s,0);
if(mysql_num_rows($sqlsss)>0) mysql_data_seek($sqlsss,0); if(mysql_num_rows($sqls2ss)>0) mysql_data_seek($sqls2ss,0);
if(mysql_num_rows($portoes)>0) mysql_data_seek($portoes,0); if(mysql_num_rows($portoes2)>0) mysql_data_seek($portoes2,0);
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

$eu=mysql_fetch_assoc(mysql_query("SELECT * FROM usuarios WHERE id=".$db['id']));
$ele=mysql_fetch_assoc(mysql_query("SELECT * FROM usuarios WHERE id=".$dbi['id']));

$idrank=mysql_query("SELECT * FROM nal_war ORDER BY id DESC LIMIT 1");
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
            <?php
            if($adicionatai>0)$adicionatai;
            $portaoadd=$adicionatai;
            $somar=$db['taijutsu']+$portaoadd;
            $somando=ceil($somar);
            ?>
            <td style="background:#2C2C2C;"><b>[<?php echo $somando; ?>]</b> <?php if($addtai1>0) echo '+'.$addtai1; ?> <?php if($sharingan>0) echo '+'.$sharingan; ?> <?php if($addtai3>0) echo '+'.$addtai3; ?> <?php if($addtai5>0) echo '+'.$addtai5; ?></td>
            <td></td>
              <?php
            if($adicionatai2>0)$adicionatai2;
            $portaoadd2=$adicionatai2;
            $somar2=$dbi['taijutsu']+$portaoadd2;
            $somando2=ceil($somar2);
            ?>
            <td align="right" style="background:#2C2C2C;padding-right:10px;">Taijutsu:</td>
            <td style="background:#2C2C2C;"><b>[<?php echo $somando2; ?>]</b> <?php if($addtai2>0) echo '+'.$addtai2; ?> <?php if($addtai4>0) echo '+'.$addtai4; ?> <?php if($addtai6>0) echo '+'.$addtai6; ?></td>
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
        <tr>
        	<td colspan="2" bgcolor="#323232" style="text-align:center">
				<?php $arma1=0; if(mysql_num_rows($sqls)==0) echo 'Nenhum equipamento.'; else do{
					if($arma1==0){
						if($dbs['categoria']=='arma') $arma1=1; else $arma1=0;
					}
					$tip='<b>'.$dbs['nome'];
					if($dbs['upgrade']>0) $tip.=' +'.$dbs['upgrade'];
					$tip.='</b><br />';
					if($dbs['taijutsu']>0) $tip.='[+'.($dbs['taijutsu']+$dbs['upgrade']).'] em Taijutsu';
					if($dbs['ninjutsu']>0) $tip.='<br />[+'.($dbs['ninjutsu']+$dbs['upgrade']).'] em Ninjutsu';
					if($dbs['genjutsu']>0) $tip.='<br />[+'.($dbs['genjutsu']+$dbs['upgrade']).'] em Genjutsu';
					?>
                    <img src="_img/equipamentos/<?php echo $dbs['imagem']; ?>.png" width="70" onmouseover="Tip('<div class=tooltip><?php echo $tip; ?></div>')" onmouseout="UnTip()" />
	<?php } while($dbs=mysql_fetch_assoc($sqls)); ?>
     <div class="sep"></div>
  <br>       <?php $arma1=0; if(mysql_num_rows($sqlss)==0) echo 'Nenhum animal lendario.'; else do{
					if($arma1==0){
						if($dbss['categoria']=='arma') $arma1=1; else $arma1=0;
					}
					$tip='<b>'.$dbss['nome'];
					$tip.='</b><br />';
					if($dbss['taijutsu']>0) $tip.='[+'.$dbss['taijutsu'].'] em Taijutsu';
					if($dbss['ninjutsu']>0) $tip.='<br />[+'.$dbss['ninjutsu'].'] em Ninjutsu';
					if($dbss['genjutsu']>0) $tip.='<br />[+'.$dbss['genjutsu'].'] em Genjutsu';
					?>
                    <img src="_img/equipamentos/<?php echo $dbss['imagem']; ?>.png" width="70" onmouseover="Tip('<div class=tooltip><?php echo $tip; ?></div>')" onmouseout="UnTip()" />
	<?php } while($dbss=mysql_fetch_assoc($sqlss)); ?>

             <div class="sep"></div>
             <br>       <?php $arma1=0; if(mysql_num_rows($sqlsss)==0) echo 'Nenhum selo ativo.'; else do{
					if($arma1==0){
						if($dbsss['categoria']=='arma') $arma1=1; else $arma1=0;
					}
					$tip='<b>'.$dbsss['nome'];
					if($dbsss['upgrade']>0) $tip.=' +'.$dbsss['upgrade'];
					$tip.='</b><br />';
					if($dbsss['taijutsu']>0) $tip.='[+'.($dbsss['taijutsu']+$dbsss['upgrade']).'] em Taijutsu';
					if($dbsss['ninjutsu']>0) $tip.='<br />[+'.($dbsss['ninjutsu']+$dbsss['upgrade']).'] em Ninjutsu';
					if($dbsss['genjutsu']>0) $tip.='<br />[+'.($dbsss['genjutsu']+$dbsss['upgrade']).'] em Genjutsu';
					?>
                    <img src="_img/equipamentos/<?php echo $dbsss['imagem']; ?>.png" width="70" onmouseover="Tip('<div class=tooltip><?php echo $tip; ?></div>')" onmouseout="UnTip()" />
	<?php } while($dbsss=mysql_fetch_assoc($sqlsss)); ?>
            <div class="sep"></div>
             <br>     <?php $arma1=0; if(mysql_num_rows($portoes)==0) echo 'Nenhum portão do chakra.'; else do{
					if($arma1==0){
						if($dbportao['categoria']=='arma') $arma1=1; else $arma1=0;
					}
					$tip='<b>'.$dbportao['nome'];
					$tip.='</b><br />';
					if($dbportao['porcentagem']>0) $tip.='Adiciona um valor de '.($dbportao['porcentagem']).' % em seu Taijutsu';
					?>
                    <img src="_img/equipamentos/<?php echo $dbportao['imagem']; ?>.png" width="70" onmouseover="Tip('<div class=tooltip><?php echo $tip; ?></div>')" onmouseout="UnTip()" />
	<?php } while($dbportao=mysql_fetch_assoc($portoes)); ?>

   </td>
            <td align="center"></td>
            <td colspan="2" bgcolor="#323232" style="text-align:center">
				<?php $arma2=0; if(mysql_num_rows($sqls2)==0) echo 'Nenhum equipamento.'; else do{
					if($arma2==0){
						if($dbs2['categoria']=='arma') $arma2=1; else $arma2=0;
					}
					$tip='<b>'.$dbs2['nome'];
					if($dbs2['upgrade']>0) $tip.=' +'.$dbs2['upgrade'];
					$tip.='</b><br />';
					if($dbs2['taijutsu']>0) $tip.='[+'.($dbs2['taijutsu']+$dbs2['upgrade']).'] em Taijutsu';
					if($dbs2['ninjutsu']>0) $tip.='<br />[+'.($dbs2['ninjutsu']+$dbs2['upgrade']).'] em Ninjutsu';
					if($dbs2['genjutsu']>0) $tip.='<br />[+'.($dbs2['genjutsu']+$dbs2['upgrade']).'] em Genjutsu';
					?>
                    <img src="_img/equipamentos/<?php echo $dbs2['imagem']; ?>.png" width="70" onmouseover="Tip('<div class=tooltip><?php echo $tip; ?></div>')" onmouseout="UnTip()" />
				<?php } while($dbs2=mysql_fetch_assoc($sqls2)); ?><br>
					<div class="sep"></div>

                     <?php $arma2=0; if(mysql_num_rows($sqls2s)==0) echo 'Nenhum animal lendario.'; else do{
					if($arma2==0){
						if($dbs2s['categoria']=='arma') $arma2=1; else $arma2=0;
					}
					$tip='<b>'.$dbs2s['nome'];
					$tip.='</b><br />';
					if($dbs2s['taijutsu']>0) $tip.='[+'.$dbs2s['taijutsu'].'] em Taijutsu';
					if($dbs2s['ninjutsu']>0) $tip.='<br />[+'.$dbs2s['ninjutsu'].'] em Ninjutsu';
					if($dbs2s['genjutsu']>0) $tip.='<br />[+'.$dbs2s['genjutsu'].'] em Genjutsu';
					?>
                    <img src="_img/equipamentos/<?php echo $dbs2s['imagem']; ?>.png" width="70" onmouseover="Tip('<div class=tooltip><?php echo $tip; ?></div>')" onmouseout="UnTip()" />
				<?php } while($dbs2s=mysql_fetch_assoc($sqls2s)); ?><br>
				<div class="sep"></div>

                     <?php $arma2=0; if(mysql_num_rows($sqls2ss)==0) echo 'Nenhum selo ativo.'; else do{
					if($arma2==0){
						if($dbs2ss['categoria']=='arma') $arma2=1; else $arma2=0;
					}
					$tip='<b>'.$dbs2ss['nome'];
					if($dbs2ss['upgrade']>0) $tip.=' +'.$dbs2ss['upgrade'];
					$tip.='</b><br />';
					if($dbs2ss['taijutsu']>0) $tip.='[+'.($dbs2ss['taijutsu']+$dbs2ss['upgrade']).'] em Taijutsu';
					if($dbs2ss['ninjutsu']>0) $tip.='<br />[+'.($dbs2ss['ninjutsu']+$dbs2ss['upgrade']).'] em Ninjutsu';
					if($dbs2ss['genjutsu']>0) $tip.='<br />[+'.($dbs2ss['genjutsu']+$dbs2ss['upgrade']).'] em Genjutsu';
					?>
                    <img src="_img/equipamentos/<?php echo $dbs2ss['imagem']; ?>.png" width="70" onmouseover="Tip('<div class=tooltip><?php echo $tip; ?></div>')" onmouseout="UnTip()" />
				<?php } while($dbs2ss=mysql_fetch_assoc($sqls2ss)); ?><br>
				<div class="sep"></div>
             <br>     <?php $arma1=0; if(mysql_num_rows($portoes2)==0) echo 'Nenhum portão do chakra.'; else do{
					if($arma1==0){
						if($dbportao2['categoria']=='arma') $arma1=1; else $arma1=0;
					}
					$tip='<b>'.$dbportao2['nome'];
					$tip.='</b><br />';
					if($dbportao2['porcentagem']>0) $tip.='Adiciona um valor de '.($dbportao2['porcentagem']).' % em seu Taijutsu';
					?>
                    <img src="_img/equipamentos/<?php echo $dbportao2['imagem']; ?>.png" width="70" onmouseover="Tip('<div class=tooltip><?php echo $tip; ?></div>')" onmouseout="UnTip()" />
	<?php } while($dbportao2=mysql_fetch_assoc($portoes2)); ?>
            </td>
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

<div class="box_top">Relatorio Detalhado do Combate</div>
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
		if(date('Y-m-d H:i:s')<$db['vip']){ $mpen=5; $spen=0; } else { $mpen=7; $spen=0; }
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
       		// clan war

       	$from_org = mysql_fetch_assoc(mysql_query("SELECT `orgid` FROM `usuarios` WHERE `id`='".$dbi['id']."'"));
		$to_org = mysql_fetch_assoc(mysql_query("SELECT `orgid` FROM `usuarios` WHERE `id`='".$db['id']."'"));
		$war = mysql_query("SELECT * FROM `org_wars_declare` WHERE `to_org`='".$from_org['orgid']."' AND `from_org`='".$to_org['orgid']."'");
		if(mysql_num_rows($war) > 0){
			mysql_query("UPDATE `org_wars_declare` SET `exp_1`=`exp_1`+'".$exp_1."',`exp_2`=`exp_2`+'".$exp_2."',`vit_1`=`vit_1`+'1',`vit_2`=`vit_2`+'0',`der_1`=`der_1`+'0',`der_2`=`der_2`+'1',`total_1`=`total_1`+'1',`total_2`=`total_2`+'1' WHERE `to_org`='".$to_org['orgid']."' AND `from_org`='".$from_org['orgid']."'") or die(mysql_error());
			mysql_query("UPDATE `org_wars_declare` SET `exp_1`=`exp_1`+'".$exp_1."',`exp_2`=`exp_2`+'".$exp_2."',`vit_1`=`vit_1`+'0',`vit_2`=`vit_2`+'1',`der_1`=`der_1`+'1',`der_2`=`der_2`+'0',`total_1`=`total_1`+'1',`total_2`=`total_2`+'1' WHERE `to_org`='".$from_org['orgid']."' AND `from_org`='".$to_org['orgid']."'") or die(mysql_error());
		}

		//tomarnocu

       		// GUERRA DE VILA //
		if ($eu['inwar'] == "sim" and $ele['inwar'] == "sim" and $ssj=="aberto"){
		if ($eu['vila']!=$ele['vila'] or $eu['vila']==$ele['vila'] and $eu['renegado']!=$ele['renegado']){
		if ($eu['renegado']=="sim"){
		mysql_query("update nal_war_vilas set score=score+1 where vilaid='7' and warid=".$idinwar);
		mysql_query("update usuarios set inwar_score=inwar_score+1 where id=".$eu['id']);
		}else{
		mysql_query("update nal_war_vilas set score=score+1 where vilaid=".$eu['vila']." and warid=".$idinwar);
		mysql_query("update usuarios set inwar_score=inwar_score+1 where id=".$eu['id']);
		}
		if ($ele['renegado']=="sim"){
		mysql_query("update nal_war_vilas set score=score-1 where vilaid='7' and warid=".$idinwar);
		mysql_query("update usuarios set inwar_score=inwar_score-1 where id=".$ele['id']);
		}else{
		mysql_query("update nal_war_vilas set score=score-1 where vilaid=".$ele['vila']." and warid=".$idinwar);
		mysql_query("update usuarios set inwar_score=inwar_score-1 where id=".$ele['id']);
		}
		}
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
		//Guerra de clan

		$from_org = mysql_fetch_assoc(mysql_query("SELECT `orgid` FROM `usuarios` WHERE `id`='".$dbi['id']."'"));
		$to_org = mysql_fetch_assoc(mysql_query("SELECT `orgid` FROM `usuarios` WHERE `id`='".$db['id']."'"));
		$war = mysql_query("SELECT * FROM `org_wars_declare` WHERE `to_org`='".$from_org['orgid']."' AND `from_org`='".$to_org['orgid']."'");
		if(mysql_num_rows($war) > 0){
			mysql_query("UPDATE `org_wars_declare` SET `exp_1`=`exp_1`+'".$exp_1."',`exp_2`=`exp_2`+'".$exp_2."',`vit_1`=`vit_1`+'1',`vit_2`=`vit_2`+'0',`der_1`=`der_1`+'0',`der_2`=`der_2`+'1',`total_1`=`total_1`+'1',`total_2`=`total_2`+'1' WHERE `to_org`='".$to_org['orgid']."' AND `from_org`='".$from_org['orgid']."'") or die(mysql_error());
			mysql_query("UPDATE `org_wars_declare` SET `exp_1`=`exp_1`+'".$exp_1."',`exp_2`=`exp_2`+'".$exp_2."',`vit_1`=`vit_1`+'0',`vit_2`=`vit_2`+'1',`der_1`=`der_1`+'1',`der_2`=`der_2`+'0',`total_1`=`total_1`+'1',`total_2`=`total_2`+'1' WHERE `to_org`='".$from_org['orgid']."' AND `from_org`='".$to_org['orgid']."'") or die(mysql_error());
		}
		//fim



		// GUERRA DE VILA //
		if ($eu['inwar'] == "sim" and $ele['inwar'] == "sim" and $ssj=="aberto"){
		if ($eu['vila']!=$ele['vila'] or $eu['vila']==$ele['vila'] and $eu['renegado']!=$ele['renegado']){
		if ($eu['renegado']=="sim"){
		mysql_query("update nal_war_vilas set score=score-1 where vilaid='7' and warid=".$idinwar);
		mysql_query("update usuarios set inwar_score=inwar_score-1 where id=".$eu['id']);
		}else{
		mysql_query("update nal_war_vilas set score=score-1 where vilaid=".$eu['vila']." and warid=".$idinwar);
		mysql_query("update usuarios set inwar_score=inwar_score-1 where id=".$eu['id']);
		}
		if ($ele['renegado']=="sim"){
		mysql_query("update nal_war_vilas set score=score+1 where vilaid='7' and warid=".$idinwar);
		mysql_query("update usuarios set inwar_score=inwar_score+1 where id=".$ele['id']);
		}else{
		mysql_query("update nal_war_vilas set score=score+1 where vilaid=".$ele['vila']." and warid=".$idinwar);
		mysql_query("update usuarios set inwar_score=inwar_score+1 where id=".$ele['id']);
		}
		}
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
	    // clan war

	    $from_org = mysql_fetch_assoc(mysql_query("SELECT `orgid` FROM `usuarios` WHERE `id`='".$dbi['id']."'"));
		$to_org = mysql_fetch_assoc(mysql_query("SELECT `orgid` FROM `usuarios` WHERE `id`='".$db['id']."'"));
		$war = mysql_query("SELECT * FROM `org_wars_declare` WHERE `to_org`='".$from_org['orgid']."' AND `from_org`='".$to_org['orgid']."'");
		if(mysql_num_rows($war) > 0){
			mysql_query("UPDATE `org_wars_declare` SET `exp_1`=`exp_1`+'".$exp_1."',`exp_2`=`exp_2`+'".$exp_2."',`vit_1`=`vit_1`+'0',`vit_2`=`vit_2`+'0',`der_1`=`der_1`+'0',`der_2`=`der_2`+'0',`total_1`=`total_1`+'1',`total_2`=`total_2`+'1' WHERE `to_org`='".$to_org['orgid']."' AND `from_org`='".$from_org['orgid']."'") or die(mysql_error());
			mysql_query("UPDATE `org_wars_declare` SET `exp_1`=`exp_1`+'".$exp_1."',`exp_2`=`exp_2`+'".$exp_2."',`vit_1`=`vit_1`+'0',`vit_2`=`vit_2`+'0',`der_1`=`der_1`+'0',`der_2`=`der_2`+'0',`total_1`=`total_1`+'1',`total_2`=`total_2`+'1' WHERE `to_org`='".$from_org['orgid']."' AND `from_org`='".$to_org['orgid']."'") or die(mysql_error());
		}
		// foderme
	}
	$exp=$exp_1.','.(4-$exp_2);
	$danos=$dano1.','.$perdido1;
	$reldoujutsu=$db['doujutsu'].','.$dbi['doujutsu'];
	$nivel=$db['nivel'].','.$dbi['nivel'];
	if($db['loginip']==$dbi['loginip']) $ip='sim'; else $ip='nao';
	if($dbi['tipo']=='player'){
		mysql_query("INSERT INTO relatorios (data, usuarioid, inimigoid, vencedor, exp, yens, taijutsu, ninjutsu, genjutsu, energia, equips1, equips2, equips3, equips4, equips5, equips6, equips7, equips8, danos, nivel, doujutsu, ip) VALUES ('".date('Y-m-d H:i:s')."', ".$db['id'].", ".$dbi['id'].", $vencedorid, '$exp', $yens, '$tai', '$nin', '$gen', '$ene', '$equips1', '$equips2', '$equips3', '$equips4', '$equips5', '$equips6', '$equips7', '$equips8', '$danos', '$nivel', '$reldoujutsu', '$ip')");
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
@mysql_free_result($sqls);
@mysql_free_result($sqls2);
@mysql_free_result($sqlss);
@mysql_free_result($sqls2s);
@mysql_free_result($sqlsss);
@mysql_free_result($sqls2ss);
@mysql_free_result($portoes);
@mysql_free_result($portoes2);
@mysql_free_result($sqlc);
@mysql_free_result($sqlc2);
@mysql_free_result($sqlj);
@mysql_free_result($sqlj2);
?>
