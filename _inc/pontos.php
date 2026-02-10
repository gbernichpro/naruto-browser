<?php
// Validar e Processar Adição
if ($db['pontos'] > 0 && isset($_GET['add'])) {
	switch($_GET['add']) {
		case '0':
            mysqli_query($mysqli_link, "UPDATE usuarios SET pontos=pontos-1, taijutsu=taijutsu+1 WHERE id=".$db['id']);
            echo "<script>self.location='?p=pontos&msg=1'</script>"; exit();
        break;
        case '1':
            mysqli_query($mysqli_link, "UPDATE usuarios SET pontos=pontos-1, ninjutsu=ninjutsu+1 WHERE id=".$db['id']);
            echo "<script>self.location='?p=pontos&msg=1'</script>"; exit();
        break;
        case '2':
            mysqli_query($mysqli_link, "UPDATE usuarios SET pontos=pontos-1, genjutsu=genjutsu+1 WHERE id=".$db['id']);
            echo "<script>self.location='?p=pontos&msg=1'</script>"; exit();
        break;
    }
}
?>

<div class="modern-card">
    <div class="modern-card-header">Distribuir Atributos</div>
    <div class="modern-card-body">
        
        <div class="aviso" style="margin-bottom: 20px;">
            Você possui <b style="color: gold; font-size: 16px;"><?php echo $db['pontos']; ?></b> pontos para distribuir.<br>
            <span style="font-size: 11px; color: #aaa;">Você ganha 1 ponto por cada nível evoluído. Distribua com sabedoria, esta ação não pode ser desfeita!</span>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
            <!-- Taijutsu -->
            <div style="background: rgba(0,0,0,0.3); padding: 15px; border-radius: 8px; text-align: center; border: 1px solid #444;">
                <img src="_img/taijutsu_icon.png" style="margin-bottom: 10px;"><br>
                <b style="color: #d64949; text-transform: uppercase;">Taijutsu</b><br>
                <span style="font-size: 24px; font-weight: bold; color: #fff;"><?php echo $db['taijutsu']; ?></span><br>
                <?php if($db['pontos']>0) { ?>
                    <a href="?p=pontos&add=0" class="modern-btn" style="margin-top: 10px; width: 100%; box-sizing: border-box;">+1 Ponto</a>
                <?php } ?>
            </div>
            
            <!-- Ninjutsu -->
            <div style="background: rgba(0,0,0,0.3); padding: 15px; border-radius: 8px; text-align: center; border: 1px solid #444;">
                <img src="_img/ninjutsu_icon.png" style="margin-bottom: 10px;"><br>
                <b style="color: #499dd6; text-transform: uppercase;">Ninjutsu</b><br>
                <span style="font-size: 24px; font-weight: bold; color: #fff;"><?php echo $db['ninjutsu']; ?></span><br>
                <?php if($db['pontos']>0) { ?>
                    <a href="?p=pontos&add=1" class="modern-btn" style="margin-top: 10px; width: 100%; box-sizing: border-box;">+1 Ponto</a>
                <?php } ?>
            </div>
            
            <!-- Genjutsu -->
            <div style="background: rgba(0,0,0,0.3); padding: 15px; border-radius: 8px; text-align: center; border: 1px solid #444;">
                <img src="_img/genjutsu_icon.png" style="margin-bottom: 10px;"><br>
                <b style="color: #d6d649; text-transform: uppercase;">Genjutsu</b><br>
                <span style="font-size: 24px; font-weight: bold; color: #fff;"><?php echo $db['genjutsu']; ?></span><br>
                <?php if($db['pontos']>0) { ?>
                    <a href="?p=pontos&add=2" class="modern-btn" style="margin-top: 10px; width: 100%; box-sizing: border-box;">+1 Ponto</a>
                <?php } ?>
            </div>
        </div>

        <?php if(isset($_GET['msg'])){ ?>
            <div class="sep"></div>
            <div class="aviso">
                <?php 
                if($_GET['msg']==1) echo "Ponto adicionado com sucesso!"; 
                if($_GET['msg']==2) echo "Pontos insuficientes!"; 
                ?>
            </div>
        <?php } ?>
        
    </div>
</div>
