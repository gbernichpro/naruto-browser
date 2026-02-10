<?php
// Se já tem org, redireciona
if($db['orgid']>0){ echo "<script>self.location='?p=myorg'</script>"; exit(); }

// Verificação de Phantom Leader (Dono sem orgid)
$check_lider = mysqli_query($mysqli_link, "SELECT id, nome FROM organizacoes WHERE liderid='".$db['id']."'");
if(mysqli_num_rows($check_lider) > 0){
    $row_lider = mysqli_fetch_assoc($check_lider);
    
    // Processar Recuperação
    if(isset($_GET['recover'])){
        mysqli_query($mysqli_link, "UPDATE usuarios SET orgid='".$row_lider['id']."' WHERE id='".$db['id']."'");
        echo "<script>alert('Vínculo com o clã restaurado com sucesso!'); self.location='?p=myorg';</script>"; exit();
    }
    
    // Aviso Visual
    echo '<div class="aviso" style="background: #a33; color: white; border: 2px solid red; padding: 15px; margin-bottom: 20px;">
        <h3 style="margin:0 0 10px 0;">⚠️ ERRO DE VÍNCULO DETECTADO</h3>
        Você consta como Líder do clã <b>'.$row_lider['nome'].'</b>, mas seu personagem está desvinculado (bug).<br><br>
        <a href="?p=org&recover=true" class="modern-btn" style="background: white; color: #a33; font-weight: bold; padding: 10px 20px;">CLIQUE AQUI PARA CORRIGIR E ENTRAR NO CLÃ</a>
    </div>';
}

$sqlo = mysqli_query($mysqli_link, "SELECT id,sigla,nome,nivel,logo,minimo FROM organizacoes ORDER BY nivel DESC");
?>

<div class="modern-card">
    <div class="modern-card-header">Clãs / Organizações</div>
    <div class="modern-card-body">
        
        <div style="display: flex; gap: 20px; align-items: center; background: rgba(0,0,0,0.2); padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <img src="_img/_detalhes/msg/32.png" width="80">
            <div style="font-size: 13px; color: #ccc;">
                Os clãs são locais onde ninjas cooperam para evoluir e dominar vilas.<br>
                Junte-se a um clã existente ou crie o seu próprio legado!
            </div>
        </div>

        <?php if(isset($_GET['msg'])){
        	switch($_GET['msg']){
        		case 1: $msg='Você deixou o clã em que estava.'; break;
        		case 2: $msg='Requisição realizada com sucesso! Aguarde aprovação.'; break;
        		case 3: $msg='Você já requisitou ingresso neste clã.'; break;
        		case 4: $msg='Seu nível é muito baixo para este clã.'; break;
        		case 5: $msg='O clã foi destruído!'; break;
        		default: $msg='';
        	}
        	if($msg) echo '<div class="aviso" style="margin-bottom: 15px;">'.$msg.'</div>';
        }
        ?>

        <div style="text-align: center; margin-bottom: 20px; display: flex; justify-content: center; gap: 10px;">
            <a href="?p=org" class="modern-btn active">Listar Clãs</a>
            <a href="?p=createorg" class="modern-btn">Criar Novo Clã</a>
        </div>

        <?php if(mysqli_num_rows($sqlo)==0) { ?>
            <div class="aviso" style="text-align: center; padding: 30px;">
                Nenhuma organização encontrada.<br>
                <a href="?p=createorg" style="font-weight: bold; color: gold;">Seja o primeiro a criar uma!</a>
            </div>
        <?php } else { ?>
            <table class="modern-table" style="width: 100%;">
                <thead>
                    <tr>
                        <th style="width: 60px;">Logo</th>
                        <th>Clã</th>
                        <th>Nível / Req</th>
                        <th style="width: 100px;">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($dbo = mysqli_fetch_assoc($sqlo)){ ?>
                    <tr>
                        <td style="text-align: center; padding: 5px;">
                            <?php if($dbo['logo']) echo '<img src="'.$dbo['logo'].'" style="max-height: 40px; border-radius: 4px;">'; else echo '<span style="font-size: 10px; color: #555;">Sem Logo</span>'; ?>
                        </td>
                        <td>
                            <b style="color: #fff;"><?php echo $dbo['nome']; ?></b><br>
                            <span style="font-size: 10px; color: #aaa;">[<?php echo $dbo['sigla']; ?>]</span>
                        </td>
                        <td>
                            <span class="nivel-badge"><?php echo $dbo['nivel']; ?></span><br>
                            <span style="font-size: 10px; color: #777;">Min Lvl: <?php echo $dbo['minimo']; ?></span>
                        </td>
                        <td style="text-align: center;">
                            <a href="?p=vieworg&id=<?php echo $dbo['id']; ?>" class="modern-btn" style="padding: 5px 10px; font-size: 11px;">Ver</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } ?>
    </div>
</div>
