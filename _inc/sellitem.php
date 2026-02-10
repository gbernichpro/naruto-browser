<?php
require_once('trava.php');

if(isset($_POST['id'], $_POST['senha'])){
    $password = $_POST['senha'];
    $id = (int)$_POST['id'];

    // Verifica senha de venda
    if($db['pass'] <> $password){
        echo "<script>self.location='?p=inventory&msg=5'</script>"; exit();
    }

    // Busca o item e valida dono
    $stmt_i = mysqli_prepare($mysqli_link, "SELECT itemid FROM inventario WHERE id=? AND usuarioid=?");
    mysqli_stmt_bind_param($stmt_i, "ii", $id, $db['id']);
    mysqli_stmt_execute($stmt_i);
    $res_i = mysqli_stmt_get_result($stmt_i);
    $dbi = mysqli_fetch_assoc($res_i);

    if(!$dbi){
        echo "<script>self.location='?p=home'</script>"; exit();
    }

    // Busca valor do item
    $stmt_v = mysqli_prepare($mysqli_link, "SELECT nome, valor FROM table_itens WHERE id=?");
    mysqli_stmt_bind_param($stmt_v, "i", $dbi['itemid']);
    mysqli_stmt_execute($stmt_v);
    $res_v = mysqli_stmt_get_result($stmt_v);
    $dbv = mysqli_fetch_assoc($res_v);
    
    $valor_venda = floor($dbv['valor'] / 2);

    // Inicia Transação
    mysqli_begin_transaction($mysqli_link);
    try {
        // Remove item
        $stmt_del = mysqli_prepare($mysqli_link, "DELETE FROM inventario WHERE id=? AND usuarioid=?");
        mysqli_stmt_bind_param($stmt_del, "ii", $id, $db['id']);
        mysqli_stmt_execute($stmt_del);

        if(mysqli_stmt_affected_rows($stmt_del) == 0){
            throw new Exception("Falha ao remover item ou item inexistente.");
        }

        // Adiciona Yens
        $stmt_upd = mysqli_prepare($mysqli_link, "UPDATE usuarios SET yens=yens+? WHERE id=?");
        mysqli_stmt_bind_param($stmt_upd, "ii", $valor_venda, $db['id']);
        mysqli_stmt_execute($stmt_upd);

        // Gera Log
        $msg = "Usuário vendeu o item: {$dbv['nome']} por " . number_format($valor_venda, 2, ',', '.') . " yens.";
        $stmt_log = mysqli_prepare($mysqli_link, "INSERT INTO log_vendas (usuarioid, data, assunto, msg) VALUES (?, NOW(), ?, ?)");
        mysqli_stmt_bind_param($stmt_log, "iss", $db['id'], $db['usuario'], $msg);
        mysqli_stmt_execute($stmt_log);

        mysqli_commit($mysqli_link);
        echo "<script>self.location='?p=inventory&msg=4&value=$valor_venda'</script>";
    } catch (Exception $e) {
        mysqli_rollback($mysqli_link);
        die("Erro ao processar venda: " . $e->getMessage());
    }
    exit();
}

if(!isset($_GET['id'])){ 
    echo "<script>self.location='?p=home'</script>"; exit(); 
}

