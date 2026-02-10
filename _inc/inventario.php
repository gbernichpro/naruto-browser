<div class="modern-card">
    <div class="modern-card-header">🍱 Refeições Rápidas</div>
    <div class="modern-card-body">
        <p style="font-size: 11px; color: #888; margin-bottom: 15px;">
            Itens do Ichiraku Bar. Visualize sua <a href="?p=inventory" style="color: gold; text-decoration: none; font-weight: bold;">mochila completa</a> para ver todos os itens.
        </p>

        <?php if(isset($_GET['msg']) && $_GET['msg'] == 1): ?>
            <div class="aviso" style="margin-bottom: 15px; font-size: 11px;">Ramen utilizado! Energia recuperada.</div>
        <?php endif; ?>

        <div style="display: flex; flex-direction: column; gap: 10px;">
            <?php 
            $count = 0;
            if($sqlr && mysqli_num_rows($sqlr) > 0):
                mysqli_data_seek($sqlr, 0); // Reinicia ponteiro se já foi usado
                while($row_r = mysqli_fetch_assoc($sqlr)): 
                    if($count >= 3) break;
                    $count++;
                    
                    switch($row_r['ramenid']){
                        case 1: $nome='Gohan'; $reg=50; break;
                        case 2: $nome='Sushi'; $reg=100; break;
                        case 3: $nome='Peixe Empanado'; $reg=250; break;
                        case 4: $nome='Sashimi'; $reg=500; break;
                        case 5: $nome='Ramen Ichiraku'; $reg=1000; break;
                        default: $nome='Comida'; $reg=0; break;
                    }
            ?>
                <div style="background: rgba(255,255,255,0.03); border: 1px solid #333; border-radius: 8px; padding: 10px; display: flex; align-items: center; gap: 12px;">
                    <img src="_img/ramen/ramen<?php echo $row_r['ramenid']; ?>.png" width="40" style="background: rgba(0,0,0,0.2); border-radius: 4px; padding: 3px;">
                    <div style="flex: 1;">
                        <div style="font-size: 12px; font-weight: bold; color: #fff;"><?php echo $nome; ?></div>
                        <div style="font-size: 9px; color: #5f5;">+<?php echo $reg; ?> Energia</div>
                    </div>
                    <form method="post" action="?p=home" style="margin: 0;">
                        <input type="hidden" name="ram_id" value="<?php echo $c->encode($row_r['id'],$chaveuniversal); ?>" />
                        <input type="hidden" name="ram_tipo" value="<?php echo $c->encode($row_r['ramenid'],$chaveuniversal); ?>" />
                        <input type="submit" class="modern-btn" style="padding: 3px 10px; font-size: 10px; background: #222;" value="Usar" />
                    </form>
                </div>
            <?php 
                endwhile; 
            else:
                echo '<div class="aviso" style="font-size: 11px;">Mochila de comida vazia.</div>';
            endif;
            ?>
        </div>

        <?php if($count >= 3): ?>
            <div style="margin-top: 15px; text-align: center;">
                <a href="?p=inventory" class="modern-btn" style="padding: 5px 20px; font-size: 10px; width: 100%; display: block; box-sizing: border-box; background: rgba(255,255,255,0.05);">Ver Todos</a>
            </div>
        <?php endif; ?>
    </div>
</div>