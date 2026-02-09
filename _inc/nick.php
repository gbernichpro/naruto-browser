<?php
$data=date('Y-m-d H:i:s');
$usuario=$db['id'];
$ass=$db['usuario'];

if(isset($_POST['troca'])) {
    $teste=mysql_query("Select * from usuarios where usuario='".antiinjection($_POST['nick'])."'");
    $test=mysql_fetch_assoc($teste);
    if(mysql_num_rows($teste)>0){echo "<script>self.location='?p=credshop&msg=20'</script>"; return;}
    $pattern = "([_ _-_,_._>_`_´_<_~_^\/_?_°_\_:_;_§_|_!_¹_²_³_£_¢_¬_§_º_@_#_%_¨_&_*_+_{_}_*_])" ;
    if(preg_match('/' . $pattern . '/', $_POST['nick']))
    {
    die("<script>self.location='?p=credshop&msg=17'</script>");
    }
   if(strlen($_POST['nick']) < 4){echo "<script>self.location='?p=credshop&msg=21'</script>"; return;}
    if($_POST['nick']==""){
			echo "<script>self.location='?p=credshop&msg=18'</script>"; return;
			}
    if($db['creditos'] < 10){
			echo "<script>self.location='?p=credshop&msg=10'</script>"; return;
			}
		else {
		mysql_query("UPDATE usuarios SET usuario='".antiinjection($_POST['nick'])."' WHERE id=".$db['id']);
		mysql_query("UPDATE usuarios SET creditos=creditos-10, creditosusados=creditosusados +10 WHERE id=".$db['id']);
        $msg='Usuario acabou de utilizar o sistema troca de nick antes:'.$ass.' Ficou '.$_POST['nick'].' ';
	    mysql_query("INSERT INTO log_creditos (usuarioid,data,assunto,msg)"."VALUES ('".$usuario."','".$data."','".$ass."','".$msg."')");
        echo "<script>self.location='?p=credshop&msg=19'</script>";
              }
   }


?>







 <form method="POST" action="?p=nick">
<table border="0" width="100%">
<tr style="background: none repeat scroll 0% 0% rgb(50, 50, 50);" onmouseover="style.background='#310000'" onmouseout="style.background='#161616'">
    	<td align="center" width="140"><img src="_img/equipamentos/pedrarara.png"></td>
        <td style="padding: 5px; text-align: center;" valign="top">
        	<strong><b>Para trocar de nick será preciso 10 creditos e colocando o nome com sabedoria pois não tem retorno caso coloque errado use com sabedoria.</b></strong><br>
            <span class="sub2"><small>+ Troca de nick.</small>
                         </span><br><b><small>Coloque o nick desejado</small></b>:

                       <input type="text" id="nick" name="nick" original-title="Digite aqui o nick desejado , sem caracter especial." class="jrrios" maxlenght="15" size="20">

         </td>



        <td align="center" width="20%">

            <span><i><b>Valor</b></i></span><br>
            <span class="sub2">10 creditos</span><br><br>

            <input id="troca" name="troca" class="botao" value="Trocar de nick" type="submit">
            </form>
        </td>
  </tr>
</table>
</div>
