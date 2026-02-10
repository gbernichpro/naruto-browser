<?php
require_once('trava.php');
require_once('Encrypt.php');
$c = new C_Encrypt();

// --- Processamento de COMPRA DE PERSONAGEM ---
if(isset($_POST['char_id'])){
	$buy = $c->decode($_POST['char_id'],$chaveuniversal);
	$nivel = $c->decode($_POST['char_nivel'],$chaveuniversal);
	vn($buy); vn($nivel);
	$personagem = $c->decode($_POST['char_char'],$chaveuniversal);
    
    // Validação de Nível
	if($db['nivel']<$nivel){ echo "<script>self.location='?p=shop&category=characters&msg=4'</script>"; exit(); }
    
    // Atualização mysqli
    $personagem_safe = mysqli_real_escape_string($mysqli_link, $personagem);
	mysqli_query($mysqli_link, "UPDATE personagens SET ".$personagem_safe."=1 WHERE usuarioid='".$db['id']."'");
    echo "<script>self.location='?p=home&msggg=1'</script>";
}

// --- Processamento de COMPRA DE ITENS ---
if(isset($_POST['buy_id'])){
	$buy = isset($_POST['buy_id']) ? mysqli_real_escape_string($mysqli_link, $_POST['buy_id']) : 0;
	// vn($buy); // vn geralmente verifica numero, pode manter se for segura
	if($buy<1){ echo "<script>self.location='?p=home'</script>"; exit(); }
    
	$category = isset($_POST['buy_cat']) ? mysqli_real_escape_string($mysqli_link, $_POST['buy_cat']) : '';
    
    // Validação de Categoria Segura
	if(($category<>'arma')&&($category<>'vestimenta')&&($category<>'calcado')&&($category<>'bijuu')&&($category<>'acessorios')&&($category<>'bolsas')){
        $data=date('Y-m-d H:i:s');
        $usuario=$db['usuario'];
        $msg='Usuario tentou burlar o sistema do Shop normal mudando a categoria , o item não foi inserido na conta do usuario.';
        mysqli_query($mysqli_link, "INSERT INTO log_bugs (data,usuario,msg)"."VALUES ('".$data."','".$usuario."','".$msg."')");
        echo "<script>self.location='?p=home'</script>"; exit(); 
    }
    
	switch($category){
		case 'arma': $sqlb = mysqli_query($mysqli_link, "SELECT valor, reqtai,reqnivel, vip,categoria,credshop FROM table_itens WHERE id='".$buy."'"); break;
		case 'vestimenta': $sqlb = mysqli_query($mysqli_link, "SELECT valor, reqgen,reqnivel, vip,categoria,credshop FROM table_itens WHERE id='".$buy."'"); break;
		case 'calcado': $sqlb = mysqli_query($mysqli_link, "SELECT valor, reqtai, vip,categoria,credshop FROM table_itens WHERE id='".$buy."'"); break;
        case 'bijuu': $sqlb = mysqli_query($mysqli_link, "SELECT valor, reqnin,reqnivel, vip,categoria,credshop FROM table_itens WHERE id='".$buy."'"); break;
        case 'acessorios': $sqlb = mysqli_query($mysqli_link, "SELECT valor, reqnivel, vip,categoria,credshop FROM table_itens WHERE id='".$buy."'"); break;
        case 'bolsas': $sqlb = mysqli_query($mysqli_link, "SELECT valor,categoria,credshop FROM table_bolsas WHERE id='".$buy."'"); break;
        default: echo "<script>self.location='?p=home'</script>"; exit();
	}
    
	if(mysqli_num_rows($sqlb)==0){ echo "<script>self.location='?p=home'</script>"; exit(); }
	$dbb = mysqli_fetch_assoc($sqlb);
    
    // Validação VIP
	if(($dbb['vip']=='sim')&&(date('Y-m-d H:i:s')>=$db['vip'])){ echo "<script>self.location='?p=home'</script>"; exit(); }
	if($category<>$dbb['categoria']){ echo "<script>self.location='?p=home'</script>"; exit(); }
    
	$valor = $dbb['valor'];
	if($category=='bolsas') {
	    $valor = floor($valor);
	} elseif(date('Y-m-d H:i:s')<$db['vip']) {
        $valor = floor($valor*(0.8)); // Desconto VIP
    }
    
	if(($db['renegado']=='nao')&&($buy==1)){ echo "<script>self.location='?p=home'</script>"; exit(); } // Bloqueio específico (item id 1 pra não renegado?)
    
	$page = isset($_POST['buy_page']) ? mysqli_real_escape_string($mysqli_link, $_POST['buy_page']) : 'weapons';
	$bloq = 0;
    
    // Validação de Requisitos
	if($category=='acessorios')
		if($db['nivel']<$dbb['reqnivel']) $bloq=9;
	// if($category=='bolsas'); // Lógica vazia no original
	if($category=='arma')
		if($db['nivel']<$dbb['reqnivel']) $bloq=9;
	if($category=='vestimenta')
		if($db['nivel']<$dbb['reqnivel']) $bloq=9;
	if($category=='calcado'){
		if($db['taijutsu']<$dbb['reqtai']) $bloq=7; // Original verificava taijutsu < reqtai
		if($db['ninjutsu']<$dbb['reqtai']) $bloq=7; // Original verificava ninjutsu < reqtai (mesmo campo reqtai usado pra tudo?)
		if($db['genjutsu']<$dbb['reqtai']) $bloq=7; // Original verificava genjutsu < reqtai
	}
	if($category=='bijuu')
		if($db['nivel']<$dbb['reqnivel']) $bloq=9;
	
	if($bloq>0){ echo "<script>self.location='?p=shop&category=".$page."&msg=".$bloq."'</script>"; exit(); }
	if($db['yens']<$valor){ echo "<script>self.location='?p=shop&category=".$page."&msg=1'</script>"; exit(); }
	
    // Verifica se já possui
	if($category<>'bolsas'){
	    $sqli = mysqli_query($mysqli_link, "SELECT count(id) conta FROM inventario WHERE usuarioid='".$db['id']."' AND itemid='".$buy."'");
        $dbi=mysqli_fetch_assoc($sqli);
	    if($dbi['conta']>0){ echo "<script>self.location='?p=shop&category=".$page."&msg=8'</script>"; exit(); }
	} else {
	    $sqli2=mysqli_query($mysqli_link, "SELECT count(id) conta FROM bolsas WHERE usuarioid='".$db['id']."' AND itemid='".$buy."'");
        $dbi2=mysqli_fetch_assoc($sqli2);
	    if($dbi2['conta']>0){ echo "<script>self.location='?p=shop&category=".$page."&msg=8'</script>"; exit(); }
    }
    
    // Efetua Compra
    mysqli_query($mysqli_link, "UPDATE usuarios SET yens=yens-".$valor." WHERE id='".$db['id']."'");
    
	if($category=='bolsas'){
        mysqli_query($mysqli_link, "INSERT INTO bolsas (usuarioid,itemid,categoria) VALUES (".$db['id'].",".$buy.",'".$category."')");
	    echo "<script>self.location='?p=shop&category=".$page."&msg=2'</script>";
	}else{
	    mysqli_query($mysqli_link, "INSERT INTO inventario (usuarioid,itemid,categoria) VALUES (".$db['id'].",".$buy.",'".$category."')");
	    echo "<script>self.location='?p=shop&category=".$page."&msg=2'</script>";
	}
}
?>

