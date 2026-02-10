<?php require_once('trava.php'); ?>
<?php
// Processamento de Busca de Ninja
if(isset($_POST['troca'])) {
    $search_nick = isset($_POST['nick']) ? trim($_POST['nick']) : '';
    
    if(preg_match("/([_ _-_,_._>_`_´_<_~_^\/_?_°_\_:_;_§_|_!_¹_²_³_£_¢_¬_§_º_@_#_%_¨_&_*_+_{_}_*_])/", $search_nick)) {
        echo "<script>self.location='?p=rank&msg=3'</script>";
        exit();
    }

    if($search_nick == ""){
        echo "<script>self.location='?p=rank&msg=2'</script>"; 
        exit();
    }

    $stmt_s = mysqli_prepare($mysqli_link, "SELECT usuario FROM usuarios WHERE usuario=?");
    mysqli_stmt_bind_param($stmt_s, "s", $search_nick);
    mysqli_stmt_execute($stmt_s);
    $res_s = mysqli_stmt_get_result($stmt_s);

    if(mysqli_num_rows($res_s) <= 0){
        echo "<script>self.location='?p=rank&msg=1'</script>"; 
        exit();
    } else {
        echo "<script>self.location='?p=view&view=".urlencode($search_nick)."'</script>";
        exit();
    }
}

// Configurações de Filtro e Paginação
$pg     = isset($_GET['pg']) ? (int)$_GET['pg'] : 0;
$filter = isset($_GET['filter']) ? (int)$_GET['filter'] : 0;

if(($filter > 11) || ($filter < 0)) { 
    echo "<script>self.location='?p=home'</script>"; 
    exit(); 
}

$filtro_sql = " WHERE status<>'banido'";
if($filter == 7) {
    $filtro_sql .= " AND renegado='sim'";
} elseif($filter > 0) {
    $filtro_sql .= " AND renegado='nao' AND vila=" . (int)$filter;
}

// Posição VIP
$posicao_vip = 0;
if(date('Y-m-d H:i:s') < $db['vip']) {
	if(($filter == 0) || ($filter == $db['vila'])) {
		$sqlc = mysqli_query($mysqli_link, "SELECT id FROM usuarios" . $filtro_sql . " ORDER BY nivel DESC, vitorias DESC, yens_fat DESC, derrotas ASC");
		$count_pos = 1;
		while($dbc = mysqli_fetch_assoc($sqlc)) {
			if($dbc['id'] == $db['id']) {
                $posicao_vip = $count_pos;
                break;
            }
			$count_pos++;
		}
	}
}

// Query de Listagem
$timeout = time() - 900;
$limit_start = $pg * 50;
$sql_list = mysqli_query($mysqli_link, "SELECT usuario, vila, renegado, nivel, orgid, vitorias, derrotas, yens_fat, timestamp, personagem FROM usuarios" . $filtro_sql . " ORDER BY nivel DESC, yens_fat DESC, vitorias DESC, derrotas ASC LIMIT $limit_start, 50");

$sql_count = mysqli_query($mysqli_link, "SELECT count(id) as conta FROM usuarios" . $filtro_sql);
$db_count = mysqli_fetch_assoc($sql_count);
$total_registros = $db_count['conta'];
?>

