<?php
$iten2=mysql_query("SELECT * FROM inventario where id='".antiinjection($_POST['premiolot'])."'");
$it=mysql_fetch_assoc($iten2);
$valor=10;
$pode=$it['upgrade'];
$valorquepode=$valor-$pode;
if(isset($_GET['junior'])) {
 if($db['creditos'] < 2){
			echo "<script>self.location='?p=credshop&msg=10'</script>"; return;
			}
 else if($it['upgrade']>=10){
			echo "<script>self.location='?p=credshop&msg=17'</script>"; return;
			}
  else{
		mysql_query("UPDATE usuarios SET creditos=creditos-2, creditosusados=creditosusados +2 WHERE id=".$db['id']);
		if($it['upgrade']>5){
        mysql_query("UPDATE inventario SET upgrade=upgrade+".$valorquepode." where usuarioid=".$db['id']." and id='".$it['id']."'");
        }
        else{
		mysql_query("UPDATE inventario SET upgrade=upgrade+5 where usuarioid=".$db['id']." and id='".$it['id']."'");
         }

   echo "<script>self.location='?p=credshop&msg=17&value=".$valorquepode."'</script>";
  }
   }


?>





          <form name="junior" action="?p=rare&junior" method="post">



<table border="0" width="100%">
<tr style="background: none repeat scroll 0% 0% rgb(50, 50, 50);" onmouseover="style.background='#310000'" onmouseout="style.background='#161616'">
    	<td align="center" width="140"><img src="_img/equipamentos/pedrarara.png"></td>
        <td style="padding: 5px; text-align: center;" valign="top">
        	<b>Esta pedra rara encontrada em poucos lugares tem o incrivel poder de refinamento de item podendo aprimorar +5 pontos no seu equipe.</b><br>
            <span class="sub2">+5 pontos no equipamento selecionado (Limite +10).
                         </span><br><b>Selecione o item a utilizar a pedra</b>:


        <select name="premiolot" size="1">
<?php
$sqle=mysql_query("SELECT * FROM table_itens i Left outer join inventario o On o.itemid=i.id where o.usuarioid='".$db['id']."'");
$dbr=mysql_fetch_assoc($sqle);
?>
<?php do{ ?>
<option value="<?=$dbr['id']?>"><?=$dbr['nome']?>+<?=$dbr['upgrade']?></option>
<?php $i++; } while($dbr=mysql_fetch_assoc($sqle)); ?>
</select>

</td>

        <td align="center" width="20%">
            <span><i><b>Custo</b></i></span><br>
            <span class="sub2">2 creditos</span><br><br>

            <input id="subm" name="subm" class="botao" value="Aprimorar" type="submit">
            </form>
        </td>
  </tr>
</table>
</div>