<?php
$categoria = 'hp';
$sqls = mysql_query("SELECT * FROM table_clashop WHERE categoria='hp'");
$dbs = mysql_fetch_assoc($sqls);
?>
<table width="100%" cellpadding="0" cellspacing="1">
    <?php if(mysql_num_rows($sqls)==0) echo '<tr><td colspan="3"><div class="aviso">Nenhum item encontrado.</div></td></tr>'; else do{ if(date('Y-m-d H:i:s')<$db['vip']) $dbs['valor']=$dbs['valor']; ?>
    <tr style="background:#323232;" onmouseover="style.background='#2C2C2C'" onmouseout="style.background='#323232'">
    	<td align="center" width="140"><img src="_img/equipamentos/<?php echo $dbs['imagem']; ?>.png" /></td>
        <td valign="top" style="padding:5px;text-align:center;">
        	<b><?php echo $dbs['nome']; ?></b><br />
            <span class="sub2"><?php echo $dbs['descricao']; ?></span><br />
      </td>
        <td align="center" width="20%">
        	<b>Valor Unitário</b><br />
            <span class="sub2"><?php echo number_format($dbs['valor']); ?> Pts</span><br /><br />
            <form method="post" action="?p=clashops&amp;en=ok" onsubmit="var b=this.querySelector('input[type=submit]'); if(b) { b.value='Carregando...'; b.disabled=true; }">
            <input type="hidden" id="buy_id" name="buy_id" value="<?php echo $dbs['id']; ?>" />
            <input type="hidden" id="buy_page" name="buy_page" value="<?php echo $categoria; ?>" />
            <input type="hidden" id="buy_cat" name="buy_cat" value="<?php echo $dbs['categoria']; ?>" />
            <?php if($dbs['vip']=='nao'){ ?><input type="submit" id="subm" name="subm" class="botao" value="Comprar" /><?php } else { if(date('Y-m-d H:i:s')>=$db['vip']) echo '<span class="sub2">Exclusiva para VIP.</span>'; else { ?><input type="submit" id="subm" name="subm" class="botao" value="Comprar" /><?php }} ?>
            </form>
        </td>
  </tr>
    <?php } while($dbs=mysql_fetch_assoc($sqls)); ?>
</table>
<?php
@mysql_free_result($sqls);
?>
