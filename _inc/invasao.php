<?php require_once('trava.php'); ?>

<?php require_once('funcoes.php');
$sqls = mysql_query("SELECT * FROM invasor ");
$dbs = mysql_fetch_assoc($sqls);
$usssq = mysql_query("SELECT * FROM usuarios where id='".antiinjection($_SESSION['logado'])."'");
$uss = mysql_fetch_assoc($usssq);
$bit=mysql_query("SELECT i.id,t.id itemid, t.nome,t.exp,t.porcentagem, t.imagem, t.categoria FROM inv_invasao i LEFT OUTER JOIN table_invshop t ON i.itemid=t.id WHERE i.usuarioid=".$db['id']." AND i.status='on'");
$junior=mysql_fetch_assoc($bit);
$bit2=mysql_query("SELECT i.id,t.id itemid, t.nome,t.exp,t.porcentagem, t.imagem, t.categoria='dano' FROM inv_invasao i LEFT OUTER JOIN table_invshop t ON i.itemid=t.id WHERE i.usuarioid=".$db['id']." AND i.categoria='dano' AND i.status='on'");
$junior2=mysql_fetch_assoc($bit2);
$diero=mysql_query("SELECT i.id,t.id itemid, t.nome, t.yens, t.yensmax, t.imagem, t.categoria FROM inv_invasao i LEFT OUTER JOIN table_invshop t ON i.itemid=t.id WHERE i.usuarioid=".$db['id']." AND i.categoria='yens' AND i.status='on'");
$ys=mysql_fetch_assoc($diero);
$percentual2 = $junior2['porcentagem'] / 100;
$taijutsufinal2=$percentual2*danoo($uss['taijutsu'],$uss['genjutsu']);
$taifin2=ceil($taijutsufinal2);
$adicionatai2=$taifin2;
if($junior2['porcentagem']>0){
$dano=danoo($uss['taijutsu'],$uss['genjutsu'])+$adicionatai2;
}
else{
$dano = danoo($uss['taijutsu'],$uss['genjutsu']);
}
$danoinvasao = danoo($uss['taijutsu'],$uss['genjutsu']);
$categoria = 'xp';
$dif = $db['nivel']+1;
$sqlshop = mysql_query("SELECT * FROM table_invshop WHERE reqnivel<$dif ORDER BY reqnivel ASC");
$dbshop = mysql_fetch_assoc($sqlshop);
$genjutsu=($dbs['reqgen']);
$nivel1=($dbs['nivelmin']);
$nivel2=($dbs['nivelmax']);
$yens1=($ys['yens']);
$yens2=($ys['yensmax']);
$exp1=($dbs['exp']);
$exp2=($dbs['expmax']);
$mpen=3;
$spen=0;

