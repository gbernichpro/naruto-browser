   <?php if(isset($_SESSION['logado'])){
   echo "<script>self.location='?p=home'</script>"; return; }
?>
<div class="modern-card">
    <div class="modern-card-header">Destaque</div>
    <div class="modern-card-body">
        <div class="welcome-panel">
            <div>
                <span class="welcome-kicker">Sua jornada começa aqui</span>
                <h2>Construa sua própria lenda ninja</h2>
                <p>Treine seus atributos, aprenda novos jutsus, participe de batalhas e leve sua vila ao topo do ranking.</p>
            </div>
            <div class="welcome-actions">
                <a href="?p=terms" class="modern-btn">Criar conta</a>
                <a href="?p=faq" class="welcome-secondary">Como jogar</a>
            </div>
        </div>
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
<div class="home-dashboard-grid">
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
        <div class="village-grid">
            <div class="village-item"><img loading="lazy" onmouseover="Tip('Vila da Folha')" onmouseout="UnTip()" src="_img/vilas/reg_folha.jpg" alt="Vila da Folha"><b><?php echo $count; ?></b><span>Jogadores</span></div>
            <div class="village-item"><img loading="lazy" onmouseover="Tip('Vila da Areia')" onmouseout="UnTip()" src="_img/vilas/reg_areia.jpg" alt="Vila da Areia"><b><?php echo $count1; ?></b><span>Jogadores</span></div>
            <div class="village-item"><img loading="lazy" onmouseover="Tip('Vila do Som')" onmouseout="UnTip()" src="_img/vilas/reg_som.jpg" alt="Vila do Som"><b><?php echo $count2; ?></b><span>Jogadores</span></div>
            <div class="village-item"><img loading="lazy" onmouseover="Tip('Vila da Chuva')" onmouseout="UnTip()" src="_img/vilas/reg_chuva.jpg" alt="Vila da Chuva"><b><?php echo $count3; ?></b><span>Jogadores</span></div>
            <div class="village-item"><img loading="lazy" onmouseover="Tip('Vila da Nuvem')" onmouseout="UnTip()" src="_img/vilas/reg_nuvem.jpg" alt="Vila da Nuvem"><b><?php echo $count4; ?></b><span>Jogadores</span></div>
            <div class="village-item"><img loading="lazy" onmouseover="Tip('Vila da Névoa')" onmouseout="UnTip()" src="_img/vilas/reg_nevoa.jpg" alt="Vila da Nevoa"><b><?php echo $count5; ?></b><span>Jogadores</span></div>
            <div class="village-item"><img loading="lazy" onmouseover="Tip('Vila da Pedra')" onmouseout="UnTip()" src="_img/vilas/reg_pedra.jpg" alt="Vila da Pedra"><b><?php echo $count6; ?></b><span>Jogadores</span></div>
        </div>
    </div>
</div>

	  </td>
  </tr>
</tbody></table>
 </div>





<script>document.forms[0].login_login.focus()</script>



