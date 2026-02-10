<?php
// Validações Iniciais
if($db['missao']>0){ echo "<script>self.location='?p=busymission'</script>"; exit(); }
if($db['orgid']==0){ echo "<script>self.location='?p=home'</script>"; exit(); }

$id_org = $db['orgid']; 

// --- AUTO-REPAIR LOGIC ---
// Verifica se a organização existe
$check_org_exist = mysqli_query($mysqli_link, "SELECT liderid FROM organizacoes WHERE id='".$id_org."'") or die("ERROR Q1: " . mysqli_error($mysqli_link));

if(mysqli_num_rows($check_org_exist) == 0){
    // Org não existe (Phantom ID). Resetar usuário.
    mysqli_query($mysqli_link, "UPDATE usuarios SET orgid=0 WHERE id='".$db['id']."'");
    echo "<script>alert('Sua organização não existe mais. status resetado.'); self.location='?p=home'</script>"; exit();
}

// Verifica se o usuário está na tabela membros
$check_member = mysqli_query($mysqli_link, "SELECT id FROM membros WHERE usuarioid='".$db['id']."' AND orgid='".$id_org."'") or die("ERROR Q_MBR: " . mysqli_error($mysqli_link));

if(mysqli_num_rows($check_member) == 0){
    // Inconsistência detectada! Reparar.
    $row_org_c = mysqli_fetch_assoc($check_org_exist);
    
    $cargo = 'Membro';
    $posicao = 3;
    // Se ele é o dono da org, vira Líder
    if($row_org_c['liderid'] == $db['id']){
        $cargo = 'Lider'; 
        $posicao = 1;
    }
    
    $insert_q = "INSERT INTO membros (orgid,usuarioid,posicao,`rank`,doado,status) VALUES ('$id_org','".$db['id']."','$posicao','$cargo',0,'sim')";
    if(!mysqli_query($mysqli_link, $insert_q)){
        die("ERRO AO REPARAR MEMBRO: " . mysqli_error($mysqli_link) . "<br>Contate o administrador.");
    }
    
    // Refaz o load da página para pegar o insert
    echo "<script>self.location='?p=myorg&msg=repair'</script>"; exit();
} else {
    // Usuário já está na tabela membros. Verificar se ele é o Líder real mas está com cargo errado.
    $check_org_l = mysqli_query($mysqli_link, "SELECT liderid FROM organizacoes WHERE id='".$id_org."'");
    $row_org_l = mysqli_fetch_assoc($check_org_l);
    
    if($row_org_l['liderid'] == $db['id']){
        // É o dono! Forçar posição 1 na tabela membros.
        mysqli_query($mysqli_link, "UPDATE membros SET posicao=1, `rank`='Lider' WHERE usuarioid='".$db['id']."' AND orgid='".$id_org."'");
    }
}
// -------------------------

// Query Principal Org
$sqlo = mysqli_query($mysqli_link, "SELECT * FROM organizacoes WHERE id=".$id_org) or die("ERROR Q_ORG: " . mysqli_error($mysqli_link));
if(mysqli_num_rows($sqlo)==0){ echo "<script>self.location='?p=home'</script>"; exit(); }
$dbo = mysqli_fetch_assoc($sqlo);

// FIX MANUAL
if(isset($_GET['fix']) && $_GET['fix']=='leader'){
    if($dbo['liderid'] == $db['id']){
        mysqli_query($mysqli_link, "UPDATE membros SET posicao=1, `rank`='Lider' WHERE usuarioid='".$db['id']."' AND orgid='".$id_org."'");
        echo "<script>alert('Liderança Forçada com Sucesso! Agora você deve ter acesso.'); self.location='?p=myorg';</script>"; exit();
    } else {
        echo "<script>alert('ERRO: Você não consta como Líder na tabela de Organizações (ID atual do Líder: ".$dbo['liderid'].").'); self.location='?p=myorg';</script>"; exit();
    }
}