if(isset($_POST['buy_id'])){
	$buy = antiinjection($_POST['buy_id']);
	vn($buy);
	if($buy<1){ echo "<script>self.location='?p=home'</script>"; return; }
	$category = antiinjection($_POST['buy_cat']);
	if(($category<>'xp')&&($category<>'dano')){ echo "<script>self.location='?p=home'</script>"; return; }
	switch($category){
		case 'xp': $sqlb = mysql_query("SELECT valor,reqnivel,nome FROM table_invshop WHERE id='".antiinjection($buy)."'"); return;
		case 'dano': $sqlb = mysql_query("SELECT valor,reqnivel,nome  FROM table_invshop WHERE id='".antiinjection($buy)."'"); break;
	}
	if(mysql_num_rows($sqlb)==0){ echo "<script>self.location='?p=home'</script>"; return; }
	$dbb = mysql_fetch_assoc($sqlb);
	if(($dbb['vip']=='sim')&&(date('Y-m-d H:i:s')>=$db['vip'])){ echo "<script>self.location='?p=home'</script>"; return; }
	$valor = $dbb['valor'];
	if(date('Y-m-d H:i:s')<$db['vip']) $valor = floor($valor);
	if(($db['renegado']=='nao')&&($buy==1)){ }

	$bloq = 0;
	    if($category=='xp')
		if($db['nivel']<$dbb['reqnivel']) $bloq=9;
		if($category=='yens')
		if($db['nivel']<$dbb['reqnivel']) $bloq=9;
		if($category=='dano')
		if($db['nivel']<$dbb['reqnivel']) $bloq=9;
	if($bloq>0){ echo "<script>self.location='?p=invasao&msgg=".$bloq."'</script>"; return; }
	if($db['creditos']<$valor){ echo "<script>self.location='?p=invasao&msgg=1'</script>"; return; }
	$sqli = mysql_query("SELECT count(id) conta FROM inv_invasao WHERE usuarioid='".$db['id']."' AND itemid='".antiinjection($buy)."'");
    $dbi=mysql_fetch_assoc($sqli);
	if($dbi['conta']>0){ echo "<script>self.location='?p=invasao&msgg=8'</script>"; return; }
    $sqlbuscavida=mysql_query("select * from table_invshop where id=$buy");
    $sqlbusca=mysql_fetch_assoc($sqlbuscavida);
    $expira=date('Y-m-d H:i:s',time()+($sqlbusca['tempo']*24*60*60));
    $data=date('y-m-d H:i:s');
    mysql_query("UPDATE usuarios SET creditos=creditos-".$valor.", creditosusados=creditosusados+".$valor." WHERE id='".$db['id']."'");
    mysql_query("INSERT INTO inv_invasao (usuarioid,itemid,categoria,expira) VALUES (".$db['id'].",".$buy.",'".$category."','".$expira."')");
	mysql_query("INSERT INTO log_creditos (usuarioid,data, assunto, msg) VALUES (".$db['id'].",'".date('Y-m-d H:i:s')."','".$db['usuario']."', 'Acabou de comprar o item <b>".$dbb['nome']."</b>, da loja da invasão , foi inserido com sucesso</b>.')");
	echo "<script>self.location='?p=invasao&msgg=2'</script>";

}

require_once('Encrypt.php');
$c=new C_Encrypt();

if(isset($_GET['equipe'])){
	if(!isset($_GET['id'])){ echo "<script>self.location='?p=home'</script>"; return; }
	$id = antiinjection($_GET['id']);
	$sqli=mysql_query("SELECT usuarioid,categoria FROM inv_invasao WHERE id=".$id);
	$dbi=mysql_fetch_assoc($sqli);
	if($dbi['usuarioid']<>$db['id']){ echo "<script>self.location='?p=home'</script>"; return; }
	$categoria = $dbi['categoria'];
	$act = antiinjection($_GET['equipe']);
	vn($id);
	mysql_query("UPDATE inv_invasao SET status='off' WHERE usuarioid=".$db['id']." AND categoria='".$categoria."'");
	if($act=='on') mysql_query("UPDATE inv_invasao SET status='on' WHERE id=".$id);
	if($act=='on') echo "<script>self.location='?p=invasao&msgg=10'</script>"; else echo "<script>self.location='?p=invasao&msgg=11'</script>";
}

$invjunr=mysql_query("SELECT i.id,i.status,i.expira,t.categoria,t.descricao,t.nome,t.imagem,t.valor FROM inv_invasao i LEFT OUTER JOIN table_invshop t ON i.itemid=t.id WHERE i.usuarioid=".$db['id']." ORDER BY status ASC");
$sasukeadm=mysql_fetch_assoc($invjunr);


if($db['energia']<=0){
     mysql_query("UPDATE usuarios SET `energia`=0   WHERE id=".$db['id']);
}

		$soma=mktime(date('H'), date('i')+($mpen), date('s')+($spen));
		$penalidade=date('Y-m-d H:i:s',$soma);
