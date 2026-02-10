<?php
if(isset($_GET['act']) && $_GET['act'] == "dcl" && empty($_GET['r'])){
    $error = '';
	$limit = 3;
	$nivel_min = (($dbo['nivel']-$limit) <= 0) ? 1 : ($dbo['nivel']-$limit);
	$nivel_max = ($dbo['nivel']+$limit);

	if($dbo['reserva'] < 30000)
		$error = "Para declarar uma guerra o clã deve ter 30.000,00 Yens";
	
    $subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
    $org_sigla = isset($_POST['org']) ? trim($_POST['org']) : '';
    $mensagem = isset($_POST['mensagem']) ? trim($_POST['mensagem']) : '';

	if(empty($error) && (strlen($subject) < 3 || strlen($subject) > 50))
		$error = "O título da guerra deve ter entre 3 e 50 caracteres.";

    // Verificar clã inimigo
    $stmt_c = mysqli_prepare($mysqli_link, "SELECT id, sigla, nivel FROM organizacoes WHERE sigla=?");
    mysqli_stmt_bind_param($stmt_c, "s", $org_sigla);
    mysqli_stmt_execute($stmt_c);
    $res_c = mysqli_stmt_get_result($stmt_c);
    $check = mysqli_fetch_assoc($res_c);

	if(empty($error) && !$check)
		$error = "Clã não encontrado.";
	
    if(empty($error) && $check['id'] == $dbo['id'])
		$error = "Você não pode declarar uma guerra ao seu clã.";
	
    if(empty($error) && $dbo['liderid'] != $db['id'])
		$error = "Para declarar uma guerra, você deve ser o líder do clã.";
	
    if(empty($error) && ($check['nivel'] < $nivel_min || $check['nivel'] > $nivel_max))
		$error = "Você só pode atacar clãs entre o nível ".$nivel_min." e o nível ".$nivel_max.".";

    // Verificar se já existe guerra
    $stmt_w = mysqli_prepare($mysqli_link, "SELECT id FROM org_wars_declare WHERE to_org=? AND from_org=?");
    mysqli_stmt_bind_param($stmt_w, "ii", $dbo['id'], $check['id']);
    mysqli_stmt_execute($stmt_w);
    $res_w = mysqli_stmt_get_result($stmt_w);

	if(empty($error) && mysqli_num_rows($res_w) != 0)
		$error = "Já existe uma guerra declarada contra este clã!";
	
    if(empty($error) && (strlen($mensagem) < 10 || strlen($mensagem) > 500))
		$error = "A mensagem da guerra deve ter entre 10 e 500 caracteres.";

	if(empty($error)){
        mysqli_begin_transaction($mysqli_link);
        try {
            // Descontar reserva
            $stmt_u = mysqli_prepare($mysqli_link, "UPDATE organizacoes SET reserva = reserva - 30000 WHERE id=?");
            mysqli_stmt_bind_param($stmt_u, "i", $db['orgid']);
            mysqli_stmt_execute($stmt_u);

            // Inserir declarações
            $now = time();
            $stmt_i1 = mysqli_prepare($mysqli_link, "INSERT INTO org_wars_declare (to_org, from_org, title, text, time) VALUES (?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt_i1, "iissi", $dbo['id'], $check['id'], $subject, $mensagem, $now);
            mysqli_stmt_execute($stmt_i1);
            $id0 = mysqli_insert_id($mysqli_link);

            $stmt_i2 = mysqli_prepare($mysqli_link, "INSERT INTO org_wars_declare (to_org, from_org, title, text, time) VALUES (?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt_i2, "iissi", $check['id'], $dbo['id'], $subject, $mensagem, $now);
            mysqli_stmt_execute($stmt_i2);
            $id1 = mysqli_insert_id($mysqli_link);

            $war_id = $id0.";".$id1;

            // Criar registro de guerra
            $stmt_iw1 = mysqli_prepare($mysqli_link, "INSERT INTO org_wars (org, war_id) VALUES (?, ?)");
            mysqli_stmt_bind_param($stmt_iw1, "is", $dbo['id'], $war_id);
            mysqli_stmt_execute($stmt_iw1);

            $stmt_iw2 = mysqli_prepare($mysqli_link, "INSERT INTO org_wars (org, war_id) VALUES (?, ?)");
            mysqli_stmt_bind_param($stmt_iw2, "is", $check['id'], $war_id);
            mysqli_stmt_execute($stmt_iw2);

            // Atualizar IDs de guerra
            $stmt_uw1 = mysqli_prepare($mysqli_link, "UPDATE org_wars_declare SET war_id=? WHERE id=?");
            mysqli_stmt_bind_param($stmt_uw1, "si", $war_id, $id0);
            mysqli_stmt_execute($stmt_uw1);

            $stmt_uw2 = mysqli_prepare($mysqli_link, "UPDATE org_wars_declare SET war_id=? WHERE id=?");
            mysqli_stmt_bind_param($stmt_uw2, "si", $war_id, $id1);
            mysqli_stmt_execute($stmt_uw2);

            // Enviar mensagens para os membros
            $date = date('Y-m-d H:i:s');
            $assunto = "O clã ".$dbo['sigla']." declarou guerra ao clã ".$check['sigla'].".";
            $msg_full = "<b>Título da guerra:</b> ".htmlspecialchars($subject)."<br />";
            $msg_full .= "<b>Mensagem:</b><br />".nl2br(htmlspecialchars($mensagem));

            $stmt_m = mysqli_prepare($mysqli_link, "SELECT id FROM usuarios WHERE orgid=? OR orgid=?");
            mysqli_stmt_bind_param($stmt_m, "ii", $dbo['id'], $check['id']);
            mysqli_stmt_execute($stmt_m);
            $res_m = mysqli_stmt_get_result($stmt_m);

            $stmt_im = mysqli_prepare($mysqli_link, "INSERT INTO mensagens (data, origem, destino, assunto, msg) VALUES (?, '0', ?, ?, ?)");
            while($row = mysqli_fetch_assoc($res_m)){
                mysqli_stmt_bind_param($stmt_im, "siss", $date, $row['id'], $assunto, $msg_full);
                mysqli_stmt_execute($stmt_im);
            }

            mysqli_commit($mysqli_link);
            echo "<script>self.location='?p=warorg&m=declare&act=dcl&r=done'</script>";
            exit();
        } catch (Exception $e) {
            mysqli_rollback($mysqli_link);
            $error = "Erro ao declarar guerra: " . $e->getMessage();
        }
	}
}
?>

<div style="padding: 10px;">
    <h3 style="color: #fff; margin-bottom: 20px; border-bottom: 1px solid #444; padding-bottom: 10px;">
        📜 Nova Declaração de Guerra
    </h3>

    <?php if(isset($_GET['r']) && $_GET['r'] == 'done'): ?>
        <div class="aviso" style="background: rgba(0, 100, 0, 0.4); border-color: #0f0; margin-bottom: 20px;">
            ✅ A guerra foi declarada com sucesso! Todos os membros foram notificados.
        </div>
    <?php endif; ?>

    <?php if(!empty($error)): ?>
        <div class="aviso" style="background: rgba(100, 0, 0, 0.4); border-color: #f00; margin-bottom: 20px;">
            ⚠️ <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form method="post" action="?p=warorg&amp;m=declare&act=dcl" onsubmit="var b=this.querySelector('input[type=submit]'); if(b) { b.value='Enviando Desafio...'; b.disabled=true; }">
        <div style="display: flex; flex-direction: column; gap: 15px;">
            <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 250px;">
                    <label class="destaque" style="display: block; margin-bottom: 5px;">Título da Guerra:</label>
                    <input type="text" name="subject" maxlength="50" placeholder="Ex: A Grande Batalha por Honra" required style="width: 100%; padding: 10px; background: #111; border: 1px solid #444; color: #fff; border-radius: 4px;">
                </div>
                <div style="flex: 1; min-width: 200px;">
                    <label class="destaque" style="display: block; margin-bottom: 5px;">Sigla do Clã Inimigo:</label>
                    <input type="text" name="org" maxlength="50" placeholder="Ex: AKTS" required style="width: 100%; padding: 10px; background: #111; border: 1px solid #444; color: #fff; border-radius: 4px;">
                </div>
            </div>

            <div>
                <label class="destaque" style="display: block; margin-bottom: 5px;">Mensagem de Guerra:</label>
                <textarea name="mensagem" rows="6" placeholder="Escreva aqui os termos ou a provocação para o clã inimigo..." required style="width: 100%; padding: 10px; background: #111; border: 1px solid #444; color: #ccc; border-radius: 4px; resize: vertical; font-family: inherit;"></textarea>
            </div>

            <div style="background: rgba(255,165,0,0.1); padding: 15px; border-radius: 8px; border: 1px solid orange; display: flex; align-items: center; justify-content: space-between;">
                <div style="font-size: 13px; color: orange;">
                    📌 <b>Atenção:</b> A declaração de guerra tem um custo fixo de <b>30.000,00 Yens</b> retirados da reserva do clã.
                </div>
                <input type="submit" id="subm" name="subm" class="modern-btn" style="background: #a22; color: #fff; padding: 10px 25px; cursor: pointer;" value="🔥 Declarar Guerra">
            </div>
        </div>
    </form>
</div>