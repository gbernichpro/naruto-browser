<div class="mission-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px; padding: 10px;">
    <?php 
    $ranks = [
        ['rank' => 'S', 'nivel' => 40, 'yens' => '5.000,00', 'img' => 'ranks.jpg', 'color' => '#ff4444'],
        ['rank' => 'A', 'nivel' => 30, 'yens' => '2.500,00', 'img' => 'ranka.jpg', 'color' => '#ff8800'],
        ['rank' => 'B', 'nivel' => 20, 'yens' => '1.500,00', 'img' => 'rankb.jpg', 'color' => '#ffff00'],
        ['rank' => 'C', 'nivel' => 10, 'yens' => '1.000,00', 'img' => 'rankc.jpg', 'color' => '#00ff00'],
        ['rank' => 'D', 'nivel' => 0,  'yens' => '500,00',   'img' => 'rankd.jpg', 'color' => '#00ccff']
    ];

    foreach($ranks as $r): 
        if($db['nivel'] >= $r['nivel']): ?>
        <div class="modern-card" style="border: 1px solid rgba(255,255,255,0.05); background: rgba(0,0,0,0.4);">
            <div class="modern-card-header" style="height: 40px; padding: 0 15px; font-size: 14px; color: <?php echo $r['color']; ?>">
                Missão Rank <?php echo $r['rank']; ?>
            </div>
            <div class="modern-card-body" style="text-align: center; display: flex; flex-direction: column; align-items: center; padding: 20px;">
                <img src="_img/missoes/<?php echo $r['img']; ?>" style="border-radius: 8px; margin-bottom: 15px; border: 2px solid <?php echo $r['color']; ?>33; width: 100%; max-width: 150px; filter: drop-shadow(0 0 10px <?php echo $r['color']; ?>22);">
                <div style="font-size: 12px; color: var(--text-dim); margin-bottom: 15px;">
                    <b style="color: #fff; display: block; margin-bottom: 5px;"><?php echo $r['yens']; ?> Yens</b>
                    <span>por hora trabalhada</span>
                </div>
                
                <form method="post" action="?p=missions" onsubmit="var b=this.querySelector('input[type=submit]'); if(b) { b.value='Enviando...'; b.disabled=true; }" style="width: 100%;">
                    <input type="hidden" name="mis_rank" value="<?php echo $c->encode($r['rank'], $chaveuniversal); ?>">
                    <select name="mis_tempo" style="width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: #fff; padding: 8px; border-radius: 5px; margin-bottom: 10px; font-size: 12px;">
                        <?php for($i=1; $i<25; $i++): ?>
                        <option value="<?php echo $c->encode($i, $chaveuniversal); ?>"><?php echo $i; ?> hora<?php echo ($i>1 ? 's' : ''); ?></option>
                        <?php endfor; ?>
                    </select>
                    <input type="submit" class="modern-btn" style="width: 100%; padding: 10px; font-size: 12px;" value="Aceitar Missão">
                </form>
            </div>
        </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
