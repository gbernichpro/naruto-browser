<?php
require_once('verificar_sala.php');

if(!isset($_GET['id']) || !isset($_GET['jutsu'])){ 
    echo "<script>self.location='?p=school'</script>"; 
    exit(); 
}

$id_sala = (int)$_GET['id'];
$id_jutsu = (int)$c->decode($_GET['jutsu'], $chaveuniversal);
$atual = date('Y-m-d H:i:s');

// Busca informações do jutsu
$stmt_j = mysqli_prepare($mysqli_link, "SELECT id, nome, natureza, forca, nivel, valor FROM table_jutsus WHERE id=?");
mysqli_stmt_bind_param($stmt_j, "i", $id_jutsu);
mysqli_stmt_execute($stmt_j);
$res_j = mysqli_stmt_get_result($stmt_j);
$dbj = mysqli_fetch_assoc($res_j);

if(!$dbj){ echo "<script>self.location='?p=school'</script>"; exit(); }

// Validações
if($dbj['nivel'] > $db['level']){ echo "<script>self.location='?p=learn&id=$id_sala'</script>"; exit(); }
if($dbj['valor'] > $db['yens']){ echo "<script>self.location='?p=learn&id=$id_sala&msg=2'</script>"; exit(); }

// Processamento de Conclusão do Minigame
if(isset($_POST['contador'])){
	$req_selos = floor($dbj['forca'] / 4);
    if($req_selos < 1) $req_selos = 1;

	if((int)$_POST['contador'] >= $req_selos){
        // Verifica se já possui
        $stmt_p = mysqli_prepare($mysqli_link, "SELECT count(id) as conta FROM jutsus WHERE usuarioid=? AND jutsu=?");
        mysqli_stmt_bind_param($stmt_p, "ii", $db['id'], $dbj['id']);
        mysqli_stmt_execute($stmt_p);
        $res_p = mysqli_stmt_get_result($stmt_p);
        $dbv = mysqli_fetch_assoc($res_p);

		if($dbv['conta'] == 0){
            mysqli_begin_transaction($mysqli_link);
            try {
                // Insere Jutsu
                $stmt_i = mysqli_prepare($mysqli_link, "INSERT INTO jutsus (usuarioid, jutsu, nivel, exp, expmax) VALUES (?, ?, 1, 0, 50)");
                mysqli_stmt_bind_param($stmt_i, "ii", $db['id'], $dbj['id']);
                mysqli_stmt_execute($stmt_i);

                // Log de Atualização
                $at_msg = '<a href="?p=view&view='.strtolower($db['usuario']).'">'.$db['usuario'].'</a> aprendeu <b>'.$dbj['nome'].'</b>.';
                $stmt_a = mysqli_prepare($mysqli_link, "INSERT INTO atualizacoes (usuarioid, texto, hora) VALUES (?, ?, ?)");
                $time_now = time();
                mysqli_stmt_bind_param($stmt_a, "isi", $db['id'], $at_msg, $time_now);
                mysqli_stmt_execute($stmt_a);

                // Deduz Yens
                $stmt_u = mysqli_prepare($mysqli_link, "UPDATE usuarios SET yens=yens-? WHERE id=?");
                mysqli_stmt_bind_param($stmt_u, "di", $dbj['valor'], $db['id']);
                mysqli_stmt_execute($stmt_u);

                mysqli_commit($mysqli_link);
                echo "<script>self.location='?p=room&id=$id_sala&msg=2'</script>"; 
                exit();
            } catch (Exception $e) {
                mysqli_rollback($mysqli_link);
                die("Erro técnico: " . $e->getMessage());
            }
		} else {
            echo "<script>self.location='?p=room&id=$id_sala'</script>"; 
            exit(); 
        }
	}
}

// Configuração do Minigame
$selos_nomes = ['bode','dragao','cobra','cachorro','coelho','boi','macaco','cavalo','tigre','rato','passaro', 'javali'];
$max_selos = floor($dbj['forca'] / 4);
if($max_selos < 1) $max_selos = 1;

$fim_time = $dbr['fim'];
$stmt_t = mysqli_prepare($mysqli_link, "SELECT timediff(?, ?) as fim");
mysqli_stmt_bind_param($stmt_t, "ss", $fim_time, $atual);
mysqli_stmt_execute($stmt_t);
$sqltempo = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_t));
$tempo_restante = $sqltempo['fim'];
?>

