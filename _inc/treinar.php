<?php
require_once('trava.php');
require_once('verificar.php');

if(!isset($_GET['id'])){ echo "<script>self.location='?p=home'</script>"; exit(); }

$pegaid = (int)$_GET['id'];

// Query Principal com mysqli
$stmt = mysqli_prepare($mysqli_link, "
    SELECT i.id, i.status, i.upgrade, i.usuarioid, t.categoria, t.descricao, 
           i.taijutsu, i.ninjutsu, i.genjutsu, t.nome, t.imagem, t.valor, 
           t.maxtai, t.maxnin, t.maxgen 
    FROM animais i 
    LEFT JOIN table_animais t ON i.itemid=t.id 
    WHERE i.id=? 
    LIMIT 1
");
mysqli_stmt_bind_param($stmt, "i", $pegaid);
mysqli_stmt_execute($stmt);
$sqli = mysqli_stmt_get_result($stmt);
$dbi = mysqli_fetch_assoc($sqli);

if(!$dbi || mysqli_num_rows($sqli) == 0){ echo "<script>self.location='?p=home'</script>"; exit(); }
if($dbi['usuarioid'] != $db['id']){ echo "<script>self.location='?p=home'</script>"; exit(); }

// Processamento de Treino
if(isset($_POST['train_taijutsu'])){
	$taijutsu = (int)$_POST['train_taijutsu'];
	$ninjutsu = (int)$_POST['train_ninjutsu'];
	$genjutsu = (int)$_POST['train_genjutsu'];
	$iddoitem = (int)$_POST['id'];

	if($taijutsu < $dbi['taijutsu'] || $ninjutsu < $dbi['ninjutsu'] || $genjutsu < $dbi['genjutsu']){ 
        echo "<script>self.location='?p=home'</script>"; 
        exit(); 
    }

	if($taijutsu > $dbi['maxtai'] || $ninjutsu > $dbi['maxnin'] || $genjutsu > $dbi['maxgen']){ 
        echo "<script>self.location='?p=treinarpet&id=$pegaid&msg=3'</script>"; 
        exit(); 
    }

	$total = 0;
    // Cálculo seguro de yens (cópia local para não afetar $dbi na exibição)
    $temp_tai = $dbi['taijutsu'];
    $temp_nin = $dbi['ninjutsu'];
    $temp_gen = $dbi['genjutsu'];

	while($taijutsu > $temp_tai){
		$total += round(($temp_tai * 2) + ($temp_tai * $temp_tai) + ($temp_tai * 0.2));
		$temp_tai++;
	}
	while($ninjutsu > $temp_nin){
		$total += round(($temp_nin * 2) + ($temp_nin * $temp_nin) + ($temp_nin * 0.2));
		$temp_nin++;
	}
	while($genjutsu > $temp_gen){
		$total += round(($temp_gen * 2) + ($temp_gen * $temp_gen) + ($temp_gen * 0.2));
		$temp_gen++;
	}

	if($total > $db['yens']){ 
        echo "<script>self.location='?p=treinarpet&id=$pegaid&msg=2'</script>"; 
        exit(); 
    }

    // Inicia Transação para segurança
    mysqli_begin_transaction($mysqli_link);
    try {
        $stmt_u = mysqli_prepare($mysqli_link, "UPDATE usuarios SET yens=yens-? WHERE id=?");
        mysqli_stmt_bind_param($stmt_u, "di", $total, $db['id']);
        mysqli_stmt_execute($stmt_u);

        $stmt_i = mysqli_prepare($mysqli_link, "UPDATE animais SET taijutsu=?, ninjutsu=?, genjutsu=? WHERE id=? AND usuarioid=?");
        mysqli_stmt_bind_param($stmt_i, "iiiii", $taijutsu, $ninjutsu, $genjutsu, $iddoitem, $db['id']);
        mysqli_stmt_execute($stmt_i);

        mysqli_commit($mysqli_link);
        echo "<script>self.location='?p=treinarpet&id=$pegaid&msg=1&yens=$total'</script>";
        exit();
    } catch (Exception $e) {
        mysqli_rollback($mysqli_link);
        die("Erro ao treinar pet: " . $e->getMessage());
    }
}
?>

<div class="modern-card">
    <div class="modern-card-header">🐾 Treinamento de Pet: <?php echo $dbi['nome']; ?></div>
    <div class="modern-card-body">
        
        <div style="display: flex; gap: 20px; align-items: center; background: rgba(0,0,0,0.2); padding: 20px; border-radius: 8px; border: 1px solid #333; margin-bottom: 20px;">
            <div style="background: rgba(255,255,255,0.05); padding: 10px; border-radius: 10px; border: 1px solid #444;">
                <img src="_img/equipamentos/<?php echo $dbi['imagem']; ?>.png" style="width: 120px; filter: drop-shadow(0 0 10px rgba(255,165,0,0.3));">
            </div>
            <div style="flex: 1;">
                <h3 style="color: gold; margin: 0 0 5px 0; font-family: 'Impact', sans-serif; letter-spacing: 1px;">
                    <?php echo $dbi['nome']; ?><?php if($dbi['upgrade'] > 0) echo ' <span style="color:#0f0;">+'.$dbi['upgrade'].'</span>'; ?>
                </h3>
                <p style="color: #ccc; font-size: 13px; line-height: 1.5; margin: 0 0 10px 0;">
                    <?php echo $dbi['descricao']; ?>
                </p>
                <div style="display: flex; gap: 10px;">
                    <span class="nivel-badge" style="background: #a00;">Tai: <?php echo $dbi['taijutsu'] + $dbi['upgrade']; ?></span>
                    <span class="nivel-badge" style="background: #00a;">Nin: <?php echo $dbi['ninjutsu'] + $dbi['upgrade']; ?></span>
                    <span class="nivel-badge" style="background: #70a;">Gen: <?php echo $dbi['genjutsu'] + $dbi['upgrade']; ?></span>
                </div>
            </div>
        </div>

        <?php if(isset($_GET['msg'])): 
            $msg_txt = '';
            $msg_class = 'aviso';
            switch($_GET['msg']){
                case 1: 
                    $y_spent = isset($_GET['yens']) ? $_GET['yens'] : 0;
                    $msg_txt = 'Treino realizado com sucesso! Foram gastos <b>'.number_format($y_spent,2,',','.').' yens</b>.';
                    $msg_class = 'sucesso';
                    break;
                case 2: $msg_txt = 'Yens insuficientes!'; break;
                case 3: $msg_txt = 'Limite de atributos do animal atingido!'; break;
            }
            if($msg_txt) echo '<div class="'.$msg_class.'" style="margin-bottom: 20px;">'.$msg_txt.'</div>';
        endif; ?>

        <div style="background: rgba(0,0,0,0.3); border-radius: 8px; border: 1px solid #444; padding: 20px; margin-bottom: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 1px solid #333;">
                <span style="color: #aaa; font-size: 12px;"><img src="_img/yens.png" width="14" align="absmiddle"> Seus Yens:</span>
                <b style="color: #fff;"><?php echo number_format($db['yens'],2,',','.'); ?> yens</b>
            </div>

            <table width="100%" cellpadding="0" cellspacing="0">
                <?php 
                $stats = [
                    ['tai', 'Taijutsu', 'ico_tai.png', $dbi['taijutsu'], $dbi['maxtai'], 'linear-gradient(90deg, #ff4D4D, #ff0000)'],
                    ['nin', 'Ninjutsu', 'ico_nin.png', $dbi['ninjutsu'], $dbi['maxnin'], 'linear-gradient(90deg, #4D94ff, #0066ff)'],
                    ['gen', 'Genjutsu', 'ico_gen.png', $dbi['genjutsu'], $dbi['maxgen'], 'linear-gradient(90deg, #b366ff, #8000ff)']
                ];
                foreach($stats as $s): 
                    $percent = ($s[3] / $s[4]) * 100;
                ?>
                <tr style="height: 60px;">
                    <td width="30"><img src="template/<?php echo $s[2]; ?>" width="18" style="opacity: 0.8;"></td>
                    <td width="100"><b style="color: #eee; font-size: 13px;"><?php echo $s[1]; ?>:</b></td>
                    <td style="padding: 0 15px;">
                        <div style="height: 8px; background: rgba(255,255,255,0.05); border-radius: 4px; overflow: hidden; border: 1px solid rgba(255,255,255,0.1);">
                            <div id="<?php echo $s[0]; ?>bar" style="height: 100%; transition: width 0.3s ease; background: <?php echo $s[5]; ?>; width: <?php echo $percent; ?>%;"></div>
                        </div>
                        <div style="font-size: 10px; color: #666; margin-top: 4px; text-transform: uppercase;">Máximo: <?php echo $s[4]; ?></div>
                    </td>
                    <td width="60">
                        <div style="display: flex; gap: 5px; justify-content: center;">
                            <img src="_img/foda/up.png" style="cursor:pointer;" onclick="change('<?php echo $s[0]; ?>', 1);">
                            <img id="<?php echo $s[0]; ?>down" src="_img/foda/down.png" style="cursor:pointer; visibility:hidden;" onclick="change('<?php echo $s[0]; ?>', -1);">
                        </div>
                    </td>
                    <td width="80" align="center"><b style="color: #fff; font-size: 16px;">| <span id="<?php echo $s[0]; ?>"><?php echo $s[3]; ?></span> |</b></td>
                    <td width="120" align="right"><b style="color: #f44; font-size: 12px;"><div id="<?php echo $s[0]; ?>value"><?php echo number_format(round(($s[3]*2)+($s[3]*$s[3])+($s[3]*0.2)),2,',','.'); ?> yens</div></b></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <div style="display: flex; flex-direction: column; gap: 8px; margin-bottom: 25px;">
            <div style="padding: 12px; background: rgba(255,0,0,0.05); border-radius: 6px; border: 1px solid rgba(255,0,0,0.1); display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #aaa; font-size: 12px;">Custo Total:</span>
                <b style="color: #f44; font-size: 15px;"><img src="_img/yens_neg.png" align="absmiddle"> <span id="totaltrain">0,00</span> yens</b>
            </div>
            <div style="padding: 12px; background: rgba(0,255,0,0.03); border-radius: 6px; border: 1px solid rgba(0,255,0,0.05); display: flex; justify-content: space-between; align-items: center;">
                <span style="color: #aaa; font-size: 12px;">Saldo Restante:</span>
                <b style="color: #0f0; font-size: 15px;"><img src="_img/yens.png" width="14" align="absmiddle"> <span id="resttrain"><?php echo number_format($db['yens'],2,',','.'); ?></span> yens</b>
            </div>
        </div>

        <div id="train_button" style="display:none; text-align: center;">
            <form method="post" action="?p=treinarpet&id=<?php echo $pegaid; ?>" id="form_train">
                <input type="hidden" name="id" value="<?php echo $pegaid; ?>" />
                <input type="hidden" name="train_taijutsu" value="<?php echo $dbi['taijutsu']; ?>" />
                <input type="hidden" name="train_ninjutsu" value="<?php echo $dbi['ninjutsu']; ?>" />
                <input type="hidden" name="train_genjutsu" value="<?php echo $dbi['genjutsu']; ?>" />
                <input type="hidden" name="restante" value="" />
                <input type="submit" class="modern-btn" style="padding: 12px 50px; font-size: 16px; min-width: 220px;" value="Confirmar Treino" />
            </form>
        </div>
    </div>
</div>

<script>
var taijutsu = <?php echo $dbi['taijutsu']; ?>;
var taipadrao = taijutsu;
var ninjutsu = <?php echo $dbi['ninjutsu']; ?>;
var ninpadrao = ninjutsu;
var genjutsu = <?php echo $dbi['genjutsu']; ?>;
var genpadrao = genjutsu;
var total = 0;
var yens = <?php echo $db['yens']; ?>;

var limits = {
    tai: <?php echo $dbi['maxtai']; ?>,
    nin: <?php echo $dbi['maxnin']; ?>,
    gen: <?php echo $dbi['maxgen']; ?>
};

function float2moeda(num) {
   if(isNaN(num)) num = "0";
   var cents = Math.floor((num*100+0.5)%100);
   num = Math.floor((num*100+0.5)/100).toString();
   if(cents < 10) cents = "0" + cents;
   for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
      num = num.substring(0,num.length-(4*i+3))+'.' + num.substring(num.length-(4*i+3));
   return num + ',' + cents;
}

function visibility(){
	document.getElementById('taidown').style.visibility = (taijutsu <= taipadrao) ? 'hidden' : 'visible';
	document.getElementById('nindown').style.visibility = (ninjutsu <= ninpadrao) ? 'hidden' : 'visible';
	document.getElementById('gendown').style.visibility = (genjutsu <= genpadrao) ? 'hidden' : 'visible';
	
    var has_changed = (taijutsu > taipadrao || ninjutsu > ninpadrao || genjutsu > genpadrao);
    document.getElementById('train_button').style.display = (has_changed && (yens - total >= 0)) ? 'block' : 'none';
}

function change(at, dir){
    var current = (at == 'tai') ? taijutsu : (at == 'nin' ? ninjutsu : genjutsu);
    var padrao = (at == 'tai') ? taipadrao : (at == 'nin' ? ninpadrao : genpadrao);
    
    // Validations
    if(dir > 0 && current >= limits[at]) return;
    if(dir < 0 && current <= padrao) return;

    var somar = 0;
    if(dir > 0){
        somar = Math.round((current * 2) + (current * current) + (current * 0.2));
    } else {
        var prev = current - 1;
        somar = -Math.round((prev * 2) + (prev * prev) + (prev * 0.2));
    }

    total += somar;
    if(at == 'tai') taijutsu += dir;
    else if(at == 'nin') ninjutsu += dir;
    else if(at == 'gen') genjutsu += dir;

    // Update UI
    document.getElementById(at).innerHTML = (at == 'tai' ? taijutsu : (at == 'nin' ? ninjutsu : genjutsu));
    document.getElementById(at + 'value').innerHTML = float2moeda(Math.round(((current+dir)*2) + ((current+dir)*(current+dir)) + ((current+dir)*0.2))) + ' yens';
    document.getElementById(at + 'bar').style.width = (((current+dir) / limits[at]) * 100) + '%';
    
    document.getElementById('totaltrain').innerHTML = float2moeda(total);
    document.getElementById('resttrain').innerHTML = float2moeda(yens - total);
    
    // Update Hidden Form
    var f = document.getElementById('form_train');
    f['train_'+at+'jutsu'].value = (at == 'tai' ? taijutsu : (at == 'nin' ? ninjutsu : genjutsu));
    f['restante'].value = yens - total;

    visibility();
}
</script>