<?php require_once('trava.php'); ?>
<div class="box_top">Loteria ninja</div>
<div class="box_middle"><div style="background: url(../_img/_detalhes/base2.PNG);width: 720px;height: 250px;">
<table cellpadding="0" cellspacing="0" width="712" height="230"><tbody><tr><td width="150">
<img width="142" style="" src="_img/_detalhes/msg/49.png"></td><td valign="top"><br><br><br>
<div style="margin-left: -345px;margin-top: 15px;height: 0px;">
<b id="title" style="font-family: impact;font-size: 20px;font-weight: normal;color: #ffffff;"  onmouseover="style.color='#F5F5F5'" onmouseout="color.color='#ffffff'">
&raquo; Loteria Ninja</b></div><br>
<div style="margin-left: 0px;margin-top: 35px;font-family: arial;font-size: 12px;color: #fff;"><b>
<b>Tente sua sorte na loteria ninja, compre tickets e participe do sorteio</br>
semanal de equipamentos Por Jogador.</br>Equipamentos aqui sorteados não necessitam de requisito minimo<br /> para serem utilizados.
</br></b>
</div></td></tr></tbody></table></div>	<?php

	if(isset($_GET['msg'])){
		switch($_GET['msg']){
			case 1: $msg='Ticket(s) comprado(s)!'; return;
			case 2: $msg='Voce nao possui yens suficientes para comprar tantos tickets!'; break;
 		case 4: $msg='Necessario uma quantia maior que 0!'; break;

		}
	echo '<div class="aviso">'.$msg.'</div><div class="sep"></div>';
	}

	?><?php
$sqlp=mysql_query("SELECT * FROM settings where `name`='loteria'");
$loteria=mysql_fetch_assoc($sqlp);
$sqlpp=mysql_query("SELECT * FROM settings where `name`='end_lotto'");
$end=mysql_fetch_assoc($sqlpp);
$sqlppp=mysql_query("SELECT * FROM settings where `name`='vencedor'");
$vencedor=mysql_fetch_assoc($sqlppp);
$juniorrios=mysql_query("SELECT * FROM settings where `name`='adm'");
$adm=mysql_fetch_assoc($juniorrios);
$sqlpppp=mysql_query("SELECT * FROM settings where `name`='preco'");
$preco=mysql_fetch_assoc($sqlpppp);
$sqlppppp=mysql_query("SELECT * FROM settings where `name`='tic'");
$vendidos=mysql_fetch_assoc($sqlppppp);
$sqla=mysql_query("SELECT * FROM settings where `name`='lottery_premio'");
$premio=mysql_fetch_assoc($sqla);
$sqlaa=mysql_query("SELECT * FROM settings where `name`='last_winner'");
$ultimo=mysql_fetch_assoc($sqlaa);
$uiii=mysql_query("SELECT * FROM settings where `name`='tipo'");
$tipo=mysql_fetch_assoc($uiii);


$unc1 = "last_winner";
$unc2 = "vencedor";
$unc3 = "loteria";
$unc4 = "preco";
$unc5 = "end_lotto";
$unc6 = "tic";
$unc7 = "lottery_premio";
$unc8 = "lotto";




