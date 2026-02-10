   <?php if(isset($_SESSION['logado'])){
   echo "<script>self.location='?p=home'</script>"; return; }
?>
<div class="modern-card">
    <div class="modern-card-header">Destaque</div>
    <div class="modern-card-body">
        <link rel="stylesheet" href="_css/default.css" type="text/css" media="screen"  />
        <link rel="stylesheet" href="_css/dark.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="_css/bar.css" type="text/css" media="screen"  />
        <link rel="stylesheet" href="_css/nivo-slider.css" type="text/css" media="screen"  />
        <div class="slider-wrapper theme-dark">
            <div  id="slider" class="nivoSlider">
                <img src="_img/0.png" data-thumb="_img/1.png" alt="" title="<b>Naruto <?php echo NARUTO_NOME; ?></b> Rpg Online - Jogue diretamente do seu navegador" />
                <img src="_img/1.png" data-thumb="_img/1.png" alt="" title="<b>Naruto <?php echo NARUTO_NOME; ?></b> Inicie sua aventura" />
            </div>
        </div>
        <script type="text/javascript" src="_js/jquery.nivo.slider.js"></script>
        <script type="text/javascript">
        $(window).load(function() {
            $('#slider').nivoSlider();
        });
        </script>
    </div>
</div>

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
<div style="display: flex; gap: 20px; margin-top: 20px;">
    <!-- News Section -->
    <div class="modern-card" style="flex: 1;">
        <div class="modern-card-header">Notícias</div>
        <div class="modern-card-body" style="height: 250px; overflow-y: auto;">
             <table width="100%" cellpadding="0" cellspacing="0" id="div_rank">
                <script>carregar();</script>
             </table>
             <div style="color: #ccc; font-size: 11px; margin-top: 20px; text-align: right; opacity: 0.7;">Total de <b style="color: var(--primary-red);">0</b> notícias</div>
        </div>
    </div>

    <!-- Ranking Section -->
    <div class="modern-card" style="flex: 1;">
        <div class="modern-card-header">
            Ranking
            <select id="sellect" style="float: right; background: rgba(0,0,0,0.5); color: #fff; border: 1px solid #444; border-radius: 4px; font-size: 11px;">
                <option>Usuarios</option>
                <option>Clãs</option>
            </select>
        </div>
        <div class="modern-card-body" style="height: 250px; overflow-y: auto;">
            <table width="100%" cellpadding="0" cellspacing="0" class="listinha" id="Usuarios">
                <?php $i=1; if(@mysql_num_rows($sqlr)==0) echo '<tr><td colspan="3"><div class="aviso">Nenhum jogador encontrado.</div></td></tr>'; else do{ ?>
                <tr>
                    <td height="30" align="center" style="border-bottom:1px solid rgba(255,255,255,0.05);"><b style="color: var(--primary-red);"><?php echo $i; ?></b></td>
                    <td style="border-bottom:1px solid rgba(255,255,255,0.05); color: #fff; padding-left: 10px;"><img src="_img/skins/naruto/rank/<?php echo $dbr['vila']; ?>.png" align="absmiddle" style="margin-right: 5px;" /> <?php echo $dbr['usuario']; ?></td>
                    <td align="center" style="border-bottom:1px solid rgba(255,255,255,0.05); color: #888;">[<?php echo $dbr['nivel']; ?>]</td>
                </tr>
                <?php $i++; } while($dbr=@mysql_fetch_assoc($sqlr)); ?>
            </table>
            <table width="100%" cellpadding="0" cellspacing="0" class="listinha" id="Clãs" style="display:none;">
                <?php $i=1; if(@mysql_num_rows($sqlo)==0) echo '<tr><td colspan="3"><div class="aviso">Nenhum clã encontrado.</div></td></tr>'; else do{ ?>
                <tr>
                    <td height="30" align="center" style="border-bottom:1px solid rgba(255,255,255,0.05);"><b style="color: var(--primary-red);"><?php echo $i; ?></b></td>
                    <td style="border-bottom:1px solid rgba(255,255,255,0.05); color: #fff; padding-left: 10px;"> [<?php echo $dbo['sigla']; ?>] <?php echo $dbo['nome']; ?></td>
                    <td align="center" style="border-bottom:1px solid rgba(255,255,255,0.05); color: #888;">[<?php echo $dbo['nivel']; ?>]</td>
                </tr>
                <?php $i++; } while($dbo=@mysql_fetch_assoc($sqlo)); ?>
            </table>
        </div>
    </div>
</div>
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
      </div>

<div class="modern-card" style="margin-top: 20px;">
    <div class="modern-card-header">População das Vilas</div>
    <div class="modern-card-body">
        <div style="display: flex; justify-content: space-around; text-align: center; color: var(--text-dim); font-size: 11px;">
            <div style="flex: 1;"><img onmouseover="Tip('Vila da Folha')" onmouseout="UnTip()" src="_img/vilas/reg_folha.jpg" style="width:40px; border-radius: 4px; border: 1px solid var(--border-subtle); margin-bottom: 5px;"><br><b style="color: #fff;"><?php echo $count; ?></b><br>Jogadores</div>
            <div style="flex: 1;"><img onmouseover="Tip('Vila da Areia')" onmouseout="UnTip()" src="_img/vilas/reg_areia.jpg" style="width:40px; border-radius: 4px; border: 1px solid var(--border-subtle); margin-bottom: 5px;"><br><b style="color: #fff;"><?php echo $count1; ?></b><br>Jogadores</div>
            <div style="flex: 1;"><img onmouseover="Tip('Vila do Som')" onmouseout="UnTip()" src="_img/vilas/reg_som.jpg" style="width:40px; border-radius: 4px; border: 1px solid var(--border-subtle); margin-bottom: 5px;"><br><b style="color: #fff;"><?php echo $count2; ?></b><br>Jogadores</div>
            <div style="flex: 1;"><img onmouseover="Tip('Vila da Chuva')" onmouseout="UnTip()" src="_img/vilas/reg_chuva.jpg" style="width:40px; border-radius: 4px; border: 1px solid var(--border-subtle); margin-bottom: 5px;"><br><b style="color: #fff;"><?php echo $count3; ?></b><br>Jogadores</div>
            <div style="flex: 1;"><img onmouseover="Tip('Vila da Nuvem')" onmouseout="UnTip()" src="_img/vilas/reg_nuvem.jpg" style="width:40px; border-radius: 4px; border: 1px solid var(--border-subtle); margin-bottom: 5px;"><br><b style="color: #fff;"><?php echo $count4; ?></b><br>Jogadores</div>
            <div style="flex: 1;"><img onmouseover="Tip('Vila da Névoa')" onmouseout="UnTip()" src="_img/vilas/reg_nevoa.jpg" style="width:40px; border-radius: 4px; border: 1px solid var(--border-subtle); margin-bottom: 5px;"><br><b style="color: #fff;"><?php echo $count5; ?></b><br>Jogadores</div>
            <div style="flex: 1;"><img onmouseover="Tip('Vila da Pedra')" onmouseout="UnTip()" src="_img/vilas/reg_pedra.jpg" style="width:40px; border-radius: 4px; border: 1px solid var(--border-subtle); margin-bottom: 5px;"><br><b style="color: #fff;"><?php echo $count6; ?></b><br>Jogadores</div>
        </div>
    </div>
</div>

	  </td>
  </tr>
</tbody></table>
 </div>





<script>document.forms[0].login_login.focus()</script>




