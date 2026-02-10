<?php
require_once('trava.php');
require_once('Encrypt.php');
$c = new C_Encrypt();

if($db['missao'] > 0){ echo "<script>self.location='?p=busymission'</script>"; exit(); }
if($db['orgid'] == 0){ echo "<script>self.location='?p=home'</script>"; exit(); }

$id_org = $db['orgid'];

// Carregar Dados da Org e Posição do Usuário (mysqli)
$stmt_o = mysqli_prepare($mysqli_link, "SELECT nome, sigla FROM organizacoes WHERE id=?");
mysqli_stmt_bind_param($stmt_o, "i", $id_org);
mysqli_stmt_execute($stmt_o);
$res_o = mysqli_stmt_get_result($stmt_o);
$dbo = mysqli_fetch_assoc($res_o);

$stmt_p = mysqli_prepare($mysqli_link, "SELECT posicao FROM membros WHERE usuarioid=? AND orgid=?");
mysqli_stmt_bind_param($stmt_p, "ii", $db['id'], $id_org);
mysqli_stmt_execute($stmt_p);
$res_p = mysqli_stmt_get_result($stmt_p);
$dbp = mysqli_fetch_assoc($res_p);
$mypos = $dbp ? $dbp['posicao'] : 3;

// --- PROCESSAMENTO DE COMPRAS ---

// 1. Compra de Itens de Equipamento (Armas, Vestimentas, Calçados)
if(isset($_POST['buy_id2'])){
	$buy2 = (int)$_POST['buy_id2'];
	$category2 = isset($_POST['buy_cat2']) ? $_POST['buy_cat2'] : '';
    $page2 = isset($_POST['buy_page']) ? $_POST['buy_page'] : 'weapons';

	if($buy2 < 1 || !in_array($category2, ['arma', 'vestimenta', 'calcado'])){ 
        echo "<script>self.location='?p=home'</script>"; exit(); 
    }

	$stmt_i = mysqli_prepare($mysqli_link, "SELECT valor, reqtai, reqnin, reqgen, vip FROM table_itens WHERE id=? AND clashop='sim'");
    mysqli_stmt_bind_param($stmt_i, "i", $buy2);
    mysqli_stmt_execute($stmt_i);
    $res_i = mysqli_stmt_get_result($stmt_i);
    
	if(mysqli_num_rows($res_i) == 0){ echo "<script>self.location='?p=home'</script>"; exit(); }
	$dbb2 = mysqli_fetch_assoc($res_i);
    
    // Verificação VIP (Excluir se expirado)
	if(($dbb2['vip'] == 'sim') && (date('Y-m-d H:i:s') >= $db['vip'])){ 
        echo "<script>self.location='?p=cla_shop&category=$page2&msg=9'</script>"; exit(); 
    }

	$valor2 = $dbb2['valor'];
    if(date('Y-m-d H:i:s') < $db['vip']) $valor2 = floor($valor2 * 0.8); // 20% desconto VIP

	$bloq = 0;
	if($category2 == 'arma' && $db['taijutsu'] < $dbb2['reqtai']) $bloq = 3;
	if($category2 == 'vestimenta' && $db['genjutsu'] < $dbb2['reqgen']) $bloq = 6;
	if($category2 == 'calcado'){
		if($db['taijutsu'] < $dbb2['reqtai'] || $db['ninjutsu'] < $dbb2['reqtai'] || $db['genjutsu'] < $dbb2['reqtai']) $bloq = 7;
	}
    
	if($bloq > 0){ echo "<script>self.location='?p=cla_shop&category=$page2&msg=$bloq'</script>"; exit(); }
	if($db['pontoscla'] < $valor2){ echo "<script>self.location='?p=cla_shop&category=$page2&msg=1'</script>"; exit(); }

    // Verifica se já possui
	$sql_has = mysqli_query($mysqli_link, "SELECT id FROM inventario WHERE usuarioid='".$db['id']."' AND itemid=$buy2");
	if(mysqli_num_rows($sql_has) > 0){ echo "<script>self.location='?p=cla_shop&category=$page2&msg=8'</script>"; exit(); }

    mysqli_begin_transaction($mysqli_link);
    try {
        mysqli_query($mysqli_link, "UPDATE usuarios SET pontoscla = pontoscla - $valor2 WHERE id = '".$db['id']."'");
        mysqli_query($mysqli_link, "INSERT INTO inventario (usuarioid, itemid, categoria) VALUES (".$db['id'].", $buy2, '$category2')");
        mysqli_commit($mysqli_link);
        echo "<script>self.location='?p=cla_shop&category=$page2&msg=2'</script>"; exit();
    } catch (Exception $e) {
        mysqli_rollback($mysqli_link);
        die("Erro na compra: " . $e->getMessage());
    }
}

