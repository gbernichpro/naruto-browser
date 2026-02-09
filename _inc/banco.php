
<meta name="Microsoft Border" content="b">
</head>
 
 
<script language="javascript">
 
function BloqueiaComando(event) {
  var tecla = String.fromCharCode(event.keyCode).toLowerCase();
 
  if (event.ctrlKey && (tecla == "c" || tecla == "v")) {
  window.event ? event.returnValue = false : event.preventDefault();
 return false
  }
}
</script>
 
 

<?php require_once('trava.php'); ?>
<?php
$sqlu=mysql_query("SELECT * FROM usuarios ");
$dbu=mysql_fetch_assoc($sqlu);
 
if(isset($_POST['don'])){
       $yens=floor($_POST['don_yens']);
        vn($yens);
		if($yens>$db['yensbanco']){ echo "<script>self.location='?p=banco&msg=2'</script>"; return; }
if($yens <= 0){ echo "<script>top.location='?p=banco&msg=4'</script>"; die(); }
        mysql_query("UPDATE usuarios SET yensbanco=yensbanco-$yens WHERE id=".$db['id']);
       
       
        mysql_query("UPDATE usuarios SET yens=yens+$yens WHERE id=".$db['id']);
        echo "<script>self.location='?p=banco&msg=1&yens=$yens'</script>";
}
?>
<div class="box_top">Retirar</div>
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
&raquo; Retirar Yens:</b></div><br>
<div style="margin-left: 0px;margin-top: 35px;font-family: arial;font-size: 12px;color: #fff;"><b>
Recupere seus yens guardados neste banco,</br>
aproveite e compre equipamentos para você.</BR>
</b>
</div></td></tr></tbody></table></div> 
  <div class="sep"></div>
        <?php
        if(isset($_GET['msg'])){
                switch($_GET['msg']){
                        case 1: if(!isset($_GET['yens'])){ echo "<script>self.location='?p=home'</script>"; break; } else $yens=(int)$_GET['yens']; $msg='Voce retiro <b>'.number_format($yens,2,',','.').' yens</b>!'; return;
                        case 2: $msg='Você não tem esse saldo disponivel.'; break;
                        case 3: $msg='Você ja feis um saque hoje, volte amanha.'; break;
                        case 4: $msg='Digite um valor maior que 0.'; break;
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
  <div class="gradiente"><img src="_img/yens.png" width="14" height="14" align="absmiddle" /> Disponivel: <?php echo number_format($db['yensbanco'],2,',','.'); ?> yens</div>
  <div class="sep"></div>
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
  <form method="post" action="?p=banco" onsubmit="subm.value='Carregando...';subm.disabled=true;" form="onKeyDown">
    <input type="hidden" id="don" name="don" value="1" />
    <span class="destaque">Yens no Banco:</span><br />
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
    <input type="text" id="don_yens" name="don_yens" onkeypress='return SomenteNumero(event)' onkeydown = "BloqueiaComando(event)" ><br />
    <span class="sub2">Digite a quantidade de yens que voce deseja retirar(apenas números).</span>
    <div class="sep"></div>
    <div align="center"><input type="submit" id="subm" name="subm" class="botao" value="Retirar" /></div>
        </form>
</div>
<div class="box_bottom"></div>
</div>
<div>
</div>

