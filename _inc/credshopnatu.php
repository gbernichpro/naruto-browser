<?php require_once('trava.php'); ?>
<?php
   //Aki é o codigo de cada item by juniorrios :P
if($_GET['fogo']) {
 if($db['creditos'] < 15){
			echo "<script>self.location='?p=credshopnatu&msg=1'</script>"; return;
 }
 elseif ($db['nivel'] < 22)
 {
			echo "<script>self.location='?p=credshopnatu&msg=3'</script>"; return;
 }
 else
mysql_query("UPDATE usuarios SET  creditos=creditos-15 ,  natureza2='fogo' , creditosusados=creditosusados +15 WHERE id=".$db['id']);
echo "<script>self.location='?p=credshopnatu&msg=2'</script>"; return;}
      //Termina aki!!!!!!!!!!!!!!

       //Aki é o codigo de cada item by juniorrios :P
if($_GET['agua']) {
 if($db['creditos'] < 15){
			echo "<script>self.location='?p=credshopnatu&msg=1'</script>"; return;
 }
 elseif ($db['nivel'] < 22)
 {
			echo "<script>self.location='?p=credshopnatu&msg=3'</script>"; return;
 }
 else
mysql_query("UPDATE usuarios SET  creditos=creditos-15 ,  natureza2='agua' , creditosusados=creditosusados +15 WHERE id=".$db['id']);
echo "<script>self.location='?p=credshopnatu&msg=2'</script>"; return;}
      //Termina aki!!!!!!!!!!!!!!
      //Aki é o codigo de cada item by juniorrios :P
if($_GET['vento']) {
 if($db['creditos'] < 15){
			echo "<script>self.location='?p=credshopnatu&msg=1'</script>"; return;
 }
 elseif ($db['nivel'] < 22)
 {
			echo "<script>self.location='?p=credshopnatu&msg=3'</script>"; return;
 }
 else
mysql_query("UPDATE usuarios SET  creditos=creditos-15 ,  natureza2='vento' , creditosusados=creditosusados +15 WHERE id=".$db['id']);
echo "<script>self.location='?p=credshopnatu&msg=2'</script>"; return;}
      //Termina aki!!!!!!!!!!!!!!

      //Aki é o codigo de cada item by juniorrios :P
if($_GET['raio']) {
 if($db['creditos'] < 15){
			echo "<script>self.location='?p=credshopnatu&msg=1'</script>"; return;
 }
 elseif ($db['nivel'] < 22)
 {
			echo "<script>self.location='?p=credshopnatu&msg=3'</script>"; return;
 }
 else
mysql_query("UPDATE usuarios SET  creditos=creditos-15 ,  natureza2='raio' , creditosusados=creditosusados +15 WHERE id=".$db['id']);
echo "<script>self.location='?p=credshopnatu&msg=2'</script>"; return;}
      //Termina aki!!!!!!!!!!!!!!

      //Aki é o codigo de cada item by juniorrios :P
if($_GET['terra']) {
 if($db['creditos'] < 15){
			echo "<script>self.location='?p=credshopnatu&msg=1'</script>"; return;
 }
 elseif ($db['nivel'] < 22)
 {
			echo "<script>self.location='?p=credshopnatu&msg=3'</script>"; return;
 }
 else
mysql_query("UPDATE usuarios SET  creditos=creditos-15 ,  natureza2='terra' , creditosusados=creditosusados +15 WHERE id=".$db['id']);
echo "<script>self.location='?p=credshopnatu&msg=2'</script>"; return;}
      //Termina aki!!!!!!!!!!!!!!


 ?>



<div class="box_top">CredShop - Seja Bem Vindo <?=$db['usuario']?> </div>
<div class="box_middle">Bem-Vindo a loja de  creditos. Temos tudo que vocÃª precisa para sua jornada no mundo ninja! Selecione uma das categorias abaixo e boas compras!<div class="sep"> </div>
<div style="background:url('_img/bar.png');height:13px;width:100%;padding:3px;"><div align="center"><a href="?p=credshopvl">Vila</a> | <a href="?p=credshopyn">Yens</a>  | <a href="?p=credshopup">Energia</a> | <a href="?p=credshopdou">Doujutsus</a> | <a href="?p=credshopnatu">Naturezas</a> | <a href="?p=credshopit">Items</a> | <a href="?p=credshopbi">Bijuus</a> | <a href="?p=credshopch">Personagens</a> </div></div>
<div class="sep"> </div>
<div style="background:url(_img/gradient.jpg) repeat-y;color:#FFFFAA;"><img src="_img/yens.png" width="14" height="14" /> <b>Meus Creditos: <?php echo number_format($db['creditos'],2,',','.'); ?> </b></div>
<div class="sep"> </div>
<div style="background:url(_img/gradient.jpg) repeat-y;color:#FFFFAA;"><img src="_img/yens.png" width="14" height="14" /> <b>Creditos Utilizados: <?php echo number_format($db['creditosusados'],2,',','.'); ?></b></div>
<div class="sep"> </div>
<div class="box_middle">

	<?php if(isset($_GET['msg'])){
		switch($_GET['msg']){
			case 1: $msg='Creditos Insuficientes!'; break;
            case 2: $msg='Natureza Com Sucesso!Aproveite Seus Novos Jutsus'; break;
            case 3: $msg='Nivel Insuficiente Para Comprar Natureza,Volte Quanto Estiver No Nivel 22'; break;

		}
	echo '<div class="aviso">'.$msg.'</div><div class="sep"></div>';
	} ?>