// 2. Compra de Consumíveis/Creditos (table_clashop)
if(isset($_POST['buy_id'])){
	$buy = (int)$_POST['buy_id'];
	$category = isset($_POST['buy_cat']) ? $_POST['buy_cat'] : '';
    $page = isset($_POST['buy_page']) ? $_POST['buy_page'] : 'hp';

	if($buy < 1 || !in_array($category, ['hp', 'cred', 'yens'])){ 
        echo "<script>self.location='?p=home'</script>"; exit(); 
    }

	$stmt_cs = mysqli_prepare($mysqli_link, "SELECT valor, vip FROM table_clashop WHERE id=?");
    mysqli_stmt_bind_param($stmt_cs, "i", $buy);
    mysqli_stmt_execute($stmt_cs);
    $res_cs = mysqli_stmt_get_result($stmt_cs);
    
	if(mysqli_num_rows($res_cs) == 0){ echo "<script>self.location='?p=home'</script>"; exit(); }
	$dbb = mysqli_fetch_assoc($res_cs);

	if(($dbb['vip'] == 'sim') && (date('Y-m-d H:i:s') >= $db['vip'])){ 
        echo "<script>self.location='?p=cla_shop&category=$page&msg=9'</script>"; exit(); 
    }

	$valor = $dbb['valor'];
    if(date('Y-m-d H:i:s') < $db['vip']) $valor = floor($valor * 0.8);

	if($db['pontoscla'] < $valor){ echo "<script>self.location='?p=cla_shop&category=$page&msg=1'</script>"; exit(); }

    mysqli_begin_transaction($mysqli_link);
    try {
        mysqli_query($mysqli_link, "UPDATE usuarios SET pontoscla = pontoscla - $valor WHERE id = '".$db['id']."'");
        mysqli_query($mysqli_link, "INSERT INTO invcla (usuarioid, itemid, categoria) VALUES (".$db['id'].", $buy, '$category')");
        mysqli_commit($mysqli_link);
        echo "<script>self.location='?p=cla_shop&category=$page&msg=2'</script>"; exit();
    } catch (Exception $e) {
        mysqli_rollback($mysqli_link);
        die("Erro na compra: " . $e->getMessage());
    }
}
?>

<div class="modern-card">
    <div class="modern-card-header">
        Loja do Clã: [<?php echo $dbo['sigla']; ?>] <?php echo $dbo['nome']; ?>
    </div>

    <!-- MENU SECUNDÁRIO UNIFICADO -->
    <div style="background: rgba(0,0,0,0.3); padding: 10px; border-bottom: 1px solid #444; display: flex; gap: 10px; overflow-x: auto;">
        <a href="?p=myorg" class="modern-btn">Info</a>
        <?php if($mypos < 3) { ?>
        <a href="?p=configorg" class="modern-btn">Configurar</a>
        <a href="?p=addorg" class="modern-btn">Recrutar</a>
        <?php } ?>
        <a href="?p=donateorg" class="modern-btn">Doar Yens</a>
        <a href="?p=warorg" class="modern-btn">Guerras</a>
        <a href="?p=cla_shop" class="modern-btn active">Loja</a>
        <a href="?p=investimentos" class="modern-btn">Investimentos</a>
    </div>

    <div class="modern-card-body">
        <!-- CABEÇALHO DA LOJA -->
        <div style="background: rgba(255,255,255,0.02); padding: 20px; border-radius: 8px; border: 1px solid #222; margin-bottom: 25px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <p style="color: #ccc; font-size: 14px; margin: 0;">Bem-vindo ao comércio dos clãs! Aqui você gasta seus pontos obtidos em missões e guerras.</p>
                <?php if(date('Y-m-d H:i:s') < $db['vip']): ?>
                    <span style="color: #ffd700; font-size: 12px; font-weight: bold; display: block; margin-top: 5px;">⭐ STATUS VIP ATIVO: 20% DE DESCONTO EM TODOS OS ITENS!</span>
                <?php endif; ?>
            </div>
            <div style="text-align: right; background: rgba(0,0,0,0.5); padding: 10px 20px; border-radius: 10px; border: 1px solid #444;">
                <span style="font-size: 11px; color: #777; display: block;">Seus Pontos de Clã</span>
                <span style="font-size: 20px; color: #fff; font-weight: bold;">
                    <img src="_img/yens.png" width="16" style="vertical-align: middle; filter: hue-rotate(90deg);"> 
                    <?php echo number_format($db['pontoscla'], 0, ',', '.'); ?> Pts
                </span>
            </div>
        </div>

        <?php
        if(isset($_GET['msg'])){
            $msg_txt = '';
            switch($_GET['msg']){
                case 1: $msg_txt = 'Pontos insuficientes para comprar este item.'; break;
                case 2: $msg_txt = 'Item comprado com sucesso! Verifique seu inventário.'; break;
                case 3: $msg_txt = 'Taijutsu insuficiente para comprar este item.'; break;
                case 4: $msg_txt = 'Nível insuficiente para desbloquear este personagem.'; break;
                case 6: $msg_txt = 'Genjutsu insuficiente para comprar este item.'; break;
                case 7: $msg_txt = 'Atributos insuficientes para comprar este item.'; break;
                case 8: $msg_txt = 'Você já possui este item no seu inventário!'; break;
                case 9: $msg_txt = 'Este item requer VIP ativo para ser adquirido.'; break;
            }
            if($msg_txt) echo '<div class="aviso" style="margin-bottom: 20px;">' . $msg_txt . '</div>';
        }
        ?>

        <!-- CATEGORIAS -->
        <div style="display: flex; gap: 10px; margin-bottom: 25px; flex-wrap: wrap;">
            <?php 
                $curr_cat = isset($_GET['category']) ? $_GET['category'] : 'weapons'; 
                $cats = [
                    'weapons' => '⚔️ Armas',
                    'armors'  => '🛡️ Vestimentas',
                    'boots'   => '👞 Calçados',
                    'hp'      => '🧪 Consumíveis',
                    'creds'   => '🎫 Créditos',
                    'yens'    => '💰 Yens'
                ];
                foreach($cats as $slug => $label):
            ?>
                <a href="?p=cla_shop&category=<?php echo $slug; ?>" class="modern-btn" style="<?php echo $curr_cat == $slug ? 'background: #a33; border-color: #f55;' : ''; ?>">
                    <?php echo $label; ?>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- LISTAGEM DE ITENS -->
        <div id="shop_content">
            <?php
            $cat_file = 'cla_shop' . $curr_cat . '.php';
            if(file_exists("_inc/$cat_file")){
                require_once($cat_file);
            } else {
                echo '<div class="aviso">Categoria não encontrada ou em manutenção.</div>';
            }
            ?>
        </div>
    </div>
</div>