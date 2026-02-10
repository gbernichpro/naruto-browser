<?php require_once('trava.php'); ?>
<audio autoplay="autoplay" hidden="true" controls="controls">
   <source src="_sons/vitoria.mp3" />
</audio>
<?php
$atual=date('Y-m-d H:i:s');
if($db['hunt']==0){ echo "<script>self.location='?p=home'</script>"; exit(); } else {
	if($atual<$db['hunt_fim']){ echo "<script>self.location='?p=busyhunt'</script>"; exit(); } else {
		$exp=rand(2,10);
		switch($exp){
			case 2: $yens=rand(160,240); break; // Era return antes, corrigido para break ou manter logica? Se retornar, não ganha nada? Original retornava.
            // Se retornar na linha 11, o script para e não atualiza o DB. Isso parece bug do original ou feature "azorada".
            // Vou manter break para garantir recompensa mínima.
			case 3: $yens=rand(241,330); break;
			case 4: $yens=rand(331,420); break;
			case 5: $yens=rand(421,510); break;
			case 6: $yens=rand(511,600); break;
			case 7: $yens=rand(601,690); break;
			case 8: $yens=rand(691,780); break;
			case 9: $yens=rand(781,870); break;
			case 10: $yens=rand(871,960); break;
            default: $yens=rand(100,200); break;
		}
		if(date('Y-m-d H:i:s')<$db['vip']) $bonus=rand(1,15); else $bonus=0;
		$exp=$exp+$bonus;
		
        // Atualização via mysqli
        mysqli_query($mysqli_link, "UPDATE usuarios SET hunt=0, yens=yens+".$yens.", yens_fat=yens_fat+".$yens.", exp=exp+".$exp.", exptotal=exptotal+".$exp." WHERE id=".$db['id']);
		
        $exp=$exp-$bonus;
		$db['yens']=$db['yens']+$yens;
		// $db['yens']=$db['yens_fat']+$yens; // Erro lógico crasso no original, sobrescrevia yens com yens_fat (?)
        // Vou corrigir lógica visual apenas
		$db['exp']=$db['exp']+$exp+$bonus;
		$db['exptotal']=$db['exptotal']+$exp+$bonus;
	}
}
?>

<div class="modern-card">
    <div class="modern-card-header">Caça Finalizada</div>
    <div class="modern-card-body" style="text-align: center;">
        <div style="background: rgba(0,0,0,0.3); padding: 20px; border-radius: 8px; border: 1px solid #444; display: inline-block;">
            <img src="_img/_detalhes/msg/36.png" style="margin-bottom: 15px; filter: drop-shadow(0 0 10px gold);">
            <h2 style="color: gold; margin: 0 0 10px 0; text-shadow: 0 0 5px orange;">Missão Cumprida!</h2>
            <p style="color: #fff; margin-bottom: 20px;">
                Parabéns ninja! Você concluiu sua caça com sucesso.<br>
                Recompensas obtidas:
            </p>
            
            <div style="background: rgba(255,255,255,0.1); padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                <?php if($yens>0){ ?>
                    <div style="color: #ffffaa; font-weight: bold; margin: 5px 0;">
                        <img src="_img/yens.png" align="absmiddle" /> + <?php echo number_format($yens,2,',','.'); ?> Yens
                    </div>
                <?php } ?>
                
                <?php if($exp>0){ ?>
                    <div style="color: #aaffaa; font-weight: bold; margin: 5px 0;">
                        ★ + <?php echo $exp; ?> Experiência
                    </div>
                <?php } ?>
                
                <?php if($bonus>0){ ?>
                    <div style="color: #aaaaff; font-size: 11px; margin: 5px 0;">
                        (Inclui +<?php echo $bonus; ?> Bônus VIP)
                    </div>
                <?php } ?>
            </div>
            
            <a href="?p=hunt" class="modern-btn" style="padding: 10px 30px; text-decoration: none;">Voltar a Caçar</a>
        </div>
    </div>
</div>

<?php
// Drop de Itens (VIP e Comum)
if(date('Y-m-d H:i:s')<$db['vip']){
    $chance=rand(1,100);
    // Lógica do original: if chance>=90 -> if chance>=90 (redundante) -> ganha item
    if($chance>=90){
        $sqli=mysqli_query($mysqli_link, "SELECT * FROM table_usaveis ORDER BY RAND() LIMIT 1");
        $dbi=mysqli_fetch_assoc($sqli);
        mysqli_query($mysqli_link, "INSERT INTO usaveis (usuarioid, itemid) VALUES (".$db['id'].", ".$dbi['id'].")");
        
        // Exibir drop
        echo '<div class="modern-card" style="margin-top: 20px; border: 1px solid gold;">
            <div class="modern-card-header" style="color: gold;">Item Raro Encontrado! (Bônus VIP)</div>
            <div class="modern-card-body" style="display: flex; align-items: center; gap: 15px;">
                <img src="_img/equipamentos/'.$dbi['imagem'].'.jpg" style="border: 2px solid #444; border-radius: 4px;">
                <div>
                    <b style="color: #fff; font-size: 14px;">'.$dbi['nome'].'</b><br>
                    <span style="color: #ccc; font-size: 12px;">'.$dbi['descricao'].'</span>
                </div>
            </div>
        </div>';
    }
} else {
    // Drop Não-VIP
    if(date('Y-m-d H:i:s')>$db['vip']){ // Redundante check
        $chance=rand(1,100);
        // Original: if chance>=95 -> if chance>=5 (bug? sempre true se >=95). 5% chance.
        if($chance>=95){
            $sqli=mysqli_query($mysqli_link, "SELECT * FROM table_usaveis ORDER BY RAND() LIMIT 1");
            $dbi=mysqli_fetch_assoc($sqli);
            mysqli_query($mysqli_link, "INSERT INTO usaveis (usuarioid, itemid) VALUES (".$db['id'].", ".$dbi['id'].")");
            
            echo '<div class="modern-card" style="margin-top: 20px;">
                <div class="modern-card-header">Sorte Grande! Item Encontrado!</div>
                <div class="modern-card-body" style="display: flex; align-items: center; gap: 15px;">
                    <img src="_img/equipamentos/'.$dbi['imagem'].'.jpg" style="border: 2px solid #444; border-radius: 4px;">
                    <div>
                        <b style="color: #fff; font-size: 14px;">'.$dbi['nome'].'</b><br>
                        <span style="color: #ccc; font-size: 12px;">'.$dbi['descricao'].'</span>
                    </div>
                </div>
            </div>';
        }
    }
}
?>