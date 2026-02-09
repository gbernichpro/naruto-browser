<?php
$sqls = mysql_query("SELECT * FROM table_personagens WHERE nivel<=".$db['nivel']." ORDER BY nivel ASC");
$dbs = mysql_fetch_assoc($sqls);
$sqlp = mysql_query("SELECT * FROM personagens WHERE usuarioid='".$db['id']."'");
$dbp = mysql_fetch_assoc($sqlp);
require_once('funcoes.php');
?>
<table width="100%" cellpadding="0" cellspacing="1">
    <?php $count=0; do{ if($dbp[$dbs['personagem']]==0){ $count++; ?>
    <tr style="background:#161616;" onmouseover="style.background='#310000'" onmouseout="style.background='#161616'">
    	<td align="center" width="200"><img src="_img/personagens/unlock_<?php echo $dbs['personagem']; ?>.jpg" width="220" height="100"/></td>
        <td align="center" valign="middle" style="padding:5px;">
        	<b>Personagem</b><br />
        	<span class="sub2"><?php fpersonagem($dbs['personagem']); ?></span><br />
      </td>
      <td align="center" width="30%">
        	<b>Nível Mínimo</b><br />
            <span class="sub2">Nível <?php echo $dbs['nivel']; ?></span><br /><br />
            <form method="post" action="?p=shop" onsubmit="subm.value='Carregando...';subm.disabled=true;">
            <input type="hidden" id="char_id" name="char_id" value="<?php echo $c->encode($dbs['id'],$chaveuniversal); ?>" />
            <input type="hidden" id="char_nivel" name="char_nivel" value="<?php echo $c->encode($dbs['nivel'],$chaveuniversal); ?>" />
            <input type="hidden" id="char_char" name="char_char" value="<?php echo $c->encode($dbs['personagem'],$chaveuniversal); ?>" />
            <input type="submit" id="subm" name="subm" class="botao" value="Desbloquear" />
            </form>
        </td>
  </tr>
    <?php }} while($dbs=mysql_fetch_assoc($sqls)); ?>
    <?php if($count==0){ echo '<tr><td colspan="3"><div class="aviso">Nenhum personagem encontrado.</div></td></tr>'; } ?>
</table>
<?php
@mysql_free_result($sqlp);
@mysql_free_result($sqls);
?>