<div class="modern-card">
    <div class="modern-card-header">Comércio da Vila</div>
    <div class="modern-card-body">
        
        <div style="display: flex; gap: 20px; align-items: center; background: rgba(0,0,0,0.2); padding: 20px; border-radius: 8px; border: 1px solid var(--border-subtle); margin-bottom: 20px;">
            <img width="80" src="_img/_detalhes/msg/28.png" style="filter: drop-shadow(0 0 10px rgba(255,165,0,0.4));">
            <div>
                <h3 style="color: var(--primary-yellow); margin: 0 0 10px 0; font-family: var(--font-header);">Bem-vindo ao Mercado!</h3>
                <p style="color: var(--text-dim); margin: 0; font-size: 13px; line-height: 1.5;">
                    Aqui você encontra tudo o que precisa para sua jornada ninja.
                </p>
                <?php if(date('Y-m-d H:i:s')<$db['vip']) echo '<p style="color: gold; margin-top: 5px; font-weight: bold; font-size: 12px;">★ VIP ATIVO: 20% de Desconto aplicado!</p>'; ?>
            </div>
        </div>

        <div style="padding: 10px; border-bottom: 1px solid rgba(255,255,255,0.05); margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center;">
            <span style="color: var(--text-dim); font-size: 12px;"><img src="_img/yens.png" width="14" align="absmiddle" /> Meus Yens:</span>
            <b style="color: #fff;"><?php echo number_format($db['yens'],2,',','.'); ?> yens</b>
        </div>

        <?php
        if(isset($_GET['msg'])){
            $msg = '';
            switch($_GET['msg']){
                case 1: $msg='Yens insuficientes.'; break;
                case 2: $msg='Item comprado com sucesso! Verifique seu <a href="?p=inventory" style="color: gold;">inventário</a>.'; break;
                case 3: $msg='Taijutsu insuficiente.'; break;
                case 4: $msg='Nível insuficiente (Personagem).'; break;
                case 5: $msg='Personagem desbloqueado!'; break;
                case 6: $msg='Genjutsu insuficiente.'; break;
                case 7: $msg='Atributos insuficientes.'; break;
                case 8: $msg='Você já possui este item!'; break;
                case 9: $msg='Nível insuficiente (Item).'; break;
            }
            if($msg) echo '<div class="aviso" style="margin-bottom: 15px;">'.$msg.'</div>';
        }
        ?>

        <!-- NAVEGAÇÃO / ABAS -->
        <div style="display: flex; gap: 10px; overflow-x: auto; padding-bottom: 10px; margin-bottom: 20px; border-bottom: 1px solid #444;">
            <a href="?p=shop&category=weapons" class="modern-btn <?php echo (!isset($_GET['category']) || $_GET['category']=='weapons') ? 'active' : ''; ?>">Armas</a>
            <a href="?p=shop&category=armors" class="modern-btn <?php echo (isset($_GET['category']) && $_GET['category']=='armors') ? 'active' : ''; ?>">Vestimentas</a>
            <a href="?p=shop&category=boots" class="modern-btn <?php echo (isset($_GET['category']) && $_GET['category']=='boots') ? 'active' : ''; ?>">Calçados</a>
            <a href="?p=shop&category=acessorios" class="modern-btn <?php echo (isset($_GET['category']) && $_GET['category']=='acessorios') ? 'active' : ''; ?>">Acessórios</a>
            <a href="?p=shop&category=bijuu" class="modern-btn <?php echo (isset($_GET['category']) && $_GET['category']=='bijuu') ? 'active' : ''; ?>">Bijuus</a>
            <a href="?p=shop&category=bolsas" class="modern-btn <?php echo (isset($_GET['category']) && $_GET['category']=='bolsas') ? 'active' : ''; ?>">Mochilas</a>
            <a href="?p=shop&category=characters" class="modern-btn <?php echo (isset($_GET['category']) && $_GET['category']=='characters') ? 'active' : ''; ?>">Personagens</a>
        </div>

        <!-- Conteúdo da Categoria -->
        <div id="shop-content">
            <?php
            if(!isset($_GET['category'])) require_once('shop_weapons.php'); else
            if(isset($_GET['category'])){
                switch($_GET['category']){
                    case 'weapons': require_once('shop_weapons.php'); break;
                    case 'armors': require_once('shop_armors.php'); break;
                    case 'characters': require_once('shop_characters.php'); break;
                    case 'boots': require_once('shop_boots.php'); break;
                    case 'bijuu': require_once('shop_bijuu.php'); break;
                    case 'acessorios': require_once('shop_acessorios.php'); break;
                    case 'bolsas': require_once('shop_bolsas.php'); break;
                    default: require_once('shop_weapons.php'); break;
                }
            }
            ?>
        </div>
        
    </div>
</div>