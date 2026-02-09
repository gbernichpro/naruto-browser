<td width="200" valign="top"><div align="center">
<td width="170"><div style="width:200px;">
<div class="alinhar" style="width:170px;">
<div id="msg" style="margin-bottom:4px;">
	</div>
<?php require_once('trava.php'); ?>
<?php
switch($db['vila']){
	case 1: $vila='folha'; if($db['renegado']=='sim') $txtvila='Akatsuki (Vila da Folha)'; else $txtvila='Vila da Folha'; break;
	case 2: $vila='areia'; if($db['renegado']=='sim') $txtvila='Akatsuki (Vila da Areia)'; else $txtvila='Vila da Areia'; break;
	case 3: $vila='som'; if($db['renegado']=='sim') $txtvila='Akatsuki (Vila do Som)'; else $txtvila='Vila do Som'; break;
	case 4: $vila='chuva'; if($db['renegado']=='sim') $txtvila='Akatsuki (Vila da Chuva)'; else $txtvila='Vila da Chuva'; break;
	case 5: $vila='nuvem'; if($db['renegado']=='sim') $txtvila='Akatsuki (Vila da Nuvem)'; else $txtvila='Vila da Nuvem'; break;
	case 6: $vila='nevoa'; if($db['renegado']=='sim') $txtvila='Akatsuki (Vila da Névoa)'; else $txtvila='Vila da Névoa'; break;
	case 8: $vila='pedra'; if($db['renegado']=='sim') $txtvila='Akatsuki (Vila da Pedra)'; else $txtvila='Vila da Pedra'; break;
	case 9: $vila='cachoeira'; if($db['renegado']=='sim') $txtvila='Akatsuki (Vila da Cachoeira)'; else $txtvila='Vila da Cachoeira'; break;
	case 10: $vila='neve'; if($db['renegado']=='sim') $txtvila='Akatsuki (Vila da Neve)'; else $txtvila='Vila da Neve'; break;
	case 11: $vila='grama'; if($db['renegado']=='sim') $txtvila='Akatsuki (Vila da Grama)'; else $txtvila='Vila da Grama'; break;
	case 99: $vila='folha'; $txtvila='Vila da Folha'; break;
} ?>
<?php if((!isset($_GET['p']))or(isset($_GET['p']))&&($_GET['p']<>'attack')){ ?>
<?php if((!isset($_GET['p']))or(isset($_GET['p']))&&($_GET['p']<>'view')&&($_GET['p']<>'prepare')){ ?>
<div id="msg" style="margin-bottom:4px;">
<?php 
		if($db['energia'] < $db['energiamax']){
?>
<script>
$(document).ready(function(){
$("#energia").click(function(){ top.location='?p=ramen'; });
});
</script>
<div>
<img style='opacity:1;' src='img/energia.png' id='energia' class='icons-no-menu' onmouseover="Tip('Regenere sua energia agora, indo ao <b>Ichiraku Bar</b>.')" onmouseout='UnTip();'></div>
<?php
}
?>
	<?php
	$sqlm=mysql_query("SELECT count(id) conta FROM mensagens WHERE destino=".$db['id']." AND status='naolido'");
	$dbm=@mysql_fetch_assoc ($sqlm);
	$sqla=mysql_query("SELECT count(id) conta FROM relatorios WHERE inimigoid=".$db['id']." AND status='nao'");
	$dba=@mysql_fetch_assoc ($sqla);
	if($dbm['conta']>0){
		echo '<div class="action"><a href="?p=messages">'.$dbm['conta'].' nova';
		if($dbm['conta']>1) echo 's';
		echo ' mensage';
		if($dbm['conta']>1) echo 'ns'; else echo 'm';
		echo '!</a></div>';
	}
	if($dba['conta']>0){
		echo '<div class="action"><a href="?p=reports">Você foi atacado '.$dba['conta'].' vez';
		if($dba['conta']>1) echo 'es';
		echo '!</a></div>';
	}
	?>
</div>

<?php } ?>

<?php
if((!isset($_GET['p']))or($_GET['p']=='view')or($_GET['p']=='prepare')){
	}
	else{
// CALCULO //
$tempo = time();
$calc = $tempo + 86400;
if ($db['premiodiario'] > $tempo){
<?php } else { ?>
	<script type="text/javascript">
	$(function(){
$(".receber").click(function(){
	var id = $(this).attr('id');
	$('#recebeajax').load("_inc/diario.php", {jogador: id});
		return false;
});
		return false;
});
	</script>
<div id="msg" style="margin-bottom:4px;">
<div id="recebeajax"></div>
<?php
echo '<div class="action"><a href="javascript:void(0)" class="receber" id="'.$c->encode($db['id'],$chaveuniversal).'"><b>Recompensa Diária</b></a>';
?>


</div>
<?php }}?>
<div align="center" style="background:url(_img/personagens/no_avatar.jpg) no-repeat top;height:150px;" id="tip-direita" original-title="Seu personagem é <b><?php echo $db['personagem']; ?></b>"><a href="<?php if($db['avatar']==0) echo '?p=avatar'; else echo '?p=config&type=avat'; ?>"><img src="_img/personagens/<?php echo $db['personagem']; ?>/<?php echo $db['avatar']; ?>.jpg" width="170" height="150" border="0" /></a></div><br>
<div align="center"><img src="_img/vilas/<?php if($db['renegado']=='sim') echo 'akatsuki_'; ?><?php echo $vila; ?>.jpg"  id="tip-cima" original-title="Você reside na vila: <br><b style='font-size:12px;'><?php echo $txtvila; ?></b>" /></div>
<?php } ?>
<div class="titulo">Principal</div>