$atual=date('Y-m-d H:i:s');
	if (($_POST['atacar']) and ($atual>$db['penalidade_fim']) and ($db['energia'] > 0))
	{
	if($db['genjutsu']<=$genjutsu){ echo "<script>self.location='?p=invasao&msg=1'</script>"; return; }
	if($db['nivel']<$nivel1){ echo "<script>self.location='?p=invasao&msg=2'</script>"; return; }
	if($db['nivel']>$nivel2){ echo "<script>self.location='?p=invasao&msg=3'</script>"; return; }
	if($db['energia']<25){ echo "<script>self.location='?p=invasao&msg=5'</script>"; return; }
	if($dbs['hp']>0){
    if(($dbs['hp']-$dano)<=0){
	$randpremio=rand(0,0);
    $exp=rand($exp1,$exp2);
	if(($ys['categoria']=='yens')&&($yens1>0)&&($yens2>0)){
	$yens=rand($yens1,$yens2);
	}
	else {
	$yens=0;
	}
	mysql_query("update `invasor` set `hp`=0,`vitorias`=vitorias+1 ");
	mysql_query("update `usuarios` set energia=energia-".$danoinvasao." , exp=exp+".$exp.", exptotal=exptotal+".$exp.", yens=yens+".$dbs['premio'].", yens_fat=yens_fat+".$dbs['premio'].", penalidade_fim='".$penalidade."'  WHERE id=".$db['id']);
   $msggg="<div class=aviso>Voce venceu o invasor, sua recompensa ja esta disponivel na sua pagina principal.</div><div class=sep></div>";
$por =$db['usuario'];
$query = mysql_query("update `invasor` set `derrotadopor`='".$por."', status='t', hp=0");
}else{
$randpremio=rand(0,0);
if ($dano==0){
$exp=0;
}
elseif($junior['exp']>0){
$exp=rand($exp1,$exp2)+$junior['exp'];

}
else{
$exp=rand($exp1,$exp2);
}
if(($ys['categoria']=='yens')&&($yens1>0)&&($yens2>0)){
	$yens=rand($yens1,$yens2);
	}
	else {
	$yens=0;
	}
mysql_query("update `invasor` set `hp`=hp-$dano,`vitorias`=vitorias+1,`premio`=premio+".$randpremio."");
mysql_query("update `usuarios` set energia=energia-".$danoinvasao." , exp=exp+".$exp.", exptotal=exptotal+".$exp.",yens=yens+".$yens.", yens_fat=yens_fat+".$yens.", penalidade_fim='".$penalidade."'  WHERE id=".$db['id']);

}
if($db['energia']<=0){
     mysql_query("UPDATE usuarios SET `energia`=0   WHERE id=".$db['id']);
}
    if($yens>0){
    $totalyn="<li><b>Você obteve ".$yens." de yens</b></li>";
    }
    else{    $totalyn="";
    }    $msgg="<div class=aviso><ul>
    <li><b>Você atacou o invasor tirando um total de ".$dano." de energia do invasor.</b></li>
    <li><b>".$dbs['nome']." te atacou e você perdeu ". $danoinvasao." de energia.</b></li>
    <li><b>Você obteve ".$exp." de experiencia</b></li>
    ".$totalyn."

    </div><div class=\"sep\"></div> ";
    }else{


    }
    }else if($db['energia']<=0){
     mysql_query("UPDATE usuarios SET `energia`=0   WHERE id=".$db['id']);
     $msgg="<div class=aviso>Voce nao possui energia suficientes para atacar</div><div class=sep></div>";
}


?>
<div class="box_top">Invasao</div>
<div class="box_middle" >
	<?php if(isset($_GET['msg'])){
		switch($_GET['msg']){
			case 1: $msg='Genjutsu Insuficiente para atacar!'; break;
			case 2: $msg='Seu nivel é menor que o nivel minimo'; break;
			case 3: $msg='Seu nivel é maior que o nivel maximo'; break;
			case 5: $msg='Energia insuficiente para atacar sua energia é menor que 25 utilize um ramem!.'; break;
		}
	echo '<div class="aviso">'.$msg.'</div><div class="sep"></div>';
	} ?>
	<div style="background: url(../_img/_detalhes/base2.PNG);width: 720px;height: 250px;">
