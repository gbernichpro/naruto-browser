<?php require_once('trava.php'); ?>
<?php
  // Patch Ver: 1.1 - Undefined Key Fix applied
  //junior_rios01@hotmail.com email para contato.
  //powered juniorrios
  //proibida copia ilegal direitos reservados.

if(isset($_POST['troca'])) {
    $pattern = "([_ _-_,_._>_`_´_<_~_^\/_?_°_\_:_;_§_|_!_¹_²_³_£_¢_¬_§_º_@_#_%_¨_&_*_+_{_}_*_])" ;
    if(preg_match('/' . $pattern . '/', $_POST['nick']))
    {
    die("<script>self.location='?p=rank&filter=".$valor."&msg=3'</script>");
    }
    $teste=mysql_query("Select * from usuarios where usuario='".antiinjection($_POST['nick'])."'");
    $test=mysql_fetch_assoc($teste);
    if($db['renegado']=='sim'){
    $valor=7;
    }
    else{    $valor=$db['vila'];
    }
    if(mysql_num_rows($teste)<=0){echo "<script>self.location='?p=rank&filter=".$valor."&msg=1'</script>"; return;}
    if($_POST['nick']==""){
			echo "<script>self.location='?p=rank&filter=".$valor."&msg=2'</script>"; return;
			}
      else{

        echo "<script>self.location='?p=view&view=".$_POST['nick']."'</script>";

        //Termina aki!!!!!!!!!!!!!!
              }
   }


?>

<?php

$vilaantiga = $db['vila'];
if($db['renegado']=='sim') $db['vila']=7;

$pg     = isset($_GET['pg']) ? (int)$_GET['pg'] : 0;
$filter = isset($_GET['filter']) ? (int)$_GET['filter'] : 0;

if(($filter > 11) || ($filter < 0)) { echo "<script>self.location='?p=home'</script>"; return; }

$filtro = " WHERE status<>'banido'";
if($filter == 7) {
    $filtro = " WHERE status<>'banido' AND renegado='sim'";
} elseif($filter > 0) {
    $filtro = " WHERE status<>'banido' AND renegado='nao' AND vila=" . $filter;
}

if(date('Y-m-d H:i:s') < $db['vip']) {
	$posicao = 0; 
    $stop = 0;
	if(($filter == 0) || ($filter == $db['vila'])) {
		$sqlc = mysql_query("SELECT id FROM usuarios" . $filtro . " ORDER BY nivel DESC, vitorias DESC, yens_fat DESC, derrotas ASC");
		$dbc  = mysql_fetch_assoc($sqlc);
		while($dbc && $stop == 0) {
			if($dbc['id'] == $db['id']) $stop = 1;
			$posicao++;
            $dbc = mysql_fetch_assoc($sqlc);
		}
	}
}

$timeout = time() - 900;
$sqlr    = mysql_query("SELECT usuario, vila, renegado, nivel, orgid, vitorias, derrotas, yens_fat, timestamp, personagem FROM usuarios" . $filtro . " ORDER BY nivel DESC, yens_fat DESC, vitorias DESC, derrotas ASC LIMIT " . ($pg * 50) . ", 50");
$dbr     = mysql_fetch_assoc($sqlr);
$sqlv    = mysql_query("SELECT count(id) conta FROM usuarios" . $filtro);
$dbv     = mysql_fetch_assoc($sqlv);




?>









<div class="box_top">Ranking</div>
<?php if(isset($_GET['msg'])){
		switch($_GET['msg']){
			case 1: $msg='<script>top.$.prompt("Usuario não existe!");</script>'; break;
            case 2: $msg='<script>top.$.prompt("Digite um nome de usuario para pesquisa!");</script>'; break;
            case 3: $msg='<script>top.$.prompt("Pesquisa invalida!");</script>'; break;


		}
	echo $msg;
	} ?>