<div class="shur"></div> <a href="?p=home">Inicio</a>
<div class="box3_bottom"></div>
<div class="shur"></div> <a href="?p=jornal">Jornal</a>
<div class="box3_bottom"></div>
<div class="shur"></div> <a href="?p=messages">Mensagens</a>
<div class="box3_bottom"></div>
<div class="shur"></div> <a href="?p=reports">Relatórios</a>
<div class="box3_bottom"></div>
<div class="shur"></div> <a href="?p=config">Configurações</a>
<div class="box3_bottom"></div>
<div class="titulo">Personagem</div>

<div class="shur"></div> <a href="?p=book">Bingo Book</a>
<div class="box3_bottom"></div>
<div class="shur"></div> <a href="?p=inventory">Inventário</a>
<div class="box3_bottom"></div>
<div class="shur"></div> <a href="?p=pontos">Atributos</a>
<div class="box3_bottom"></div>
<div class="shur"></div> <a href="?p=jutsus">Jutsus</a>
<div class="box3_bottom"></div>


<div class="titulo">Outros</div>

<div class="shur"></div> <a href="javascript:void(0);" onclick="document.getElementById('city').style.display='block'">Cidade</a>
<div class="box3_bottom"></div>
<div class="shur"></div> <a href="?p=rank">Ranking</a>
<div class="box3_bottom"></div>
<div class="shur"></div> <a href="?p=rankorg">Ranking [org]</a>
<div class="box3_bottom"></div>
<div class="shur"></div> <a href="?p=loteria">Loteria Ninja</a>
<div class="box3_bottom"></div>
<div class="shur"></div> <a href="?p=cassa">Cassino Ninja</a>
<div class="box3_bottom"></div>
<div class="shur"></div> <a href="?p=doarbanco">Banco yens</a>
<div class="box3_bottom"></div>
<div class="shur"></div> <a href="?p=akatsuki">Akatsuki</a>
<div class="box3_bottom"></div>
<div class="shur"></div> <a href="#" >Chat</a>
<div class="box3_bottom"></div>

<div class="titulo">Especiais</div>

<div class="shur"></div> <a href="?p=invasao">Invasão</a>
<div class="box3_bottom"></div>
<div class="shur"></div> <a href="?p=guerra">Guerra de Vilas</a>
<div class="box3_bottom"></div>
<div class="shur"></div> <a href="?p=torneio">Arena</a>
<div class="box3_bottom"></div>


<div class="titulo">Vip</div>

<div class="shur"></div> <a href="?p=vip">Vip</a>
<div class="box3_bottom"></div>
<div class="shur"></div> <a href="?p=credshop">Credshop</a>
<div class="box3_bottom"></div>
<?php require_once('friendlist.php'); ?>
<?php require_once('menu_comum.php'); ?>
</div>