<?php
require_once('verificar_sala.php');

if(!isset($_GET['id'])){ echo "<script>self.location='?p=school'</script>"; exit(); }

$id_sala = (int)$_GET['id'];
$atual = date('Y-m-d H:i:s');

// Busca tempo restante (compatibilidade com verificar_sala.php)
$fim_time = $dbr['fim'];
$stmt_t = mysqli_prepare($mysqli_link, "SELECT timediff(?, ?) as fim");
mysqli_stmt_bind_param($stmt_t, "ss", $fim_time, $atual);
mysqli_stmt_execute($stmt_t);
$res_t = mysqli_stmt_get_result($stmt_t);
$sqltempo = mysqli_fetch_assoc($res_t);
$tempo_restante = $sqltempo['fim'];

// Busca jutsus que o usuário JÁ POSSUI
$stmt_p = mysqli_prepare($mysqli_link, "SELECT jutsu FROM jutsus WHERE usuarioid=?");
mysqli_stmt_bind_param($stmt_p, "i", $db['id']);
mysqli_stmt_execute($stmt_p);
$sqlv = mysqli_stmt_get_result($stmt_p);

$existentes = [];
while($row = mysqli_fetch_array($sqlv)){
	$existentes[] = (int)$row['jutsu'];
}

// Construção da query de Jutsus Disponíveis
$filtro_existentes = !empty($existentes) ? " AND id NOT IN (" . implode(',', $existentes) . ")" : "";

// Filtro de Natureza
$naturezas = [];
for($n=1; $n<=6; $n++) {
    if(!empty($db['natureza'.$n])) $naturezas[] = $db['natureza'.$n];
}

$condicao_natureza = " AND (natureza='nenhum'";
foreach($naturezas as $nat) {
    $condicao_natureza .= " OR (natureza='" . mysqli_real_escape_param($mysqli_link, $nat) . "')";
}
$condicao_natureza .= ")";

$query_jutsus = "SELECT * FROM table_jutsus 
                 WHERE nivel <= " . ((int)$db['level'] + 2) . " 
                 $filtro_existentes 
                 $condicao_natureza 
                 ORDER BY natureza DESC, nivel ASC";

$sqlj = mysqli_query($mysqli_link, $query_jutsus);
?>

