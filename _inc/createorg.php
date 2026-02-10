<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if($db['orgid']>0){ echo "<script>self.location='?p=myorg'</script>"; exit(); }

if($db['nivel']<1){ // Mantido nivel 1
?>
    <div class="modern-card">
        <div class="modern-card-header">Criar Organização</div>
        <div class="modern-card-body">
            <div class="aviso">Seu nível é muito baixo para ser líder de uma organização.<br />Volte quando estiver no nível 5.</div>
        </div>
    </div>
<?php
} else {
    // Processamento do POST
	if(isset($_POST['org'])){
		$valor = 30000;
		if($db['yens']<$valor){ echo "<script>self.location='?p=createorg&msg=1'</script>"; exit(); }
		
        $nome = isset($_POST['org_nome']) ? mysqli_real_escape_string($mysqli_link, $_POST['org_nome']) : '';
        $sigla_raw = isset($_POST['org_sigla']) ? $_POST['org_sigla'] : '';
		$sigla = substr(strtoupper(mysqli_real_escape_string($mysqli_link, $sigla_raw)),0,4);
		
        if(empty($nome) || empty($sigla)) {
             echo "<script>self.location='?p=createorg&msg=2'</script>"; exit();
        }
        
        // Debug SQL
        $sql1 = "INSERT INTO organizacoes (vila,nome,sigla,data,liderid) VALUES (".$db['vila'].",'".$nome."','".$sigla."','".date('Y-m-d H:i:s')."',".$db['id'].")";
		if(!mysqli_query($mysqli_link, $sql1)) die("Erro INSERT ORG: " . mysqli_error($mysqli_link));
		
        $orgid = mysqli_insert_id($mysqli_link);
        
        $sql2 = "INSERT INTO membros (orgid,usuarioid,posicao,rank,doado,status) VALUES ($orgid,".$db['id'].",1,'Líder',0,'sim')";
		if(!mysqli_query($mysqli_link, $sql2)) die("Erro INSERT MEMBER: " . mysqli_error($mysqli_link));
		
        $sql3 = "UPDATE usuarios SET orgid='".$orgid."' , yens=yens-".$valor."  WHERE id=".$db['id'];
		if(!mysqli_query($mysqli_link, $sql3)) die("Erro UPDATE USER: " . mysqli_error($mysqli_link));
		
        echo "<script>self.location='?p=myorg'</script>"; exit();
	}
?>

<!-- Estilos para o Modal -->
<style>
.modal-overlay {
    position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,0.8); z-index: 9999;
    display: flex; align-items: center; justify-content: center;
}
.modal-box {
    width: 300px; animation: popIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
@keyframes popIn { from { transform: scale(0.8); opacity: 0; } to { transform: scale(1); opacity: 1; } }
</style>

<!-- Modal Logic -->
<?php
if(isset($_GET['msg'])){
    $msg_text = "";
    switch($_GET['msg']){
        case 1: $msg_text = "Yens Insuficientes!<br><small>Você precisa de 30.000 yens.</small>"; break;
        case 2: $msg_text = "Preencha todos os campos!"; break;
    }
    if($msg_text){
        echo '
        <div class="modal-overlay" id="errModal">
            <div class="modern-card modal-box">
                <div class="modern-card-header" style="background: linear-gradient(to right, #d32f2f, #b71c1c);">Atenção Ninja</div>
                <div class="modern-card-body" style="text-align:center;">
                    <p style="color: #fff; font-size: 14px; margin-bottom: 20px;">'.$msg_text.'</p>
                    <button class="modern-btn" onclick="document.getElementById(\'errModal\').remove()">Entendido</button>
                </div>
            </div>
        </div>';
    }
}
?>

<div class="modern-card">
    <div class="modern-card-header">Crie Seu Clã</div>
    <div class="modern-card-body">
        <div style="display: flex; gap: 20px; align-items: center; background: rgba(0,0,0,0.2); padding: 20px; border-radius: 8px; border: 1px solid var(--border-subtle); margin-bottom: 20px;">
            <img width="80" src="_img/_detalhes/msg/33.png" style="filter: drop-shadow(0 0 10px rgba(50,150,255,0.3));">
            <div>
                <h3 style="color: var(--primary-yellow); margin: 0 0 10px 0; font-family: var(--font-header);">Liderança Ninja</h3>
                <p style="color: var(--text-dim); margin: 0; font-size: 13px; line-height: 1.5;">
                    Para criar uma organização, é necessário espírito de liderança e recursos.
                    Conquiste os melhores ninjas do mundo sob sua bandeira!
                </p>
                <p style="color: var(--text-dim); margin-top: 5px; font-size: 12px;">
                    <span style="color: #fff; font-weight: bold;">Custo:</span> 30.000,00 yens.
                </p>
            </div>
        </div>

        <div style="padding: 10px; border-bottom: 1px solid rgba(255,255,255,0.05); margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center;">
            <span style="color: var(--text-dim); font-size: 12px;"><img src="_img/yens.png" width="14" align="absmiddle" /> Meus Yens:</span>
            <b style="color: #fff;"><?php echo number_format($db['yens'],2,',','.'); ?> yens</b>
        </div>
        
        <form method="post" action="?p=createorg">
            <input type="hidden" id="org" name="org" value="1">
            
            <div style="margin-bottom: 15px;">
                <label style="display: block; color: var(--text-dim); font-size: 11px; margin-bottom: 5px;">Nome da Organização</label>
                <input type="text" id="org_nome" name="org_nome" class="modern-input" style="width: 100%;" placeholder="Ex: Akatsuki">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display: block; color: var(--text-dim); font-size: 11px; margin-bottom: 5px;">Sigla (4 Letras)</label>
                <input type="text" id="org_sigla" name="org_sigla" maxlength="4" size="6" class="modern-input" style="width: 100px; text-transform: uppercase;" placeholder="EX: AKTS">
            </div>

            <div style="margin-top: 20px; text-align: center;">
                <input type="submit" id="subm" name="subm" class="modern-btn" value="Criar Organização" style="padding: 10px 30px; font-size: 14px;" />
            </div>
        </form>
    </div>
</div>
<?php } ?>