$id_get = (int)$_GET['id'];
$stmt_q = mysqli_prepare($mysqli_link, "SELECT i.id, i.status, i.upgrade, i.usuarioid, t.categoria, t.descricao, t.taijutsu, t.ninjutsu, t.genjutsu, t.nome, t.imagem, t.valor 
                                       FROM inventario i 
                                       LEFT OUTER JOIN table_itens t ON i.itemid=t.id 
                                       WHERE i.id=? AND i.usuarioid=?");
mysqli_stmt_bind_param($stmt_q, "ii", $id_get, $db['id']);
mysqli_stmt_execute($stmt_q);
$res_q = mysqli_stmt_get_result($stmt_q);
$dbi = mysqli_fetch_assoc($res_q);

if(!$dbi){ 
    echo "<script>self.location='?p=home'</script>"; exit(); 
}
?>

<div class="modern-card">
    <div class="modern-card-header">💰 Venda de Item</div>
    <div class="modern-card-body">
        
        <div style="background: rgba(255,165,0,0.1); border-left: 4px solid gold; padding: 15px; margin-bottom: 25px; border-radius: 4px;">
            <p style="margin: 0; font-size: 13px; color: #ddd;">
                Você está prestes a vender este item para a Vila. O valor recebido será exatamente <b>50% do valor de mercado</b>. Esta ação é irreversível.
            </p>
        </div>

        <div style="display: flex; gap: 20px; align-items: start; background: rgba(0,0,0,0.2); padding: 20px; border-radius: 8px; border: 1px solid #333; margin-bottom: 25px;">
            <div style="position: relative; background: rgba(0,0,0,0.3); padding: 10px; border-radius: 8px; border: 1px solid #444;">
                <img src="_img/equipamentos/<?php echo $dbi['imagem']; ?>.png" style="width: 100px; filter: drop-shadow(0 0 10px rgba(0,0,0,0.5));">
                <?php if($dbi['upgrade'] > 0): ?>
                    <span style="position: absolute; top: -5px; right: -5px; background: #f00; color: #fff; padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: bold; border: 1px solid #fff;">+<?php echo $dbi['upgrade']; ?></span>
                <?php endif; ?>
            </div>
            
            <div style="flex: 1;">
                <h3 style="margin: 0 0 10px 0; color: gold; font-family: 'Impact', sans-serif; letter-spacing: 1px;"><?php echo $dbi['nome']; ?></h3>
                <p style="font-size: 12px; color: #888; margin-bottom: 15px; line-height: 1.4;"><?php echo $dbi['descricao']; ?></p>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 10px;">
                    <?php if($dbi['taijutsu'] > 0): ?>
                        <div style="font-size: 11px; color: #ccc;"><img src="_img/equipamentos/up.png" width="12" style="vertical-align: middle;"> Taijutsu: <span style="color: #5f5;">+<?php echo ($dbi['taijutsu'] + $dbi['upgrade']); ?></span></div>
                    <?php endif; ?>
                    <?php if($dbi['ninjutsu'] > 0): ?>
                        <div style="font-size: 11px; color: #ccc;"><img src="_img/equipamentos/up.png" width="12" style="vertical-align: middle;"> Ninjutsu: <span style="color: #5f5;">+<?php echo ($dbi['ninjutsu'] + $dbi['upgrade']); ?></span></div>
                    <?php endif; ?>
                    <?php if($dbi['genjutsu'] > 0): ?>
                        <div style="font-size: 11px; color: #ccc;"><img src="_img/equipamentos/up.png" width="12" style="vertical-align: middle;"> Genjutsu: <span style="color: #5f5;">+<?php echo ($dbi['genjutsu'] + $dbi['upgrade']); ?></span></div>
                    <?php endif; ?>
                </div>

                <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #333;">
                    <div style="font-size: 11px; color: #666; text-transform: uppercase;">Valor de Recompra:</div>
                    <div style="font-size: 20px; color: #5f5; font-weight: bold;">
                        <img src="_img/yens.png" width="18" style="vertical-align: middle; margin-right: 5px;">
                        <?php echo number_format(($dbi['valor']/2), 2, ',', '.'); ?> <span style="font-size: 12px;">yens</span>
                    </div>
                </div>
            </div>
        </div>

        <form method="post" action="?p=sellitem" style="max-width: 400px; margin: 0 auto; background: rgba(0,0,0,0.1); padding: 20px; border-radius: 8px; border: 1px solid #222;" onsubmit="var b=this.querySelector('input[type=submit]'); if(b) { b.value='Processando...'; b.disabled=true; }">
            <input type="hidden" name="id" value="<?php echo $dbi['id']; ?>" />
            
            <div style="margin-bottom: 20px; text-align: center;">
                <label style="display: block; color: #aaa; font-size: 12px; margin-bottom: 10px;">SENHA DE SEGURANÇA:</label>
                <input type="password" name="senha" class="modern-input" style="width: 100%; text-align: center; font-size: 16px; letter-spacing: 3px;" placeholder="••••••" required>
            </div>

            <div style="text-align: center;">
                <input type="submit" class="modern-btn" style="width: 100%; padding: 12px; background: #822; color: #fff; font-weight: bold;" value="VENDER AGORA" />
                <a href="?p=inventory" style="display: block; margin-top: 15px; font-size: 11px; color: #666; text-decoration: none;">Desistir da Venda</a>
            </div>
        </form>

    </div>
</div>