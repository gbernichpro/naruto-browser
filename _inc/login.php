   <?php if(isset($_SESSION['logado'])){
   echo "<script>self.location='?p=home'</script>"; return; }
?>
<div class="box_top">Destaque</div>
<div class="box_middle"><link rel="stylesheet" href="_scripts/default.css" type="text/css" media="screen"  />
    <link rel="stylesheet" href="_scripts/dark.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="_scripts/bar.css" type="text/css" media="screen"  />
    <link rel="stylesheet" href="_scripts/nivo-slider.css" type="text/css" media="screen"  />
<div class="slider-wrapper theme-dark">
        <div  id="slider" class="nivoSlider">
		<img src="img/0.png" data-thumb="images/1.png" alt="" title="<b>Naruto <?php echo NARUTO_NOME; ?></b> Rpg Online - Joque diretamente do seu navegador" />
		<img src="img/1.png" data-thumb="images/1.png" alt="" title="<b>Naruto <?php echo NARUTO_NOME; ?></b> Inicie sua aventura" />
        </div>
        </div>


    <script type="text/javascript" src="_scripts/jquery.nivo.slider.js"></script>
    <script type="text/javascript">
    $(window).load(function() {
        $('#slider').nivoSlider();
    });
    </script>
</div><div class="sep"></div>
<center></center>
	      </div>
<div class="box_bottom"></div>

  <?php
$sqlr=mysql_query("SELECT usuario, nivel, vila FROM usuarios WHERE tipo='player' ORDER BY nivel DESC, yens_fat DESC, vitorias DESC, derrotas ASC LIMIT 5");
$dbr=@mysql_fetch_assoc($sqlr);
$sqlo=mysql_query("SELECT * FROM organizacoes  ORDER BY nivel DESC LIMIT 10");
$dbo=@mysql_fetch_assoc($sqlo);
?>


<script>
$(document).ready(function(){

$("#sellect").change(function(){
$('.listinha').hide();
$("#"+$(this).val()).show();
});
});

</script>
<script>
$(document).ready(function(){

$("#sellect").change(function(){
$('.listinha').hide();
$("#"+$(this).val()).show();
});
});
</script>



  <script>
function carregar(){
$("#div_rank").load('_ajax/ajax_noticias.php');
}

</script>
<table width="651" height="290">
  <tbody><tr>
    <td colspan="2"><div align="center"></div><table height="10px" align="center" cellpadding="2" cellspacing="5">
      <tbody></tbody></table></td>
  </tr>
  <tr>
    <td width="219" height="82"><table width="335" height="242" align="right">
      <tbody><tr>
        <td width="325" height="209" background="template/noticias.png" valign="top">
		<div style="height:38px;"></div>
		<div style="height:120px;" id="pagina_carrega">		 <table width="95%" cellpadding="0" cellspacing="0">

            <tbody><tr>
              <td colspan="3" height="2" bgcolor="#777777"></td>
            </tr>



             <div id="div_rank"><script>carregar();</script></div>



          </tbody></table></div>
<div style="color: #FFF;font-size: 13px;font-family: verdana;width: 280px;margin-top: 52px;margin-left:25px;">Total de <b style="color: #FFD600;">0</b> noticias </div>
		</td>
      </tr>
    </tbody></table></td>
    <td width="320"><table width="337"  height="242" align="right">
      <tbody><tr>
        <td width="299" height="209" valign="top" background="template/ranking.png"><select id="sellect" style="margin-left:50px;margin-top: 10px;position:absolute;background:transparent;"><option>Usuarios</option><option>Clãs</option></select><br>
          <br>
          <br>
          <table width="95%" cellpadding="0" cellspacing="0" class="listinha" id="Usuarios">

            <tbody><tr>
              <td colspan="3" height="2" bgcolor="#777777"></td>
            </tr>
                    <?php $i=1; if(@mysql_num_rows($sqlr)==0) echo '<tr><td colspan="3"><div class="aviso">Nenhuma conta cadastrada.</div></td></tr>'; else do{ ?>
                    <tr>
                                  <div align="left"><tr>
                    	<td height="20" align="center" style="border-bottom:1px solid #333333;"><b style="color:#F5F6B4;"><?php echo $i; ?></b></td>
                    	<td style="border-bottom:1px solid #333333;"><img src="_img/skins/naruto/rank/<?php echo $dbr['vila']; ?>.png" align="absmiddle" /> <?php echo $dbr['usuario']; ?></td>
                        <td align="center" style="border-bottom:1px solid #333333;">[<?php echo $dbr['nivel']; ?>]</td>
                    </tr></div>
                    <?php $i++; } while($dbr=@mysql_fetch_assoc($sqlr)); ?>
                    <tr>
                    	<td colspan="3" height="20" align="center"><span class="sub2">Ranking exibido em tempo real</span></td>
                    </tr>
                </table>
<table width="100%" cellpadding="0" cellspacing="0" class="listinha" id="Clãs" style="display:none;">

                    <tr>
                    	<td colspan="3" height="2" bgcolor="#777777"></td>
                    </tr>
                    <?php $i=1; if(@mysql_num_rows($sqlo)==0) echo '<tr><td colspan="3"><div class="aviso">Nenhum clã cadastrado.</div></td></tr>'; else do{ ?>
                    <tr>
                    	<td height="20" align="center" style="border-bottom:1px solid #333333;"><b style="color:#F5F6B4;"><?php echo $i; ?></b></td>
                    	<td style="border-bottom:1px solid #333333;"> [<?php echo $dbo['sigla']; ?>] <?php echo $dbo['nome']; ?></td>
                        <td align="center" style="border-bottom:1px solid #333333;">[<?php echo $dbo['nivel']; ?>]</td>
                    </tr>
                    <?php $i++; } while($dbo=@mysql_fetch_assoc($sqlo)); ?>
                    <tr>
                    	<td colspan="3" height="20" align="center"><span class="sub2">Ranking exibido em tempo real</span></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>