<div class="box_middle"><div style="background: url(../_img/_detalhes/base2.PNG);width: 720px;height: 250px;">
<table cellpadding="0" cellspacing="0" width="712" height="230"><tbody><tr><td width="150">
<img width="142" style="" src="_img/_detalhes/msg/26.png"></td><td valign="top"><br><br><br>
<div style="margin-left: -345px;margin-top: 15px;height: 0px;">
<b id="title" style="font-family: impact;font-size: 20px;font-weight: normal;color: #ffffff;"  onmouseover="style.color='#F5F5F5'" onmouseout="color.color='#ffffff'">
&raquo; Ranking!</b></div><br>
<div style="margin-left: 0px;margin-top: 35px;font-family: arial;font-size: 12px;color: #fff;"><b>
Abaixo está o ranking atual do jogo. Você pode filtrar os ninjas por</br>
vila, utilizando o formulário abaixo. Se você é um jogador VIP, pode</br>
utilizar o link abaixo para ir diretamente para a sua posição no ranking.</br>
</br>
</div></td></tr></tbody></table></div><div class="sep"></div>
	<div align="center">
	        <form method="POST" action="?p=rank">
        <small>Procurar um ninja especifico de qualquer vila</small><br /> <input type="text" id="nick" name="nick" original-title="Procurar ninjas para caçar diretamente" class="jrrios" maxlenght="15" size="20">
        <input id="troca" name="troca" class="botao" value="Procurar" type="submit">
    </div></form><div class="sep"></div>
	<div align="center">
    <form method="get" action="?">
    <input type="hidden" name="p" value="rank" />
    <select id="filter" name="filter">
    	<option value="0">Geral</option>
    	<option value="1"<?php if($filter == 1) echo ' selected="selected"'; ?>>Vila da Folha</option>
        <option value="2"<?php if($filter == 2) echo ' selected="selected"'; ?>>Vila da Areia</option>
        <option value="3"<?php if($filter == 3) echo ' selected="selected"'; ?>>Vila do Som</option>
        <option value="4"<?php if($filter == 4) echo ' selected="selected"'; ?>>Vila da Chuva</option>
        <option value="5"<?php if($filter == 5) echo ' selected="selected"'; ?>>Vila da Nuvem</option>
        <option value="6"<?php if($filter == 6) echo ' selected="selected"'; ?>>Vila da Névoa</option>
        <option value="8"<?php if($filter == 8) echo ' selected="selected"'; ?>>Vila da Pedra</option>
        <option value="9"<?php if($filter == 9) echo ' selected="selected"'; ?>>Vila da Cachoeira</option>
        <option value="10"<?php if($filter == 10) echo ' selected="selected"'; ?>>Vila da Neve</option>
        <option value="11"<?php if($filter == 11) echo ' selected="selected"'; ?>>Vila da Grama</option>
        <option value="7"<?php if($filter == 7) echo ' selected="selected"'; ?>>Akatsuki</option>
    </select>&nbsp;
    <select id="pg" name="pg">
    	<?php $i=1; $pag=0; do{ ?>
        <option value="<?php echo $pag; ?>"<?php if($pg == $pag) echo ' selected="selected"'; ?>>De <?php echo $i; ?> à <?php echo $i+49; ?></option>
        <?php $i=$i+50; $pag++; } while($i<=$dbv['conta']); ?>
    </select>&nbsp;
    <input type="submit" id="subm" name="subm" class="botao" value="Filtrar" />
    </form>
               <div class="sep"></div>
    <?php if(date('Y-m-d H:i:s')<$db['vip']){ ?>
    <?php if(($filter == 0) || ($filter == $db['vila'])){ ?>
    <div class="aviso"><small>Sua posição no Ranking Geral</small>: <b><?php echo $posicao; ?>&ordm; lugar</b> [<a href="?p=rank&amp;filter=<?php echo $filter; ?>&amp;pg=<?php echo floor($posicao/50); ?>">Visualizar</a>]</div>
    <div class="sep"></div>
    <?php } ?>
    <?php } ?><div class="aviso">
    <table width="100%" cellpadding="0" cellspacing="0">
    	<tr style="background:url(_img/skins/naruto/gradient.jpg);font-weight:bold;">
    	<td width="60" align="left"></td>
    	<td width="50" align="left" height="20">P.</td>
        <td width="150" align="left">Ninja</td>
        <td width="30" align="left">Vila</td>
                <td width="40" align="left">Nv</td>
        <td width="55" align="left">Vit</td>
        <td width="75" align="left">Der</td>
        <td width="105" align="left">Yens Fat.</td>
            
        </tr>
        <tr>
        	<td colspan="9"><div class="sep"></div></td>
        </tr>
        <?php $i=1+(($pg)*50); if(mysql_num_rows($sqlr)==0) echo '<tr><td colspan="8"><div class="aviso">Nenhum ninja encontrado.</div></div></tr>'; else do{ ?>
        <tr class="table_dados" height="20">
		            <td align="left"><img src="_img/rank/<?php echo $dbr['personagem']; ?>.jpg" width="100" height="30"/></td>
        	<td align="left" style="font-size:14px;"><?php echo $i; ?>&ordm; <?php if($dbr['usuario']==$db['usuario']) echo '<span style="height:20px;background:#0099FF;font-size:14px;">&nbsp;</span>'; ?></td>
            <td align="left" style="font-size:14px;">&nbsp;<a href="?p=view&amp;view=<?php echo $dbr['usuario']; ?>" style="font-size:14px;font-weight:bold;"><?php echo $dbr['usuario']; ?></a></td>
            <td align="left" style="font-size:14px;"><img src="_img/rank/<?php if($dbr['vila']==10) echo '1'; else { if($dbr['renegado']=='sim') echo '7'; else echo $dbr['vila']; } ?>.png" /></td>


            <td align="left" style="font-size:14px;">[<?php echo $dbr['nivel']; ?>]</td>
            <td align="left" style="font-size:14px;"><?php echo $dbr['vitorias']; ?></td>
            <td align="left" style="font-size:14px;"><?php echo $dbr['derrotas']; ?></td>
            <td align="left" style="font-size:14px;"><?php echo number_format($dbr['yens_fat'],2,',','.'); ?></td>
            <td align="left" width="18" style="font-size:14px;"><span style="height:20px;background:<?php if($dbr['timestamp']>=$timeout) echo '#66FF00'; else echo '#FF0000'; ?>;">&nbsp;</span></td>
        </tr>
        <tr>
        	<td colspan="9"><div class="sep"></div></td>
        </tr>
        <?php $i++; } while($dbr=mysql_fetch_assoc($sqlr)); ?>
    </table></div>

<div align="center"><div id="div_rank"><script>carregar(1);</script></div></div>
    <fieldset style="text-align:center"><legend>Legenda</legend>
	    <span style="height:20px;background:#66FF00;font-size:14px;">&nbsp;</span> <b>Conectado</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <span style="height:20px;background:#FF0000;font-size:14px;">&nbsp;</span> <b>Desconectado</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<span style="height:20px;background:#0099FF;font-size:14px;">&nbsp;</span> <b>Minha posição</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    </fieldset>
</div>
</div>
<div class="box_bottom"></div>
<?php
@mysql_free_result($sqlr);
@mysql_free_result($sqlv);

?>