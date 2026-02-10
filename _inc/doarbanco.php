<?php require_once('trava.php'); ?>
<?php

// Check banco limite
if($db['yensbanco'] >= 200001){
    echo "<script>self.location='?p=banco&msg=3'</script>"; exit();
}

$sqlu=mysqli_query($mysqli_link, "SELECT * FROM usuarios WHERE id='".$db['id']."'");
$dbu=mysqli_fetch_assoc($sqlu);
 
if(isset($_POST['don'])){
        $yens=isset($_POST['don_yens']) ? (int)$_POST['don_yens'] : 0;
        // vn($yens); // Validar Numero? Função global.
        
        if($yens > $db['yens']){ 
            echo "<script>self.location='?p=doarbanco&msg=2'</script>"; exit(); 
        }
        
        if($yens <= 0){ 
            echo "<script>self.location='?p=banco&msg=4'</script>"; exit(); 
        }
        
        // Transaction logic
        mysqli_query($mysqli_link, "UPDATE usuarios SET yensbanco=yensbanco+$yens, yens=yens-$yens WHERE id=".$db['id']);
        
        echo "<script>self.location='?p=doarbanco&msg=1&yens=$yens'</script>"; exit();
}
?>
<div class="modern-card">
    <div class="modern-card-header">Banco de Konoha - Depósito</div>
    <div class="modern-card-body">
        
        <div style="display: flex; gap: 20px; align-items: center; background: rgba(0,0,0,0.2); padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <img src="_img/_detalhes/msg/5.png" style="width: 80px; border-radius: 8px;">
            <div>
                <h3 style="color: gold; margin: 0 0 5px 0;">Depósito de Yens</h3>
                <p style="color: #ccc; font-size: 13px;">
                    Guarde seus Yens com segurança. Ninjas podem tentar roubá-lo se você andar com muito dinheiro!<br>
                    <span style="color: #aaa;">Limite do Banco: <b>200.000 Yens</b></span>
                </p>
            </div>
        </div>

        <?php
        if(isset($_GET['msg'])){
                $msg = '';
                switch($_GET['msg']){
                        case 1: 
                            $yens = isset($_GET['yens']) ? number_format($_GET['yens'],2,',','.') : '0,00';
                            $msg='Depósito de <b>'.$yens.' yens</b> realizado com sucesso!'; 
                            break;
                        case 2: $msg='Você não possui a quantia de yens informada.'; break;
						case 3: $msg='Limite do banco atingido ou valor inválido.'; break;
                        default: $msg = '';
                }
                if($msg) echo '<div class="aviso" style="margin-bottom: 20px;">'.$msg.'</div>';
        }
        ?>

        <div style="display: flex; gap: 10px; margin-bottom: 20px;">
            <a href="?p=doarbanco" class="modern-btn" style="flex: 1; text-align: center; background: #444;">Depositar</a>
            <a href="?p=banco" class="modern-btn" style="flex: 1; text-align: center;">Retirar</a>
        </div>

        <div class="stats-box" style="margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; padding: 10px; border-bottom: 1px solid #444;">
                <span>💰 Yens na Carteira:</span>
                <span style="color: gold;"><?php echo number_format($db['yens'],2,',','.'); ?></span>
            </div>
            <div style="display: flex; justify-content: space-between; padding: 10px;">
                <span>🏦 Yens no Banco:</span>
                <span style="color: #afa;"><?php echo number_format($db['yensbanco'],2,',','.'); ?></span>
            </div>
        </div>

        <form method="post" action="?p=doarbanco" onsubmit="var b=this.querySelector('input[type=submit]'); if(b) { b.value='Depositando...'; b.disabled=true; }">
            <input type="hidden" name="don" value="1" />
            
            <label class="destaque">Valor para Depósito:</label>
            <input type="number" id="don_yens" name="don_yens" class="modern-input" style="width: 100%; margin-bottom: 10px;" placeholder="Digite o valor..." min="1" max="<?php echo 200001 - $db['yensbanco']; ?>" required>
            
            <div align="center">
                <input type="submit" id="subm" name="subm" class="modern-btn" value="Confirmar Depósito" />
            </div>
        </form>

    </div>
</div>