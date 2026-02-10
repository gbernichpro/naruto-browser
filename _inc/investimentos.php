<?php
if($db['orgid']==0){ echo "<script>self.location='?p=home'</script>"; exit(); }

// Carregar dados necessários (mysqli)
$id_org = $db['orgid'];
$sql_org = mysqli_query($mysqli_link, "SELECT reserva, nome, sigla FROM organizacoes WHERE id=$id_org");
$dbo = mysqli_fetch_assoc($sql_org);

// Pegar posição do usuário
$sql_pos = mysqli_query($mysqli_link, "SELECT posicao FROM membros WHERE usuarioid=".$db['id']." AND orgid=$id_org");
$dbp = mysqli_fetch_assoc($sql_pos);
$mypos = $dbp ? $dbp['posicao'] : 3;

// Pegar investimentos ATUAIS
$sqli = mysqli_query($mysqli_link, "SELECT i.*, t.*, i.id idinvestimento FROM clas_investimentos i LEFT OUTER JOIN table_investimentos t ON i.invid=t.id WHERE i.orgid=$id_org");

// Lista de IDs já investidos para exclusão na lista de novos
$excluidos = [];
$investimentos_atuais = [];
while($row = mysqli_fetch_assoc($sqli)){
    $investimentos_atuais[] = $row;
    $excluidos[] = $row['invid'];
}

// Pegar investimentos DISPONÍVEIS (não comprados ainda)
$query_t = "SELECT * FROM table_investimentos";
if(!empty($excluidos)) {
    $query_t .= " WHERE id NOT IN (" . implode(',', $excluidos) . ")";
}
$sqlt = mysqli_query($mysqli_link, $query_t);
?>

