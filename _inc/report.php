<?php require_once('trava.php'); ?>
<?php require_once('funcoes.php'); ?>
<?php
if(!isset($_GET['id'])){ echo "<script>self.location='?p=home'</script>"; return; }
$sqlr=mysql_query("SELECT r.*, u.usuario usuario1, u.personagem personagem1, u2.usuario usuario2, u2.personagem personagem2, u.renegado renegado1, u2.renegado renegado2, u.avatar avatar1, u2.avatar avatar2, u.vila vila1, u2.vila vila2 FROM relatorios r LEFT OUTER JOIN usuarios u ON r.usuarioid=u.id LEFT OUTER JOIN usuarios u2 ON r.inimigoid=u2.id WHERE r.id=".$_GET['id']);
$dbr=@mysql_fetch_assoc($sqlr);
if(($dbr['usuarioid']<>$db['id'])&&($dbr['inimigoid']<>$db['id'])){ echo "<script>self.location='?p=home'</script>"; return; }
if(($dbr['status']=='nao')&&($dbr['inimigoid']==$db['id'])) mysql_query("UPDATE relatorios SET status='sim' WHERE id=".$dbr['id']);

$taijutsu=explode(',',$dbr['taijutsu']);
$ninjutsu=explode(',',$dbr['ninjutsu']);
$genjutsu=explode(',',$dbr['genjutsu']);
$energia=explode(',',$dbr['energia']);
$equips1=explode(',',$dbr['equips1']);
$equips2=explode(',',$dbr['equips2']);
$equips3=explode(',',$dbr['equips3']);
$equips4=explode(',',$dbr['equips4']);
$equips5=explode(',',$dbr['equips5']);
$equips6=explode(',',$dbr['equips6']);
$equips7=explode(',',$dbr['equips7']);
$equips8=explode(',',$dbr['equips8']);
$danos=explode(',',$dbr['danos']);
$nivel=explode(',',$dbr['nivel']);
$exp=explode(',',$dbr['exp']);
$doujutsu=explode(',',$dbr['doujutsu']);
switch($doujutsu[0]){
	case 1: $txtdoujutsu1='Sharingan'; break;
	case 2: $txtdoujutsu1='Byakugan'; break;
	case 3: $txtdoujutsu1='Rinnegan'; break;
	case 4: $txtdoujutsu1='Mangekyou_Sharingan'; break;
	case 5: $txtdoujutsu1='Fuumetsu_Mangekyou_Sharingan'; break;
}
switch($doujutsu[1]){
	case 1: $txtdoujutsu2='Sharingan'; break;
	case 2: $txtdoujutsu2='Byakugan'; break;
	case 3: $txtdoujutsu2='Rinnegan'; break;
	case 5: $txtdoujutsu1='Fuumetsu_Mangekyou_Sharingan'; break;
}
if($dbr['vencedor']==$dbr['usuarioid']){ $vencedor=$dbr['usuario1']; $perdedor=$dbr['usuario2']; } else { $vencedor=$dbr['usuario2']; $perdedor=$dbr['usuario1']; }

