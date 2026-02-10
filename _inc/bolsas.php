<?php
require_once('Encrypt.php');
$c = new C_Encrypt();

// Ações de Equipar/Desequipar Bolsa
if(isset($_GET['action'])){
	if(!isset($_GET['id'])){ echo "<script>self.location='?p=home'</script>"; exit(); }
	$id = (int)$_GET['id'];
    
    $stmt_sel = mysqli_prepare($mysqli_link, "SELECT usuarioid FROM bolsas WHERE id=?");
    mysqli_stmt_bind_param($stmt_sel, "i", $id);
    mysqli_stmt_execute($stmt_sel);
    $res_sel = mysqli_stmt_get_result($stmt_sel);
    $dbi = mysqli_fetch_assoc($res_sel);

	if(!$dbi || $dbi['usuarioid'] != $db['id']){ echo "<script>self.location='?p=home'</script>"; exit(); }

	$act = $_GET['action'];
	mysqli_query($mysqli_link, "UPDATE bolsas SET status='off' WHERE usuarioid='".$db['id']."'");
	
    if($act == 'on'){
        $stmt_on = mysqli_prepare($mysqli_link, "UPDATE bolsas SET status='on' WHERE id=?");
        mysqli_stmt_bind_param($stmt_on, "i", $id);
        mysqli_stmt_execute($stmt_on);
        echo "<script>self.location='?p=inventory&msg=2'</script>";
    } else {
        echo "<script>self.location='?p=inventory&msg=3'</script>";
    }
    exit();
}

// Venda de Bolsa
if(isset($_GET['sell'])){
	$id = (int)$_GET['sell'];
	
    $stmt_s = mysqli_prepare($mysqli_link, "SELECT b.itemid, b.usuarioid, t.valor FROM bolsas b JOIN table_bolsas t ON b.itemid=t.id WHERE b.id=?");
    mysqli_stmt_bind_param($stmt_s, "i", $id);
    mysqli_stmt_execute($stmt_s);
    $res_s = mysqli_stmt_get_result($stmt_s);
    $dbi = mysqli_fetch_assoc($res_s);

	if(!$dbi || $dbi['usuarioid'] != $db['id']){ echo "<script>self.location='?p=home'</script>"; exit(); }
	
    mysqli_begin_transaction($mysqli_link);
    try {
        $stmt_del = mysqli_prepare($mysqli_link, "DELETE FROM bolsas WHERE id=?");
        mysqli_stmt_bind_param($stmt_del, "i", $id);
        mysqli_stmt_execute($stmt_del);

        $stmt_up = mysqli_prepare($mysqli_link, "UPDATE usuarios SET yens=yens+? WHERE id=?");
        mysqli_stmt_bind_param($stmt_up, "di", $dbi['valor'], $db['id']);
        mysqli_stmt_execute($stmt_up);

        mysqli_commit($mysqli_link);
        echo "<script>self.location='?p=bolsas&msg=4&value=".$dbi['valor']."'</script>";
    } catch (Exception $e) {
        mysqli_rollback($mysqli_link);
        die("Erro ao vender bolsa: " . $e->getMessage());
    }
    exit();
}