<div class="modern-card">
    <div class="modern-card-header">📜 Biblioteca de Jutsus: Aprender</div>
    <div class="modern-card-body">
        
        <div style="display: flex; gap: 20px; align-items: center; background: rgba(0,0,0,0.2); padding: 20px; border-radius: 8px; border: 1px solid #333; margin-bottom: 25px;">
            <img src="_img/_detalhes/msg/5.png" style="width: 100px; border-radius: 8px; filter: drop-shadow(0 0 5px rgba(255,255,255,0.1));">
            <div style="flex: 1;">
                <h3 style="color: gold; margin: 0 0 5px 0; font-family: 'Impact', sans-serif; letter-spacing: 1px;">SENSEI À ESPERA</h3>
                <p style="color: #ccc; font-size: 13px; line-height: 1.6; margin: 0;">
                    Estes são os jutsus que você é capaz de compreender no momento. Escolha sua próxima técnica e inicie o treinamento de selos.<br>
                    <span style="color: #6cf; font-size: 12px; font-weight: bold;" id="sala_tempo">Tempo na Sala: <?php echo $tempo_restante; ?></span>
                </p>
            </div>
            <div style="text-align: right;">
                <a href="?p=room&id=<?php echo $id_sala; ?>" class="modern-btn" style="background: #444; padding: 5px 15px; font-size: 11px;">Voltar</a>
            </div>
        </div>

        <?php if(isset($_GET['msg'])): 
            $msg_txt = '';
            switch($_GET['msg']){
                case 1: $msg_txt = 'Você não possui nível 15 para controlar naturezas!'; break;
                case 2: $msg_txt = 'Yens insuficientes para este pergaminho.'; break;
            }
            if($msg_txt) echo '<div class="aviso" style="margin-bottom: 20px;">'.$msg_txt.'</div>';
        endif; ?>

        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 20px;">
            <?php 
            if(mysqli_num_rows($sqlj) == 0):
                echo '<div class="aviso" style="grid-column: 1/-1;">Nenhum jutsu novo disponível para seu nível ou natureza.</div>';
            else:
                while($dbj = mysqli_fetch_assoc($sqlj)): 
                    // Verificação de Doujutsu
                    if($dbj['doujutsu'] > 0 && $db['doujutsu'] != $dbj['doujutsu']) continue;
                    if($dbj['doujutsu_nivel'] > $db['doujutsu_nivel']) continue;
                    
                    $nat_label = 'Nenhuma';
                    $nat_icon = '';
                    if($dbj['natureza'] != 'nenhum'){
                        $nat_icon = '<img src="_img/jutsus/'.$dbj['natureza'].'.png" height="14" align="absmiddle"> ';
                        switch($dbj['natureza']){
                            case 'agua': $nat_label = 'Água (Suiton)'; break;
                            case 'fogo': $nat_label = 'Fogo (Katon)'; break;
                            case 'raio': $nat_label = 'Raio (Raiton)'; break;
                            case 'terra': $nat_label = 'Terra (Doton)'; break;
                            case 'vento': $nat_label = 'Vento (Fuuton)'; break;
                        }
                    }
            ?>
                <div style="background: rgba(0,0,0,0.3); border: 1px solid #444; border-radius: 10px; padding: 15px; display: flex; gap: 15px; transition: transform 0.2s; position: relative;" onmouseover="this.style.borderColor='var(--primary-red)'" onmouseout="this.style.borderColor='#444'">
                    <img src="_img/jutsus/<?php echo $dbj['id']; ?>.jpg" style="width: 80px; height: 80px; border-radius: 6px; border: 1px solid #222;" onerror="this.src='_img/jutsus/0.jpg'">
                    <div style="flex: 1;">
                        <h4 style="color: #fff; margin: 0 0 5px 0; font-size: 15px;"><?php echo $dbj['nome']; ?></h4>
                        <div style="font-size: 11px; color: #888; margin-bottom: 8px;">
                            <?php echo $nat_icon . $nat_label; ?> | Força: <span style="color: #f44;"><?php echo $dbj['forca']; ?></span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: flex-end;">
                            <div>
                                <span style="font-size: 10px; color: #666; display: block;">Requisitos:</span>
                                <b style="font-size: 12px; color: #eee;">Nível <?php echo $dbj['nivel']; ?></b>
                            </div>
                            <div style="text-align: right;">
                                <b style="color: #0f0; font-size: 12px; display: block; margin-bottom: 5px;"><?php echo number_format($dbj['valor'],2,',','.'); ?> yens</b>
                                <?php if($db['level'] >= $dbj['nivel']): ?>
                                    <a href="?p=pratice&id=<?php echo $id_sala; ?>&jutsu=<?php echo $c->encode($dbj['id'],$chaveuniversal); ?>" class="modern-btn" style="padding: 4px 15px; font-size: 11px;">Aprender</a>
                                <?php else: ?>
                                    <span style="color: #f44; font-size: 10px; font-weight: bold;">Nível Insuficiente</span>
                                <?php endif; ?>
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
            <a href="?p=room&leave=true" class="modern-btn" style="background: #a00; border-color: #f00;">Sair da Sala</a>
        </div>
    </div>
</div>

<script>
var conc = 0;
function calculafim(){
    if(conc == 0){
        var el = document.getElementById("sala_tempo");
        if(!el) return;
        var tmp = el.innerHTML.replace("Tempo na Sala: ", "").split(":");
        var h = parseInt(tmp[0]);
        var m = parseInt(tmp[1]);
        var s = parseInt(tmp[2]);
        
        s--;
        if(s < 0){ s = 59; m--; }
        if(m < 0){ m = 59; h--; }
        
        if(h < 0){
            self.location = "?p=school";
            conc = 1;
            return;
        }

        var res = (h < 10 ? "0" + h : h) + ":" + (m < 10 ? "0" + m : m) + ":" + (s < 10 ? "0" + s : s);
        el.innerHTML = "Tempo na Sala: " + res;
    }
}
setInterval(calculafim, 1000);
</script>
