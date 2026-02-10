<?php
require_once('Encrypt.php');
$c = new C_Encrypt();

// Ações de Equipar/Desequipar
if(isset($_GET['action'])){
	if(!isset($_GET['id'])){ echo "<script>self.location='?p=home'</script>"; exit(); }
	$id = (int)$_GET['id'];
    
    $stmt_sel = mysqli_prepare($mysqli_link, "SELECT usuarioid, categoria FROM inventario WHERE id=?");
    mysqli_stmt_bind_param($stmt_sel, "i", $id);
    mysqli_stmt_execute($stmt_sel);
    $res_sel = mysqli_stmt_get_result($stmt_sel);
    $dbi = mysqli_fetch_assoc($res_sel);

	if(!$dbi || $dbi['usuarioid'] != $db['id']){ echo "<script>self.location='?p=home'</script>"; exit(); }
	
    $categoria = $dbi['categoria'];
	$act = $_GET['action'];

	mysqli_query($mysqli_link, "UPDATE inventario SET status='off' WHERE usuarioid='".$db['id']."' AND categoria='".$categoria."'");
	
    if($act == 'on'){
        $stmt_on = mysqli_prepare($mysqli_link, "UPDATE inventario SET status='on' WHERE id=?");
        mysqli_stmt_bind_param($stmt_on, "i", $id);
        mysqli_stmt_execute($stmt_on);
        echo "<script>self.location='?p=inventory&msg=2'</script>";
    } else {
        echo "<script>self.location='?p=inventory&msg=3'</script>";
    }
    exit();
}

// Uso de Ramen (Consumível)
if(isset($_POST['ram_id'])){
	$id = $c->decode($_POST['ram_id'], $chaveuniversal);
	$tipo = $c->decode($_POST['ram_tipo'], $chaveuniversal);
	
    $stmt_r = mysqli_prepare($mysqli_link, "SELECT id FROM ramen WHERE usuarioid=? AND id=?");
    mysqli_stmt_bind_param($stmt_r, "ii", $db['id'], $id);
    mysqli_stmt_execute($stmt_r);
    $res_r = mysqli_stmt_get_result($stmt_r);

	if(mysqli_num_rows($res_r) > 0){
		switch($tipo){
			case 1: $hp = 50; break;
			case 2: $hp = 100; break;
			case 3: $hp = 250; break;
			case 4: $hp = 500; break;
			case 5: $hp = 1000; break;
            default: $hp = 0; break;
		}
        
		if($hp > 0){
            $energia_nova = ($db['energia'] + $hp >= $db['energiamax']) ? $db['energiamax'] : $db['energia'] + $hp;
            
            mysqli_begin_transaction($mysqli_link);
            try {
                $stmt_del = mysqli_prepare($mysqli_link, "DELETE FROM ramen WHERE id=?");
                mysqli_stmt_bind_param($stmt_del, "i", $id);
                mysqli_stmt_execute($stmt_del);

                $stmt_up = mysqli_prepare($mysqli_link, "UPDATE usuarios SET energia=? WHERE id=?");
                mysqli_stmt_bind_param($stmt_up, "ii", $energia_nova, $db['id']);
                mysqli_stmt_execute($stmt_up);

                mysqli_commit($mysqli_link);
                echo "<script>self.location='?p=inventory&msg=1&e=$hp'</script>"; 
            } catch (Exception $e) {
                mysqli_rollback($mysqli_link);
                die("Erro ao processar ramen: " . $e->getMessage());
            }
            exit();
        }
	}
}

// Queries de Listagem
$stmt_l_r = mysqli_prepare($mysqli_link, "SELECT * FROM ramen WHERE usuarioid=?");
mysqli_stmt_bind_param($stmt_l_r, "i", $db['id']);
mysqli_stmt_execute($stmt_l_r);
$sqlr = mysqli_stmt_get_result($stmt_l_r);

