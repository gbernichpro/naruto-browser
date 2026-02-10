<?php
if($db['orgid'] == 0){ echo "<script>self.location='?p=home'</script>"; exit(); }

// Carregar dados da organização
$id_org = $db['orgid'];
$stmt_o = mysqli_prepare($mysqli_link, "SELECT nome, sigla, reserva, liderid FROM organizacoes WHERE id=?");
mysqli_stmt_bind_param($stmt_o, "i", $id_org);
mysqli_stmt_execute($stmt_o);
$res_o = mysqli_stmt_get_result($stmt_o);
$dbo = mysqli_fetch_assoc($res_o);

// Pegar posição do usuário
$stmt_p = mysqli_prepare($mysqli_link, "SELECT posicao FROM membros WHERE usuarioid=? AND orgid=?");
mysqli_stmt_bind_param($stmt_p, "ii", $db['id'], $id_org);
mysqli_stmt_execute($stmt_p);
$res_p = mysqli_stmt_get_result($stmt_p);
$dbp = mysqli_fetch_assoc($res_p);
$mypos = $dbp ? $dbp['posicao'] : 3;

// Processar doação
if(isset($_POST['don'])){
    $yens = isset($_POST['don_yens']) ? (int)floor($_POST['don_yens']) : 0;
    
    if($yens > 0){
        // Valida se o usuário tem o dinheiro
        if($yens > $db['yens']){
            echo "<script>self.location='?p=donateorg&msg=2'</script>"; 
            exit();
        }

        $pontos_cla = floor($yens / 1000);

        // Transação de doação
        mysqli_begin_transaction($mysqli_link);
        try {
            // 1. Desconta do usuário
            $stmt_u = mysqli_prepare($mysqli_link, "UPDATE usuarios SET yens = yens - ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt_u, "ii", $yens, $db['id']);
            mysqli_stmt_execute($stmt_u);

            // 2. Soma na reserva da org
            $stmt_org = mysqli_prepare($mysqli_link, "UPDATE organizacoes SET reserva = reserva + ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt_org, "ii", $yens, $id_org);
            mysqli_stmt_execute($stmt_org);

            // 3. Atualiza registro de doação do membro
            $stmt_m = mysqli_prepare($mysqli_link, "UPDATE membros SET doado = doado + ? WHERE orgid = ? AND usuarioid = ?");
            mysqli_stmt_bind_param($stmt_m, "iii", $yens, $id_org, $db['id']);
            mysqli_stmt_execute($stmt_m);

            // 4. Concede pontos de clã se houver
            if($pontos_cla > 0){
                $stmt_p_cla = mysqli_prepare($mysqli_link, "UPDATE usuarios SET pontoscla = pontoscla + ? WHERE id = ?");
                mysqli_stmt_bind_param($stmt_p_cla, "ii", $pontos_cla, $db['id']);
                mysqli_stmt_execute($stmt_p_cla);
            }

            mysqli_commit($mysqli_link);
            echo "<script>self.location='?p=donateorg&msg=1&yens=$yens'</script>";
            exit();
        } catch (Exception $e) {
            mysqli_rollback($mysqli_link);
            die("Erro crítico na doação: " . $e->getMessage());
        }
    }
}
?>

<div class="modern-card">
    <div class="modern-card-header">
        Doar para o Clã: [<?php echo $dbo['sigla']; ?>] <?php echo $dbo['nome']; ?>
    </div>

    <!-- MENU SECUNDÁRIO UNIFICADO -->
    <div style="background: rgba(0,0,0,0.3); padding: 10px; border-bottom: 1px solid #444; display: flex; gap: 10px; overflow-x: auto;">
        <a href="?p=myorg" class="modern-btn">Info</a>
        <?php if($mypos < 3) { ?>
        <a href="?p=configorg" class="modern-btn">Configurar</a>
        <a href="?p=addorg" class="modern-btn">Recrutar</a>
        <?php } ?>
        <a href="?p=donateorg" class="modern-btn active">Doar Yens</a>
        <a href="?p=warorg" class="modern-btn">Guerras</a>
        <a href="?p=cla_shop" class="modern-btn">Loja</a>
        <a href="?p=investimentos" class="modern-btn">Investimentos</a>
    </div>

    <div class="modern-card-body">
        <?php
        if(isset($_GET['msg'])){
            $msg_txt = '';
            switch($_GET['msg']){
                case 1: 
                    $y_doado = isset($_GET['yens']) ? (int)$_GET['yens'] : 0;
                    $msg_txt = 'Obrigado! Foram doados <b>' . number_format($y_doado, 0, ',', '.') . ' yens</b> para o seu clã.'; 
                    break;
                case 2: 
                    $msg_txt = 'Você não possui a quantia de yens informada.'; 
                    break;
            }
            if($msg_txt) echo '<div class="aviso" style="margin-bottom: 20px;">' . $msg_txt . '</div>';
        }
        ?>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
            <!-- SALDO USUÁRIO -->
            <div style="background: rgba(0,0,0,0.2); padding: 20px; border-radius: 8px; border: 1px solid #333; text-align: center;">
                <span style="font-size: 13px; color: #777; display: block; margin-bottom: 5px;">Seu Saldo</span>
                <span style="font-size: 20px; color: #fff; font-weight: bold;">
                    <img src="_img/yens.png" width="16" style="vertical-align: middle;"> 
                    <?php echo number_format($db['yens'], 0, ',', '.'); ?>
                </span>
            </div>

            <!-- RESERVA CLÃ -->
            <div style="background: rgba(255,215,0,0.05); padding: 20px; border-radius: 8px; border: 1px solid rgba(255,215,0,0.2); text-align: center;">
                <span style="font-size: 13px; color: #aaa; display: block; margin-bottom: 5px;">Reserva do Clã</span>
                <span style="font-size: 20px; color: #ffd700; font-weight: bold;">
                    <img src="_img/yens.png" width="16" style="vertical-align: middle;"> 
                    <?php echo number_format($dbo['reserva'], 0, ',', '.'); ?>
                </span>
            </div>
        </div>

        <div style="background: rgba(255,255,255,0.02); padding: 20px; border-radius: 8px; border: 1px solid #222; margin-bottom: 30px;">
            <p style="color: #ccc; font-size: 14px; line-height: 1.6; margin: 0;">
                Para que o clã cresça, é necessário que os membros ajudem com um empurrãozinho financeiro! Doe yens para seu clã, para que o mesmo possa ampliar suas instalações e garantir benefícios para todos.
            </p>
            <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #333; display: flex; align-items: center; gap: 10px;">
                <span style="background: #a33; color: #fff; padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: bold;">BÔNUS</span>
                <span style="color: #ffd700; font-size: 12px; font-weight: bold;">
                    Cada 1.000 Yens doados = 1 Ponto de Contribuição (Pts de Clã)
                </span>
            </div>
        </div>

        <form method="post" action="?p=donateorg" onsubmit="var b=this.querySelector('input[type=submit]'); if(b) { b.value='Processando Doação...'; b.disabled=true; }">
            <input type="hidden" id="don" name="don" value="1" />
            
            <div style="max-width: 400px; margin: 0 auto;">
                <label class="destaque" style="display: block; text-align: center; margin-bottom: 15px; font-size: 16px;">Quantia para Doação:</label>
                <div style="position: relative; display: flex; align-items: center; background: #111; border: 1px solid #444; border-radius: 4px; padding: 5px 15px;">
                    <img src="_img/yens.png" width="20" style="margin-right: 10px;">
                    <input type="number" id="don_yens" name="don_yens" placeholder="Digite apenas números..." min="1" max="<?php echo (int)$db['yens']; ?>" step="1" required style="width: 100%; padding: 10px; background: transparent; border: none; color: #fff; font-size: 18px; outline: none;">
                </div>
                <span class="sub2" style="display: block; text-align: center; margin-top: 8px; font-size: 11px; color: #555;">Dica: Doações em múltiplos de 1.000 garantem pontos extras!</span>

                <div style="margin-top: 30px; text-align: center;">
                    <input type="submit" id="subm" name="subm" class="modern-btn" style="background: #282; padding: 15px 40px; font-size: 18px; font-weight: bold; cursor: pointer; width: 100%;" value="Realizar Doação">
                    <br><br>
                    <a href="?p=myorg" style="color: #777; font-size: 13px; text-decoration: none;">← Voltar para Informações</a>
                </div>
            </div>
        </form>
    </div>
</div>