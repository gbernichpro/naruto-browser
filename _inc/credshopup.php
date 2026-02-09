<?php

 if($_GET['en']) {
 if($db['creditos'] < 1){
			echo "<script>self.location='?p=credshop&msg=10'</script>"; return;
 }else
  mysql_query("UPDATE usuarios SET creditos=creditos-1, creditosusados=creditosusados+1, energia=".$db['energiamax']." WHERE id=".$db['id']);
		echo "<script>self.location='?p=credshop&msg=11'</script>"; return;}

 ?>



	<?php if(isset($_GET['msg'])){
		switch($_GET['msg']){
			case 1: $msg='Creditos insuficientes!'; break;
			case 2: $msg='Parabens! 100% Restaurado com sucesso'; break;
		}
	echo '<div class="aviso">'.$msg.'</div><div class="sep"></div>';
	} ?>

<table border="0" width="100%">
<tr style="background: none repeat scroll 0% 0% rgb(50, 50, 50);" onmouseover="style.background='#310000'" onmouseout="style.background='#161616'">
    	<td align="center" width="140"><img src="_img/equipamentos/energia.png"></td>
        <td style="padding: 5px; text-align: center;" valign="top">
        	<b>Recuperação Total Da Energia</b><br>
            <span class="sub2">100% De Restauração.</span><br>
     </td>
        <td align="center" width="20%">

            <span class="sub2">1 creditos</span><br><br>
            <form method="post" action="?p=credshopup&en=ok1" onsubmit="subm.value='Carregando';subm.disabled=true;">
            <input id="subm" name="subm" class="botao" value="Adiquirir" type="submit">            </form>
        </td>
  </tr>













</table>

</div>