<table border="0" width="100%">
<tr style="background: none repeat scroll 0% 0% rgb(50, 50, 50);" onmouseover="style.background='#310000'" onmouseout="style.background='#161616'">
    	<td align="center" width="140"><img src="_img/equipamentos/fogo.jpg"></td>
        <td style="padding: 5px; text-align: center;" valign="top">
        	<b>Natrureza de Fogo</b><br>
            <span class="sub2">Libere Jutsus de Fogo.</span><br>
     </td>
        <td align="center" width="20%">
              <b>2° Natureza</b><br />
              <b>Requisito:</b><br />
             <span class="sub2"> Nivel 22</span><br />
               <b>Preco:</b><br />
            <span class="sub2">15 creditos</span><br><br>
            <form method="post" action="?p=credshopnatu&fogo=ok" onsubmit="subm.value='Carregando';subm.disabled=true;">
            <input id="subm" name="subm" class="botao" value="Adiquirir" type="submit">            </form>
        </td>
  </tr>

<table border="0" width="100%">
<tr style="background: none repeat scroll 0% 0% rgb(50, 50, 50);" onmouseover="style.background='#310000'" onmouseout="style.background='#161616'">
    	<td align="center" width="140"><img src="_img/equipamentos/agua.jpg"></td>
        <td style="padding: 5px; text-align: center;" valign="top">
        	<b>Natureza da Agua</b><br>
            <span class="sub2">Libere Jutsus da agua.</span><br>
     </td>
        <td align="center" width="20%">
              <b>2° Natureza</b><br />
              <b>Requisito:</b><br />
             <span class="sub2"> Nivel 22</span><br />
               <b>Preco:</b><br />
            <span class="sub2">15 creditos</span><br><br>
            <form method="post" action="?p=credshopnatu&agua=ok" onsubmit="subm.value='Carregando';subm.disabled=true;">
            <input id="subm" name="subm" class="botao" value="Adiquirir" type="submit">            </form>
        </td>
  </tr>

<table border="0" width="100%">
<tr style="background: none repeat scroll 0% 0% rgb(50, 50, 50);" onmouseover="style.background='#310000'" onmouseout="style.background='#161616'">
    	<td align="center" width="140"><img src="_img/equipamentos/vento.jpg"></td>
        <td style="padding: 5px; text-align: center;" valign="top">
        	<b>Natureza do Vento</b><br>
            <span class="sub2">Libere Jutsus do vento.</span><br>
     </td>
        <td align="center" width="20%">
              <b>2° Natureza</b><br />
              <b>Requisito:</b><br />
             <span class="sub2"> Nivel 22</span><br />
               <b>Preco:</b><br />
            <span class="sub2">15 creditos</span><br><br>
            <form method="post" action="?p=credshopnatu&vento=ok" onsubmit="subm.value='Carregando';subm.disabled=true;">
            <input id="subm" name="subm" class="botao" value="Adiquirir" type="submit">            </form>
        </td>
  </tr>

<table border="0" width="100%">
<tr style="background: none repeat scroll 0% 0% rgb(50, 50, 50);" onmouseover="style.background='#310000'" onmouseout="style.background='#161616'">
    	<td align="center" width="140"><img src="_img/equipamentos/raio.jpg"></td>
        <td style="padding: 5px; text-align: center;" valign="top">
        	<b>Natureza do Raio</b><br>
            <span class="sub2">Libere Jutsus do Raio.</span><br>
     </td>
        <td align="center" width="20%">
              <b>2° Natureza</b><br />
              <b>Requisito:</b><br />
             <span class="sub2"> Nivel 22</span><br />
               <b>Preco:</b><br />
            <span class="sub2">15 creditos</span><br><br>
            <form method="post" action="?p=credshopnatu&raio=ok" onsubmit="subm.value='Carregando';subm.disabled=true;">
            <input id="subm" name="subm" class="botao" value="Adiquirir" type="submit">            </form>
        </td>
  </tr>

<table border="0" width="100%">
<tr style="background: none repeat scroll 0% 0% rgb(50, 50, 50);" onmouseover="style.background='#310000'" onmouseout="style.background='#161616'">
    	<td align="center" width="140"><img src="_img/equipamentos/terra.jpg"></td>
        <td style="padding: 5px; text-align: center;" valign="top">
        	<b>Natureza da Terra</b><br>
            <span class="sub2">Libere Jutsus da terra.</span><br>
     </td>
        <td align="center" width="20%">
              <b>2° Natureza</b><br />
              <b>Requisito:</b><br />
             <span class="sub2"> Nivel 22</span><br />
               <b>Preco:</b><br />
            <span class="sub2">15 creditos</span><br><br>
            <form method="post" action="?p=credshopnatu&terra=ok" onsubmit="subm.value='Carregando';subm.disabled=true;">
            <input id="subm" name="subm" class="botao" value="Adiquirir" type="submit">            </form>
        </td>
  </tr>






</table>

</div>
<div class="box_bottom"></div>
