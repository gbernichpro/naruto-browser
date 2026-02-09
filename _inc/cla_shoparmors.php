<?php
$categoria = 'armors';
$dif = $db['nivel']+1;
$sqls = mysql_query("SELECT * FROM table_itens WHERE categoria='vestimenta' AND clashop='sim' AND reqnivel<$dif ORDER BY reqnivel ASC");
$dbs = mysql_fetch_assoc($sqls);
?>
<table width="100%" cellpadding="0" cellspacing="1">
  <?php if(mysql_num_rows($sqls)==0) echo '<tr><td colspan="3"><div class="aviso">Nenhum item encontrado.</div></td></tr>'; else do{ if(date('Y-m-d H:i:s')<$db['vip']) $dbs['valor']=$dbs['valor']-($dbs['valor']*0.15); ?>
      <!--<?php
    $texto="<table border=0 width=100%><div style='float:left;'><font color='#FFFFFF'><b>".$dbs["nome"]."</b></font></div><div style='float:right;'><font color='#00FF00'><b>Detalhes</b></font></div>";
    $texto.="<table border=0 width=100%><tr><td valign=top align='left' ><table border=0 width=100%><tr><td width=50 align='left' ><tr><td width=50 align='left'><b>Nivel Requerido</b></td><td>".$dbs["reqnivel"]."</td></tr></table></td><td align='left'><table border=0 width=100%><tr><td width=50 align='left'><b class=add>Taijutsu:</b></td><td align='left'> +".$dbs["taijutsu"]."</td></tr><tr><td width=50 align='left'><b class=add>Ninjutsu:</b></td><td align='left'> +".$dbs["ninjutsu"]."</td></tr><tr><td width=50 align='left'><b class=add>Genjutsu:</b></td><td> +".$dbs["genjutsu"]."</td></tr></table></td></tr></table>";
    $texto.="<div class=sep></div>";
    $texto.="<table border=0 width=100%><tr><td width=50 align='left'>Preço:".number_format($dbs['valor'],2,',','.')." Yens.</td></tr>";
    ?> -->

    <tr style="background:#323232;" onmouseover="style.background='#2C2C2C'" onmouseout="style.background='#323232'">
    	<td align="center" width="140"><img src="_img/equipamentos/<?php echo $dbs['imagem']; ?>.png" id="tip-direita" original-title="<?=$texto?>"/></td>
        <td valign="top" style="padding:5px;text-align:center;">
        	<b><?php echo $dbs['nome']; ?></b><br />
            <span class="sub2"><?php echo $dbs['descricao']; ?></span><br />
          <b><?php if($dbs['taijutsu']>0) echo '<img src="_img/equipamentos/up.png" width="14" height="14" align="absmiddle" /> [+'.$dbs['taijutsu'].'] em Taijutsu<br />'; ?>
            <?php if($dbs['ninjutsu']>0) echo '<img src="_img/equipamentos/up.png" width="14" height="14" align="absmiddle" /> [+'.$dbs['ninjutsu'].'] em Ninjutsu<br />'; ?>
            <?php if($dbs['genjutsu']>0) echo '<img src="_img/equipamentos/up.png" width="14" height="14" align="absmiddle" /> [+'.$dbs['genjutsu'].'] em Genjutsu<br />'; ?></b>
      </td>
        <td align="center" width="20%">
            <b>Nivel Mínimo</b><br />
            <span class="sub2"><?php echo $dbs['reqnivel']; ?> </span><br /><br />
            <b>Valor Unitário</b><br />
            <span class="sub2"><?php echo $dbs['valor']; ?> Pts</span><br /><br />
            <form method="post" action="?p=cla_shop" onsubmit="subm.value='Carregando...';subm.disabled=true;">
            <input type="hidden" id="buy_id2" name="buy_id2" value="<?php echo $dbs['id']; ?>" />
            <input type="hidden" id="buy_page2" name="buy_page2" value="<?php echo $categoria; ?>" />
            <input type="hidden" id="buy_cat2" name="buy_cat2" value="<?php echo $dbs['categoria']; ?>" />
            <?php if($dbs['vip']=='nao'){ ?><input type="submit" id="subm" name="subm" class="botao" value="Comprar" /><?php } else { if(date('Y-m-d H:i:s')>=$db['vip']) echo '<span class="sub2">Exclusiva para VIP.</span>'; else { ?><input type="submit" id="subm" name="subm" class="botao" value="Comprar" /><?php }} ?>
            </form>
        </td>
  </tr>
    <?php } while($dbs=mysql_fetch_assoc($sqls)); ?>
</table>
<?php
@mysql_free_result($sqls);
?>