if ($loteria['value'] == t)
{

	if (time() > $end['value']){

	mysql_query("update `settings` set `value`='f' where `name`='".$unc3."'");
	$wpaodsla = mysql_query("select * from `lotto` order by RAND() limit 1");
	$ipwpwpwpa=mysql_fetch_assoc($wpaodsla);
$ganhad=mysql_query("SELECT * FROM usuarios where `id`=".$ipwpwpwpa['player_id']."");
$ganhou=mysql_fetch_assoc($ganhad);

	if ($tipo['value']=='creditos'){
$query = mysql_query("update `usuarios` set `creditos`=`creditos`+".$vencedor['value']." where `id`=".$ipwpwpwpa['player_id']."");
	mysql_query("update `settings` set `value`='".$ganhou['usuario']."' where `name`='".$unc1."'");
	mysql_query("update `settings` set `value`=".$vencedor['value']." where `name`='".$unc7."'");
	$premiorecebido = "" . $vencedor['value'] . " de creditos";
	}elseif ($tipo['value']=='item'){
	$itotuuejdb = mysql_query("select `nome` from `table_itens` where id=".$vencedor['value']."");
    $ioeowkewttttee=mysql_fetch_assoc($itotuuejdb);
    mysql_query("INSERT INTO inventario (usuarioid,itemid) values ('".$ipwpwpwpa['player_id']."','".$vencedor['value']."')");

		$premiorecebido = $ioeowkewttttee['nome'];
	}


$peoeajjwwa =mysql_query("select `usuario` from `usuarios` where `id`=".$ipwpwpwpa['player_id']."");
 $totkooowowow=mysql_fetch_assoc($peoeajjwwa);

	$query = mysql_query("update `settings` set `value`='".$totkooowowow['usuario']."' where `name`='".$unc1."'");
	$query = mysql_query("update `settings` set `value`='".$premiorecebido."' where `name`='".$unc7."'");
	$query = mysql_query("update `settings` set `value`=0 where `name`='".$unc6."'");
	$query = mysql_query("update `settings` set `value`=0 where `name`='".$unc5."'");
	$query = mysql_query("delete from `lotto`");

	echo "<fieldset><legend><b>A loteria está fechada</b></legend>\n";
	echo "<table>";
	echo "<tr>";
	echo "<td><b>Último ganhador:</b></td>";
	echo "<td>" . $totkooowowow['usuario'] . "</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td><b>Prêmio recebido:</b></td>";
	echo "<td>" . $premio['value'] . "</td>";
	echo "</tr>";
	echo "</table>";
	echo "</fieldset>";
	echo "<br/>";
	echo "<a href=\"home.php\">Voltar</a>.";



	exit;
	}

	if ($_POST['buy'])
	{
		$error = 0;

		if (!is_numeric($_POST['amount'])) {

		echo "O valor " . $_POST['for'] . " não é válido! <a href=\"?p=loteria\">Voltar</a>.";

		$error = 1;
		exit;
		}

		if ($_POST['amount'] < 1){

            	echo "<script>self.location='?p=loteria&msg=4'</script>";

		$error = 1;
		exit;
		}

		if ($_POST['amount'] > 99){

            	echo "<script>self.location='?p=loteria&msg=3'</script>";
        		$error = 1;
		exit;
        }

		$total = ceil($_POST['amount'] * $preco['value']);

		if ($total > $db['yens']){

            	echo "<script>self.location='?p=loteria&msg=2'</script>";
        		$error = 1;
		exit;
        }


		if ($error == 0){

			$query = mysql_query("update `usuarios` set yens=yens-".$total." where `id`=".$db['id']."");
			$query = mysql_query("update `settings` set value=value+".antiinjection($_POST['amount'])." where `name`='tic'");

		$num = antiinjection($_POST['amount']);

		$sql = "INSERT INTO lotto (player_id) VALUES";

		for ($i = 0; $i < $num; $i++)
		{
		    $sql .= "(".$db['id'].")" . (($i == $num - 1) ? "" : ", ");
		}

		$result=mysql_query($sql);


            	echo "<script>self.location='?p=loteria&msg=1'</script>";


		}

	}



	if ($tipo['value'] =='item'){
$itcheckedcheckondb=mysql_query("select * from `table_itens` where id=".$vencedor['value']."");
$itchecked=mysql_fetch_assoc($itcheckedcheckondb);
	$premio = $itchecked['nome'];
	$premiotype = 1;
	}else{
	$premio = "" . $vencedor['value'] . " de creditos";
	$premiotype = 2;
	}	echo "<table cellpadding=\"3\" cellspacing=\"0\">";
	echo "<tr align='left' style=\"background: url('_img/gradient2.jpg') repeat-y scroll 0% 0% transparent;\">";
	echo "<td><b>Premio:</b></td>";
	echo "<td>" . $premio . "</td>";
	echo "</tr>";

	echo "<tr align='left'>";
	echo "<td><b>Tempo Restante:</b></td>";
		$end = $end['value'] - time();
		$days = floor($end/60/60/24);
		$hours = $end/60/60%24;
		$minutes = $end/60%60;
		$comecaem = "$days dia(s) $hours hora(s) $minutes minuto(s)";
		$nova_data = date("d/m/Y G:i",  $end['value']);
	echo "<td><div class='aviso' id='mensagem'>" . $comecaem . " <a href=\"?p=loteria\">Atualizar</a></td></div>";
	echo "</tr>";

	echo "<tr align='left' style=\"background: url('_img/gradient2.jpg') repeat-y scroll 0% 0% transparent;\">";
	echo "<td><b>Preco por Ticket:</b></td>";
	echo "<td>" . $preco['value']. " yens</td>";
	echo "</tr>";

	echo "<tr align='left'>";
	echo "<td><b>Tickets Vendidos:</b></td>";
	echo "<td>" . $vendidos['value'] . "</td>";
	echo "</tr>";


	echo "<tr align='left' style=\"background: url('_img/gradient2.jpg') repeat-y scroll 0% 0% transparent;\">";
	echo "<td><b>Yens Arrecadados:</b></td>";
	echo "<td>" .(($preco['value'])*($vendidos['value'])) . "</td>";
	echo "</tr>";

	echo "<tr align='left'>";
	echo "<td><b>Loteria aberta por:</b></td>";
	echo "<td>" . $adm['value'] . "</td>";
	echo "</tr>";
 ?>
	    	<tr align='left' style="background:url(_img/gradient2.jpg) repeat-y;color:#FFFFAA;">
        	<td align="right" style="padding-right:10px;"><img src="_img/yens.png" width="14" height="14" align="absmiddle" /> <b>Meus Yens:</b></td>
            <td colspan="2"><b><?php echo number_format($db['yens'],2,',','.'); ?> yens</b></td>
        </tr>
        <?php

	echo "</table>";
echo"<div class=sep></div>";
	if ($tipo['value']=='creditos'){
	echo "<div style=\"background: url(_img/bar.png) repeat scroll 0% 0% transparent; height: 13px; width: 525px; padding: 3px; margin-left: -6px; margin-top: 0px;\"><center>A premiacao dessa loteria sera <b>" . $premio . "</b>.</center></div>";
	}else if ($tipo['value']=='item'){
	if ($itchecked['optimized'] == 10) {
	echo "<table width=\"100%\" bgcolor=\"#CEBBEE\">\n";
	}else{
	echo "<table width=\"100%\">\n";
	} ?>
<tr style="background: none repeat scroll 0% 0% rgb(50, 50, 50);" onmouseover="style.background='#2C2C2C'" onmouseout="style.background='#323232'">
    	<td align="center" width="140"><img src="_img/equipamentos/<?=$itchecked['imagem']?>.png"></td>
        <td style="padding: 5px; text-align: center;" valign="top">
        	<b><?=$itchecked['nome']?></b><br>
            <span class="sub2"><?=$itchecked['descricao']?>.</span><br>
              <b><?php if($itchecked['taijutsu']>0) echo '<img src="_img/equipamentos/up.png" width="14" height="14" align="absmiddle" /> [+'.$itchecked['taijutsu'].'] em Taijutsu<br />'; ?>
            <?php if($itchecked['ninjutsu']>0) echo '<img src="_img/equipamentos/up.png" width="14" height="14" align="absmiddle" /> [+'.$itchecked['ninjutsu'].'] em Ninjutsu<br />'; ?>
            <?php if($itchecked['genjutsu']>0) echo '<img src="_img/equipamentos/up.png" width="14" height="14" align="absmiddle" /> [+'.$itchecked['genjutsu'].'] em Genjutsu<br />'; ?></b>
        </td>

  </tr>
  <?php
	echo "</table>";


}

	$get=mysql_query("select * from `lotto` where `player_id`=".$db['id']."");
    $ok=mysql_num_rows($get);

   echo"<div class=sep></div>";
    echo "
  <table border=\"0\" width=\"100%\">
<tr>
  <td>
  ";
  	echo "<form method=\"POST\" action=\"?p=loteria\">";
	echo "<b>Quantia:</b> <input type=\"text\" name=\"amount\" value=\"1\" size=\"10\" maxlength=\"2\"/><input class=\"botao\" type=\"submit\" name=\"buy\" value=\"Comprar\">";
	echo "</form>";
	echo"


  </td>
  <td><b>Voce ja comprou:</b> " . $ok . " tickets.</td>
</tr>
</table>


    ";


	}else{

$ganhad=mysql_query("SELECT * FROM usuarios where `usuario`='".$ultimo['value']."'");
$ganhou=mysql_fetch_assoc($ganhad);
	echo "<fieldset style='background:#444444;'><legend><b>A loteria ninja esta fechada</b></legend>\n";
	echo "<table>";
	echo "<tr>";
	if($ultimo['value'] =='')
	echo "<td>";
	else
	echo "<td>
<a href=\"?p=view&view=".$ultimo['value']."\"><img src=\"_img/personagens/" . $ganhou['personagem'] . "/" . $ganhou['avatar'] . ".jpg\" width=\"80\" height=\"80\" border=\"0\" /></a>

    </td><td>";

	echo "<table>";
	echo "<tr>";
	echo "<td><b>Ultimo ganhador:</b></td>";
	echo "<td>" . $ultimo['value'] . "</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td><b>Premio recebido:</b></td>";
	echo "<td>" . $premio['value'] . "</td>";
	echo "</tr>";
	echo "<td><b>Loteria aberta por:</b></td>";
	echo "<td>" . $adm['value'] . "</td>";
	echo "</tr>";
	echo "</table>";


	echo "</td>";
	echo "</tr>";
	echo "</table>";


	echo "</fieldset> <br>";
	echo"<div class=aviso>A loteria esta fechada</div>";

}


?>

</div>
<div class="box_bottom"></div>