<table cellpadding="0" cellspacing="0" width="712" height="230"><tbody><tr><td width="150">
<img width="142" style="" src="_img/_detalhes/msg/6.png"></td><td valign="top"><br><br><br>
<div style="margin-left: -345px;margin-top: 15px;height: 0px;">
<b id="title" style="font-family: impact;font-size: 20px;font-weight: normal;color: #ffffff;"  onmouseover="style.color='#F5F5F5'" onmouseout="color.color='#ffffff'">
&raquo; Invasão!</b></div><br>
<div style="margin-left: 0px;margin-top: 35px;font-family: arial;font-size: 12px;color: #fff;"><b>
Aqui você encontrará os nps mais forte do jogo!<br />
Derrote eles e ganhe experiência e yens</br>
Lembrando que todos os dias, sera aberto uma invasão.</br>
</br>
</div></td></tr></tbody></table></div>
   <style>
.field_set{
   border:#FF0D0D 2px solid;
   background-color:#000000;
   -moz-border-radius: 5px;
   -webkit-border-radius: 5px;
}
</style>
<?php
$max=100;
?>
<fieldset class="field_set">
<table width="100%" cellpadding="0" cellspacing="0">
<tbody><tr>
<td><img src="_img/personagens/<?php echo $db['personagem']; ?>/<?php echo $db['avatar']; ?>.jpg" width="80" id="tip-direita" original-title="Você" height="80" border="0"></td>

 <td valign="top" align="left" style="padding-right:10px;"><div align="right"><img src="template/ico_exp.png" width="17" height="17"></div></td>
 <td valign="top" height="22" style="background:url(_img/bars/juniorviadosqn.png) no-repeat;"><img src="_img/bars/left_bar_exp.png"><img src="_img/bars/center_bar_exp.png" width="<?php echo (($db['exp']*$max)/$db['expmax']); ?>" height="19"><img src="_img/bars/right_bar_exp.png"></td>
 <td valign="top"><b>|<small> <?php echo $db['exp']; ?> / <?php echo $db['expmax']; ?> </small>|</b></td>

 <td valign="bottom" align="left" style="padding-right:10px;"><div align="right"><img src="template/ico_hp.png" width="16" height="16"></div></td>
 <td valign="bottom"><img src="_img/bars/left_bar_en.png"><img src="_img/bars/center_bar_en.png" width="<?php echo (($db['energia']*$max)/$db['energiamax']); ?>" height="19"><img src="_img/bars/right_bar_en.png"></td>
 <td valign="bottom"><b>|<small> <?php echo $db['energia']; ?> / <?php echo $db['energiamax']; ?> </small>|</b></td>
</tr>



</tbody></table>
</fieldset>
<?php
	if(isset($_GET['msgg'])){
		switch($_GET['msgg']){
			case 1: $msg='Creditos insuficientes para comprar este item.'; break;
			case 2: $msg='Item comprado com sucesso! Visite seu <a href="?p=inventory">inventário</a> agora mesmo!'; break;
			case 3: $msg='Taijutsu insuficiente para comprar este item.'; break;
			case 4: $msg='Nível insuficiente para desbloquear este personagem.'; break;
			case 5: $msg='Personagem desbloqueado!'; break;
			case 6: $msg='Genjutsu insuficiente para comprar este item.'; break;
			case 7: $msg='Taijutsu, Ninjutsu ou Genjutsu insuficiente para comprar este item.'; break;
			case 8: $msg='<script>top.$.prompt("Você ja possui este item no seu inventario!");</script>'; break;
			case 9: $msg='Nível insuficiente para adiquirir este item.'; break;
		    case 10: $msg='Item equipado com sucesso!'; break;
			case 11: $msg='Item desequipado com sucesso!'; break;
		}
	echo '<div class="aviso">'.$msg.'</div><div class="sep"></div>';
	}
	?>


<div class="sep"></div>
    	<?php 	if ($_POST['atacar']){
	echo ''.$msggg.'';
	} ?>
    <?php 	if ($_POST['atacar']){
	echo ''.$msgg.'';
	} ?>
	 <?php
if($dbs['hp']<=0){?>
<div class="aviso" align="center">Invasão encerrada aguarde as proximas horas!</div><div class="sep"></div>
<?php } ?>

<?php
$sqls=mysql_query("SELECT * FROM invasor");
$dbs=mysql_fetch_assoc($sqls);
?>
	<table width="100%" cellpadding="0" cellspacing="0">
