<?php
$categoria = 'animais';
$dif = $db['nivel']+7;
$sqls = mysql_query("SELECT * FROM table_animais WHERE categoria='animais' AND reqnivel<$dif AND credshop='nao' ORDER BY reqnivel ASC");
$dbs = mysql_fetch_assoc($sqls);
?>
<table width="100%" cellpadding="0" cellspacing="1">
    <?php if(mysql_num_rows($sqls)==0) echo '<tr><td colspan="3"><div class="aviso">Nenhum animal lendario.</div></td></tr>'; else do{ if(date('Y-m-d H:i:s')<$db['vip']) $dbs['valor']=$dbs['valor']-($dbs['valor']*0.2); ?>
    <tr style="background:#161616;" onmouseover="style.background='#310000'" onmouseout="style.background='#161616'">
    	<td align="center" width="140"><img src="_img/equipamentos/<?php echo $dbs['imagem']; ?>.png" /></td>
        <td valign="top" style="padding:5px;text-align:center;">
            <?php
            $tai=$dbs['maxtai'];
            $nin=$dbs['maxnin'];
            $gen=$dbs['maxgen'];
            ?>
          <div align="center" style="height:20px;line-height:20px;"><b><?php echo $dbs['nome']; ?></b></div>
            <div class="sep2"></div>
            <div style="float:left;width:170px;text-align:left;margin-right:10px;"><?php echo $dbs['descricao']; ?></div>
            <div style="float:left;width:150px;color:#FFFFFF;text-align:center;font-size:13px;font-weight:bold;">
            <div style="background:url(_img/skins/naruto/shop/atributo<?php if($tai<0) echo '_r'; if($tai==0) echo '_a'; ?>.png) no-repeat center;line-height:42px;width:50px;float:left;"><?php if($tai>0) echo ''.$tai; else echo $tai; ?></div>
            <div style="background:url(_img/skins/naruto/shop/atributo<?php if($nin<0) echo '_r'; if($nin==0) echo '_a'; ?>.png) no-repeat center;line-height:42px;width:50px;float:left;"><?php if($nin>0) echo ''.$nin; else echo $nin; ?></div>
            <div style="background:url(_img/skins/naruto/shop/atributo<?php if($gen<0) echo '_r'; if($gen==0) echo '_a'; ?>.png) no-repeat center;line-height:42px;width:50px;float:left;"><?php if($gen>0) echo ''.$gen; else echo $gen; ?></div>
            <br />
            <div style="font-size:8px;font-weight:normal;font-family:Arial;">

                <div style="width:50px;float:left;">*MAXAP TAIJUTSU.</div>
                <div style="width:50px;float:left;">*MAXAP NINJUTSU</div>
                <div style="width:50px;float:left;">*MAXAP GENJUTSU</div>
                <div style="width:250px;color:#FFFF0F;float:center;"> *MAXAP=ATRIBUTOS MAXIMOS QUE O ANIMAL PODERÁ SER APRIMORADO.</div>

               </div>
                </div>
            <div class="clear"></div>
            <div class="sep2"></div>


      </td>
        <td align="center" width="20%">
        	<b>Nivel Mínimo</b><br />
            <span class="sub2"><?php echo $dbs['reqnivel']; ?> niveis.</span><br /><br />
            <b>Valor Unitário</b><br />
            <span class="sub2"><?php echo number_format($dbs['valor'],2,',','.'); ?> yens</span><br /><br />
            <form method="post" action="?p=animais" onsubmit="var b=this.querySelector('input[type=submit]'); if(b) { b.value='Carregando...'; b.disabled=true; }">
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