$stmt_l_i = mysqli_prepare($mysqli_link, "
    SELECT i.id, i.status, i.upgrade, t.categoria, t.descricao, t.taijutsu, t.ninjutsu, t.genjutsu, t.nome, t.imagem, t.valor 
    FROM inventario i 
    LEFT JOIN table_itens t ON i.itemid=t.id 
    WHERE i.usuarioid=? AND venda='nao' 
    ORDER BY status ASC
");
mysqli_stmt_bind_param($stmt_l_i, "i", $db['id']);
mysqli_stmt_execute($stmt_l_i);
$sqli = mysqli_stmt_get_result($stmt_l_i);
?>

<div class="modern-card">
    <div class="modern-card-header">📦 Seu Inventário</div>
    <div class="modern-card-body">
        
        <div style="background: rgba(0,0,0,0.3); border-radius: 12px; border: 1px solid rgba(255,255,255,0.05); padding: 20px; margin-bottom: 25px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="font-family: var(--font-header); font-size: 18px; color: #fff; margin: 0; display: flex; align-items: center; gap: 10px;">
                    <img src="_img/yens.png" width="20" height="20"> Mochila Ninja
                </h3>
                <div style="background: rgba(255,215,0,0.15); color: #ffd700; padding: 5px 15px; border-radius: 20px; font-size: 12px; font-weight: bold; border: 1px solid rgba(255,215,0,0.2);">
                    <?php echo number_format($db['yens'], 2, ',', '.'); ?> Yens
                </div>
            </div>
            
            <div class="inventory-nav" style="display: flex; gap: 10px; margin-bottom: 20px; overflow-x: auto; padding-bottom: 5px;">
                <a href="?p=inventory" class="modern-btn active" style="padding: 8px 15px; font-size: 11px; white-space: nowrap;">⚡ Itens</a>
                <a href="?p=parchments" class="modern-btn" style="padding: 8px 15px; font-size: 11px; white-space: nowrap; background: rgba(255,255,255,0.05);">📜 Pergaminhos</a>
                <a href="?p=animais" class="modern-btn" style="padding: 8px 15px; font-size: 11px; white-space: nowrap; background: rgba(255,255,255,0.05);">🦁 Animais</a>
                <a href="?p=selos" class="modern-btn" style="padding: 8px 15px; font-size: 11px; white-space: nowrap; background: rgba(255,255,255,0.05);">💠 Selos</a>
                <a href="?p=portao" class="modern-btn" style="padding: 8px 15px; font-size: 11px; white-space: nowrap; background: rgba(255,255,255,0.05);">⛩️ Portões</a>
                <a href="?p=bolsas" class="modern-btn" style="padding: 8px 15px; font-size: 11px; white-space: nowrap; background: rgba(255,255,255,0.05);">🎒 Bolsas</a>
            </div>

            <?php if(isset($_GET['msg'])): 
                $msg_txt = '';
                switch($_GET['msg']){
                    case 1: $msg_txt = 'Ramen utilizado! Sua energia regenerou <b>'.$_GET['e'].' pontos.</b>'; break;
                    case 2: $msg_txt = 'Equipado com sucesso!'; break;
                    case 3: $msg_txt = 'Item guardado na mochila.'; break;
                    case 4: $v = $_GET['value'] ?? 0; $msg_txt = 'Venda realizada por '.number_format($v,2,',','.').' yens.'; break;
                    case 5: $msg_txt = 'Falha na verificação de identidade (Senha).'; break;
                }
                if($msg_txt) echo '<div class="aviso" style="margin-bottom: 20px;">'.$msg_txt.'</div>';
            endif; ?>

            <!-- RAMEN SECTION -->
            <?php if(mysqli_num_rows($sqlr) > 0): ?>
            <h4 style="font-family: 'Impact', sans-serif; font-size: 14px; color: gold; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px; border-left: 3px solid gold; padding-left: 10px;">Consumíveis (Ichiraku)</h4>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 15px; margin-bottom: 30px;">
                <?php while($row_r = mysqli_fetch_assoc($sqlr)): 
                    switch($row_r['ramenid']){
                        case 1: $nome='Gohan'; $reg='50'; break;
                        case 2: $nome='Sushi'; $reg='100'; break;
                        case 3: $nome='Peixe Empanado'; $reg='250'; break;
                        case 4: $nome='Sashimi'; $reg='500'; break;
                        case 5: $nome='Ramen Ichiraku'; $reg='1.000'; break;
                        default: $nome='Comida'; $reg='0'; break;
                    }
                ?>
                <div style="background: rgba(255,255,255,0.03); padding: 12px; display: flex; align-items: center; gap: 12px; border-radius: 8px; border: 1px solid #333;">
                    <img src="_img/ramen/ramen<?php echo $row_r['ramenid']; ?>.png" style="width: 50px; height: 50px; border-radius: 5px; background: rgba(0,0,0,0.2); padding: 5px;">
                    <div style="flex: 1;">
                        <div style="font-size: 13px; font-weight: bold; margin-bottom: 3px; color: #fff;"><?php echo $nome; ?></div>
                        <div style="font-size: 10px; color: #5f5;">⚡ +<?php echo $reg; ?> Energia</div>
                    </div>
                    <form method="post" action="?p=inventory">
                        <input type="hidden" name="ram_id" value="<?php echo $c->encode($row_r['id'],$chaveuniversal); ?>" />
                        <input type="hidden" name="ram_tipo" value="<?php echo $c->encode($row_r['ramenid'],$chaveuniversal); ?>" />
                        <input type="submit" class="modern-btn" style="padding: 4px 10px; font-size: 10px; background: #222;" value="Usar">
                    </form>
                </div>
                <?php endwhile; ?>
            </div>
            <?php endif; ?>

            <!-- EQUIPMENT SECTION -->
            <?php if(mysqli_num_rows($sqli) > 0): ?>
            <h4 style="font-family: 'Impact', sans-serif; font-size: 14px; color: #0cf; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px; border-left: 3px solid #0cf; padding-left: 10px;">Arsenal e Vestimentas</h4>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 15px;">
                <?php while($row_i = mysqli_fetch_assoc($sqli)): 
                    $is_on = ($row_i['status'] == 'on');
                ?>
                <div style="background: <?php echo $is_on ? 'rgba(0,100,255,0.08)' : 'rgba(255,255,255,0.02)'; ?>; border: 1px solid <?php echo $is_on ? '#06f' : '#333'; ?>; border-radius: 10px; overflow: hidden; transition: 0.2s;">
                    <div style="padding: 15px;">
                        <div style="display: flex; gap: 15px; margin-bottom: 12px;">
                            <div style="position: relative;">
                                <img src="_img/equipamentos/<?php echo $row_i['imagem']; ?>.png" style="width: 60px; height: 60px; background: rgba(0,0,0,0.3); border-radius: 8px; padding: 5px; border: 1px solid #444;">
                                <?php if($is_on): ?>
                                    <div style="position: absolute; top: -5px; right: -5px; background: #0f0; width: 12px; height: 12px; border-radius: 50%; border: 2px solid #000; box-shadow: 0 0 5px #0f0;"></div>
                                <?php endif; ?>
                            </div>
                            <div style="flex: 1;">
                                <div style="font-size: 15px; font-weight: bold; color: #fff; margin-bottom: 5px;">
                                    <?php echo $row_i['nome']; ?><?php if($row_i['upgrade']>0) echo ' <span style="color: gold;">+'. $row_i['upgrade'] .'</span>'; ?>
                                </div>
                                <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                                    <?php if($row_i['taijutsu']>0) echo '<span style="font-size: 10px; background: rgba(255,0,0,0.1); color: #f55; padding: 2px 6px; border-radius: 4px; border: 1px solid rgba(255,0,0,0.2);">Tai +'.($row_i['taijutsu']+$row_i['upgrade']).'</span>'; ?>
                                    <?php if($row_i['ninjutsu']>0) echo '<span style="font-size: 10px; background: rgba(0,255,0,0.1); color: #5f5; padding: 2px 6px; border-radius: 4px; border: 1px solid rgba(0,255,0,0.2);">Nin +'.($row_i['ninjutsu']+$row_i['upgrade']).'</span>'; ?>
                                    <?php if($row_i['genjutsu']>0) echo '<span style="font-size: 10px; background: rgba(0,150,255,0.1); color: #5cf; padding: 2px 6px; border-radius: 4px; border: 1px solid rgba(0,150,255,0.2);">Gen +'.($row_i['genjutsu']+$row_i['upgrade']).'</span>'; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 12px;">
                            <div style="color: #888; font-size: 11px;">Venda: <b style="color: gold;"><?php echo number_format($row_i['valor']/2,0,',','.'); ?></b> yens</div>
                            <a href="?p=inventory&action=<?php echo $is_on ? 'off' : 'on'; ?>&id=<?php echo $row_i['id']; ?>" class="modern-btn" style="padding: 5px 15px; font-size: 11px; background: <?php echo $is_on ? '#444' : '#06f'; ?>;">
                                <?php echo $is_on ? 'Retirar' : 'Equipar'; ?>
                            </a>
                        </div>
                    </div>
                    <div style="background: rgba(0,0,0,0.3); padding: 8px 15px; display: flex; justify-content: center; gap: 20px; font-size: 11px; border-top: 1px solid rgba(255,255,255,0.03);">
                        <a href="?p=sellitem&id=<?php echo $row_i['id']; ?>" style="color: #f44; text-decoration: none; font-weight: bold;">Vender</a>
                        <a href="?p=blacksmith&id=<?php echo $row_i['id']; ?>" style="color: gold; text-decoration: none; font-weight: bold;">Ferreiro</a>
                        <a href="?p=senditem&id=<?php echo $row_i['id']; ?>" style="color: #aaa; text-decoration: none;">Enviar</a>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <?php endif; ?>

            <?php if(mysqli_num_rows($sqlr) == 0 && mysqli_num_rows($sqli) == 0): ?>
                <div class="aviso" style="margin-top: 20px;">Você não possui itens no momento. Visite o Comércio!</div>
            <?php endif; ?>
        </div>
    </div>
</div>
