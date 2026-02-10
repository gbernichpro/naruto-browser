<?php require_once('trava.php'); ?>
<?php

if($_GET['yens']) {
 if($db['creditos'] < 1){
			echo "<script>self.location='?p=credshop&msg=10'</script>"; return;
 }else
mysql_query("UPDATE usuarios SET  creditos=creditos-1 , yens=yens + 15000, yens_fat=yens_fat+ 15000, creditosusados=creditosusados +1 WHERE id=".$db['id']);
			echo "<script>self.location='?p=credshop&msg=12'</script>"; return;}

if($_GET['yens2']) {
 if($db['creditos'] < 3){
			echo "<script>self.location='?p=credshop&msg=10'</script>"; return;
 }else
mysql_query("UPDATE usuarios SET  creditos=creditos-3 , yens=yens + 45000, yens_fat=yens_fat+ 45000, creditosusados=creditosusados +3 WHERE id=".$db['id']);
			echo "<script>self.location='?p=credshop&msg=12'</script>"; return;}

if($_GET['yens3']) {
 if($db['creditos'] < 10){
			echo "<script>self.location='?p=credshop&msg=10'</script>"; return;
 }else
mysql_query("UPDATE usuarios SET  creditos=creditos-10 , yens=yens + 150000, yens_fat=yens_fat+ 150000, creditosusados=creditosusados +10 WHERE id=".$db['id']);
			echo "<script>self.location='?p=credshop&msg=12'</script>"; return;}

if($_GET['yens4']) {
 if($db['creditos'] < 50){
			echo "<script>self.location='?p=credshop&msg=10'</script>"; return;
 }else
mysql_query("UPDATE usuarios SET  creditos=creditos-50 , yens=yens + 750000, yens_fat=yens_fat+ 750000, creditosusados=creditosusados +20 WHERE id=".$db['id']);
			echo "<script>self.location='?p=credshop&msg=12'</script>"; return;}
      //Termina aki!!!!!!!!!!!!!!

 ?>





	<?php if(isset($_GET['msg'])){
		switch($_GET['msg']){
			case 1: $msg='Creditos Insuficientes!'; break;
			case 2: $msg='Troca De Creditos Por Yens Com Sucesso'; break;

		}
	echo '<div class="aviso">'.$msg.'</div><div class="sep"></div>';
	} ?>


<table border="0" width="100%">
<tr style="background: none repeat scroll 0% 0% rgb(50, 50, 50);" onmouseover="style.background='#310000'" onmouseout="style.background='#161616'">
    	<td align="center" width="140"><img src="_img/equipamentos/yens.png"></td>
        <td style="padding: 5px; text-align: center;" valign="top">
        	<b>15000,00 Yens</b><br>
            <span class="sub2">Com Yens Voce Podera Comprar Itens Especiais No Naruto a Lenda!.</span><br>
     </td>
        <td align="center" width="20%">
              <b>Preco</b><br />
            <span class="sub2">1 creditos</span><br><br>
            <form method="post" action="?p=credshop&amp;en=ok" onsubmit="var b=this.querySelector('input[type=submit]'); if(b) { b.value='Carregando...'; b.disabled=true; }">
            <input id="subm" name="subm" class="botao" value="Adiquirir" type="submit">            </form>
        </td>
  </tr>

<table border="0" width="100%">
<tr style="background: none repeat scroll 0% 0% rgb(50, 50, 50);" onmouseover="style.background='#310000'" onmouseout="style.background='#161616'">
    	<td align="center" width="140"><img src="_img/equipamentos/yens.png"></td>
        <td style="padding: 5px; text-align: center;" valign="top">
        	<b>45000,00 Yens</b><br>
            <span class="sub2">Com Yens Voce Podera Comprar Itens Especiais No Naruto a Lenda!.</span><br>
     </td>
        <td align="center" width="20%">
              <b>Preco</b><br />
            <span class="sub2">3 creditos</span><br><br>
            <form method="post" action="?p=credshopyn&yens2=ok" onsubmit="var b=this.querySelector('input[type=submit]'); if(b) { b.value='Carregando...'; b.disabled=true; }">
            <input id="subm" name="subm" class="botao" value="Adiquirir" type="submit">            </form>
        </td>
  </tr>

<table border="0" width="100%">
<tr style="background: none repeat scroll 0% 0% rgb(50, 50, 50);" onmouseover="style.background='#310000'" onmouseout="style.background='#161616'">
    	<td align="center" width="140"><img src="_img/equipamentos/yens.png"></td>
        <td style="padding: 5px; text-align: center;" valign="top">
        	<b>Pacote Ninja 150000,00 Yens</b><br>
            <span class="sub2">Com Yens Voce Podera Comprar Itens Especiais No Naruto a Lenda!.</span><br>
     </td>
        <td align="center" width="20%">
              <b>Preco</b><br />
            <span class="sub2">10 creditos</span><br><br>
            <form method="post" action="?p=credshopyn&yens3=ok" onsubmit="var b=this.querySelector('input[type=submit]'); if(b) { b.value='Carregando...'; b.disabled=true; }">
            <input id="subm" name="subm" class="botao" value="Adiquirir" type="submit">            </form>
        </td>
  </tr>

<table border="0" width="100%">
<tr style="background: none repeat scroll 0% 0% rgb(50, 50, 50);" onmouseover="style.background='#310000'" onmouseout="style.background='#161616'">
    	<td align="center" width="140"><img src="_img/equipamentos/yens.png"></td>
        <td style="padding: 5px; text-align: center;" valign="top">
        	<b>Pacote Ninja 750000,00 Yens</b><br>
            <span class="sub2">Com Yens Voce Podera Comprar Itens Especiais No Naruto a Lenda!.</span><br>
     </td>
        <td align="center" width="20%">
              <b>Preco</b><br />
            <span class="sub2">50 creditos</span><br><br>
            <form method="post" action="?p=credshopyn&yens4=ok" onsubmit="var b=this.querySelector('input[type=submit]'); if(b) { b.value='Carregando...'; b.disabled=true; }">
            <input id="subm" name="subm" class="botao" value="Adiquirir" type="submit">            </form>
        </td>
  </tr>








</table>

</div>

