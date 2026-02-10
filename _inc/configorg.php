<?php
// Verificação de permissão (apenas líderes e conselheiros)
$stmt_v = mysqli_prepare($mysqli_link, "SELECT posicao FROM membros WHERE usuarioid=? AND orgid=? AND status='sim'");
mysqli_stmt_bind_param($stmt_v, "ii", $db['id'], $db['orgid']);
mysqli_stmt_execute($stmt_v);
$res_v = mysqli_stmt_get_result($stmt_v);
$dbv = mysqli_fetch_assoc($res_v);

if(!$dbv || $dbv['posicao'] == 3){ 
    echo "<script>self.location='?p=myorg'</script>"; 
    exit(); 
}

// Carregar dados da organização
$stmt_c = mysqli_prepare($mysqli_link, "SELECT * FROM organizacoes WHERE id=?");
mysqli_stmt_bind_param($stmt_c, "i", $db['orgid']);
mysqli_stmt_execute($stmt_c);
$res_c = mysqli_stmt_get_result($stmt_c);
$dbc = mysqli_fetch_assoc($res_c);

// Processar formulário
if(isset($_POST['org'])){
	$logo = isset($_POST['org_logo']) ? trim($_POST['org_logo']) : '';
	$minimo = isset($_POST['org_nivel']) ? (int)$_POST['org_nivel'] : 0;
	$desc = isset($_POST['org_desc']) ? $_POST['org_desc'] : '';
    
	if($minimo < 0) $minimo = 0;
    
    $stmt_u = mysqli_prepare($mysqli_link, "UPDATE organizacoes SET descricao=?, minimo=?, logo=? WHERE id=?");
    mysqli_stmt_bind_param($stmt_u, "sisi", $desc, $minimo, $logo, $dbc['id']);
    
    if(mysqli_stmt_execute($stmt_u)){
        echo "<script>self.location='?p=configorg&msg=1'</script>";
    } else {
        echo "<script>alert('Erro ao salvar: ".mysqli_error($mysqli_link)."');</script>";
    }
    exit();
}

$mypos = $dbv['posicao'];
?>

<div class="modern-card">
    <div class="modern-card-header">
        Configurar Clã: [<?php echo $dbc['sigla']; ?>] <?php echo $dbc['nome']; ?>
    </div>
    
    <!-- MENU SECUNDÁRIO UNIFICADO -->
    <div style="background: rgba(0,0,0,0.3); padding: 10px; border-bottom: 1px solid #444; display: flex; gap: 10px; overflow-x: auto;">
        <a href="?p=myorg" class="modern-btn">Info</a>
        <?php if($mypos < 3) { ?>
        <a href="?p=configorg" class="modern-btn active">Configurar</a>
        <a href="?p=addorg" class="modern-btn">Recrutar</a>
        <?php } ?>
        <a href="?p=donateorg" class="modern-btn">Doar Yens</a>
        <a href="?p=warorg" class="modern-btn">Guerras</a>
        <a href="?p=cla_shop" class="modern-btn">Loja</a>
        <a href="?p=investimentos" class="modern-btn">Investimentos</a>
    </div>

    <div class="modern-card-body">
        <?php
        if(isset($_GET['msg'])){
            $msg_txt = '';
            switch($_GET['msg']){
                case 1: $msg_txt = 'Configurações salvas com sucesso!'; break;
            }
            if($msg_txt) echo '<div class="aviso" style="margin-bottom: 15px;">'.$msg_txt.'</div>';
        }
        ?>

        <form method="post" action="?p=configorg" onsubmit="var b=this.querySelector('input[type=submit]'); if(b) { b.value='Salvando...'; b.disabled=true; }">
            <input type="hidden" id="org" name="org" value="1">
            
            <div style="display: flex; flex-direction: column; gap: 20px;">
                <!-- BLOCO IDENTIDADE -->
                <div style="background: rgba(0,0,0,0.2); padding: 15px; border-radius: 8px; border: 1px solid #333;">
                    <h3 style="color: #fff; margin-bottom: 15px; font-size: 16px; display: flex; align-items: center; gap: 10px;">
                        <span style="color: gold;">🖼️</span> Identidade Visual
                    </h3>
                    
                    <div style="margin-bottom: 10px;">
                        <label class="destaque" style="display: block; margin-bottom: 5px;">URL do Logotipo:</label>
                        <input type="text" id="org_logo" name="org_logo" value="<?php echo htmlspecialchars($dbc['logo']); ?>" style="width: 100%; padding: 10px; background: #111; border: 1px solid #444; color: #fff; border-radius: 4px;">
                        <span class="sub2" style="font-size: 11px; color: #777; margin-top: 4px; display: block;">Tamanho ideal: 195x140 pixels.</span>
                    </div>

                    <div style="margin-top: 15px; text-align: center;">
                        <span style="font-size: 11px; color: #555;">Prévia Atual:</span><br>
                        <img src="<?php echo ($dbc['logo']=='') ? '_img/org/no_logo.png' : $dbc['logo']; ?>" style="max-width: 150px; border-radius: 5px; margin-top: 5px; border: 1px solid #333;">
                    </div>
                </div>

                <!-- BLOCO RECRUTAMENTO -->
                <div style="background: rgba(0,0,0,0.2); padding: 15px; border-radius: 8px; border: 1px solid #333;">
                    <h3 style="color: #fff; margin-bottom: 15px; font-size: 16px; display: flex; align-items: center; gap: 10px;">
                        <span style="color: #5af;">⚔️</span> Requisitos de Entrada
                    </h3>
                    
                    <div>
                        <label class="destaque" style="display: block; margin-bottom: 5px;">Nível Mínimo para Recrutamento:</label>
                        <input type="number" id="org_nivel" name="org_nivel" value="<?php echo $dbc['minimo']; ?>" min="1" max="100" style="width: 80px; padding: 10px; background: #111; border: 1px solid #444; color: #fff; border-radius: 4px;">
                        <span class="sub2" style="font-size: 11px; color: #777; margin-top: 4px; display: block;">Membros abaixo deste nível não poderão ser recrutados.</span>
                    </div>
                </div>

                <!-- BLOCO DESCRIÇÃO -->
                <div style="background: rgba(0,0,0,0.2); padding: 15px; border-radius: 8px; border: 1px solid #333;">
                    <h3 style="color: #fff; margin-bottom: 15px; font-size: 16px; display: flex; align-items: center; gap: 10px;">
                        <span style="color: #a5f;">📜</span> Manifesto do Clã
                    </h3>
                    
                    <textarea id="org_desc" name="org_desc" style="width:100%; height:200px; padding: 10px; background: #111; border: 1px solid #444; color: #ccc; border-radius: 4px; resize: vertical; font-family: inherit;"><?php echo htmlspecialchars($dbc['descricao']); ?></textarea>
                    <span class="sub2" style="font-size: 11px; color: #777; margin-top: 4px; display: block;">Esta descrição será exibida publicamente na página do clã.</span>
                </div>
            </div>

            <div style="margin-top: 30px; text-align: center;">
                <input type="submit" id="subm" name="subm" class="modern-btn" style="background: #282; padding: 12px 30px; font-size: 16px; font-weight: bold; cursor: pointer;" value="Salvar Alterações">
                <br><br>
                <a href="?p=myorg" style="color: #777; font-size: 13px; text-decoration: none;">← Voltar para Informações</a>
            </div>
        </form>
    </div>
</div>