// Processar Lógica de Exp da Org (Level Up)
if($dbo['exp']>=$dbo['expmax']){
	$dif = $dbo['exp'] - $dbo['expmax'];
    // Trava check nível 60
    if($dbo['nivel'] >= 60) {
        $update_org = "UPDATE organizacoes SET nivel=60, exp=0, expmax=99999 WHERE id=".$dbo['id'];
        $dbo['nivel'] = 60;
    } else {
    	$update_org = "UPDATE organizacoes SET exp=$dif, expmax=expmax+10, nivel=nivel+1 WHERE id=".$dbo['id'];
    	$dbo['nivel']++;
    	// $dbo['exp']=$dif; // Visual update only
    }
    mysqli_query($mysqli_link, $update_org);
}

// Processar Saída/Deletar (Logica simplificada get)
if(isset($_GET['del'])){
	$id_del = isset($_GET['del']) ? (int)$c->decode($_GET['del'],$chaveuniversal) : 0;
	$sqld = mysqli_query($mysqli_link, "SELECT usuarioid, orgid FROM membros WHERE id=".$id_del) or die("ERROR Q_DEL_CHECK: " . mysqli_error($mysqli_link));
    if(mysqli_num_rows($sqld) > 0){
    	$dbd = mysqli_fetch_assoc($sqld);
    	// Validações de segurança
    	if($dbd['orgid'] == $db['orgid'] && $dbo['liderid'] == $db['id']){
        	mysqli_query($mysqli_link, "DELETE FROM membros WHERE id=".$id_del);
        	mysqli_query($mysqli_link, "UPDATE usuarios SET orgid=0 WHERE id=".$dbd['usuarioid']);
        	
        	// Enviar mensagem
        	$msg_sys = "Infelizmente o Administrador do seu clã lhe expulsou do mesmo.<br />Procure um outro clã para não ficar em desvantagem.";
        	$msg_safe = mysqli_real_escape_string($mysqli_link, $msg_sys);
        	mysqli_query($mysqli_link, "INSERT INTO mensagens (data, origem, destino, assunto, msg) VALUES ('".date('Y-m-d H:i:s')."', 0, ".$dbd['usuarioid'].", 'Você foi expulso do clã!', '$msg_safe')");
        	
        	echo "<script>self.location='?p=myorg&msg=5'</script>"; exit();
    	}
    }
}

// Processar Doação
if(isset($_POST['donate'])){
	$valor = isset($_POST['don_valor']) ? (int)$_POST['don_valor'] : 0;
	if($valor > 0){
    	if($db['yens'] < $valor){ 
    	    echo "<script>self.location='?p=myorg&msg=12'</script>"; exit(); 
    	}
    	mysqli_query($mysqli_link, "UPDATE usuarios SET yens=yens-$valor WHERE id=".$db['id']);
    	mysqli_query($mysqli_link, "UPDATE organizacoes SET reserva=reserva+$valor WHERE id=".$id_org);
    	mysqli_query($mysqli_link, "UPDATE membros SET doado=doado+$valor WHERE usuarioid=".$db['id']); // Assumindo unicidade usuarioid/orgid
    	echo "<script>self.location='?p=myorg&msg=13'</script>"; exit();
	}
}

// --- LÓGICA DE INVESTIMENTOS ---
// Evoluir Investimento Existente
if(isset($_GET['evolve'])){
    // Pegar posição atual antes de tudo
    $sqle_c = mysqli_query($mysqli_link, "SELECT posicao FROM membros WHERE usuarioid=".$db['id']." AND orgid=".$id_org);
    $dbe_c = mysqli_fetch_assoc($sqle_c);
    $mypos_c = $dbe_c ? $dbe_c['posicao'] : 3;

    if($mypos_c == 3){ echo "<script>alert('Apenas Líder e Conselheiros podem evoluir investimentos.'); self.location='?p=investimentos'</script>"; exit(); }
    
    $id_evolve = (int)$_GET['evolve'];
    $sql_e = mysqli_query($mysqli_link, "SELECT i.*, t.custo as custo_base, t.tai, t.nin, t.gen FROM clas_investimentos i JOIN table_investimentos t ON i.invid=t.id WHERE i.id=$id_evolve AND i.orgid=$id_org");
    
    if($dbe_v = mysqli_fetch_assoc($sql_e)){
        $custo_evolve = $dbe_v['custo_base'] + ($dbe_v['nivel'] * 20000);
        if($dbo['reserva'] >= $custo_evolve){
            mysqli_query($mysqli_link, "UPDATE organizacoes SET reserva=reserva-$custo_evolve WHERE id=$id_org");
            mysqli_query($mysqli_link, "UPDATE clas_investimentos SET nivel=nivel+1, taijutsu=taijutsu+".$dbe_v['tai'].", ninjutsu=ninjutsu+".$dbe_v['nin'].", genjutsu=genjutsu+".$dbe_v['gen']." WHERE id=$id_evolve");
            echo "<script>alert('Investimento aprimorado com sucesso!'); self.location='?p=investimentos'</script>"; exit();
        } else {
            echo "<script>alert('Reserva do Clã insuficiente!'); self.location='?p=investimentos'</script>"; exit();
        }
    }
}

