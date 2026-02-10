<?php
if(isset($_POST['vip_aut'])){
	$sqlv = mysqli_query($mysqli_link, "SELECT count(id) conta FROM vip WHERE status='analise' AND usuarioid='".$db['id']."'");
	$dbv = mysqli_fetch_assoc($sqlv);
	if($dbv['conta']>1){ echo "<script>self.location='?p=vipform&msg=3'</script>"; exit(); }
	$data = date('Y-m-d H:i:s');
	$aut = isset($_POST['vip_aut']) ? mysqli_real_escape_string($mysqli_link, $_POST['vip_aut']) : '';
	
    // Validação de Autenticação
	if(strlen($aut)<10){ echo "<script>self.location='?p=vipform&msg=1'</script>"; exit(); } // Reduzi min para 10 para flexibilidade
	if(strlen($aut)>60){ echo "<script>self.location='?p=vipform&msg=1'</script>"; exit(); } // Aumentei max para UUIDs modernos
	
    $item_encoded = isset($_POST['vip_item']) ? $_POST['vip_item'] : '';
    $item = $c->decode($item_encoded, $chaveuniversal);
    
	switch($item){
		case 'vip30': $valor=7; break; // Valores fixos? Deveriam vir de config/banco?
        case '10Creditos': $valor=10; break;
        case '25Creditos': $valor=25; break;
        case '1Creditos': $valor=1; break;
        case '75Creditos': $valor=75; break;
        case '50Creditos': $valor=50; break;
        case '100Creditos': $valor=100; break;
		default: echo "<script>self.location='?p=vipform&msg=2'</script>"; exit();
	}
	
    $meio_encoded = isset($_POST['vip_meio']) ? $_POST['vip_meio'] : '';
	$meio=$c->decode($meio_encoded, $chaveuniversal);
	$userid=$db['id'];
	
	if($aut==''){ echo "<script>self.location='?p=vipform&msg=1'</script>"; exit(); }
	
    // Insert Seguro
    $item_safe = mysqli_real_escape_string($mysqli_link, $item);
    $meio_safe = mysqli_real_escape_string($mysqli_link, $meio);
    
	mysqli_query($mysqli_link, "INSERT INTO vip (data, descricao, autenticacao, usuarioid, valor, meio) VALUES ('$data', '$item_safe', '$aut', $userid, $valor, '$meio_safe')");
	echo "<script>alert('Confirmação enviada! Aguarde a análise.'); self.location='?p=home'</script>"; // Mudei redirecionamento para home pois donations não existe ainda? Ou vou criar? Vou criar doacao.php mas link donations pode não existir.
}
?>
<div class="modern-card">
    <div class="modern-card-header">Confirmar Doação</div>
    <div class="modern-card-body">
        <div style="background: rgba(0,0,0,0.2); padding: 15px; border-radius: 5px; margin-bottom: 20px; font-size: 13px; color: #ccc;">
            Utilize o formulário abaixo para nos informar sobre uma doação que você tenha feito.<br>
            O prazo para entrega da VIP é de até <b>2 dias úteis</b> após a confirmação do pagamento.
        </div>
        
    	<?php if(isset($_GET['msg'])){
    		switch($_GET['msg']){
    			case 1: $msg='Código de Autenticação inválido.'; break;
    			case 2: $msg='Item não encontrado.'; break;
    			case 3: $msg='Você já tem confirmações pendentes em análise. Aguarde.'; break;
    		}
    	    echo '<div class="aviso" style="margin-bottom: 15px;">'.$msg.'</div>';
    	} ?>
    	
    	<form method="post" action="?p=vipform" onsubmit="var b=this.querySelector('input[type=submit]'); if(b) { b.value='Enviando...'; b.disabled=true; }">
        	<label class="destaque">Item Adquirido:</label>
            <select id="vip_item" name="vip_item" class="modern-input" style="width: 100%; margin-bottom: 10px;">
            	<option value="<?php echo $c->encode('vip30',$chaveuniversal); ?>" selected="selected">Conta VIP - 30 dias</option>
                <option value="<?php echo $c->encode('10Creditos',$chaveuniversal); ?>">10 Créditos</option>
                <option value="<?php echo $c->encode('25Creditos',$chaveuniversal); ?>">25 Créditos</option>
               	<option value="<?php echo $c->encode('50Creditos',$chaveuniversal); ?>">50 Créditos</option>
               	<option value="<?php echo $c->encode('75Creditos',$chaveuniversal); ?>">75 Créditos</option>
               	<option value="<?php echo $c->encode('100Creditos',$chaveuniversal); ?>">100 Créditos</option>
            </select>
            
            <label class="destaque">Código da Transação (ID):</label>
            <input type="text" id="vip_aut" name="vip_aut" value="" class="modern-input" style="width: 100%; margin-bottom: 5px;" placeholder="Ex: 9E88-2929-..." required />
            <span class="sub2" style="display: block; margin-bottom: 15px;">Cole o código de transação fornecido pelo PagSeguro/PayPal.</span>
            
            <label class="destaque">Meio de Pagamento:</label>
            <select id="vip_meio" name="vip_meio" class="modern-input" style="width: 100%; margin-bottom: 20px;">
            	<option value="<?php echo $c->encode('ps',$chaveuniversal); ?>" selected="selected">PagSeguro</option>
                <option value="<?php echo $c->encode('pp',$chaveuniversal); ?>">PayPal</option>
                <option value="<?php echo $c->encode('pix',$chaveuniversal); ?>">PIX</option>
            </select>
            
            <div align="center">
                <input type="submit" id="subm" name="subm" class="modern-btn" value="Confirmar Doação" />
            </div>
        </form>
    </div>
</div>