<div class="modern-card">
    <div class="modern-card-header">
        Investimentos do Clã: [<?php echo $dbo['sigla']; ?>] <?php echo $dbo['nome']; ?>
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
        <a href="?p=cla_shop" class="modern-btn">Loja</a>
        <a href="?p=investimentos" class="modern-btn active">Investimentos</a>
    </div>

    <div class="modern-card-body">
        <!-- RESERVA -->
        <div style="background: rgba(255,215,0,0.1); border: 1px solid rgba(255,215,0,0.3); padding: 15px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
            <span style="font-size: 14px; color: #aaa; display: block; margin-bottom: 5px;">Reserva Atual do Clã</span>
            <span style="font-size: 24px; color: #ffd700; font-weight: bold;">
                <img src="_img/yens.png" width="20" style="vertical-align: middle;"> 
                <?php echo number_format($dbo['reserva'], 2, ',', '.'); ?> Yens
            </span>
        </div>

        <p style="color: #777; font-size: 13px; margin-bottom: 20px; text-align: center;">Utilize a reserva do clã para aprimorar as instalações e garantir bônus permanentes para todos os membros.</p>

        <!-- INVESTIMENTOS ATUAIS -->
        <h3 style="color: #fff; margin-bottom: 15px; font-size: 18px; border-left: 4px solid #f00; padding-left: 10px;">Aprimoramentos Atuais</h3>
        
        <?php if(empty($investimentos_atuais)): ?>
            <div class="aviso" style="margin-bottom: 30px;">Nenhum investimento realizado até o momento.</div>
        <?php else: ?>
            <div style="display: grid; gap: 15px; margin-bottom: 40px;">
                <?php foreach($investimentos_atuais as $dbi): ?>
                    <div style="background: rgba(0,0,0,0.2); border: 1px solid #333; padding: 15px; border-radius: 8px; display: flex; gap: 20px; align-items: center;">
                        <img src="_img/skins/<?php echo $db['config_skin']; ?>/clas/clan0<?php echo $dbi['nivel']; ?>.png" onerror="this.src='_img/org/instalação.png'" style="width: 100px; height: 100px; border-radius: 8px; border: 1px solid #444; background: #111;">
                        <div style="flex: 1;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                                <b style="color: #fff; font-size: 16px;"><?php echo $dbi['nome']; ?></b>
                                <span style="background: #a33; padding: 2px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">Nv <?php echo $dbi['nivel']; ?></span>
                            </div>
                            <p style="color: #777; font-size: 12px; margin-bottom: 10px;"><?php echo $dbi['descricao']; ?></p>
                            
                            <!-- BÔNUS -->
                            <div style="font-size: 11px; color: #ffd700; display: flex; gap: 10px;">
                                <?php if($dbi['taijutsu']>0) echo '<span>Taijutsu: +'.$dbi['taijutsu'].'</span>'; ?>
                                <?php if($dbi['ninjutsu']>0) echo '<span>Ninjutsu: +'.$dbi['ninjutsu'].'</span>'; ?>
                                <?php if($dbi['genjutsu']>0) echo '<span>Genjutsu: +'.$dbi['genjutsu'].'</span>'; ?>
                            </div>

                            <div style="margin-top: 15px; display: flex; justify-content: space-between; align-items: center;">
                                <div style="font-size: 12px; color: #fff;">
                                    <b>Próximo Nível:</b> 
                                    <span style="color: #ffd700;"><?php echo number_format(($dbi['custo']+($dbi['nivel']*20000)), 0, ',', '.'); ?> Yens</span>
                                </div>
                                <?php if($mypos < 3): ?>
                                    <a href="?p=myorg&evolve=<?php echo $dbi['idinvestimento']; ?>" class="modern-btn" style="background: #282; padding: 5px 15px; font-size: 12px;">📈 Aprimorar</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- DISPONÍVEIS -->
        <h3 style="color: #fff; margin-bottom: 15px; font-size: 18px; border-left: 4px solid #555; padding-left: 10px;">Novos Investimentos</h3>
        
        <?php if(mysqli_num_rows($sqlt) == 0): ?>
            <div class="aviso">Todos os investimentos possíveis já foram realizados!</div>
        <?php else: ?>
            <div style="display: grid; gap: 15px;">
                <?php while($dbt = mysqli_fetch_assoc($sqlt)): ?>
                    <div style="background: rgba(255,255,255,0.02); border: 1px solid #222; padding: 15px; border-radius: 8px; display: flex; gap: 20px; align-items: center;">
                        <img src="_img/skins/<?php echo $db['config_skin']; ?>/clas/clan01.png" onerror="this.src='_img/org/instalação.png'" style="width: 80px; height: 80px; border-radius: 8px; border: 1px solid #333; opacity: 0.6;">
                        <div style="flex: 1;">
                            <b style="color: #ccc; font-size: 15px;"><?php echo $dbt['nome']; ?></b>
                            <p style="color: #555; font-size: 11px; margin-bottom: 8px;"><?php echo $dbt['descricao']; ?></p>
                            
                            <div style="font-size: 10px; color: #666; display: flex; gap: 10px;">
                                <?php if($dbt['tai']>0) echo '<span>Taijutsu: +'.$dbt['tai'].'</span>'; ?>
                                <?php if($dbt['nin']>0) echo '<span>Ninjutsu: +'.$dbt['nin'].'</span>'; ?>
                                <?php if($dbt['gen']>0) echo '<span>Genjutsu: +'.$dbt['gen'].'</span>'; ?>
                            </div>

                            <div style="margin-top: 10px; display: flex; justify-content: space-between; align-items: center;">
                                <div style="font-size: 12px; color: #888;"><b>Custo Inicial:</b> <?php echo number_format($dbt['custo'], 0, ',', '.'); ?> Yens</div>
                                <?php if($mypos < 3): ?>
                                    <a href="?p=myorg&buy=<?php echo $dbt['id']; ?>" class="modern-btn" style="background: #444; padding: 5px 15px; font-size: 12px;">➕ Construir</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>

        <?php if($mypos == 3): ?>
            <div class="aviso" style="margin-top: 20px;">Nota: Apenas o líder e conselheiros podem realizar investimentos.</div>
        <?php endif; ?>
    </div>
</div>