// Comprar Novo Investimento
if(isset($_GET['buy'])){
    // Pegar posição atual antes de tudo
    $sqlb_c = mysqli_query($mysqli_link, "SELECT posicao FROM membros WHERE usuarioid=".$db['id']." AND orgid=".$id_org);
    $dbb_c = mysqli_fetch_assoc($sqlb_c);
    $mypos_c = $dbb_c ? $dbb_c['posicao'] : 3;

    if($mypos_c == 3){ echo "<script>alert('Apenas Líder e Conselheiros podem comprar investimentos.'); self.location='?p=investimentos'</script>"; exit(); }
    
    $id_buy = (int)$_GET['buy'];
    $sql_b = mysqli_query($mysqli_link, "SELECT * FROM table_investimentos WHERE id=$id_buy");
    
    if($dbb_v = mysqli_fetch_assoc($sql_b)){
        // Verifica se já possui
        $check_has = mysqli_query($mysqli_link, "SELECT id FROM clas_investimentos WHERE invid=$id_buy AND orgid=$id_org");
        if(mysqli_num_rows($check_has) > 0){
             echo "<script>alert('Seu clã já possui este investimento!'); self.location='?p=investimentos'</script>"; exit();
        }
        
        if($dbo['reserva'] >= $dbb_v['custo']){
            mysqli_query($mysqli_link, "UPDATE organizacoes SET reserva=reserva-".$dbb_v['custo']." WHERE id=$id_org");
            mysqli_query($mysqli_link, "INSERT INTO clas_investimentos (orgid, invid, nivel, taijutsu, ninjutsu, genjutsu) VALUES ($id_org, $id_buy, 1, ".$dbb_v['tai'].", ".$dbb_v['nin'].", ".$dbb_v['gen'].")");
            echo "<script>alert('Investimento realizado com sucesso!'); self.location='?p=investimentos'</script>"; exit();
        } else {
            echo "<script>alert('Reserva do Clã insuficiente!'); self.location='?p=investimentos'</script>"; exit();
        }
    }
}
// -------------------------------

// Queries de Exibição
$order = isset($_GET['order']) ? 'ORDER BY missoes DESC, posicao ASC, niveluser DESC' : 'ORDER BY posicao ASC, niveluser DESC';
$sqlm = mysqli_query($mysqli_link, "SELECT m.*,u.usuario,u.nivel niveluser,u.pontoscla, u.timestamp FROM membros m LEFT OUTER JOIN usuarios u ON m.usuarioid=u.id WHERE m.status='sim' AND m.orgid=".$id_org." ".$order) or die("ERROR Q_MEMB: " . mysqli_error($mysqli_link));

// Dados do Usuário na Org
$sqle = mysqli_query($mysqli_link, "SELECT posicao FROM membros WHERE usuarioid=".$db['id']." AND orgid=".$id_org) or die("ERROR Q_ES: " . mysqli_error($mysqli_link));
$dbe = mysqli_fetch_assoc($sqle);
$mypos = $dbe ? $dbe['posicao'] : 3; // Default membro se falhar

// die("DEBUG: Mid-point reached. MyPos: " . $mypos);

