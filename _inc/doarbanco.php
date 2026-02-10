<?php 
require_once('trava.php'); 

$limite_banco = 200000;

if(isset($_POST['don'])){
    $yens = (int)$_POST['don_yens'];
    
    // Validações
    if($yens > $db['yens']){ 
        echo "<script>self.location='?p=doarbanco&msg=2'</script>"; exit(); 
    }
    if($yens <= 0){ 
        echo "<script>self.location='?p=banco&msg=4'</script>"; exit(); 
    }
    if(($db['yensbanco'] + $yens) > $limite_banco){
        echo "<script>self.location='?p=doarbanco&msg=3'</script>"; exit();
    }

    // Início da Transação Financeira
    mysqli_begin_transaction($mysqli_link);
    try {
        // Deduz da carteira
        $stmt_c = mysqli_prepare($mysqli_link, "UPDATE usuarios SET yens=yens-? WHERE id=?");
        mysqli_stmt_bind_param($stmt_c, "ii", $yens, $db['id']);
        mysqli_stmt_execute($stmt_c);

        // Adiciona ao banco
        $stmt_b = mysqli_prepare($mysqli_link, "UPDATE usuarios SET yensbanco=yensbanco+? WHERE id=?");
        mysqli_stmt_bind_param($stmt_b, "ii", $yens, $db['id']);
        mysqli_stmt_execute($stmt_b);

        mysqli_commit($mysqli_link);
        echo "<script>self.location='?p=doarbanco&msg=1&yens=$yens'</script>";
    } catch (Exception $e) {
        mysqli_rollback($mysqli_link);
        die("Erro crítico no processamento bancário: " . $e->getMessage());
    }
    exit();
}
?>

<div class="modern-card">
    <div class="modern-card-header">🏦 Banco de Konoha - Depósito</div>
    <div class="modern-card-body">
        
        <div style="display: flex; gap: 20px; align-items: center; background: rgba(0,0,0,0.2); padding: 15px; border-radius: 8px; margin-bottom: 25px;">
            <img src="_img/_detalhes/msg/5.png" style="width: 80px; border-radius: 8px; filter: drop-shadow(0 0 10px rgba(0,0,0,0.5));">
            <div>
                <h3 style="color: gold; margin: 0 0 5px 0; font-family: 'Impact', sans-serif; letter-spacing: 1px;">DEPÓSITO DE FUNDOS</h3>
                <p style="color: #ccc; font-size: 13px; margin: 0;">
                    Proteja sua fortuna contra ladrões. O banco é o lugar mais seguro para seus Yens.<br>
                    <span style="color: #aaa; font-size: 11px;">Limite Máximo: <b><?php echo number_format($limite_banco,0,',','.'); ?></b> yens.</span>
                </p>
            </div>
        </div>

        <div style="display: flex; gap: 10px; margin-bottom: 25px;">
            <a href="?p=doarbanco" class="modern-btn active" style="flex: 1; text-align: center; border-bottom: 2px solid gold;">DEPOSITAR</a>
            <a href="?p=banco" class="modern-btn" style="flex: 1; text-align: center;">RETIRAR</a>
        </div>

        <?php
        if(isset($_GET['msg'])){
            $msg_txt = '';
            switch($_GET['msg']){
                case 1: $y = number_format((int)$_GET['yens'],2,',','.'); $msg_txt = 'Depósito de <b>'.$y.' yens</b> realizado com sucesso!'; break;
                case 2: $msg_txt = 'Você não possui essa quantia na carteira.'; break;
                case 3: $msg_txt = 'Limite do banco atingido ou valor excede o permitido.'; break;
            }
            if($msg_txt) echo '<div class="aviso" style="margin-bottom: 20px;">'.$msg_txt.'</div>';
        }
        ?>

        <div style="background: rgba(0,0,0,0.3); border-radius: 8px; padding: 15px; margin-bottom: 25px; border: 1px solid #333; display: flex; justify-content: space-around; align-items: center;">
            <div style="text-align: center;">
                <div style="font-size: 11px; color: #888; text-transform: uppercase;">Yens na Carteira</div>
                <div style="font-size: 18px; color: gold; font-weight: bold;"><?php echo number_format($db['yens'],2,',','.'); ?></div>
            </div>
            <div style="width: 1px; height: 30px; background: #444;"></div>
            <div style="text-align: center;">
                <div style="font-size: 11px; color: #888; text-transform: uppercase;">Guardado no Banco</div>
                <div style="font-size: 18px; color: #5f5; font-weight: bold;"><?php echo number_format($db['yensbanco'],2,',','.'); ?></div>
            </div>
        </div>

        <form method="post" action="?p=doarbanco" onsubmit="var b=this.querySelector('input[type=submit]'); if(b) { b.value='Processando...'; b.disabled=true; }">
            <input type="hidden" name="don" value="1" />
            
            <div style="margin-bottom: 20px;">
                <label style="color: #aaa; font-size: 12px; display: block; margin-bottom: 8px;">QUANTIA PARA DEPOSITAR:</label>
                <div style="position: relative;">
                    <img src="_img/yens.png" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 20px;">
                    <input type="number" name="don_yens" class="modern-input" style="width: 100%; padding-left: 45px; box-sizing: border-box;" placeholder="0" min="1" max="<?php echo min($db['yens'], $limite_banco - $db['yensbanco']); ?>" required>
                </div>
                <span style="font-size: 10px; color: #666; margin-top: 5px; display: block;">Saldo disponível no banco: <b><?php echo number_format($limite_banco - $db['yensbanco'],0,',','.'); ?></b> yens.</span>
            </div>

            <div style="text-align: center;">
                <input type="submit" class="modern-btn" style="padding: 12px 40px; font-weight: bold; letter-spacing: 1px;" value="CONFIRMAR DEPÓSITO" />
            </div>
        </form>

        <div style="margin-top: 30px; padding: 15px; background: rgba(0,0,0,0.2); border-radius: 8px; border: 1px solid #222;">
            <p style="font-size: 11px; color: #555; margin: 0; text-align: center;">
                O Banco de Konoha garante 100% de segurança sobre seus fundos depositados.
            </p>
        </div>
    </div>
</div>
