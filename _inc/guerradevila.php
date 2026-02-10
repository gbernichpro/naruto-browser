<?php require_once('trava.php'); ?>
<?php
// Lógica de Status da Guerra
$query_war = mysqli_query($mysqli_link, "SELECT * FROM nal_war ORDER BY id DESC LIMIT 1");
$war_data = mysqli_fetch_assoc($query_war);

$ssj = $war_data ? $war_data['status'] : 'fechado';
$idinwar = $war_data ? $war_data['id'] : 0;
$vencedorinwar = $war_data ? $war_data['vencedor'] : 0;

// Vilas Array
$vilas_nomes = [
    1 => 'Vila da Folha', 2 => 'Vila da Areia', 3 => 'Vila do Som', 
    4 => 'Vila da Chuva', 5 => 'Vila da Nuvem', 6 => 'Vila da Névoa', 
    8 => 'Vila da Pedra', 9 => 'Vila da Cachoeira', 10 => 'Vila da Neve', 
    11 => 'Vila da Grama', 7 => 'Akatsuki'
];
?>

<div class="modern-card">
    <div class="modern-card-header">Guerra de Vilas</div>
    <div class="modern-card-body">
        
        <div style="display: flex; gap: 20px; align-items: center; background: rgba(0,0,0,0.2); padding: 20px; border-radius: 8px; border: 1px solid var(--border-subtle); margin-bottom: 20px;">
            <img width="80" src="_img/guerra.jpg" style="border-radius: 8px; filter: drop-shadow(0 0 10px rgba(255,50,50,0.4));">
            <div>
                <h3 style="color: #faa; margin: 0 0 10px 0; font-family: var(--font-header);">Prepare-se para o Combate!</h3>
                <p style="color: var(--text-dim); margin: 0; font-size: 13px; line-height: 1.5;">
                    Ninjas de todo o mundo se reúnem para defender suas vilas.<br>
                    A Guerra dura 7 dias. A vila com maior Score vence e seus membros participantes são premiados em Yens!
                </p>
                <?php if($ssj == 'aberto'){ ?>
                    <p style="color: #afa; margin-top: 10px; font-weight: bold;">
                        Fim da Guerra: <?php echo date('d/m/Y H:i', strtotime($war_data['fim'])); ?>
                    </p>
                <?php } ?>
            </div>
        </div>

        <?php if($ssj == 'fim'){ ?>
            <div style="text-align: center; margin-bottom: 20px;">
                <h2 style="color: gold; text-shadow: 0 0 10px #f00;">
                    <?php echo $idinwar; ?>ª Guerra Finalizada!
                </h2>
                <div style="font-size: 18px; color: #fff;">
                    Vila Campeã: <span style="color: #faa; font-weight: bold;"><?php echo isset($vilas_nomes[$vencedorinwar]) ? $vilas_nomes[$vencedorinwar] : 'Indefinido'; ?></span>
                </div>
                
                <div style="margin-top: 20px;">
                    <button class="modern-btn" onclick="document.getElementById('lista_antiga').style.display = (document.getElementById('lista_antiga').style.display=='none' ? 'block' : 'none');">
                        Ver Histórico de Guerras
                    </button>
                </div>

                <div id="lista_antiga" style="display: none; margin-top: 20px;">
                    <table class="modern-table" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Vila Vencedora</th>
                                <th>Score</th>
                                <th>Prêmio</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $rankingsql = mysqli_query($mysqli_link, "SELECT * FROM nal_war ORDER BY id DESC LIMIT 10");
                            while($ranking = mysqli_fetch_assoc($rankingsql)){
                                $nal_vila = mysqli_fetch_assoc(mysqli_query($mysqli_link, "SELECT * FROM nal_war_vilas WHERE vilaid='".$ranking['vencedor']."' AND warid='".$ranking['id']."'"));
                                $nome_vila = isset($vilas_nomes[$ranking['vencedor']]) ? $vilas_nomes[$ranking['vencedor']] : 'Desconhecida';
                            ?>
                            <tr>
                                <td><?php echo $ranking['id']; ?>ª</td>
                                <td><?php echo $nome_vila; ?></td>
                                <td><?php echo $nal_vila ? $nal_vila['score'] : 0; ?></td>
                                <td><?php echo number_format($ranking['premio'],2,',','.'); ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php } ?>

        <?php if($ssj == 'inscricao'){ ?>
            <?php
            // Lógica de Inscrição
            $nivel1 = $war_data['nivelmin'];
            $nivel2 = $war_data['nivelmax'];
            $valor = $war_data['custo'];
            
            if(isset($_POST['inscrever'])){
                if(isset($_SESSION['logado'])){
                    if($db['yens'] < $valor){ $msg_err = 'Yens insuficientes!'; }
                    elseif($db['nivel'] < $nivel1){ $msg_err = 'Seu nível é muito baixo!'; }
                    elseif($db['nivel'] > $nivel2){ $msg_err = 'Seu nível é muito alto!'; }
                    elseif($db['inwar'] == 'sim'){ $msg_err = 'Você já está inscrito!'; }
                    else {
                        mysqli_query($mysqli_link, "UPDATE usuarios SET inwar='sim', yens=yens-$valor WHERE id=".$db['id']);
                        $msg_suc = "Inscrição realizada com sucesso! Prepare-se!";
                        // Reload db data
                        $db['inwar'] = 'sim';
                        $db['yens'] -= $valor;
                    }
                }
            }
            
            $count_inscritos = mysqli_fetch_assoc(mysqli_query($mysqli_link, "SELECT count(id) as total FROM usuarios WHERE inwar = 'sim'"));
            $total_inscritos = $count_inscritos['total'];
            ?>

            <div style="background: rgba(0,0,0,0.3); padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <h4 style="color: #fff; border-bottom: 1px solid #444; padding-bottom: 5px; margin-top: 0;">Inscrições Abertas</h4>
                
                <?php if(isset($msg_err)) echo '<div class="aviso">'.$msg_err.'</div>'; ?>
                <?php if(isset($msg_suc)) echo '<div class="aviso" style="background: rgba(50,255,50,0.1); border: 1px solid #0f0;">'.$msg_suc.'</div>'; ?>

                <div style="display: flex; flex-wrap: wrap; gap: 20px; justify-content: space-between; font-size: 13px; color: #ccc;">
                    <div>Inscritos: <b style="color: #fff;"><?php echo $total_inscritos; ?></b></div>
                    <div>Nível Mínimo: <b style="color: #fff;"><?php echo $nivel1; ?></b></div>
                    <div>Nível Máximo: <b style="color: #fff;"><?php echo $nivel2; ?></b></div>
                    <div>Taxa: <b style="color: gold;"><?php echo number_format($valor,2,',','.'); ?> Yens</b></div>
                </div>
                
                <div class="sep"></div>
                
                <?php if($db['inwar'] == 'sim'){ ?>
                    <div style="text-align: center; color: #afa; font-weight: bold; padding: 10px;">
                        ✓ Você já está inscrito na Guerra de Vilas!
                    </div>
                <?php } else { ?>
                    <form method="post" action="?p=guerradevila">
                        <input type="hidden" name="inscrever" value="1">
                        <div style="text-align: center; margin-top: 15px;">
                            <input type="submit" class="modern-btn" value="Inscrever-se Agora" style="background: #28a745; width: 200px;">
                        </div>
                    </form>
                <?php } ?>
            </div>
        <?php } ?>
        
        <?php if($ssj == 'aberto'){ ?>
            <!-- MENU DA GUERRA -->
            <div style="display: flex; gap: 10px; margin-bottom: 20px; justify-content: center;">
                <a href="?p=guerradevila" class="modern-btn <?php echo (!isset($_GET['vila'])) ? 'active' : ''; ?>">Placar das Vilas</a>
                <a href="?p=guerradevila&vila=ninjas" class="modern-btn <?php echo (isset($_GET['vila']) && $_GET['vila']=='ninjas') ? 'active' : ''; ?>">Ninjas</a>
                <a href="?p=guerradevila&vila=top3" class="modern-btn <?php echo (isset($_GET['vila']) && $_GET['vila']=='top3') ? 'active' : ''; ?>">Top 3</a>
            </div>

            <?php if(!isset($_GET['vila'])){ ?>
                <!-- PLACAR DAS VILAS -->
                <table class="modern-table" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Vila</th>
                            <th>Logo</th>
                            <th>Score</th>
                            <th>Ninjas Vivos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $Ranksql = mysqli_query($mysqli_link, "SELECT * FROM nal_war_vilas WHERE warid='".$idinwar."' ORDER BY score DESC");
                        $i = 1;
                        while($Rank_vilas = mysqli_fetch_assoc($Ranksql)){
                            // Contar ninjas vivos
                            $cond = ($Rank_vilas['vilaid'] == 7) ? "renegado='sim'" : "vila='".$Rank_vilas['vilaid']."' AND renegado='nao'";
                            $count = mysqli_query($mysqli_link, "SELECT count(id) as total FROM usuarios WHERE $cond AND inwar='sim'");
                            $ninjas = mysqli_fetch_assoc($count);
                            $nome_vila = isset($vilas_nomes[$Rank_vilas['vilaid']]) ? $vilas_nomes[$Rank_vilas['vilaid']] : 'Desconhecida';
                        ?>
                        <tr>
                            <td><?php echo $i++; ?>°</td>
                            <td><?php echo $nome_vila; ?></td>
                            <td><img src="_img/rank/<?php echo $Rank_vilas['vilaid']; ?>.png"></td>
                            <td style="color: gold; font-weight: bold;"><?php echo number_format($Rank_vilas['score'],0,',','.'); ?></td>
                            <td><?php echo $ninjas['total']; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } elseif($_GET['vila'] == 'top3'){ ?>
                <!-- TOP 3 NINJAS -->
                <table class="modern-table" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Ninja</th>
                            <th>Vila</th>
                            <th>Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $idrank = mysqli_query($mysqli_link, "SELECT * FROM usuarios WHERE inwar='sim' ORDER BY inwar_score DESC LIMIT 3");
                        $pos = 1;
                        while($seis = mysqli_fetch_assoc($idrank)){
                            $vila_id = ($seis['renegado']=='sim') ? 7 : $seis['vila'];
                        ?>
                        <tr>
                            <td><?php echo $pos++; ?>°</td>
                            <td>
                                <a href="?p=view&view=<?php echo $seis['usuario']; ?>" style="font-weight: bold; color: #fff;">
                                    <?php echo $seis['usuario']; ?>
                                </a><br>
                                <span style="font-size: 10px; color: #aaa;">Lvl <?php echo $seis['nivel']; ?></span>
                            </td>
                            <td><img src="_img/rank/<?php echo $vila_id; ?>.png"></td>
                            <td style="color: gold;"><?php echo $seis['inwar_score']; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } elseif($_GET['vila'] == 'ninjas'){ ?>
                <!-- LISTA DE NINJAS -->
                <div style="background: rgba(0,0,0,0.3); padding: 10px; margin-bottom: 15px; text-align: center;">
                    <form method="post">
                        <label>Filtrar por Vila:</label>
                        <select name="pesquisa" class="modern-input" style="width: auto;">
                            <option value="0">Todas</option>
                            <?php foreach($vilas_nomes as $vid => $vnome){ echo "<option value='$vid'>$vnome</option>"; } ?>
                        </select>
                        <input type="submit" name="pesquisar" value="Filtrar" class="modern-btn">
                    </form>
                </div>
                
                <table class="modern-table" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Ninja</th>
                            <th>Vila</th>
                            <th>Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $where_clause = "inwar='sim'";
                        if(isset($_POST['pesquisa']) && $_POST['pesquisa'] != 0){
                            $pesq = (int)$_POST['pesquisa'];
                            if($pesq == 7) $where_clause .= " AND renegado='sim'";
                            else $where_clause .= " AND vila='$pesq' AND renegado='nao'";
                        }
                        
                        $idrank = mysqli_query($mysqli_link, "SELECT * FROM usuarios WHERE $where_clause ORDER BY inwar_score DESC LIMIT 50");
                        while($seis = mysqli_fetch_assoc($idrank)){
                            $vila_id = ($seis['renegado']=='sim') ? 7 : $seis['vila'];
                        ?>
                        <tr>
                            <td>
                                <a href="?p=view&view=<?php echo $seis['usuario']; ?>" style="font-weight: bold; color: #fff;">
                                    <?php echo $seis['usuario']; ?>
                                </a>
                            </td>
                            <td><img src="_img/rank/<?php echo $vila_id; ?>.png"></td>
                            <td><?php echo $seis['inwar_score']; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php } ?>
            
        <?php } ?>

    </div>
</div>