<tr><?php if($dbs['hp']<=0)
echo '';
else 
echo '<td width="200" valign="top"><img src="_img/personagens/'.$dbs['nome'].'/0.jpg" border="0">';
?>


</td><td>

	<table width="100%" cellpadding="0" cellspacing="0">


        <tr style="background:url(_img/gradient.jpg) repeat-y;">
        	<td align="left" style="padding-left:10px;"><b>Invasor</b></td>
      <td colspan="2"><?php echo $dbs['nome']; ?></td>
        </tr>
        <tr>
        	<td align="left" style="padding-left:10px;"><b>Ataques</b></td>
          <td colspan="2"><?php echo $dbs['vitorias']; ?></td>
        </tr>
                <tr style="background:url(_img/gradient.jpg) repeat-y;">
        	<td align="left" style="padding-left:10px;"><b>Hp de <?php echo $dbs['nome']; ?></b></td>
          <td colspan="2"><?php echo $dbs['hp']; ?> / <?php echo $dbs['hpmaximo']; ?></td>
        </tr>
        <tr>



        	<td align="left" style="padding-left:10px;"><b>Nivel Requerido:</b></td>
          <td colspan="2"><?php echo $dbs['nivelmin']; ?> Até <?php echo $dbs['nivelmax']; ?></td>
        </tr>
        <tr>
                <tr style="background:url(_img/gradient.jpg) repeat-y;">
        	<td align="left" style="padding-left:10px;"><b>Genjutsu Minimo</b></td>
          <td colspan="2"><?php echo $dbs['reqgen']; ?></td>
        </tr>

        	<td align="left" style="padding-left:10px;"><b>Premiacao final:</b></td>
          <td colspan="2"><?php echo number_format($dbs['premio'],2,',','.'); ?> yens</b></td>
        </tr>
              <tr style="background:url(_img/gradient.jpg) repeat-y;">
        <td align="left" style="padding-left:10px;"><b>Experiencia:</b></td>

          <td colspan="2"><?php echo $dbs['exp']; ?> Até <?php echo $dbs['expmax']; ?></td>

        </tr>


        	<td align="left" style="padding-left:10px;"><b>Data/Inicio da invasão:</b></td>
          <td colspan="2"><?php echo $dbs['data']; ?></b></td>
        </tr>
          <tr style="background:url(_img/gradient.jpg) repeat-y;">
        <td align="left" style="padding-left:10px;"><b>Aberto por:</b></td>

          <td colspan="2"><?php echo $dbs['abertopor']; ?></td>

        </tr>

 <?php
if($dbs['hp']<=0){?>                <tr style="background:url(_img/gradient.jpg) repeat-y;">
        	<td align="left" style="padding-left:10px;"><b>Vencedor</b></td>
          <td colspan="2"><?php echo $dbs['derrotadopor']; ?></td>
        </tr>
<?php } ?>




</table>
</td>
</tr></table>


<?php
$atual=date('Y-m-d H:i:s');
if(($dbs['hp']>0) and ($atual>$db['penalidade_fim'])){?>

 <div class="sep"></div><center><form method="POST" action="?p=invasao">
<input class="botao" type="submit" name="atacar" value="Atacar <?php echo $dbs['nome']; ?>">
</form> </center>
<?php } ?>

<?php