$vilas = [
	1 => 'Vila da Folha', 2 => 'Vila da Areia', 3 => 'Vila do Som', 4 => 'Vila da Chuva',
	5 => 'Vila da Nuvem', 6 => 'Vila da Névoa', 7 => 'Akatsuki', 8 => 'Vila da Pedra',
	9 => 'Vila da Cachoeira', 10 => 'Vila da Neve', 11 => 'Vila da Grama'
];
$txtvila = isset($vilas[$dbo['vila']]) ? $vilas[$dbo['vila']] : 'Desconhecida';
$data_fundacao = date('d/m/Y', strtotime($dbo['data']));
?>

<div class="modern-card">
    <div class="modern-card-header">
        [<?php echo $dbo['sigla']; ?>] <?php echo $dbo['nome']; ?>
    </div>
    
    <!-- DEBUG INFO REMOVED -->
    <?php if($dbo['liderid'] == $db['id'] && $mypos != 1) { ?>
        <div style="background: #222; color: #fff; font-size: 11px; padding: 5px; text-align: center; border-bottom: 1px solid #444;">
            <div style="margin-top: 5px;"><a href="?p=myorg&fix=leader" class="modern-btn" style="background: red; color: white; padding: 5px 10px;">[CLIQUE AQUI PARA CORRIGIR LIDERANÇA]</a></div>
        </div>
    <?php } ?>
    
    <!-- MENU SECUNDÁRIO -->
    <div style="background: rgba(0,0,0,0.3); padding: 10px; border-bottom: 1px solid #444; display: flex; gap: 10px; overflow-x: auto;">
        <a href="?p=myorg" class="modern-btn active">Info</a>
        <?php if($mypos < 3) { ?>
        <a href="?p=configorg" class="modern-btn">Configurar</a>
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
        	    case 'repair': $msg_txt='Sua afiliação ao clã foi corrigida automaticamente!'; break;
        		case 1: $msg_txt='Seu clã já está no limite de membros.'; break;
        		case 5: $msg_txt='Membro excluído!'; break;
        		case 12: $msg_txt='Você não possui essa quantidade de yens para doar.'; break;
        		case 13: $msg_txt='Yens doados para o clã!'; break;
        		// Adicionar outros cases conforme necessidade...
        	}
        	if($msg_txt) echo '<div class="aviso" style="margin-bottom: 15px;">'.$msg_txt.'</div>';
        }
        ?>

        <div style="display: flex; gap: 20px; flex-wrap: wrap;">
            <!-- LOGO -->
            <div style="flex: 0 0 200px; text-align: center;">
                 <a href="?p=configorg"><img src="<?php echo ($dbo['logo']=='') ? '_img/org/no_logo.png' : $dbo['logo']; ?>" style="max-width: 100%; border-radius: 8px; border: 1px solid #444; box-shadow: 0 0 10px rgba(0,0,0,0.5);"></a>
            </div>

            <!-- INFO -->
            <div style="flex: 1; min-width: 300px;">
                <table class="modern-table" style="width: 100%;">
                    <tr><td style="color:#aaa;">Vila:</td><td><?php echo $txtvila; ?></td></tr>
                    <tr><td style="color:#aaa;">Fundação:</td><td><?php echo $data_fundacao; ?></td></tr>
                    <tr>
                        <td style="color:#aaa;">Nível:</td>
                        <td>
                            <span class="nivel-badge"><?php echo $dbo['nivel']; ?></span>
                            <?php if($dbo['nivel']<60) echo "<span style='font-size:10px; color:#777;'>(".($dbo['expmax']-$dbo['exp'])." xp para up)</span>"; ?>
                        </td>
                    </tr>
                    <tr><td style="color:#aaa;">Reserva:</td><td style="color:gold; font-weight:bold;"><?php echo number_format($dbo['reserva'],2,',','.'); ?> Yens</td></tr>
                    <tr><td style="color:#aaa;">Membros:</td><td><?php echo mysqli_num_rows($sqlm); ?> / <?php echo 5+($dbo['nivel']*5); ?></td></tr>
                    <tr><td style="color:#aaa;">Guerras (V/D/T):</td><td><span style="color:#afa;"><?php echo $dbo['war_v']; ?></span> / <span style="color:#faa;"><?php echo $dbo['war_d']; ?></span> / <?php echo $dbo['war_v']+$dbo['war_d']; ?></td></tr>
                </table>
            </div>
        </div>
        
        <div class="sep"></div>
        <div style="background: rgba(0,0,0,0.2); padding: 15px; border-radius: 5px; color: #ccc; margin-bottom: 20px; font-style: italic;">
            <?php if($dbo['descricao']<>'') echo nl2br(strip_tags($dbo['descricao'])); else echo 'Nenhuma descrição.'; ?>
        </div>
        
        <h3 style="border-bottom: 1px solid #444; padding-bottom: 5px; color: #fff; margin-bottom: 15px;">Membros</h3>
        
        <table class="modern-table" style="width: 100%;">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Membro</th>
                    <th>Título / Rank</th>
                    <th>Posição</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if(mysqli_num_rows($sqlm)==0) {
                    echo '<tr><td colspan="5" style="text-align:center; padding: 20px;">Nenhum membro encontrado (Lista Vazia).</td></tr>';
                } else {
                    $i=1;
                    $timeout = time()-900;
                    while($dbm = mysqli_fetch_assoc($sqlm)){
                        $online = ($dbm['timestamp'] >= $timeout) ? 'online' : 'offline';
                        
                        $cargo = 'Membro';
                        $cor = '#aaa';
                        switch($dbm['posicao']){
                            case 1: $cargo='Líder'; $cor='gold'; break;
                            case 2: $cargo='Conselheiro'; $cor='#d5aec2'; break;
                            case 3: $cargo='Membro'; $cor='#aaa'; break;
                        }
                ?>
                <tr>
                    <td style="text-align: center;">
                        <?php echo $i; ?><br>
                        <img src="_img/<?php echo $online; ?>.png" width="10" title="<?php echo $online; ?>">
                    </td>
                    <td>
                        <a href="?p=view&amp;view=<?php echo strtolower($dbm['usuario']); ?>" style="font-weight: bold; color: #fff;"><?php echo $dbm['usuario']; ?></a>
                        <div style="font-size: 10px; color: #777;">Lvl <?php echo $dbm['niveluser']; ?></div>
                    </td>
                    <td>
                        <?php echo htmlspecialchars($dbm['rank']); ?><br>
                        <span style="font-size: 10px; color: #aaa;">Doou: <?php echo number_format($dbm['doado'],0,',','.'); ?></span>
                    </td>
                    <td style="color: <?php echo $cor; ?>; font-weight: bold;"><?php echo $cargo; ?></td>
                    <td>
                        <?php if($dbo['liderid']==$db['id'] && $dbm['id']!=$db['id']){ // Só lider expulsa e não a si mesmo ?>
                            <a href="?p=myorg&del=<?php echo $c->encode($dbm['id'],$chaveuniversal); ?>" onclick="return confirm('Tem certeza que deseja expulsar este membro?');" class="modern-btn" style="background: #a33; padding: 2px 5px; font-size: 10px;">Expulsar</a>
                        <?php } ?>
                    </td>
                </tr>
                <?php $i++; } } ?>
            </tbody>
        </table>
        
        <!-- Sair/Destruir -->
        <div style="margin-top: 30px; text-align: center;">
            <a href="?p=<?php echo ($mypos==1) ? 'destroyorg' : 'leaveorg'; ?>" onclick="return confirm('Tem certeza absoluta?');" class="modern-btn" style="background: #800;">
                <?php echo ($mypos==1) ? 'Destruir Clã' : 'Sair do Clã'; ?>
            </a>
            
            <?php if($mypos < 3) { ?>
                <a href="?p=myorg<?php echo isset($_GET['order']) ? '' : '&order=missions'; ?>" class="modern-btn">
                    Ordenar por <?php echo isset($_GET['order']) ? 'Posição' : 'Missões'; ?>
                </a>
            <?php } ?>
        </div>

    </div>
</div>
