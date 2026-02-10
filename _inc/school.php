<?php require_once('verificar.php'); ?>
<?php
// Cancelamento de Treino
if(isset($_GET['cancel'])){
    $stmt_c = mysqli_prepare($mysqli_link, "UPDATE usuarios SET treino='0', treino_fim='0000-00-00 00:00:00' WHERE id=?");
    mysqli_stmt_bind_param($stmt_c, "i", $db['id']);
    mysqli_stmt_execute($stmt_c);
	$db['treino'] = 0;
	$db['treino_fim'] = '0000-00-00 00:00:00';
}

$atual = date('Y-m-d H:i:s');

// Busca de salas (Principal)
$query_salas = "SELECT s.*, u.usuario FROM salas s LEFT JOIN usuarios u ON s.usuarioid=u.id ORDER BY s.id ASC";
$sqls = mysqli_query($mysqli_link, $query_salas);

// Verifica se o usuário já tem sala reservada
$stmt_v = mysqli_prepare($mysqli_link, "SELECT * FROM salas WHERE usuarioid=? AND fim > ?");
mysqli_stmt_bind_param($stmt_v, "is", $db['id'], $atual);
mysqli_stmt_execute($stmt_v);
$sqlv = mysqli_stmt_get_result($stmt_v);
$dbv = mysqli_fetch_assoc($sqlv);

if(mysqli_num_rows($sqlv) == 0):
?>
<div class="modern-card">
    <div class="modern-card-header">🏫 Escola Ninja</div>
    <div class="modern-card-body">
        
        <div style="display: flex; gap: 20px; align-items: center; background: rgba(0,0,0,0.2); padding: 20px; border-radius: 8px; border: 1px solid #333; margin-bottom: 25px;">
            <img src="_img/_detalhes/msg/18.png" style="width: 100px; border-radius: 8px; filter: drop-shadow(0 0 5px rgba(255,255,255,0.1));">
            <div>
                <h3 style="color: gold; margin: 0 0 10px 0; font-family: 'Impact', sans-serif; letter-spacing: 1px;">DOMINE NOVOS JUTSUS</h3>
                <p style="color: #ccc; font-size: 13px; line-height: 1.6; margin: 0;">
                    Bem-vindo à Escola Ninja! Aqui você poderá aprender novos jutsus, descobrir a natureza do seu chakra e praticar habilidades com seu sensei.<br>
                    <span style="color: #f90; font-size: 11px;">⚠️ Você pode permanecer na sala por até 15 minutos por sessão.</span>
                </p>
            </div>
        </div>

        <?php if(isset($_GET['cancel'])): ?>
            <div class="aviso" style="margin-bottom: 20px;">Treino cancelado com sucesso!</div>
        <?php endif; ?>

        <div style="background: rgba(255,255,255,0.03); padding: 10px; border-radius: 5px; margin-bottom: 15px; border: 1px solid #444; text-align: center;">
            <span style="color: #aaa; font-size: 12px; text-transform: uppercase; letter-spacing: 1px;">
                Salas Disponíveis: <b style="color: #0f0;" id="disp_count">0</b> de <?php echo mysqli_num_rows($sqls); ?>
            </span>
        </div>

        <table class="modern-table" style="width: 100%;">
            <thead>
                <tr>
                    <th style="width: 100px;">Sala</th>
                    <th>Ocupante</th>
                    <th style="text-align: center; width: 120px;">Status</th>
                    <th style="text-align: right; width: 100px;">Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $disponiveis = 0;
                $i = 1;
                mysqli_data_seek($sqls, 0); // Reiniciar ponteiro se necessário
                while($row_s = mysqli_fetch_assoc($sqls)): 
                    $is_free = (!$row_s['usuarioid'] || $atual > $row_s['fim']);
                    if($is_free) $disponiveis++;
                ?>
                <tr>
                    <td style="font-weight: bold; color: #eee;">Sala <?php echo $i++; ?></td>
                    <td>
                        <?php if($is_free): ?>
                            <span style="color: #555; font-style: italic;">Ninguém</span>
                        <?php else: ?>
                            <a href="?p=view&view=<?php echo $row_s['usuario']; ?>" style="color: #0099ff; font-weight: bold;"><?php echo $row_s['usuario']; ?></a>
                        <?php endif; ?>
                    </td>
                    <td style="text-align: center;">
                        <?php if($is_free): ?>
                            <span style="color: #0f0; font-size: 11px; font-weight: bold; background: rgba(0,255,0,0.1); padding: 3px 8px; border-radius: 4px; border: 1px solid rgba(0,255,0,0.2);">LIVRE</span>
                        <?php else: ?>
                            <span style="color: #f90; font-size: 11px; font-weight: bold; background: rgba(255,150,0,0.1); padding: 3px 8px; border-radius: 4px; border: 1px solid rgba(255,150,0,0.2);">OCUPADA</span>
                        <?php endif; ?>
                    </td>
                    <td style="text-align: right;">
                        <?php if($is_free): ?>
                            <a href="?p=room&id=<?php echo $row_s['id']; ?>" class="modern-btn" style="padding: 4px 12px; font-size: 11px;">Entrar</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>
<script>document.getElementById('disp_count').innerHTML = '<?php echo $disponiveis; ?>';</script>

<?php else: ?>
<?php
    $fim_time = $dbv['fim'];
    // Busca diferença de tempo via PHP (alternativa ao timediff do MySQL se preferir, mas vamos manter compatibilidade)
    $stmt_t = mysqli_prepare($mysqli_link, "SELECT timediff(?, ?) as fim");
    mysqli_stmt_bind_param($stmt_t, "ss", $fim_time, $atual);
    mysqli_stmt_execute($stmt_t);
    $res_t = mysqli_stmt_get_result($stmt_t);
    $sqltempo = mysqli_fetch_assoc($res_t);
    $tempo_restante = $sqltempo['fim'];
?>
<div class="modern-card">
    <div class="modern-card-header">🏫 Escola Ninja: Reserva Ativa</div>
    <div class="modern-card-body">
        <div class="aviso" id="mensagem" style="text-align: center; background: rgba(0,150,255,0.1); border-color: #0099ff; padding: 20px;">
            <p style="margin: 0 0 10px 0;">Você já tem uma sala reservada seguindo seus estudos!</p>
            <div style="font-size: 24px; font-weight: bold; color: #fff; font-family: monospace;" id="sala_tempo"><?php echo $tempo_restante; ?></div>
            <span style="color: #aaa; font-size: 11px; text-transform: uppercase;">Tempo Restante na Sala</span>
        </div>

        <div style="margin-top: 30px; display: flex; gap: 15px; justify-content: center;">
            <a href="?p=room&id=<?php echo $dbv['id']; ?>" class="modern-btn" style="padding: 12px 30px; min-width: 200px;">Retornar para a Sala</a>
            <a href="?p=room&leave=true" class="modern-btn" style="padding: 12px 30px; background: #444;">Cancelar Reserva</a>
        </div>
    </div>
</div>

<script>
var conc = 0;
function calculafim(){
    if(conc == 0){
        var el = document.getElementById("sala_tempo");
        if(!el) return;
        var tmp = el.innerHTML.split(":");
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
        el.innerHTML = res;
    }
}
setInterval(calculafim, 1000);
</script>
<?php endif; ?>