if($atual<$db['penalidade_fim']){
	$fim=$db['penalidade_fim'];
	$sqltempo=mysql_fetch_assoc(mysql_query("SELECT timediff('$fim','$atual') as fim"));
	$fim=$sqltempo['fim'];
	$msgconc="<script>self.location='?p=invasao'</script>";
	$msg='Você acabou de sair de uma batalha.<br />Faltam <b><span id="pen_tempo">'.$fim.'</span></b> para liberar o proximo ataque.';
}
?>
<script language="javascript" type="text/javascript">
var conc=0;
function calculafim(div,divtotal){
	if(conc==0){
	var navegador=navigator.appName;
	var tmp = document.getElementById(div).innerHTML.split(":");
	var s = tmp[2];
	var m = tmp[1];
	var h = tmp[0];
	s--;
	if (s < 00){ s = 59;	m--; }
	if (m < 00){ m = 59;	h--; };
	s = new String(s); if (s.length < 2) s = "0" + s;
	m = new String(m); if (m.length < 2) m = "0" + m;
	h = new String(h); if (h.length < 2) h = "0" + h;

	var temp = h + ":" + m + ":" + s;

	document.getElementById(div).innerHTML = temp;
	document.getElementById(div).value = temp;
	atualiza(div,divtotal);
	}
}
<?php if($atual<$db['penalidade_fim']) echo "window.setInterval('calculafim(\"pen_tempo\",\"mensagem\")',1000);"; ?>
function atualiza(div,divtotal){
  	if((document.getElementById(div).value) < "00:00:01"){
  		self.location="?p=invasao";
  		conc=1;
	}
}
</script>

	<?php
	if($atual<$db['penalidade_fim'])
    	echo " <div class=\"sep\"></div><div class=aviso>".$msg."</div>";
	else
		echo $msgconc;
	?>



 <div class="sep"></div>

            <div align="center"><h1><b><img src="_img/shopesp2.png" width="" height="" alt="" border="0"></b></big></h1>
            <div class="sep"></div>
 <div class="box_middle"></div><small>Seja bem vindo a loja de itens exclusivamente para a invasão equipamentos usaveis que você poderá obter com creditos para um melhor desempenho ou ganho na invasão estes items tem duração de dias caso use-o ou não será expirado.  </small><div class="sep"></div>
	<div style="background:url(_img/gradient.jpg) repeat-y;color:#FFFFAA;"><img src="_img/yens.png" width="14" height="14" /> <b>Meus Creditos: <?php echo number_format($db['creditos'],2,',','.'); ?> Creditos</b></div><div class="sep"></div>
 <table width="100%" cellpadding="0" cellspacing="1">


    <?php if(mysql_num_rows($invjunr)>0) do{ ?>
    <tr>
    	<td colspan="2"><div class="sep"></div></td>
    </tr>
    <tr class="table_dados" style="background:#323232;" onmouseover="style.background='#2C2C2C'" onmouseout="style.background='#323232'">
    	<td align="center" width="140"><img src="_img/equipamentos/<?php echo $sasukeadm['imagem']; ?>.png" /></td>
        <td style="padding:5px;">
        	<b><?php echo $sasukeadm['nome']; ?></b><br />
            <span class="sub2"><?php echo $sasukeadm['descricao']; ?></span><br />
             <span class="sub2"><font color='#FFFFFF'><b>Duração do item  </b></font>: <font color='#00FF00'><small><b><?php echo $sasukeadm['expira'];?></b></small></font></span><br />
           <a href="?p=invasao&equipe=<?php if($sasukeadm['status']=='off') echo 'on'; else echo 'off'; ?>&id=<?php echo $sasukeadm['id']; ?>"><?php if($sasukeadm['status']=='off') echo 'Equipar'; else echo 'Retirar'; ?></a>
          </td>
  	</tr>
    <?php } while($sasukeadm=mysql_fetch_assoc($invjunr)); ?>
    </table>
    <?php if((mysql_num_rows($invjunr)==0)){ ?>
    <div class="sep"></div>
    <div class="aviso">Você não possui nenhum item para utilizar na invasão.</div>
    <?php } ?>