// Listagem
$stmt_l = mysqli_prepare($mysqli_link, "
    SELECT i.id, i.status, i.upgrade, t.categoria, t.descricao, t.taijutsu, t.ninjutsu, t.genjutsu, t.nome, t.imagem, t.valor 
    FROM bolsas i 
    LEFT JOIN table_bolsas t ON i.itemid=t.id 
    WHERE i.usuarioid=? AND venda='nao' 
    ORDER BY status ASC
");
mysqli_stmt_bind_param($stmt_l, "i", $db['id']);
mysqli_stmt_execute($stmt_l);
$sqli = mysqli_stmt_get_result($stmt_l);
?>

<div class="modern-card">
    <div class="modern-card-header">🎒 Mochilas Ninja</div>
    <div class="modern-card-body">
        
        <div style="display: flex; gap: 20px; align-items: center; background: rgba(0,0,0,0.2); padding: 20px; border-radius: 8px; border: 1px solid #333; margin-bottom: 25px;">
            <img src="_img/_detalhes/msg/12.png" style="width: 100px; border-radius: 8px; filter: drop-shadow(0 0 5px rgba(255,255,255,0.1));">
            <div>
                <h3 style="color: gold; margin: 0 0 10px 0; font-family: 'Impact', sans-serif; letter-spacing: 1px;">MINHA CARGA</h3>
                <p style="color: #ccc; font-size: 13px; line-height: 1.6; margin: 0;">
                    Gerencie suas mochilas aqui. Cada mochila expande sua capacidade de carregar itens.<br>
                    <span style="color: #6cf; font-size: 11px;">⚠️ Apenas uma mochila pode ser equipada por vez.</span>
                </p>
            </div>
        </div>

        <div class="inventory-nav" style="display: flex; gap: 10px; margin-bottom: 25px; overflow-x: auto;">
            <a href="?p=inventory" class="modern-btn" style="padding: 8px 15px; font-size: 11px; background: rgba(255,255,255,0.05);">⚡ Itens</a>
            <a href="?p=bolsas" class="modern-btn active" style="padding: 8px 15px; font-size: 11px;">🎒 Mochilas</a>
            <a href="?p=parchments" class="modern-btn" style="padding: 8px 15px; font-size: 11px; background: rgba(255,255,255,0.05);">📜 Pergaminhos</a>
        </div>

        <div style="background: rgba(255,215,0,0.03); padding: 10px; border-radius: 5px; margin-bottom: 15px; border: 1px solid #444; text-align: center;">
            <span style="color: #aaa; font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">
                Yens em mãos: <b style="color: gold;"><?php echo number_format($db['yens'],2,',','.'); ?></b>
            </span>
        </div>

        <?php if(isset($_GET['msg'])): 
            $msg_txt = '';
            switch($_GET['msg']){
                case 2: $msg_txt = 'Mochila equipada!'; break;
                case 3: $msg_txt = 'Mochila guardada.'; break;
                case 4: $v = $_GET['value'] ?? 0; $msg_txt = 'Mochila vendida por '.number_format($v,2,',','.').' yens.'; break;
            }
            if($msg_txt) echo '<div class="aviso" style="margin-bottom: 20px;">'.$msg_txt.'</div>';
        endif; ?>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 20px;">
            <?php 
            if(mysqli_num_rows($sqli) == 0):
                echo '<div class="aviso" style="grid-column: 1/-1;">Você não possui mochilas extras.</div>';
            else:
                while($dbi = mysqli_fetch_assoc($sqli)): 
                    $is_on = ($dbi['status'] == 'on');
            ?>
                <div style="background: <?php echo $is_on ? 'rgba(0,150,255,0.1)' : 'rgba(0,0,0,0.3)'; ?>; border: 1px solid <?php echo $is_on ? '#09f' : '#444'; ?>; border-radius: 10px; padding: 15px; display: flex; gap: 15px; position: relative;">
                    <img src="_img/equipamentos/<?php echo $dbi['imagem']; ?>.png" style="width: 70px; height: 70px; border-radius: 6px; background: rgba(0,0,0,0.4); padding: 5px; border: 1px solid #222;">
                    <div style="flex: 1;">
                        <h4 style="color: #fff; margin: 0 0 5px 0; font-size: 15px;">
                            <?php echo $dbi['nome']; ?>
                            <?php if($is_on) echo ' <span style="background: #0f0; color: #000; font-size: 9px; padding: 1px 5px; border-radius: 3px; vertical-align: middle;">EQUIPADA</span>'; ?>
                        </h4>
                        <p style="font-size: 11px; color: #888; margin-bottom: 10px;"><?php echo $dbi['descricao']; ?></p>
                        
                        <div style="display: flex; justify-content: space-between; align-items: flex-end;">
                            <div style="font-size: 10px; color: #666;">
                                Venda: <b style="color: gold;"><?php echo number_format($dbi['valor'],0,',','.'); ?> yens</b>
                            </div>
                            <div style="display: flex; gap: 8px;">
                                <a href="?p=bolsas&sell=<?php echo $dbi['id']; ?>" class="modern-btn" style="background: #500; padding: 4px 10px; font-size: 10px;">Vender</a>
                                <a href="?p=bolsas&action=<?php echo $is_on ? 'off' : 'on'; ?>&id=<?php echo $dbi['id']; ?>" class="modern-btn" style="padding: 4px 12px; font-size: 10px; background: <?php echo $is_on ? '#444' : '#06f'; ?>;">
                                    <?php echo $is_on ? 'Retirar' : 'Equipar'; ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php 
                endwhile; 
            endif;
            ?>
        </div>

        <div style="margin-top: 30px; text-align: center;">
            <a href="?p=home" class="modern-btn" style="background: #333; border-color: #444;">Voltar para Vila</a>
        </div>
    </div>
</div>