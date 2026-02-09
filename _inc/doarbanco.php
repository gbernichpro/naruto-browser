<?php require_once('trava.php'); ?>
<?php

if($db['yensbanco'] < 200001){
}else{
echo "<script>self.location='?p=banco&msg=3'</script>";
 
  }
$sqlu=mysql_query("SELECT * FROM usuarios ");
$dbu=mysql_fetch_assoc($sqlu);
 
if(isset($_POST['don'])){
        $yens=(int)$_POST['don_yens'];
        vn($yens);
        if($yens>$db['yens']){ echo "<script>self.location='?p=doarbanco&msg=2'</script>";
         return; }
if($yens <= 0){ echo "<script>top.location='?p=banco&msg=4'</script>"; die(); }
        mysql_query("UPDATE usuarios SET yensbanco=yensbanco+$yens WHERE id=".$db['id']);
        mysql_query("UPDATE usuarios SET yens=yens-$yens WHERE id=".$db['id']);
        echo "<script>self.location='?p=doarbanco&msg=1&yens=$yens'</script>";
}
?>
<div class="box_top">Depositar</div>
<div class="box_middle">
	  <ul class="menu">


	<li><a href="?p=doarbanco">Depositar</a>


	</li>
	<li><a href="?p=banco">Retirar</a>

	</li>




</ul> 

<div style="background: url(../_img/_detalhes/base2.PNG);width: 720px;height: 250px;">
<table cellpadding="0" cellspacing="0" width="712" height="230"><tbody><tr><td width="150">
<img width="142" style="" src="_img/_detalhes/msg/5.png"></td><td valign="top"><br><br><br>
<div style="margin-left: -345px;margin-top: 15px;height: 0px;">
<b id="title" style="font-family: impact;font-size: 20px;font-weight: normal;color: #ffffff;"  onmouseover="style.color='#F5F5F5'" onmouseout="color.color='#ffffff'">
&raquo; Déposito Yens:</b></div><br>
<div style="margin-left: 0px;margin-top: 35px;font-family: arial;font-size: 12px;color: #fff;"><b>
Guarde seus yens para que possar usar depois,</br>
pois a muitos ninjas que possam lhe roubar.</BR>
</b>
</div></td></tr></tbody></table></div>
  <div class="sep"></div>
        <?php
        if(isset($_GET['msg'])){
                switch($_GET['msg']){
                        case 1: if(!isset($_GET['yens'])){ echo "<script>self.location='?p=home'</script>"; break; } else $yens=$_GET['yens']; $msg='Foram depósitados <b>'.number_format($yens,2,',','.').' yens</b>!'; return;
                        case 2: $msg='Você não possui a quantia de yens informada.'; break;
						case 3: $msg='Digite um valor maior que 0.'; break;
                }
        echo '<div class="aviso">'.$msg.'</div><div class="sep"></div>';}
        ?>
    <script language='JavaScript'>
function SomenteNumero(e){
    var tecla=(window.event)?event.keyCode:e.which;  
    if((tecla>47 && tecla<58)) return true;
    else{
        if (tecla==8 || tecla==0) return true;
        else  return false;
    }
}
</script>
    <div style="padding-left:5px;background:url(_img/gradient.jpg) repeat-y;font-weight:bold;color:#e9e9e9;"><img src="_img/yens.png" width="14" height="14" align="absmiddle" /> Meus yens: <?php echo number_format($db['yens'],2,',','.'); ?> yens</div><div class="sep"></div>
    <script language='JavaScript'>
function SomenteNumero(e){
    var tecla=(window.event)?event.keyCode:e.which;  
    if((tecla>47 && tecla<58)) return true;
    else{
        if (tecla==8 || tecla==0) return true;
        else  return false;
    }
}
</script>
    <form method="post" action="?p=doarbanco" onsubmit="subm.value='Carregando...';subm.disabled=true;">
    <input type="hidden" id="don" name="don" value="1" />
    <span class="destaque">Yens para Deposito:</span><br />
    <script language='JavaScript'>
function SomenteNumero(e){
    var tecla=(window.event)?event.keyCode:e.which;  
    if((tecla>47 && tecla<58)) return true;
    else{
        if (tecla==8 || tecla==0) return true;
        else  return false;
    }
}
</script>
    <input type="text" id="don_yens" name="don_yens" onkeypress='return SomenteNumero(event)'>  <br />
    <span class="sub2">Digite a quantidade de yens para doação (apenas números).</span>
    <div class="sep"></div>
    <div align="center"><input type="submit" id="subm" name="subm" class="botao" value="Depositar" /></div>
    </form>
</div>
<div class="box_bottom"></div>