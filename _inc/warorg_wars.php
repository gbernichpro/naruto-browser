<?php
include_once("funcoes.php");

if(!isset($_GET['view']) || empty($_GET['view'])){
    // LISTAGEM DE GUERRAS
?>
    <div style="padding: 10px;">
        <h3 style="color: #fff; margin-bottom: 15px; border-bottom: 2px solid #444; padding-bottom: 10px;">
            ⚔️ Guerras em Andamento
        </h3>
        
        <div style="display: grid; gap: 15px;">
            <?php
            $stmt_l = mysqli_prepare($mysqli_link, "SELECT id, war_id FROM org_wars WHERE org=? ORDER BY id DESC");
            mysqli_stmt_bind_param($stmt_l, "i", $dbo['id']);
            mysqli_stmt_execute($stmt_l);
            $res_l = mysqli_stmt_get_result($stmt_l);

            if(mysqli_num_rows($res_l) == 0):
                echo '<div class="aviso">Seu clã não está em nenhuma guerra no momento.</div>';
            else:
                while($row = mysqli_fetch_assoc($res_l)):
                    $stmt_e = mysqli_prepare($mysqli_link, "SELECT * FROM org_wars_declare WHERE to_org=? AND war_id=?");
                    mysqli_stmt_bind_param($stmt_e, "is", $dbo['id'], $row['war_id']);
                    mysqli_stmt_execute($stmt_e);
                    $res_e = mysqli_stmt_get_result($stmt_e);
                    $exe = mysqli_fetch_assoc($res_e);

                    if(!$exe) continue;

                    $stmt_io = mysqli_prepare($mysqli_link, "SELECT sigla, nome, logo FROM organizacoes WHERE id=?");
                    mysqli_stmt_bind_param($stmt_io, "i", $exe['from_org']);
                    mysqli_stmt_execute($stmt_io);
                    $res_io = mysqli_stmt_get_result($stmt_io);
                    $i_org = mysqli_fetch_assoc($res_io);
            ?>
                <div style="background: rgba(255,255,255,0.05); border: 1px solid #333; border-radius: 10px; padding: 15px; display: flex; align-items: center; gap: 20px; transition: transform 0.2s;" onmouseover="this.style.transform='translateX(5px)'" onmouseout="this.style.transform='translateX(0)'">
                    <img src="<?php echo ($i_org['logo'] == '') ? '_img/org/no_logo.png' : $i_org['logo']; ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 50%; border: 2px solid #555;">
                    <div style="flex: 1;">
                        <h4 style="margin: 0; color: #fff; font-size: 16px;">
                            <span style="color: #f00;">VS</span> [<?php echo $i_org['sigla']; ?>] <?php echo $i_org['nome']; ?>
                        </h4>
                        <p style="margin: 5px 0 0; font-size: 12px; color: #aaa;">
                            <b>Título:</b> <?php echo htmlspecialchars($exe['title']); ?> | 
                            <b>Início:</b> <?php echo format_date($exe['time']); ?>
                        </p>
                    </div>
                    <a href="?p=warorg&m=wars&view=<?php echo $exe['id']; ?>" class="modern-btn" style="background: #444; color: #fff; padding: 5px 15px; font-size: 12px;">Detalhes</a>
                </div>
            <?php endwhile; endif; ?>
        </div>
    </div>
<?php
} else {
    // VISUALIZAÇÃO DE UMA GUERRA ESPECÍFICA
    $view_id = (int)$_GET['view'];
    $stmt_e = mysqli_prepare($mysqli_link, "SELECT * FROM org_wars_declare WHERE id=?");
    mysqli_stmt_bind_param($stmt_e, "i", $view_id);
    mysqli_stmt_execute($stmt_e);
    $res_e = mysqli_stmt_get_result($stmt_e);
    $exe = mysqli_fetch_assoc($res_e);

    if(!$exe){
        echo '<div class="aviso">Confronto não encontrado.</div>';
        return;
    }

    $stmt_io = mysqli_prepare($mysqli_link, "SELECT * FROM organizacoes WHERE id=?");
    mysqli_stmt_bind_param($stmt_io, "i", $exe['from_org']);
    mysqli_stmt_execute($stmt_io);
    $res_io = mysqli_stmt_get_result($stmt_io);
    $i_org = mysqli_fetch_assoc($res_io);

	if($dbo['liderid'] == $db['id']){
        $error = '';
		if(isset($_GET['act']) && $_GET['act'] == "o_paz"){
            $stmt_p1 = mysqli_prepare($mysqli_link, "SELECT id FROM org_wars_paz WHERE war_id=?");
            mysqli_stmt_bind_param($stmt_p1, "s", $exe['war_id']);
            mysqli_stmt_execute($stmt_p1);
            if(mysqli_num_rows(mysqli_stmt_get_result($stmt_p1)) > 0){
				$error = "Já existe um pedido de paz em aberto!";
            } else {
				ofers_paz($dbo['id'], $dbo['sigla'], $exe['from_org'], $i_org['sigla'], $exe['war_id']);
				echo "<script>self.location='?p=warorg&m=wars&view=".$exe['id']."'</script>";
                exit();
			}
		}
		if(isset($_GET['act']) && $_GET['act'] == "a_paz"){	
            $stmt_p2 = mysqli_prepare($mysqli_link, "SELECT id FROM org_wars_paz WHERE war_id=?");
            mysqli_stmt_bind_param($stmt_p2, "s", $exe['war_id']);
            mysqli_stmt_execute($stmt_p2);
            if(mysqli_num_rows(mysqli_stmt_get_result($stmt_p2)) <= 0){
				$error = "O pedido de paz não foi encontrado.";
            } else {
				acept_paz($dbo['id'], $dbo['sigla'], $exe['from_org'], $i_org['sigla'], $exe['war_id']);
				echo "<script>self.location='?p=warorg&m=wars&view=".$exe['id']."'</script>";
                exit();
			}
		}
		if(isset($_GET['act']) && $_GET['act'] == "r_paz"){	
            $stmt_p3 = mysqli_prepare($mysqli_link, "SELECT id FROM org_wars_paz WHERE war_id=?");
            mysqli_stmt_bind_param($stmt_p3, "s", $exe['war_id']);
            mysqli_stmt_execute($stmt_p3);
            if(mysqli_num_rows(mysqli_stmt_get_result($stmt_p3)) <= 0){
				$error = "O pedido de paz não foi encontrado.";
            } else {
				reject_paz($dbo['id'], $dbo['sigla'], $exe['from_org'], $i_org['sigla'], $exe['war_id']);
				echo "<script>self.location='?p=warorg&m=wars&view=".$exe['id']."'</script>";
                exit();
			}
		}

        $stmt_oe = mysqli_prepare($mysqli_link, "SELECT id FROM org_wars_paz WHERE war_id=?");
        mysqli_stmt_bind_param($stmt_oe, "s", $exe['war_id']);
        mysqli_stmt_execute($stmt_oe);
		$o_paz = mysqli_num_rows(mysqli_stmt_get_result($stmt_oe)) > 0;

        $stmt_oq = mysqli_prepare($mysqli_link, "SELECT id FROM org_wars_paz WHERE to_id=? AND from_id=?");
        mysqli_stmt_bind_param($stmt_oq, "ii", $dbo['id'], $exe['from_org']);
        mysqli_stmt_execute($stmt_oq);
		$paz = mysqli_num_rows(mysqli_stmt_get_result($stmt_oq)) > 0;
	}
?>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
        google.load("visualization", "1", {packages:["corechart"]});
        google.setOnLoadCallback(drawCharts);

        function drawCharts() {
            var chartsData = [
                {id: 'ALL', title: 'Total de Batalhas', v1: <?php echo $exe['total_1']; ?>, v2: <?php echo $exe['total_2']; ?>},
                {id: 'VIT', title: 'Vitórias', v1: <?php echo $exe['vit_1']; ?>, v2: <?php echo $exe['vit_2']; ?>},
                {id: 'DER', title: 'Derrotas', v1: <?php echo $exe['der_1']; ?>, v2: <?php echo $exe['der_2']; ?>},
                {id: 'EXP', title: 'Experiência Acumulada', v1: <?php echo $exe['exp_1']; ?>, v2: <?php echo $exe['exp_2']; ?>}
            ];

            chartsData.forEach(function(config) {
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Tipo');
                data.addColumn('number', '<?php echo $dbo['sigla'];?>');
                data.addColumn('number', '<?php echo $i_org['sigla'];?>');
                data.addRows([['Total', config.v1, config.v2]]);

                var chart = new google.visualization.ColumnChart(document.getElementById(config.id));
                chart.draw(data, {
                    width: '100%',
                    height: 240,
                    backgroundColor: 'transparent',
                    legend: {textStyle: {color: '#fff'}},
                    title: config.title,
                    titleTextStyle: {color: '#fff', fontSize: 16},
                    hAxis: {textStyle: {color: '#aaa'}},
                    vAxis: {textStyle: {color: '#aaa'}, gridlines: {color: '#333'}},
                    colors: ['#282', '#a22']
                });
            });
        }

        function showChart(id) {
            $(".allStats").hide();
            $("#" + id).fadeIn();
            drawCharts(); // Re-redesenha para ajustar tamanho se necessário
        }
    </script>

    <div style="padding: 10px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 2px solid #444; padding-bottom: 10px;">
            <h3 style="margin: 0; color: #fff;">
                🛡️ <?php echo htmlspecialchars($exe['title']); ?>
            </h3>
            <div style="font-size: 14px; color: #aaa;">
                <b style="color: #282;"><?php echo $dbo['sigla']; ?></b> <span style="color: #666;">X</span> <b style="color: #a22;"><?php echo $i_org['sigla']; ?></b>
            </div>
        </div>

        <?php if(!empty($error)): ?>
            <div class="aviso" style="margin-bottom: 15px;"><?php echo $error; ?></div>
        <?php endif; ?>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 15px; margin-bottom: 20px;">
            <div style="background: rgba(0,0,0,0.2); padding: 15px; border-radius: 8px; border: 1px solid #333;">
                <h4 style="margin: 0 0 10px; color: gold;">📋 Detalhes</h4>
                <div style="font-size: 13px; color: #ccc; line-height: 1.6;">
                    <b>Inimigo:</b> <a href="?p=vieworg&id=<?php echo $exe['from_org']; ?>" style="color: #f00;"><?php echo $i_org['nome'];?> (<?php echo $i_org['sigla'];?>)</a><br>
                    <b>Início:</b> <?php echo format_date($exe['time']); ?><br>
                    <b>Duração:</b> <?php echo format_time_now(time()-$exe['time']); ?>
                </div>
            </div>

            <div style="background: rgba(0,0,0,0.2); padding: 15px; border-radius: 8px; border: 1px solid #333;">
                <h4 style="margin: 0 0 10px; color: #5af;">📊 Quick Stats</h4>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 5px; font-size: 12px;">
                    <button onclick="showChart('ALL')" class="modern-btn" style="padding: 5px;">🔥 Batalhas</button>
                    <button onclick="showChart('VIT')" class="modern-btn" style="padding: 5px;">🏆 Vitórias</button>
                    <button onclick="showChart('DER')" class="modern-btn" style="padding: 5px;">💀 Derrotas</button>
                    <button onclick="showChart('EXP')" class="modern-btn" style="padding: 5px;">⭐ Experiência</button>
                </div>
            </div>
        </div>

        <div id="charts-container" style="background: rgba(0,0,0,0.3); border-radius: 10px; border: 1px solid #444; margin-bottom: 20px;">
            <div id="ALL" class="allStats"></div>
            <div id="VIT" class="allStats" style="display:none"></div>
            <div id="DER" class="allStats" style="display:none"></div>
            <div id="EXP" class="allStats" style="display:none"></div>
        </div>

        <div style="overflow-x: auto;">
            <table width="100%" class="modern-table" style="text-align: center;">
                <thead>
                    <tr>
                        <th align="left">Status</th>
                        <th>Experiência</th>
                        <th>Total</th>
                        <th>Vitórias</th>
                        <th>Derrotas</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="background: rgba(40, 130, 40, 0.1);">
                        <td align="left"><b style="color: #2e2;">Seu Clã</b></td>
                        <td><?php echo format_number($exe['exp_1']); ?></td>
                        <td><?php echo format_number($exe['total_1']); ?></td>
                        <td><?php echo format_number($exe['vit_1']); ?></td>
                        <td><?php echo format_number($exe['der_1']); ?></td>
                    </tr>
                    <tr style="background: rgba(130, 40, 40, 0.1);">
                        <td align="left"><b style="color: #e22;">Clã Inimigo</b></td>
                        <td><?php echo format_number($exe['exp_2']); ?></td>
                        <td><?php echo format_number($exe['total_2']); ?></td>
                        <td><?php echo format_number($exe['vit_2']); ?></td>
                        <td><?php echo format_number($exe['der_2']); ?></td>
                    </tr>
                    <tr style="border-top: 1px solid #444;">
                        <td align="left"><b>Diferença</b></td>
                        <td style="color: <?php echo ($exe['exp_1'] - $exe['exp_2']) >= 0 ? '#2e2' : '#e22'; ?>">
                            <?php echo format_number($exe['exp_1']-$exe['exp_2']); ?>
                        </td>
                        <td><?php echo format_number($exe['total_1']-$exe['total_2']); ?></td>
                        <td><?php echo format_number($exe['vit_1']-$exe['vit_2']); ?></td>
                        <td><?php echo format_number($exe['der_1']-$exe['der_2']); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <?php if($dbo['liderid'] == $db['id']): ?>
            <div style="margin-top: 20px; text-align: right; background: rgba(255,255,255,0.05); padding: 15px; border-radius: 8px;">
                <?php if($paz): ?>
                    <a href="?p=warorg&m=wars&view=<?php echo $exe['id']; ?>&act=a_paz" class="modern-btn" style="background: #282; margin-right: 10px;">🤝 Aceitar Oferta de Paz</a>
                    <a href="?p=warorg&m=wars&view=<?php echo $exe['id']; ?>&act=r_paz" class="modern-btn" style="background: #a22;">❌ Rejeitar Paz</a>
                <?php elseif($o_paz): ?>
                    <span style="color: #aaa; font-style: italic;">🕊️ Oferta de Paz já foi enviada...</span>
                <?php else: ?>
                    <a href="?p=warorg&m=wars&view=<?php echo $exe['id']; ?>&act=o_paz" class="modern-btn" style="background: #44a;">🕊️ Propor Acordo de Paz</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
<?php } ?>