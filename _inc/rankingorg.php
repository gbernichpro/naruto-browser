<div class="box_top">Ranking de clas</div>
<div class="box_middle"><div style="background: url(../_img/_detalhes/base2.PNG);width: 720px;height: 250px;">
<table cellpadding="0" cellspacing="0" width="712" height="230"><tbody><tr><td width="150">
<img width="142" style="" src="_img/_detalhes/msg/23.png"></td><td valign="top"><br><br><br>
<div style="margin-left: -345px;margin-top: 15px;height: 0px;">
<b id="title" style="font-family: impact;font-size: 20px;font-weight: normal;color: #ffffff;"  onmouseover="style.color='#F5F5F5'" onmouseout="color.color='#ffffff'">
&raquo; Ranking!</b></div><br>
<div style="margin-left: 0px;margin-top: 35px;font-family: arial;font-size: 12px;color: #fff;"><b>
Abaixo está o ranking atual das organizaçoes do jogo. Você pode filtrar</br>
por vila, utilizando o formulário abaixo.</br>
</br>
</div></td></tr></tbody></table></div><div class="sep"></div>
<center>
<?
 $pagina = $_GET["pagina"];
 $SQL = mysql_query("SELECT * FROM organizacoes") or die(mysql_error());
 $Qtde = 20;
 $Total = mysql_num_rows($SQL);
 $Paginas = ceil($Total/$Qtde);
 if(empty($pagina)){
   $limit = 0;
   $PaginaCorrente = 0;
 } else  {
   $limit = $pagina;
   $PaginaCorrente = $pagina;
 }
 $inicio = $limit * $Qtde;
      if($pagina > 0){
	$menos = $pagina -1;
	$url ="?p=rankorg&pagina=$menos";
	echo "<a href='".$url."'>Anterior</a> |";
    }
    echo " Página $PaginaCorrente de $Paginas ";
    if($pagina < $Paginas)
   {
      $mais = $pagina+1;
      $url ="?p=rankorg&pagina=$mais";
     echo " | <a href='".$url."' >Próxima</a>";
   }

?></center><div class="sep"></div>
			<div align="center"> <div id="div_loto"><div class="aviso">


<table width="100%" cellpadding="0" cellspacing="0">


 <?
$sqlo = mysql_query("SELECT * FROM organizacoes ORDER BY nivel DESC,pontos DESC Limit $inicio,$Qtde");
if(mysql_num_rows($sqlo)==0) echo '<tr id="barra3"><td colspan="8"><center><b><div class="aviso">Nenhuma organização encontrada.</div></div></tr>';
while($dbo = mysql_fetch_array($sqlo))
{
	$sqlrr=mysql_query("SELECT * FROM usuarios WHERE id='".$dbo['liderid']."'");
	$dbr=mysql_fetch_assoc($sqlo);
	?>

			
			
	
	<tbody><tr style="background:url(_img/skins/naruto/gradient.jpg);font-weight:bold;">
    	<td width="50" align="center" height="20">P.</td>
        <td width="150">Organização</td>
        <td width="30">Vila</td>
        <td width="40" align="center">Nv</td>
        <td width="55" align="center">Vit</td>
        <td width="55" align="center">Der</td>
        <td>Reserva.</td>
    </tr>
    
	<tr>
    	<td colspan="8"><div class="sep"></div></td>
    </tr>
        <tr style="background:url(_img/skins/naruto/gradient.jpg);font-weight:bold;">
    	<td height="20" align="center">
1º</td>
        <td> <a href="?p=vieworg&amp;id=<?php echo $dbo['id']; ?>" style="font-size:14px;font-weight:bold;"><?php echo $dbo['nome']; ?></a></td>
        <td><img src="_img/skins/naruto/rank/<?php echo $dbo['vila']; ?>.png"></td>
        <td align="center">[<?php echo $dbo['nivel']; ?>]</td>
        <td align="center"><?php echo $dbo['war_v']; ?></td>
        <td align="center"><?php echo $dbo['war_d']; ?></td>
        <td><?php echo number_format($dbo['reserva'],2,',','.'); ?></td>
    </tr>
    </tbody>

	<?
}

	 echo"</table></div></div></div>";
 ?>
</div>
<div class="box_bottom"></div>