switch($dbr['vila1']){
	case 1: $vila='folha'; if($dbr['renegado1']=='sim') $txtvila='Akatsuki (Vila da Folha)'; else $txtvila='Vila da Folha'; break;
	case 2: $vila='areia'; if($dbr['renegado1']=='sim') $txtvila='Akatsuki (Vila da Areia)'; else $txtvila='Vila da Areia'; break;
	case 3: $vila='som'; if($dbr['renegado1']=='sim') $txtvila='Akatsuki (Vila do Som)'; else $txtvila='Vila do Som'; break;
	case 4: $vila='chuva'; if($dbr['renegado1']=='sim') $txtvila='Akatsuki (Vila da Chuva)'; else $txtvila='Vila da Chuva'; break;
	case 5: $vila='nuvem'; if($dbr['renegado1']=='sim') $txtvila='Akatsuki (Vila da Nuvem)'; else $txtvila='Vila da Nuvem'; break;
	case 6: $vila='nevoa'; if($dbr['renegado1']=='sim') $txtvila='Akatsuki (Vila da Névoa)'; else $txtvila='Vila da Névoa'; break;
	case 8: $vila='pedra'; if($dbr['renegado1']=='sim') $txtvila='Akatsuki (Vila da Pedra)'; else $txtvila='Vila da Pedra'; break;
	case 9: $vila='cachoeira'; if($dbr['renegado1']=='sim') $txtvila='Akatsuki (Vila da Cachoeira)'; else $txtvila='Vila da Cachoeira'; break;
	case 10: $vila='neve'; if($dbr['renegado1']=='sim') $txtvila='Akatsuki (Vila da Neve)'; else $txtvila='Vila da Neve'; break;
	case 11: $vila='grama'; if($dbr['renegado1']=='sim') $txtvila='Akatsuki (Vila da Grama)'; else $txtvila='Vila da Grama'; break;
} ?>
<?php
switch($dbr['vila2']){
	case 1: $vilai='folha'; if($dbr['renegado2']=='sim') $txtvilai='Akatsuki (Vila da Folha)'; else $txtvilai='Vila da Folha'; break;
	case 2: $vilai='areia'; if($dbr['renegado2']=='sim') $txtvilai='Akatsuki (Vila da Areia)'; else $txtvilai='Vila da Areia'; break;
	case 3: $vilai='som'; if($dbr['renegado2']=='sim') $txtvilai='Akatsuki (Vila do Som)'; else $txtvilai='Vila do Som'; break;
	case 4: $vilai='chuva'; if($dbr['renegado2']=='sim') $txtvilai='Akatsuki (Vila da Chuva)'; else $txtvilai='Vila da Chuva'; break;
	case 5: $vilai='nuvem'; if($dbr['renegado2']=='sim') $txtvilai='Akatsuki (Vila da Nuvem)'; else $txtvilai='Vila da Nuvem'; break;
	case 6: $vilai='nevoa'; if($dbr['renegado2']=='sim') $txtvilai='Akatsuki (Vila da Névoa)'; else $txtvilai='Vila da Névoa'; break;
	case 8: $vilai='pedra'; if($dbr['renegado2']=='sim') $txtvilai='Akatsuki (Vila da Pedra)'; else $txtvilai='Vila da Pedra'; break;
	case 9: $vilai='cachoeira'; if($dbr['renegado2']=='sim') $txtvilai='Akatsuki (Vila da Cachoeira)'; else $txtvilai='Vila da Cachoeira'; break;
	case 10: $vilai='neve'; if($dbr['renegado2']=='sim') $txtvilai='Akatsuki (Vila da Neve)'; else $txtvilai='Vila da Neve'; break;
	case 11: $vilai='grama'; if($dbr['renegado2']=='sim') $txtvilai='Akatsuki (Vila da Grama)'; else $txtvilai='Vila da Grama'; break;
} ?>
<div class="box_top"><?php echo $dbr['usuario1']; ?> x <?php echo $dbr['usuario2']; ?></div>
<div class="box_middle">
	<table width="100%" cellpadding="0" cellspacing="0">
    	<tr>
        	<td colspan="2" align="center"><b><?php echo $dbr['usuario1']; ?></b></td>
            <td align="center">&nbsp;</td>
            <td colspan="2" align="center"><b><?php echo $dbr['usuario2']; ?></b></td>
        </tr>
        <tr>
        	<td colspan="2" align="center"><div class="sep"></div></td>
            <td align="center"></td>
            <td colspan="2" align="center"><div class="sep"></div></td>
        </tr>
    	<tr>
        	<td colspan="2" align="center" style="background:url(_img/personagens/no_avatar.jpg) no-repeat center #444444;"><img src="_img/personagens/<?php echo $dbr['personagem1']; ?>/<?php echo $dbr['avatar1']; ?>.jpg" onmouseover="Tip('<div class=tooltip><?php echo fpersonagem($dbr['personagem1']); ?></div>')" onmouseout="UnTip()" /></td>
          <td align="center" width="15%"><img src="_img/versus.jpg" /></td>
          <td colspan="2" align="center" style="background:url(_img/personagens/no_avatar.jpg) no-repeat center #444444;"><img src="_img/personagens/<?php echo $dbr['personagem2']; ?>/<?php echo $dbr['avatar2']; ?>.jpg"onmouseover="Tip('<div class=t wiooltip><?php echo fpersonagem($dbr['personagem2']); ?></div>')" onmouseout="UnTip()" /></td>
      </tr>
        <tr>
        	<td colspan="2" bgcolor="#444444" align="center"><img src="_img/vilas/<?php if($dbr['renegado1']=='sim') echo 'akatsuki_'; ?><?php echo $vila; ?>.jpg" onmouseover="Tip('<div class=tooltip><?php echo $txtvila; ?></div>');" onmouseout="UnTip()" /></td>
            <td align="center">&nbsp;</td>
            <td colspan="2" bgcolor="#444444" align="center"><img src="_img/vilas/<?php if($dbr['renegado2']=='sim') echo 'akatsuki_'; ?><?php echo $vilai; ?>.jpg" onmouseover="Tip('<div class=tooltip><?php echo $txtvilai; ?></div>');" onmouseout="UnTip()" /></td>
        </tr>
        <tr>
        	<td colspan="2" align="center"><div class="sep"></div></td>
            <td align="center"></td>
            <td colspan="2" align="center"><div class="sep"></div></td>
        </tr>
        <tr style="height:17px;">
        	<td width="19%" align="right" style="background:#323232;padding-right:10px;">Nível:</td>
          <td width="23%" style="background:#323232;"><?php if($dbr['renegado1']=='sim') echo 'Nukenin'; else rankNinja($nivel[0]); ?> <b>[Nível <?php echo $nivel[0]; ?>]</b></td>
          <td width="15%"></td>
          <td width="19%" align="right" style="background:#323232;padding-right:10px;">Nível:</td>
          <td width="24%" style="background:#323232;"><?php if($dbr['renegado2']=='sim') echo 'Nukenin'; else rankNinja($nivel[1]); ?> <b>[Nível <?php echo $nivel[1]; ?>]</b></td>
      </tr>
        <tr style="height:17px;">
        	<td align="right" style="background:#2C2C2C;padding-right:10px;">Taijutsu:</td>
            <td style="background:#2C2C2C;"><b>[<?php echo $taijutsu[0]; ?>]</b></td>
            <td></td>
            <td align="right" style="background:#2C2C2C;padding-right:10px;">Taijutsu:</td>
            <td style="background:#2C2C2C;"><b>[<?php echo $taijutsu[1]; ?>]</b></td>
        </tr>
        <tr style="height:17px;">
        	<td align="right" style="background:#323232;padding-right:10px;">Ninjutsu:</td>
            <td style="background:#323232;"><b>[<?php echo $ninjutsu[0]; ?>]</b></td>
            <td></td>
            <td align="right" style="background:#323232;padding-right:10px;">Ninjutsu:</td>
            <td style="background:#323232;"><b>[<?php echo $ninjutsu[1]; ?>]</b></td>
        </tr>
        <tr style="height:17px;">
        	<td align="right" style="background:#2C2C2C;padding-right:10px;">Genjutsu:</td>
            <td style="background:#2C2C2C;"><b>[<?php echo $genjutsu[0]; ?>]</b></td>
            <td></td>
            <td align="right" style="background:#2C2C2C;padding-right:10px;">Genjutsu:</td>
            <td style="background:#2C2C2C;"><b>[<?php echo $genjutsu[1]; ?>]</b></td>
        </tr>
        <tr style="height:17px;">
        	<td align="right" style="background:#323232;padding-right:10px;">Energia:</td>
            <td style="background:#323232;"><b>[<?php echo $energia[0]; ?>]</b></td>
            <td></td>
            <td align="right" style="background:#323232;padding-right:10px;">Energia:</td>
            <td style="background:#323232;"><b>[<?php echo $energia[1]; ?>]</b></td>
        </tr>
        <tr>
        	<td colspan="2" align="center"><div class="sep"></div></td>
            <td align="center"></td>
            <td colspan="2" align="center"><div class="sep"></div></td>
        </tr>
        <tr>
        	<td colspan="2" bgcolor="#323232" style="text-align:center">
				<?php $i=0; if($equips1[0]=='') echo 'Nenhum equipamento.'; else {
					$itens='';
					do{
						if($i==0) $itens.=' AND'; else $itens.=' OR';
						$itens.=' id='.$equips1[$i];
						$i++;
					} while($i<count($equips1));
					$sqls=mysql_query("SELECT imagem FROM table_itens WHERE 1=1".$itens);
					$dbs=@mysql_fetch_assoc($sqls);
					do{ ?><img src="_img/equipamentos/<?php echo $dbs['imagem']; ?>.png" width="70" />
				<?php } while($dbs=@mysql_fetch_assoc($sqls)); } ?>

                 <div class="sep"></div>
                        <?php $i=0; if($equips3[0]=='') echo 'Nenhum animal lendario.'; else {
					$itens='';
					do{
						if($i==0) $itens.=' AND'; else $itens.=' OR';
						$itens.=' id='.$equips3[$i];
						$i++;
					} while($i<count($equips3));
					$sqlss=mysql_query("SELECT imagem FROM table_animais WHERE 1=1".$itens);
					$dbss=mysql_fetch_assoc($sqlss);
					do{ ?><img src="_img/equipamentos/<?php echo $dbss['imagem']; ?>.png" width="70" />
				<?php } while($dbss=@mysql_fetch_assoc($sqlss)); } ?>

                 <div class="sep"></div>
                  <div class="sep"></div>
                        <?php $i=0; if($equips5[0]=='') echo 'Nenhum selo ativo.'; else {
					$itens='';
					do{
						if($i==0) $itens.=' AND'; else $itens.=' OR';
						$itens.=' id='.$equips5[$i];
						$i++;
					} while($i<count($equips5));
					$sqlsss=mysql_query("SELECT imagem FROM table_selos WHERE 1=1".$itens);
					$dbsss=mysql_fetch_assoc($sqlsss);
					do{ ?><img src="_img/equipamentos/<?php echo $dbsss['imagem']; ?>.png" width="70" />
				<?php } while($dbsss=@mysql_fetch_assoc($sqlsss)); } ?>
				<div class="sep"></div>
                        <?php $i=0; if($equips7[0]=='') echo 'Nenhum portão do chakra.'; else {
					$itens='';
					do{
						if($i==0) $itens.=' AND'; else $itens.=' OR';
						$itens.=' id='.$equips7[$i];
						$i++;
					} while($i<count($equips7));
					$sqlssss=mysql_query("SELECT imagem FROM table_portoes WHERE 1=1".$itens);
					$dbssss=mysql_fetch_assoc($sqlssss);
					do{ ?><img src="_img/equipamentos/<?php echo $dbssss['imagem']; ?>.png" width="70" />
				<?php } while($dbssss=@mysql_fetch_assoc($sqlssss)); } ?>


                 <div class="sep"></div>
           <br>
             </td>
            <td align="center"></td>
            <td colspan="2" bgcolor="#323232" style="text-align:center">
				<?php $i=0; if($equips2[0]=='') echo 'Nenhum equipamento.'; else {
					$itens='';
					do{
						if($i==0) $itens.=' AND'; else $itens.=' OR';
						$itens.=' id='.$equips2[$i];
						$i++;
					} while($i<count($equips2));
					$sqls=mysql_query("SELECT imagem FROM table_itens WHERE 1=1".$itens);
					$dbs=@mysql_fetch_assoc($sqls);
					do{ ?><img src="_img/equipamentos/<?php echo $dbs['imagem']; ?>.png" width="70" />
				<?php } while($dbs=mysql_fetch_assoc($sqls)); } ?>

                <div class="sep"></div>
                        <?php $i=0; if($equips4[0]=='') echo 'Nenhum animal lendario.'; else {
					$itens='';
					do{
						if($i==0) $itens.=' AND'; else $itens.=' OR';
						$itens.=' id='.$equips4[$i];
						$i++;
					} while($i<count($equips4));
					$sqlss=mysql_query("SELECT imagem FROM table_animais WHERE 1=1".$itens);
					$dbss=mysql_fetch_assoc($sqlss);
					do{ ?><img src="_img/equipamentos/<?php echo $dbss['imagem']; ?>.png" width="70" />
				<?php } while($dbss=@mysql_fetch_assoc($sqlss)); } ?>

                 <div class="sep"></div>


                        <?php $i=0; if($equips6[0]=='') echo 'Nenhum selo ativo.'; else {
					$itens='';
					do{
						if($i==0) $itens.=' AND'; else $itens.=' OR';
						$itens.=' id='.$equips6[$i];
						$i++;
					} while($i<count($equips6));
					$sqlsss=mysql_query("SELECT imagem FROM table_selos WHERE 1=1".$itens);
					$dbsss=mysql_fetch_assoc($sqlsss);
					do{ ?><img src="_img/equipamentos/<?php echo $dbsss['imagem']; ?>.png" width="70" />
				<?php } while($dbsss=@mysql_fetch_assoc($sqlsss)); } ?>


                  <div class="sep"></div>
                        <?php $i=0; if($equips8[0]=='') echo 'Nenhum Portão do Chakra.'; else {
					$itens='';
					do{
						if($i==0) $itens.=' AND'; else $itens.=' OR';
						$itens.=' id='.$equips8[$i];
						$i++;
					} while($i<count($equips8));
					$sqlsssss=mysql_query("SELECT imagem FROM table_selos WHERE 1=1".$itens);
					$dbsssss=mysql_fetch_assoc($sqlsssss);
					do{ ?><img src="_img/equipamentos/<?php echo $dbsssss['imagem']; ?>.png" width="70" />
				<?php } while($dbsssss=@mysql_fetch_assoc($sqlsssss)); } ?>
                   <div class="sep"></div>

     </td>
             <td align="center"></td>
            <td colspan="2" bgcolor="#323232" style="text-align:center">
  <br>
	 <div class="sep"></div>

	<br>  </td>

            <td align="center"></td>
             </td>
        	<td colspan="2" align="center"><div class="sep"></div></td>
            <td align="center"></td>
            <td colspan="2" align="center"><div class="sep"></div></td>
        </tr>
        <tr>
        	<td colspan="2" bgcolor="#323232" style="text-align:center"><?php if($doujutsu[0]==0) echo 'Nenhum doujutsu.'; else { ?><img src="_img/doujutsus/<?php echo strtolower($txtdoujutsu1); ?>.jpg" /><?php } ?></td>
            <td></td>
            <td colspan="2" bgcolor="#323232" style="text-align:center"><?php if($doujutsu[1]==0) echo 'Nenhum doujutsu.'; else { ?><img src="_img/doujutsus/<?php echo strtolower($txtdoujutsu2); ?>.jpg" /><?php } ?></td>
        </tr>
        <tr>
        	<td colspan="2" align="center"><div class="sep"></div></td>
            <td align="center"></td>
            <td colspan="2" align="center"><div class="sep"></div></td>
        </tr>
    </table>
