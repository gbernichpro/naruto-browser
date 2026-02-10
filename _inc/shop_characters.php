<?php
$sqls = mysql_query("SELECT * FROM table_personagens WHERE nivel<=".$db['nivel']." ORDER BY nivel ASC");
$dbs = mysql_fetch_assoc($sqls);
$sqlp = mysql_query("SELECT * FROM personagens WHERE usuarioid='".$db['id']."'");
$dbp = mysql_fetch_assoc($sqlp);
require_once('funcoes.php');
?>
<div class="shop-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; padding: 10px;">
    <?php 
    $count = 0; 
    if(mysql_num_rows($sqls) > 0):
        while($dbs = mysql_fetch_assoc($sqls)):
            if($dbp[$dbs['personagem']] == 0): 
                $count++;
    ?>
        <div class="modern-card character-card" style="border: 1px solid rgba(255,255,255,0.05); background: rgba(0,0,0,0.6); overflow: hidden; transition: transform 0.3s ease;">
            <div style="position: relative; height: 120px; overflow: hidden;">
                <img src="_img/personagens/unlock_<?php echo $dbs['personagem']; ?>.jpg" style="width: 100%; height: 100%; object-fit: cover; filter: brightness(0.7) contrast(1.1);">
                <div style="position: absolute; bottom: 0; left: 0; right: 0; background: linear-gradient(to top, rgba(0,0,0,0.9), transparent); padding: 15px 20px;">
                    <span style="color: #fff; font-family: var(--font-header); font-size: 16px; text-transform: uppercase; letter-spacing: 1px;">
                        <?php fpersonagem($dbs['personagem']); ?>
                    </span>
                </div>
                <div style="position: absolute; top: 10px; right: 10px; background: rgba(0,0,0,0.6); padding: 4px 10px; border-radius: 20px; font-size: 10px; border: 1px solid rgba(255,255,255,0.1);">
                    Lvl <?php echo $dbs['nivel']; ?>
                </div>
            </div>
            
            <div class="modern-card-body" style="padding: 20px; text-align: center;">
                <p style="font-size: 11px; color: var(--text-dim); margin-bottom: 20px; line-height: 1.5;">Desbloqueie este personagem para utilizá-lo em suas batalhas e missões especiais.</p>
                
                <form method="post" action="?p=shop" onsubmit="var b=this.querySelector('input[type=submit]'); if(b) { b.value='Desbloqueando...'; b.disabled=true; }">
                    <input type="hidden" name="char_id" value="<?php echo $c->encode($dbs['id'], $chaveuniversal); ?>" />
                    <input type="hidden" name="char_nivel" value="<?php echo $c->encode($dbs['nivel'], $chaveuniversal); ?>" />
                    <input type="hidden" name="char_char" value="<?php echo $c->encode($dbs['personagem'], $chaveuniversal); ?>" />
                    <input type="submit" class="modern-btn" style="width: 100%; padding: 12px; font-size: 12px; font-weight: bold;" value="Desbloquear Agora">
                </form>
            </div>
        </div>
    <?php 
            endif;
        endwhile;
    endif; 
    
    if($count == 0): ?>
        <div class="aviso" style="grid-column: 1 / -1;">Nenhum personagem disponível para desbloqueio no momento.</div>
    <?php endif; ?>
</div>
<?php 
@mysql_free_result($sqlp);
@mysql_free_result($sqls); 
?>
<?php
@mysql_free_result($sqlp);
@mysql_free_result($sqls);
?>