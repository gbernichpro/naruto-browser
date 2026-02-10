<?php
$categoria = 'bolsas';
// Conversão para mysqli - Tabela 'table_bolsas'
$sqls = mysqli_query($mysqli_link, "SELECT * FROM table_bolsas ORDER BY valor ASC");
?>
<div class="shop-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; padding: 10px;">
    <?php if(mysqli_num_rows($sqls)==0): ?>
        <div class="aviso" style="grid-column: 1 / -1;">Nenhuma mochila disponível.</div>
    <?php else: 
        while($dbs = mysqli_fetch_assoc($sqls)): 
            // Bolsas não tem desconto VIP (conforme shop.php original)
    ?>
        <div class="modern-card" style="border: 1px solid rgba(255,255,255,0.05); background: rgba(0,0,0,0.4); display: flex; flex-direction: column;">
            <div class="modern-card-header" style="height: 45px; padding: 0 15px; font-size: 14px; display: flex; justify-content: space-between; align-items: center;">
                <span><?php echo $dbs['nome']; ?></span>
                <span style="font-size: 10px; color: var(--text-dim);">Capacidade: +Slot</span>
            </div>
            <div class="modern-card-body" style="padding: 15px; flex: 1; display: flex; flex-direction: column;">
                <div style="display: flex; gap: 15px; margin-bottom: 15px;">
                    <div style="flex: 0 0 80px; height: 80px; background: rgba(255,255,255,0.03); border-radius: 8px; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(255,255,255,0.05);">
                        <img src="_img/equipamentos/<?php echo $dbs['imagem']; ?>.jpg" style="max-width: 60px; max-height: 60px; filter: drop-shadow(0 0 5px rgba(255,255,255,0.2));">
                    </div>
                    <div style="flex: 1; font-size: 11px;">
                        <p style="color: var(--text-dim); margin-bottom: 10px; line-height: 1.4;"><?php echo $dbs['descricao']; ?></p>
                        <div style="display: flex; flex-wrap: wrap; gap: 5px;">
                             <span style="background: rgba(100,200,100,0.1); color: #ada; padding: 2px 6px; border-radius: 4px;">EXPANSÃO</span>
                        </div>
                    </div>
                </div>

                <div style="margin-top: auto; padding-top: 15px; border-top: 1px solid rgba(255,255,255,0.05); display: flex; justify-content: space-between; align-items: center;">
                    <div style="font-family: var(--font-header);">
                        <span style="display: block; font-size: 9px; color: var(--text-dim);">PREÇO</span>
                        <b style="color: #ffd700; font-size: 14px;"><?php echo number_format($dbs['valor'], 2, ',', '.'); ?> <span style="font-size: 9px;">yens</span></b>
                    </div>
                    
                    <form method="post" action="?p=shop" onsubmit="var b=this.querySelector('input[type=submit]'); if(b) { b.value='Processando...'; b.disabled=true; }">
                        <input type="hidden" name="buy_id" value="<?php echo $dbs['id']; ?>" />
                        <input type="hidden" name="buy_page" value="<?php echo $categoria; ?>" />
                        <input type="hidden" name="buy_cat" value="<?php echo $dbs['categoria']; ?>" />
                        
                        <input type="submit" class="modern-btn" style="padding: 8px 15px; font-size: 11px;" value="Comprar" />
                    </form>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>
<?php if(isset($sqls)) mysqli_free_result($sqls); ?>