</div>
<div class="box_bottom"></div>

<div class="box_top">Relatorio do Combate</div>
<div class="box_middle">
	<?php
	if(file_exists('reports/r'.$dbr['id'].'.txt')){
		$fp=fopen('reports/r'.$dbr['id'].'.txt','r');
		$texto = fread($fp, filesize('reports/r'.$dbr['id'].'.txt'));
		echo $texto;
	} else echo '<div class="aviso">Relatório não encontrado.</div>';
	?>
</div>
<div class="box_bottom"></div>

<div class="box_top">Resultado do Combate</div>
<div class="box_middle">
	<table width="100%" cellpadding="0" cellspacing="0">
    	<tr style="background:url(_img/gradient.jpg) repeat-y;">
        	<td width="25%"><b><?php echo $dbr['usuario1']; ?></b></td>
            <td>causou <?php echo $danos[0]; ?> pontos de dano</td>
            <td>perdeu <?php echo $danos[1]; ?> pontos de energia</td>
        </tr>
        <tr>
        	<td colspan="3"><div class="sep"></div></td>
        </tr>
        <tr style="background:url(_img/gradient.jpg) repeat-y;">
        	<td><b><?php echo $dbr['usuario2']; ?></b></td>
            <td>causou <?php echo $danos[1]; ?> pontos de dano</td>
            <td>perdeu <?php echo $danos[0]; ?> pontos de energia</td>
        </tr>
        <tr>
        	<td colspan="3"><div class="sep"></div></td>
        </tr>
        <tr>
        	<td colspan="3"><div class="aviso"><b><?php echo $vencedor; ?></b> venceu o combate.<br /><span class="sub2"><?php echo $vencedor; ?> recebeu <?php echo number_format($dbr['yens'],2,',','.'); ?> yen<?php if($dbr['yens']>1) echo 's'; ?> e adquiriu <?php echo $exp[0]; ?> ponto<?php if($exp[0]>1) echo 's'; ?> de experiência;<br /><?php echo $perdedor; ?> apenas adquiriu <?php echo $exp[1]; ?> ponto<?php if($exp[1]>1) echo 's'; ?> de experiência.</span></div></td>
        </tr>
    </table>
</div>
<div class="box_bottom"></div>
<?php
@mysql_free_result($sqlr);
@mysql_free_result($sqls);
?>
