<?php
require_once('trava.php');
 if($db['vip']>date('Y-m-d')) {
$vipatual=$db['vip'];
$begin_raw = $vipatual;
$begin = strtotime($begin_raw);
$course_duration = '1';
$end = mktime(0,0,0,date('m',$begin)+$course_duration, date('d', $begin), date('Y', $begin));
$vipadd=date('Y-m-d',$end);
 }elseif($db['vip']<=date('Y-m-d H:i:s')){$vipatual=$db['vip'];
$begin_raw = $vipatual;
$course_duration = '1';
$soma = mktime(date('H')+720, date('i'), date('s'));
$fim = date('Y-m-d H:i:s',$soma);
$vipadd=$fim;
}


if($_GET['comprar'] == 'vip'){
if($db['creditos'] < 10 ){echo "<script>self.location='?p=credshop&category=vip&msgg=2'</script>"; return;}
$atual=date('Y-m-d H:i:s');
if($db['vip']>date('Y-m-d')) {
$vipatual=$db['vip'];
$begin_raw = $vipatual;
$begin = strtotime($begin_raw);
$course_duration = '1';
$end = mktime(0,0,0,date('m',$begin)+$course_duration, date('d', $begin), date('Y', $begin));
$vipadd=date('Y-m-d',$end);
}
elseif($db['vip']<=date('Y-m-d')){
$soma = mktime(date('H')+720, date('i'), date('s'));
$fim = date('Y-m-d H:i:s',$soma);
$vipadd=$fim;
}
mysql_query("UPDATE usuarios SET vip='".antiinjection($vipadd)."', vip_inicio='".antiinjection($atual)."',creditos=creditos-10, creditosusados=creditosusados+35 WHERE id='".$db['id']."'");
$data=date('Y-m-d H:i:s');
$usuario=$db['id'];
$ass=$db['usuario'];
$msg='Usuario Adiquirio um pacote vip. Usuario: '.$db['usuario'].' por 10 creditos.';
mysql_query("INSERT INTO log_creditos (usuarioid,data,assunto,msg)"."VALUES ('".$usuario."','".$data."','".$ass."','".$msg."')");

echo "<script>self.location='?p=credshop&category=vip&msgg=3'</script>"; return;
}







?>


<?php if(isset($_GET['msgg'])){
	switch($_GET['msgg']){
		case 1: $msgg='Voce ja possui  VIP, aguarde ele acabar para poder comprar novamente..'; break;
		case 2: $msgg='Creditos insuficientes.'; break;
		case 3: $msgg='VIP adiquirido com sucesso.'; break;
	}
	echo '<div class="aviso">'.$msgg.'</div><div class="sep"></div>';
	} ?>
<br>


<table border="0" width="100%">
<tr style="background: none repeat scroll 0% 0% rgb(50, 50, 50);" onmouseover="style.background='#310000'" onmouseout="style.background='#161616'">
    	<td align="center" width="140"><img src="_img/equipamentos/vip.png"></td>
        <td style="padding: 5px; text-align: center;" valign="top">
        	<b>Comprar Vip</b><br>
            <span class="sub2">30 Dias de Vip.</span><br>
           <span class="sub2">

      <?php
if($db['vip']>date('Y-m-d')) {
print '
<b><small><font color="#FFFFFF">Seu vip acaba em</font>:</b></small> = <font color="#00FF40">'.$begin_raw.'</font>';
}
elseif($db['vip']<=date('Y-m-d')) {print '
<b><small><font color="#FFFFFF">Seu vip acaba em</font>:</b></small> = <font color="#0000FF">Expirou</font>';
}
if($db['vip']>date('Y-m-d')) {

print '
<br><small><font color="#FFFFFF">Final do proximo vip </small></font>= <font color="#0000FF">'.date('Y-m-d',$end).'</font>';

}
elseif($db['vip']<=date('Y-m-d')) {
     print '
<br><font color="#FFFFFF"><small>Final do proximo vip </small></font> = '.$vipadd.'<font color="#00FF00">+ 2 Dias (Bônus)</font>';
}

      ?>
           </span><br>
 </td>
        <td align="center" width="20%">
              <b>Valor</b><br />
            <span class="sub2">10 créditos</span><br><br>
            <form method="post" action="?p=credshop&category=vip&comprar=vip" onsubmit="subm.value='Carregando';subm.disabled=true;">
            <input id="subm" name="subm" class="botao" value="Adiquirir" type="submit">            </form>
        </td>
  </tr>
  </table>



</div>