</td>
  </tr>
  <?php
$sql = mysql_query("SELECT * FROM usuarios WHERE vila = '1'");
$i=0;
$count = mysql_num_rows($sql);
while ($show = mysql_fetch_array($sql))
{
$i=$i+1;
$ii = $count-$i;
}
?>
  <?php
$sql = mysql_query("SELECT * FROM usuarios WHERE vila = '2'");
$i=0;
$count1 = mysql_num_rows($sql);
while ($show = mysql_fetch_array($sql))
{
$i=$i+1;
$ii = $count1-$i;
}
?>
  <?php
$sql = mysql_query("SELECT * FROM usuarios WHERE vila = '3'");
$i=0;
$count2 = mysql_num_rows($sql);
while ($show = mysql_fetch_array($sql))
{
$i=$i+1;
$ii = $count2-$i;
}
?>
  <?php
$sql = mysql_query("SELECT * FROM usuarios WHERE vila = '4'");
$i=0;
$count3 = mysql_num_rows($sql);
while ($show = mysql_fetch_array($sql))
{
$i=$i+1;
$ii = $count3-$i;
}
?>
  <?php
$sql = mysql_query("SELECT * FROM usuarios WHERE vila = '5'");
$i=0;
$count4 = mysql_num_rows($sql);
while ($show = mysql_fetch_array($sql))
{
$i=$i+1;
$ii = $count4-$i;
}
?>
  <?php
$sql = mysql_query("SELECT * FROM usuarios WHERE vila = '6'");
$i=0;
$count5 = mysql_num_rows($sql);
while ($show = mysql_fetch_array($sql))
{
$i=$i+1;
$ii = $count5-$i;
}
?>
  <?php
$sql = mysql_query("SELECT * FROM usuarios WHERE vila = '7'");
$i=0;
$count6 = mysql_num_rows($sql);
while ($show = mysql_fetch_array($sql))
{
$i=$i+1;
$ii = $count6-$i;
}
?>
  <tr>
    <td height="82" colspan="2"><div align="center">
      <table height="10px" align="center" cellpadding="2" cellspacing="5">
        <tbody><tr>
        </tr>
      </tbody></table>
      </div>

	  <table width="708" height="200" cellpadding="0" cellspacing="0" background="template/vilas.png" style="font-size:10px;text-align:center;color:#FFB800;">
	  <tbody><tr>
  	  <td style="width:10px;"></td>
	  <td style="width:10px;"></td>
  	  <td style="width:10px;"></td>
  	  <td style="width:10px;"></td>
	  	  <td style="width:10px;"><img onmouseover="Tip('Vila da folha')" onmouseout="UnTip()" src="_img/vilas/reg_folha.jpg" style="width:30px;border:1px solid  rgba(255, 255, 255, 0.4);border-radius:3px;"><br><?php echo $count; ?><br>Jogadores</td>
	  	  <td style="width:10px;"><img onmouseover="Tip('Vila da Areia')" onmouseout="UnTip()" src="_img/vilas/reg_areia.jpg" style="width:30px;border:1px solid  rgba(255, 255, 255, 0.4);border-radius:3px;"><br><?php echo $count1; ?><br>Jogadores</td>
	  	  <td style="width:10px;"><img onmouseover="Tip('Vila do som')" onmouseout="UnTip()" src="_img/vilas/reg_som.jpg" style="width:30px;border:1px solid  rgba(255, 255, 255, 0.4);border-radius:3px;"><br><?php echo $count2; ?><br>Jogadores</td>
	  	  <td style="width:10px;"><img onmouseover="Tip('Vila da chuva')" onmouseout="UnTip()" src="_img/vilas/reg_chuva.jpg" style="width:30px;border:1px solid  rgba(255, 255, 255, 0.4);border-radius:3px;"><br><?php echo $count3; ?><br>Jogadores</td>
	  	  <td style="width:10px;"><img onmouseover="Tip('Vila da nuvem')" onmouseout="UnTip()" src="_img/vilas/reg_nuvem.jpg" style="width:30px;border:1px solid  rgba(255, 255, 255, 0.4);border-radius:3px;"><br><?php echo $count4; ?><br>Jogadores</td>
	  	  <td style="width:10px;"><img onmouseover="Tip('Vila da nevoa')" onmouseout="UnTip()" src="_img/vilas/reg_nevoa.jpg" style="width:30px;border:1px solid  rgba(255, 255, 255, 0.4);border-radius:3px;"><br><?php echo $count5; ?><br>Jogadores</td>
	  	  <td style="width:10px;"><img onmouseover="Tip('Vila da pedra')" onmouseout="UnTip()" src="_img/vilas/reg_pedra.jpg" style="width:30px;border:1px solid  rgba(255, 255, 255, 0.4);border-radius:3px;"><br><?php echo $count6; ?><br>Jogadores</td>
	  	  <td style="width:10px;"></td>
	  <td style="width:10px;"></td>
  	  <td style="width:10px;"></td>
  	  <td style="width:10px;"></td>

	  </tr>
	  </tbody></table>

	  </td>
  </tr>
</tbody></table>
 </div>





<script>document.forms[0].login_login.focus()</script>




