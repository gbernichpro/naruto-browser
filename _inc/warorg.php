<?php
// Verificação de permissão básica (deve pertencer a um clã)
if($db['orgid'] == 0){
    echo "<script>self.location='?p=home'</script>";
    exit();
}

// Carregar dados da organização
$stmt_o = mysqli_prepare($mysqli_link, "SELECT * FROM organizacoes WHERE id=?");
mysqli_stmt_bind_param($stmt_o, "i", $db['orgid']);
mysqli_stmt_execute($stmt_o);
$res_o = mysqli_stmt_get_result($stmt_o);
$dbo = mysqli_fetch_assoc($res_o);

if(!$dbo){
    echo "<script>self.location='?p=home'</script>";
    exit();
}

// Verificação de posição do membro
$stmt_v = mysqli_prepare($mysqli_link, "SELECT posicao FROM membros WHERE usuarioid=? AND orgid=? AND status='sim'");
mysqli_stmt_bind_param($stmt_v, "ii", $db['id'], $db['orgid']);
mysqli_stmt_execute($stmt_v);
$res_v = mysqli_stmt_get_result($stmt_v);
$dbv = mysqli_fetch_assoc($res_v);
$mypos = $dbv ? $dbv['posicao'] : 3;

if(empty($_GET['m']))
	$_GET['m'] = 'wars';

$allow_modes = array("declare","wars"/*,"archive"*/);
?>

<div class="modern-card">
    <div class="modern-card-header">
        Guerras do Clã: [<?php echo $dbo['sigla']; ?>] <?php echo $dbo['nome']; ?>
    </div>
    
    <!-- MENU SECUNDÁRIO UNIFICADO -->
    <div style="background: rgba(0,0,0,0.3); padding: 10px; border-bottom: 1px solid #444; display: flex; gap: 10px; overflow-x: auto;">
        <a href="?p=myorg" class="modern-btn">Info</a>
        <?php if($mypos < 3) { ?>
        <a href="?p=configorg" class="modern-btn">Configurar</a>
        <a href="?p=addorg" class="modern-btn">Recrutar</a>
        <?php } ?>
        <a href="?p=donateorg" class="modern-btn">Doar Yens</a>
        <a href="?p=warorg" class="modern-btn active">Guerras</a>
        <a href="?p=cla_shop" class="modern-btn">Loja</a>
        <a href="?p=investimentos" class="modern-btn">Investimentos</a>
    </div>

    <!-- SUB-MENU DE GUERRA -->
    <div style="background: rgba(255,255,255,0.05); padding: 5px 15px; border-bottom: 1px solid #333; display: flex; gap: 15px; font-size: 13px;">
        <a href="?p=warorg&m=wars" style="color: <?php echo $_GET['m'] == 'wars' ? '#ff0' : '#aaa'; ?>; text-decoration: none; font-weight: bold;">
            ⚔️ Guerras Atuais
        </a>
        <a href="?p=warorg&m=declare" style="color: <?php echo $_GET['m'] == 'declare' ? '#ff0' : '#aaa'; ?>; text-decoration: none; font-weight: bold;">
            📜 Declarar Guerra
        </a>
    </div>

    <div class="modern-card-body">
        <?php
            if(in_array($_GET['m'], $allow_modes)){
                require_once("warorg_".$_GET['m'].".php");
            } else {
                echo '<div class="aviso">Módulo não encontrado.</div>';
            }
        ?>
    </div>
</div>