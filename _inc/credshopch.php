<?php
          // Script começa aki
 if($_GET['tobi']) {
 if($db['creditos'] < 20){
			echo "<script>self.location='?p=credshop&msg=10'</script>"; return;
 }else
  mysql_query("UPDATE usuarios SET creditos=creditos-20, creditosusados=creditosusados+20 WHERE id=".$db['id']);
  mysql_query("UPDATE personagens SET tobi=1 WHERE usuarioid=".$db['id']);
		echo "<script>self.location='?p=credshop&msg=16'</script>"; return;}
                    //fim do script by juniorrios

                     // Script começa aki
 if($_GET['nidaime']) {
 if($db['creditos'] < 25){
			echo "<script>self.location='?p=credshop&msg=10'</script>"; return;
 }else
  mysql_query("UPDATE usuarios SET creditos=creditos-25, creditosusados=creditosusados+25 WHERE id=".$db['id']);
  mysql_query("UPDATE personagens SET nidaime=1 WHERE usuarioid=".$db['id']);
		echo "<script>self.location='?p=credshop&msg=16'</script>"; return;}
                    //fim do script by juniorrios
                      // Script começa aki
 if($_GET['senju']) {
 if($db['creditos'] < 30){
			echo "<script>self.location='?p=credshop&msg=10'</script>"; return;
 }else
  mysql_query("UPDATE usuarios SET creditos=creditos-30, creditosusados=creditosusados+30 WHERE id=".$db['id']);
  mysql_query("UPDATE personagens SET senju=1 WHERE usuarioid=".$db['id']);
		echo "<script>self.location='?p=credshop&msg=16'</script>"; return;}
                    //fim do script by juniorrios
                     // Script começa aki
 if($_GET['mizukage']) {
 if($db['creditos'] < 25){
			echo "<script>self.location='?p=credshop&msg=10'</script>"; return;
 }else
  mysql_query("UPDATE usuarios SET creditos=creditos-25, creditosusados=creditosusados+25 WHERE id=".$db['id']);
  mysql_query("UPDATE personagens SET mizukage=1 WHERE usuarioid=".$db['id']);
		echo "<script>self.location='?p=credshop&msg=16'</script>"; return;}
                    //fim do script by juniorrios


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
    	<td align="center" ><img src="_img/personagens/tobi/1.jpg" width="100" height="100"></td>
        <td style="padding: 5px; text-align: center;" valign="top">
        	<b>Liberar Tobi</b><br>
            <span class="sub2">Atenção após a compra vá em configuração ativar seu personagem.</span><br>
     </td>
        <td align="center" width="20%">
            <b>Preço</b><br />
            <span class="sub2">20 creditos</span><br><br>
            <form method="post" action="?p=credshopch&tobi=ok" onsubmit="subm.value='Carregando';subm.disabled=true;">
            <input id="subm" name="subm" class="botao" value="Adiquirir" type="submit">            </form>
        </td>
  </tr>


   <table border="0" width="100%">
<tr style="background: none repeat scroll 0% 0% rgb(50, 50, 50);" onmouseover="style.background='#310000'" onmouseout="style.background='#161616'">
    	<td align="center" ><img src="_img/personagens/nidaime/1.jpg" width="100" height="100"></td>
        <td style="padding: 5px; text-align: center;" valign="top">
        	<b>Liberar Senju Tobirama</b><br>
            <span class="sub2">Atenção após a compra vá em configuração ativar seu personagem.</span><br>
     </td>
        <td align="center" width="20%">
            <b>Preço</b><br />
            <span class="sub2">25 creditos</span><br><br>
            <form method="post" action="?p=credshopch&nidaime=ok" onsubmit="subm.value='Carregando';subm.disabled=true;">
            <input id="subm" name="subm" class="botao" value="Adiquirir" type="submit">            </form>
        </td>
  </tr>

   <table border="0" width="100%">
<tr style="background: none repeat scroll 0% 0% rgb(50, 50, 50);" onmouseover="style.background='#310000'" onmouseout="style.background='#161616'">
    	<td align="center"><img src="_img/personagens/senju/1.jpg"width="100" height="100"></td>
        <td style="padding: 5px; text-align: center;" valign="top">
        	<b>Liberar Senju Hashirama</b><br>
            <span class="sub2">Atenção após a compra vá em configuração ativar seu personagem.</span><br>
     </td>
        <td align="center" width="20%">
           <b>Preço</b><br />
            <span class="sub2">30 creditos</span><br><br>
            <form method="post" action="?p=credshopch&senju=ok" onsubmit="subm.value='Carregando';subm.disabled=true;">
            <input id="subm" name="subm" class="botao" value="Adiquirir" type="submit">            </form>
        </td>
  </tr>
<table border="0" width="100%">
<tr style="background: none repeat scroll 0% 0% rgb(50, 50, 50);" onmouseover="style.background='#310000'" onmouseout="style.background='#161616'">
    	<td align="center"><img src="_img/personagens/mizukage/1.jpg"width="100" height="100"></td>
        <td style="padding: 5px; text-align: center;" valign="top">
        	<b>Liberar Mizukage</b><br>
            <span class="sub2">Atenção após a compra vá em configuração ativar seu personagem.</span><br>
     </td>
        <td align="center" width="20%">
           <b>Preço</b><br />
            <span class="sub2">25 creditos</span><br><br>
            <form method="post" action="?p=credshopch&mizukage=ok" onsubmit="subm.value='Carregando';subm.disabled=true;">
            <input id="subm" name="subm" class="botao" value="Adiquirir" type="submit">            </form>
        </td>
  </tr>







</table>

</div>
