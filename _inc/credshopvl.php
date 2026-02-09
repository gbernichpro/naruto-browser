<?php require_once('trava.php'); ?>
<?php
if($_GET['akt']) {
 $vila=antiinjection($_POST["vilamigration"]);
 if(($vila<>1)&&($vila<>2)&&($vila<>3)&&($vila<>4)&&($vila<>5)&&($vila<>6)&&($vila<>7)&&($vila<>8)&&($vila<>9)&&($vila<>10)&&($vila<>11)){
			echo "<script>self.location='?p=credshop&msg=23'</script>"; return;
 }
 if($db['creditos'] < 10){
			echo "<script>self.location='?p=credshop&msg=10'</script>"; return;
 }else
    mysql_query("UPDATE usuarios SET  creditos=creditos-10 , vila=".$vila." , renegado='nao' ,creditosusados=creditosusados +10 WHERE id=".$db['id']);
			echo "<script>self.location='?p=credshop&msg=9'</script>"; return;}





 ?>





	<?php if(isset($_GET['msg'])){
		switch($_GET['msg']){
			case 1: $msg='Creditos Insuficientes!'; break;
			case 2: $msg='Troca Com Sucesso!Aproveite Sua Nova Vila'; break;

		}
	echo '<div class="aviso">'.$msg.'</div><div class="sep"></div>';
	} ?>

<form method="post" action="?p=credshopvl&akt=ok" onsubmit="subm.value='Carregando';subm.disabled=true;">
<table border="0" width="100%">
<tr style="background: none repeat scroll 0% 0% rgb(50, 50, 50);" onmouseover="style.background='#310000'" onmouseout="style.background='#161616'">
    	<td align="center" width="140"><img src="_img/equipamentos/trocavila.png"></td>
        <td style="padding: 5px; text-align: center;" valign="top">
        	<b>Ticket Migrar de vila</b><br>
            <span class="sub2">Arrume sua mala e viaje para outra vila com este ticket escolha a vila para migrar!.</span><br>
              <select size="1" name="vilamigration" id="aaa">

<option value="1">Vila da Folha</option>
<option value="8">Vila da Pedra</option>
<option value="6">Vila da nevoa</option>
<option value="5">Vila da Nuvem</option>
<option value="3">Vila do Som</option>
<option value="2">Vila da Areia</option>
<option value="9">Vila da Cachoeira</option>
<option value="10">Vila da Neve</option>
<option value="11">Vila da grama</option>

</select>
     </td>
        <td align="center" width="20%">
              <b>Preco</b><br />
            <span class="sub2">10 creditos</span><br><br>

            <input id="subm" name="subm" class="botao" value="Adiquirir" type="submit">            </form>
        </td>
  </tr>








</table>

</div>

