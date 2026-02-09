<?php require_once('trava.php'); ?>
<?php
   //Aki é o codigo de cada item by juniorrios :P
if($_GET['dou1']) {
 if($db['creditos'] < 20){
			echo "<script>self.location='?p=credshop&msg=10'</script>"; return;
 }else
		mysql_query("UPDATE usuarios SET creditos=creditos-20,  doujutsu=1, doujutsu_nivel=5 , doujutsu_expmax=80 ,creditosusados=creditosusados +20 WHERE id=".$db['id']);
		$doujutsu='1';
		$db['doujutsu_nivel']=5;
			echo "<script>self.location='?p=credshop&msg=13'</script>"; return;}
        //Termina aki!!!!!!!!!!!!!!

      //Aki é o codigo de cada item by juniorrios :P
if($_GET['dou2']) {
 if($db['creditos'] < 20){
			echo "<script>self.location='?p=credshop&msg=10'</script>"; return;
 }else
		mysql_query("UPDATE usuarios SET creditos=creditos-20,  doujutsu=2, doujutsu_nivel=5 , doujutsu_expmax=80 ,creditosusados=creditosusados +20 WHERE id=".$db['id']);
		$doujutsu='2';
		$db['doujutsu_nivel']=5;
			echo "<script>self.location='?p=credshop&msg=13'</script>"; return;}
        //Termina aki!!!!!!!!!!!!!!

   //Aki é o codigo de cada item by juniorrios :P
if($_GET['dou3']) {
 if($db['creditos'] < 20){
			echo "<script>self.location='?p=credshop&msg=10'</script>"; return;
 }else
		mysql_query("UPDATE usuarios SET creditos=creditos-20,  doujutsu=3, doujutsu_nivel=5 , doujutsu_expmax=80 ,creditosusados=creditosusados +20 WHERE id=".$db['id']);
		$doujutsu='3';
		$db['doujutsu_nivel']=5;
			echo "<script>self.location='?p=credshop&msg=13'</script>"; return;}
        //Termina aki!!!!!!!!!!!!!!
        
        //Aki é o codigo de cada item by juniorrios :P
if($_GET['dou4']) {
 if($db['creditos'] < 40){
			echo "<script>self.location='?p=credshop&msg=10'</script>"; return;
 }else
		mysql_query("UPDATE usuarios SET creditos=creditos-40,  doujutsu=5, doujutsu_nivel=1 , doujutsu_expmax=160 ,creditosusados=creditosusados +40 WHERE id=".$db['id']);
		$doujutsu='5';
		$db['doujutsu_nivel']=1;
			echo "<script>self.location='?p=credshop&msg=13'</script>"; return;}
        //Termina aki!!!!!!!!!!!!!!
        
        
        //Aki é o codigo de cada item by juniorrios :P
if($_GET['dou5']) {
 if($db['creditos'] < 30){
			echo "<script>self.location='?p=credshop&msg=10'</script>"; return;
 }else
		mysql_query("UPDATE usuarios SET creditos=creditos-30,  doujutsu=4, doujutsu_nivel=1 , doujutsu_expmax=160 ,creditosusados=creditosusados +30 WHERE id=".$db['id']);
		$doujutsu='4';
		$db['doujutsu_nivel']=1;
			echo "<script>self.location='?p=credshop&msg=13'</script>"; return;}
        //Termina aki!!!!!!!!!!!!!!

   ?>




	<?php if(isset($_GET['msg'])){
		switch($_GET['msg']){
			case 1: $msg='Creditos Insuficientes!'; break;
            case 2: $msg='Troca De Creditos Por Yens Com Sucesso'; break;
			case 3: $msg='Doujutsu Adiquirido Com Sucesso'; break;
			case 4: $msg='Troca Com Sucesso!Aproveite Sua Nova Vila'; break;
			case 5: $msg='Natureza Adicionada Com Sucesso!Aproveite'; break;
			case 6: $msg='eNivel Insuficiente Para Comprar Natureza,Volte Quanto Estiver No Nivel 22'; break;
		}
	echo '<div class="aviso">'.$msg.'</div><div class="sep"></div>';
	} ?>



