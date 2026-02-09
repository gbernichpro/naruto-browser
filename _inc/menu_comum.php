
<?php if(isset($_SESSION['logado'])) if(date('Y-m-d H:i:s')<$db['vip']){ if(($_GET['p']<>'view')&&($_GET['p']<>'prepare')){
?>
<div class="box5_top" align="center"><img src="_img/star.png" />Sua conta é VIP</div>
<div class="box5_middle" style="text-align:center;">


    <div style="text-align:center;font-size:11px;">
<table border="0" width="100%">
<tr>
  <td>Início: </td>
  <td><?php $ex=explode(' ',$db['vip_inicio']); $data=explode('-',$ex[0]); echo $data[2].'/'.$data[1].'/'.$data[0]; ?> <?php echo $ex[1]; ?></td>
</tr>
<tr>
  <td>Fim:</td>
  <td><?php $ex=explode(' ',$db['vip']); $data=explode('-',$ex[0]); echo $data[2].'/'.$data[1].'/'.$data[0]; ?> <?php echo $ex[1]; ?></td>
</tr>
</table>
 </div>

</div>
<div class="box5_bottom"></div>
<?php }} ?>
 <style>
 .highlightit img{
opacity: 0.5;
margin-top:5px;
-moz-border-radius:3px;
-webkit-border-radius:3px;
}

.highlightit:hover img{
opacity: 1;
margin-top:5px;
-moz-border-radius:3px;
-webkit-border-radius:3px;
}
</style>
<?php if(isset($_SESSION['logado'])) if(($db['tipodeconta'] == 'admin') && ($_GET['p']<>'view')){
?>
<div class="box5_top" align="center"><img src="_img/star.png" />Você é Administrador</div>
<div class="box5_middle" style="text-align:center;">


    <div style="text-align:center;font-size:11px;">
<table border="0" width="100%">
<tr>
  <td><a href="?p=painel">Painel administrativo</a>
</td>
</tr>
</table>
 </div>

</div>
<div class="box5_bottom"></div>
<?php } ?>