<?php
@mysql_free_result($invjunr);
?>
 <div class="sep"></div>
   <div align="center"><h1><b><img src="_img/shopesp.png" width="" height="" alt="" border="0"></b></big></h1>
  <div class="sep"></div>

      <table width="100%" cellpadding="0" cellspacing="1">
  <?php if(mysql_num_rows($sqlshop)==0) echo '<tr><td colspan="3"><div class="aviso">Nenhum item encontrado.</div></td></tr>'; else do{ if(date('Y-m-d H:i:s')<$db['vip']) $dbshop['valor']=$dbshop['valor']; ?>
      <?php
    $texto="<table border=0 width=100%><div style='float:left;'><font color='#FFFFFF'><b>".$dbshop["nome"]."</b></font></div><div style='float:right;'><font color='#00FF00'><b>Detalhes</b></font></div>";
    if($dbshop['categoria']=='xp'){
    $texto.="<table border=0 width=100%><tr><td valign=top align='left' ><table border=0 width=100%><tr><td width=50 align='left' ><tr><td width=50 align='left'><b>Nivel Requerido</b></td><td>".$dbshop["reqnivel"]."</td></tr></table></td><td align='left'><table border=0 width=100%><tr><td width=50 align='left'><b class=add>Experiencia:</b></td><td align='left'> +".$dbshop["exp"]."</td></tr><tr><td width=50 align='left'><b class=add>Duração:</b></td><td align='left'> +".$dbshop["tempo"]." Dias</td></tr><tr><td width=50 align='left'><b class=add></b></td><td> </td></tr></table></td></tr></table>";
                                }
    else if($dbshop['categoria']=='dano'){
    $texto.="<table border=0 width=100%><tr><td valign=top align='left' ><table border=0 width=100%><tr><td width=50 align='left' ><tr><td width=50 align='left'><b>Nivel Requerido</b></td><td>".$dbshop["reqnivel"]."</td></tr></table></td><td align='left'><table border=0 width=100%><tr><td width=50 align='left'><b class=add>Dano:</b></td><td align='left'> +".$dbshop["porcentagem"]." %</td></tr><tr><td width=50 align='left'><b class=add>Duração:</b></td><td align='left'> +".$dbshop["tempo"]." Dias</td></tr><tr><td width=50 align='left'><b class=add></b></td><td> </td></tr></table></td></tr></table>";
                                }
    else if($dbshop['categoria']=='yens'){
    $texto.="<table border=0 width=100%><tr><td valign=top align='left' ><table border=0 width=100%><tr><td width=50 align='left' ><tr><td width=50 align='left'><b>Nivel Requerido</b></td><td>".$dbshop["reqnivel"]."</td></tr></table></td><td align='left'><table border=0 width=100%><tr><td width=50 align='left'><b class=add>Yens Random:</b></td><td align='left'> ".$dbshop["yens"]."-".$dbshop["yensmax"]." Yens</td></tr><tr><td width=50 align='left'><b class=add>Duração:</b></td><td align='left'> +".$dbshop["tempo"]." Dias</td></tr><tr><td width=50 align='left'><b class=add></b></td><td> </td></tr></table></td></tr></table>";
                                }
    $texto.="<div class=sep></div>";
    $texto.="<table border=0 width=100%><tr><td width=50 align='left'>Preço:".number_format($dbshop['valor'],2,',','.')." Creditos.</td></tr>";
    ?>

    <tr style="background:#323232;" onmouseover="style.background='#2C2C2C'" onmouseout="style.background='#323232'">
    	<td align="center" width="140"><img src="_img/equipamentos/<?php echo $dbshop['imagem']; ?>.png" id="tip-direita" original-title="<?=$texto?>"/></td>
        <td valign="top" style="padding:5px;text-align:center;">
        	<b><?php echo $dbshop['nome']; ?></b><br />
            <span class="sub2"><?php echo $dbshop['descricao']; ?></span><br />

      </td>
        <td align="center" width="20%">
        	<b>Nivel Mínimo</b><br />
            <span class="sub2"><?php echo $dbshop['reqnivel']; ?> </span><br /><br />
            <b>Valor Unitário</b><br />
            <span class="sub2"><?php echo number_format($dbshop['valor'],2,',','.'); ?> Creditos</span><br /><br />
            <form method="post" action="?p=invasao" onsubmit="subm.value='Carregando...';subm.disabled=true;">
            <input type="hidden" id="buy_id" name="buy_id" value="<?php echo $dbshop['id']; ?>" />
            <input type="hidden" id="buy_cat" name="buy_cat" value="<?php echo $dbshop['categoria']; ?>" />
           <input type="submit" id="subm" name="subm" class="botao" value="Comprar" />
            </form>
        </td>
  </tr>
    <?php } while($dbshop=mysql_fetch_assoc($sqlshop)); ?>
</table>
<?php
@mysql_free_result($sqlshop);
?>
   <?php echo $yens;?>
 </fieldset>





 <div class="sep"></div>
</div>
</div>
</div>
</div>
</div>
<div class="box_bottom"></div>