<table border="0" width="100%">
<tr style="bacound: none repeat scroll 0% 0% rgb(50, 50, 50);" onmouseover="style.background='#310000'" onmouseout="style.background='#161616'">
    	<td align="center" width="140"><img src="_img/doujutsus/sharingan.jpg"></td>
        <td style="padding: 5px; text-align: center;" valign="top">
        	<b>Sharingan + Nivel 5</b><br>
            <span class="sub2">Desperte o Sharingan + 5 Niveis Completos.</span><br>
     </td>
        <td align="center" width="20%">
              <b>Preço</b><br />
            <span class="sub2">20 creditos</span><br><br>
            <form method="post" action="?p=credshopdou&dou1=ok" onsubmit="subm.value='Carregando';subm.disabled=true;">
            <input id="subm" name="subm" class="botao" value="Adiquirir" type="submit">            </form>
        </td>
  </tr>

<table border="0" width="100%">
<tr style="bacound: none repeat scroll 0% 0% rgb(50, 50, 50);" onmouseover="style.background='#310000'" onmouseout="style.background='#161616'">
    	<td align="center" width="140"><img src="_img/doujutsus/byakugan.jpg"></td>
        <td style="padding: 5px; text-align: center;" valign="top">
        	<b>Byakugan + Nivel 5</b><br>
            <span class="sub2">Desperte o Byakugan + 5 Niveis Completos.</span><br>
     </td>
        <td align="center" width="20%">
              <b>Preço</b><br />
            <span class="sub2">20 creditos</span><br><br>
            <form method="post" action="?p=credshopdou&dou2=ok" onsubmit="subm.value='Carregando';subm.disabled=true;">
            <input id="subm" name="subm" class="botao" value="Adiquirir" type="submit">            </form>
        </td>
  </tr>

<table border="0" width="100%">
<tr style="bacound: none repeat scroll 0% 0% rgb(50, 50, 50);" onmouseover="style.background='#310000'" onmouseout="style.background='#161616'">
    	<td align="center" width="140"><img src="_img/doujutsus/rinnegan.jpg"></td>
        <td style="padding: 5px; text-align: center;" valign="top">
        	<b>Rinnegan + Nivel 5</b><br>
            <span class="sub2">Desperte o Rinnegan + 5 Niveis Completos.</span><br>
     </td>
        <td align="center" width="20%">
              <b>Preço</b><br />
            <span class="sub2">20 creditos</span><br><br>
            <form method="post" action="?p=credshopdou&dou3=ok" onsubmit="subm.value='Carregando';subm.disabled=true;">
            <input id="subm" name="subm" class="botao" value="Adiquirir" type="submit">            </form>
        </td>
  </tr>
   <table border="0" width="100%">
<tr style="bacound: none repeat scroll 0% 0% rgb(50, 50, 50);" onmouseover="style.background='#310000'" onmouseout="style.background='#161616'">
    	<td align="center" width="140"><img src="_img/doujutsus/mangekyou_sharingan.jpg"></td>
        <td style="padding: 5px; text-align: center;" valign="top">
        	<b>Mangekyou Sharingan</b><br>
            <span class="sub2"></span><br>
     </td>
        <td align="center" width="20%">
              <b>Preço</b><br />
            <span class="sub2">30 creditos</span><br><br>
            <form method="post" action="?p=credshopdou&dou5=ok" onsubmit="subm.value='Carregando';subm.disabled=true;">
            <input id="subm" name="subm" class="botao" value="Adiquirir" type="submit">            </form>
        </td>
  </tr>

  
  
  <table border="0" width="100%">
<tr style="bacound: none repeat scroll 0% 0% rgb(50, 50, 50);" onmouseover="style.background='#310000'" onmouseout="style.background='#161616'">
    	<td align="center" width="140"><img src="_img/doujutsus/fuumetsu_mangekyou_sharingan.jpg"></td>
        <td style="padding: 5px; text-align: center;" valign="top">
        	<b>Fuumetsu Mangekyou Sharingan</b><br>
            <span class="sub2"></span><br>
     </td>
        <td align="center" width="20%">
              <b>Preço</b><br />
            <span class="sub2">40 creditos</span><br><br>
            <form method="post" action="?p=credshopdou&dou4=ok" onsubmit="subm.value='Carregando';subm.disabled=true;">
            <input id="subm" name="subm" class="botao" value="Adiquirir" type="submit">            </form>
        </td>
  </tr>









</table>

</div>