<div class="modern-card">
    <div class="modern-card-header">⚔️ Praticando Selos: <?php echo $dbj['nome']; ?></div>
    <div class="modern-card-body">
        
        <div style="display: flex; gap: 20px; align-items: center; background: rgba(0,0,0,0.2); padding: 20px; border-radius: 8px; border: 1px solid #333; margin-bottom: 25px;">
            <img src="_img/_detalhes/msg/38.png" style="width: 100px; border-radius: 8px; filter: drop-shadow(0 0 10px rgba(255,100,0,0.3));">
            <div style="flex: 1;">
                <h3 style="color: #f60; margin: 0 0 10px 0; font-family: 'Impact', sans-serif; letter-spacing: 1px;">FOCO E VELOCIDADE</h3>
                <p style="color: #ccc; font-size: 13px; line-height: 1.6; margin: 0;">
                    Execute os selos de mão na ordem correta para dominar esta técnica. Se errar, deverá recomeçar a sequência!<br>
                    <span style="color: #0f0; font-size: 11px; font-weight: bold;">Selos Necessários: <?php echo $max_selos; ?></span>
                </p>
            </div>
            <div style="text-align: right; background: rgba(0,0,0,0.3); padding: 10px; border-radius: 5px; border: 1px solid #444;">
                <span style="color: #aaa; font-size: 10px; display: block; text-transform: uppercase;">Sala Expira em:</span>
                <b style="color: #fff; font-family: monospace;" id="sala_tempo"><?php echo $tempo_restante; ?></b>
            </div>
        </div>

        <div style="background: url('_img/school/fundo_selos.jpg') center no-repeat; height: 160px; border-radius: 10px; display: flex; align-items: center; justify-content: center; position: relative; border: 1px solid #222; margin-bottom: 30px; box-shadow: inset 0 0 50px rgba(0,0,0,0.8);">
            
            <div style="display: flex; gap: 40px; align-items: center; margin-right: 50px;">
                <div style="text-align: center;">
                    <span style="color: #aaa; font-size: 10px; display: block; margin-bottom: 5px; text-transform: uppercase;">Executar Selo:</span>
                    <img id="repeat" src="_img/school/selo_<?php echo $selos_nomes[rand(0, 7)]; ?>.jpg" style="border: 2px solid gold; border-radius: 10px; box-shadow: 0 0 15px rgba(255,215,0,0.4);">
                </div>
                
                <div style="text-align: center; background: rgba(0,0,0,0.5); padding: 15px; border-radius: 10px; border: 1px solid #444; min-width: 120px;">
                    <div style="font-size: 12px; color: #888; text-transform: uppercase; margin-bottom: 5px;">Progresso</div>
                    <div style="font-size: 28px; color: #fff; font-family: 'Impact';"><span id="count">0</span>/<?php echo $max_selos; ?></div>
                    <div id="status" style="font-size: 12px; font-weight: bold; margin-top: 5px; color: #666;">AGUARDANDO...</div>
                </div>
            </div>
        </div>

        <div align="center" style="display: grid; grid-template-columns: repeat(4, 110px); justify-content: center; gap: 15px; background: rgba(0,0,0,0.1); padding: 20px; border-radius: 15px; border: 1px solid #333;">
            <?php for($i=1; $i<=8; $i++): ?>
                <div class="selo-btn" onclick="minigame(<?php echo $i; ?>)" style="cursor: pointer; transition: transform 0.1s; border: 1px solid #444; border-radius: 8px; overflow: hidden;" onmousedown="this.style.transform='scale(0.95)'" onmouseup="this.style.transform='scale(1)'">
                    <img id="t<?php echo $i; ?>" src="_img/school/selo_<?php echo $selos_nomes[rand(0, 11)]; ?>.jpg" style="width: 100%; display: block;">
                </div>
            <?php endfor; ?>
        </div>

        <form method="post" action="?p=pratice&id=<?php echo $id_sala; ?>&jutsu=<?php echo $_GET['jutsu']; ?>" id="form_minigame">
            <input type="hidden" id="contador" name="contador" value="0">
        </form>

        <div style="margin-top: 30px; text-align: center;">
            <a href="?p=room&leave=true" class="modern-btn" style="background: #444; font-size: 12px;">Desisitr e Sair</a>
        </div>
    </div>
</div>

<script>
var selos = ['bode','dragao','javali','cobra','cachorro','coelho','boi','macaco','cavalo','tigre','rato','passaro'];
var target_id = 0;
var conta = 0;
var max_conta = <?php echo $max_selos; ?>;

function shuffle() {
    var j, x, i;
    for (i = selos.length - 1; i > 0; i--) {
        j = Math.floor(Math.random() * (i + 1));
        x = selos[i];
        selos[i] = selos[j];
        selos[j] = x;
    }
}

function update_selos(){
    shuffle();
    target_id = Math.floor(Math.random() * 8) + 1;
    
    // Atualiza o selo alvo
    document.getElementById('repeat').src = "_img/school/selo_" + selos[target_id-1] + ".jpg";
    
    // Atualiza botões
    for(var i=1; i<=8; i++){
        document.getElementById('t' + i).src = "_img/school/selo_" + selos[i-1] + ".jpg";
    }
}

function minigame(id){
    if(id == target_id){
        conta++;
        document.getElementById('status').innerHTML = 'ACERTOU!';
        document.getElementById('status').style.color = '#0f0';
    } else {
        conta = 0;
        document.getElementById('status').innerHTML = 'ERROU!';
        document.getElementById('status').style.color = '#f00';
    }
    
    document.getElementById('count').innerHTML = conta;
    document.getElementById('contador').value = conta;
    
    if(conta >= max_conta){
        document.getElementById('form_minigame').submit();
        return;
    }
    
    update_selos();
}

// Inicializa
update_selos();

// Timer
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

<style>
.selo-btn:hover { border-color: gold !important; filter: brightness(1.2); }
</style>