<div class="modern-card">
    <div class="modern-card-header">🏅 Ranking Ninja</div>
    <div class="modern-card-body">
        
        <!-- CABEÇALHO INFORMATIVO -->
        <div style="display: flex; gap: 20px; align-items: center; margin-bottom: 25px; background: rgba(255,255,255,0.03); padding: 15px; border-radius: 10px; border: 1px solid #333;">
            <img src="_img/_detalhes/msg/26.png" style="width: 100px; border-radius: 8px;">
            <div>
                <h3 style="color: gold; margin: 0 0 10px 0; font-family: 'Impact', sans-serif; letter-spacing: 1px;">HALL DA FAMA</h3>
                <p style="color: #ccc; font-size: 13px; line-height: 1.5; margin: 0;">
                    Abaixo está o ranking oficial do Mundo Ninja. Dispute o topo para provar sua força!<br>
                    <span style="color: #777; font-size: 11px;">Filtre por vila ou procure por um ninja específico abaixo.</span>
                </p>
            </div>
        </div>

        <!-- BUSCA E FILTROS -->
        <div style="background: rgba(0,0,0,0.2); padding: 15px; border-radius: 8px; margin-bottom: 20px; display: flex; flex-wrap: wrap; gap: 20px; align-items: flex-end; border: 1px solid #444;">
            
            <form method="POST" action="?p=rank" style="flex: 1; min-width: 250px;">
                <label class="destaque" style="display: block; margin-bottom: 5px; font-size: 12px;">🔍 Procurar Ninja:</label>
                <div style="display: flex; gap: 5px;">
                    <input type="text" name="nick" placeholder="Digite o Nick..." style="flex: 1; padding: 10px; background: #111; border: 1px solid #555; color: #fff; border-radius: 4px;">
                    <input type="submit" name="troca" class="modern-btn" value="Buscar" style="background: #444;">
                </div>
            </form>

            <form method="get" action="?" style="flex: 1; min-width: 250px; display: flex; gap: 10px; align-items: flex-end;">
                <input type="hidden" name="p" value="rank" />
                <div style="flex: 1;">
                    <label class="destaque" style="display: block; margin-bottom: 5px; font-size: 12px;">🏘️ Vila:</label>
                    <select name="filter" style="width: 100%; padding: 10px; background: #111; border: 1px solid #555; color: #fff; border-radius: 4px;">
                        <option value="0">Geral</option>
                        <option value="1"<?php if($filter == 1) echo ' selected'; ?>>Folha</option>
                        <option value="2"<?php if($filter == 2) echo ' selected'; ?>>Areia</option>
                        <option value="3"<?php if($filter == 3) echo ' selected'; ?>>Som</option>
                        <option value="4"<?php if($filter == 4) echo ' selected'; ?>>Chuva</option>
                        <option value="5"<?php if($filter == 5) echo ' selected'; ?>>Nuvem</option>
                        <option value="6"<?php if($filter == 6) echo ' selected'; ?>>Névoa</option>
                        <option value="8"<?php if($filter == 8) echo ' selected'; ?>>Pedra</option>
                        <option value="9"<?php if($filter == 9) echo ' selected'; ?>>Cachoeira</option>
                        <option value="10"<?php if($filter == 10) echo ' selected'; ?>>Neve</option>
                        <option value="11"<?php if($filter == 11) echo ' selected'; ?>>Grama</option>
                        <option value="7"<?php if($filter == 7) echo ' selected'; ?>>Akatsuki / Renegados</option>
                    </select>
                </div>
                <div style="width: 150px;">
                    <label class="destaque" style="display: block; margin-bottom: 5px; font-size: 12px;">📄 Página:</label>
                    <select name="pg" style="width: 100%; padding: 10px; background: #111; border: 1px solid #555; color: #fff; border-radius: 4px;">
                        <?php 
                        for($p=0; $p < ceil($total_registros/50); $p++){
                            $start_range = ($p * 50) + 1;
                            $end_range = ($p + 1) * 50;
                            echo "<option value='$p'".($pg == $p ? ' selected' : '').">#$start_range - $end_range</option>";
                        }
                        ?>
                    </select>
                </div>
                <input type="submit" class="modern-btn" value="Filtrar" style="height: 38px;">
            </form>
        </div>

        <?php if($posicao_vip > 0): ?>
            <div class="aviso" style="margin-bottom: 20px; background: rgba(0, 150, 255, 0.1); border-color: #0099FF; display: flex; justify-content: space-between; align-items: center;">
                <span>✨ Sua posição atual no Ranking: <b><?php echo $posicao_vip; ?>º lugar</b></span>
                <a href="?p=rank&filter=<?php echo $filter; ?>&pg=<?php echo floor(($posicao_vip-1)/50); ?>" class="modern-btn" style="padding: 5px 15px; font-size: 11px; background: #0099FF; color: #fff;">Ver minha posição</a>
            </div>
        <?php endif; ?>

        <?php 
        if(isset($_GET['msg'])){
            $msg_txt = '';
            switch($_GET['msg']){
                case 1: $msg_txt = 'Usuário não encontrado!'; break;
                case 2: $msg_txt = 'Digite um nome para pesquisar!'; break;
                case 3: $msg_txt = 'Pesquisa inválida (caracteres proibidos)!'; break;
            }
            if($msg_txt) echo '<div class="aviso" style="margin-bottom: 15px;">⚠️ '.$msg_txt.'</div>';
        }
        ?>

        <!-- TABELA DE RANKING -->
        <div style="overflow-x: auto;">
            <table class="modern-table" style="width: 100%;">
                <thead>
                    <tr>
                        <th style="width: 110px;">Visual</th>
                        <th style="width: 60px; text-align: center;">Pos.</th>
                        <th>Ninja</th>
                        <th style="text-align: center;">Vila</th>
                        <th style="text-align: center;">Nv.</th>
                        <th style="text-align: center;">Vit.</th>
                        <th style="text-align: center;">Der.</th>
                        <th style="text-align: right;">Yens Fat.</th>
                        <th style="width: 30px;"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $curr_pos = ($pg * 50) + 1; 
                    if(mysqli_num_rows($sql_list) == 0): 
                    ?>
                        <tr><td colspan="9" style="text-align: center; padding: 30px; color: #777;">Nenhum ninja encontrado nessas condições.</td></tr>
                    <?php 
                    else: 
                        while($dbr = mysqli_fetch_assoc($sql_list)): 
                            $is_me = ($dbr['usuario'] == $db['usuario']);
                            $is_online = ($dbr['timestamp'] >= $timeout);
                    ?>
                        <tr style="<?php echo $is_me ? 'background: rgba(0, 153, 255, 0.15); border-left: 3px solid #0099ff;' : ''; ?>">
                            <td style="padding: 5px;">
                                <img src="_img/rank/<?php echo $dbr['personagem']; ?>.jpg" style="width: 100px; height: 33px; border-radius: 4px; object-fit: cover; border: 1px solid #444;">
                            </td>
                            <td style="text-align: center; font-weight: bold; color: <?php echo $curr_pos <= 3 ? 'gold' : '#ccc'; ?>;">
                                <?php echo $curr_pos; ?>º
                            </td>
                            <td>
                                <a href="?p=view&view=<?php echo urlencode($dbr['usuario']); ?>" style="font-weight: bold; color: #fff; text-decoration: none;">
                                    <?php echo $dbr['usuario']; ?>
                                </a>
                            </td>
                            <td style="text-align: center;">
                                <?php 
                                    $v_img = $dbr['vila'];
                                    if($dbr['renegado'] == 'sim') $v_img = 7;
                                    elseif($dbr['vila'] == 10) $v_img = 1; // Padronizar Neve/Folha se necessário
                                ?>
                                <img src="_img/rank/<?php echo $v_img; ?>.png" style="width: 20px; filter: drop-shadow(0 0 2px #000);">
                            </td>
                            <td style="text-align: center;"><span class="nivel-badge"><?php echo $dbr['nivel']; ?></span></td>
                            <td style="text-align: center; color: #0f0; font-size: 12px;"><?php echo $dbr['vitorias']; ?></td>
                            <td style="text-align: center; color: #f44; font-size: 12px;"><?php echo $dbr['derrotas']; ?></td>
                            <td style="text-align: right; font-family: 'Courier New', monospace; color: gold; font-weight: bold; font-size: 13px;">
                                <?php echo number_format($dbr['yens_fat'], 2, ',', '.'); ?>
                            </td>
                            <td style="text-align: center;">
                                <div style="width: 10px; height: 10px; border-radius: 50%; background: <?php echo $is_online ? '#0f0' : '#f00'; ?>; box-shadow: 0 0 5px <?php echo $is_online ? '#0f0' : '#f00'; ?>;" title="<?php echo $is_online ? 'Online' : 'Offline'; ?>"></div>
                            </td>
                        </tr>
                    <?php 
                            $curr_pos++;
                        endwhile; 
                    endif; 
                    ?>
                </tbody>
            </table>
        </div>

        <!-- LEGENDA E PAGINAÇÃO RODAPÉ -->
        <div style="margin-top: 25px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; border-top: 1px solid #333; padding-top: 15px;">
            <div style="display: flex; gap: 15px; font-size: 11px; color: #888;">
                <div style="display: flex; align-items: center; gap: 5px;">
                    <div style="width: 8px; height: 8px; border-radius: 50%; background: #0f0;"></div> Online
                </div>
                <div style="display: flex; align-items: center; gap: 5px;">
                    <div style="width: 8px; height: 8px; border-radius: 50%; background: #f00;"></div> Offline
                </div>
                <div style="display: flex; align-items: center; gap: 5px;">
                    <div style="width: 15px; height: 8px; background: rgba(0, 153, 255, 0.3); border: 1px solid #0099ff;"></div> Você
                </div>
            </div>

            <div style="display: flex; gap: 5px;">
                <?php if($pg > 0): ?>
                    <a href="?p=rank&filter=<?php echo $filter; ?>&pg=<?php echo $pg - 1; ?>" class="modern-btn" style="padding: 5px 15px;">&laquo; Anterior</a>
                <?php endif; ?>
                
                <?php if($curr_pos <= $total_registros): ?>
                    <a href="?p=rank&filter=<?php echo $filter; ?>&pg=<?php echo $pg + 1; ?>" class="modern-btn" style="padding: 5px 15px;">Próxima &raquo;</a>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>
<?php
// Limpeza Opcional
if(isset($sql_list) && is_object($sql_list)) mysqli_free_result($sql_list);
?>