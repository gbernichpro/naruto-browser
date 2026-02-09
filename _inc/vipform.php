<?php
if(isset($_POST['vip_aut'])){
	$sqlv = mysql_query("SELECT count(id) conta FROM vip WHERE status='analise' AND usuarioid='".$db['id']."'");
	$dbv = mysql_fetch_assoc($sqlv);
	if($dbv['conta']>1){ echo "<script>self.location='?p=vipform&msg=3'</script>"; return; }
	$data = date('Y-m-d H:i:s');
	$aut = antiinjection($_POST['vip_aut']);
	if(strlen($aut)<15){ echo "<script>self.location='?p=vipform&msg=1'</script>"; return; }
	if(strlen($aut)>36){ echo "<script>self.location='?p=vipform&msg=1'</script>"; return; }
	$item = $c->decode($_POST['vip_item'],$chaveuniversal);
	switch($item){
		case 'vip30': $valor=7; break;
        case '10Creditos': $valor=10; break;
        case '25Creditos': $valor=25; break;
        case '1Creditos': $valor=1; break;
        case '75Creditos': $valor=75; break;
        case '50Creditos': $valor=50; break;
        case '100Creditos': $valor=100; break;

		default: echo "<script>self.location='?p=vipform&msg=2'</script>"; break;
	}
	$meio=$c->decode($_POST['vip_meio'],$chaveuniversal);
	$userid=$db['id'];
	if($aut==''){ echo "<script>self.location='?p=vipform&msg=1'</script>"; return; }
	mysql_query("INSERT INTO vip (data, descricao, autenticacao, usuarioid, valor, meio) VALUES ('$data', '$item', '$aut', $userid, $valor, '$meio')");
	echo "<script>self.location='?p=donations'</script>";
}
?>
<div class="box_top">Confirmar Doação</div>
<div class="box_middle">Utilize o formulário abaixo para nos informar sobre uma doação que você tenha feito. Recomendamos que preencha os campos abaixo com informações coerentes sobre sua doação, caso contrário não poderemos identificá-la. O prazo para entrega da VIP é de até 2 dias úteis <b>após a confirmação do PagSeguro/PayPal</b>.<div class="sep"></div>
	<?php if(isset($_GET['msg'])){
		switch($_GET['msg']){
			case 1: $msg='Código de Autenticação inválido.'; break;
			case 2: $msg='Item não encontrado.'; break;
			case 3: $msg='Máximo de 2 confirmações por pessoa.'; break;
		}
	echo '<div class="aviso">'.$msg.'</div><div class="sep"></div>';
	} ?>
	<form method="post" action="?p=vipform" onsubmit="subm.value='Carregando...';subm.disabled=true;">
    	<div class="destaque">Item</div>
        <select id="vip_item" name="vip_item">
        	<option value="<?php echo $c->encode('vip30',$chaveuniversal); ?>" selected="selected">Conta VIP Naruto a Lenda - 30 dias</option>
            <option value="<?php echo $c->encode('1Creditos',$chaveuniversal); ?>" selected="selected">1 Credito</option>
            <option value="<?php echo $c->encode('10Creditos',$chaveuniversal); ?>" selected="selected">10 Creditos</option>
            <option value="<?php echo $c->encode('25Creditos',$chaveuniversal); ?>" selected="selected">25 Creditos</option>
           	<option value="<?php echo $c->encode('50Creditos',$chaveuniversal); ?>" selected="selected">50 Creditos</option>
           	<option value="<?php echo $c->encode('75Creditos',$chaveuniversal); ?>" selected="selected">75 Creditos</option>
           	<option value="<?php echo $c->encode('100Creditos',$chaveuniversal); ?>" selected="selected">100 Creditos</option>



        </select><br />
        <span class="sub2">Selecione a descrição do item.</span><br /><br />
        <div class="destaque">Código de Autenticação</div>
        <input type="text" id="vip_aut" name="vip_aut" value="" size="50"/><br />
        <span class="sub2">Copie o código de Transação enviado à você pelo PagSeguro .</span><br /><br />
        <div class="destaque">Pagseguro é o Codigo da Transação ..</div>
        <div class="destaque">Meio de Pagamento</div>
        <select id="vip_meio" name="vip_meio">
        	<option value="<?php echo $c->encode('ps',$chaveuniversal); ?>" selected="selected">PagSeguro</option>
        </select><br />
        <span class="sub2">Selecione o meio de pagamento utilizado.</span>
        <div class="sep"></div>
        <div align="center"><input type="submit" id="subm" name="subm" class="botao" value="Enviar" /></div>
    </form>
</div>
<div class="box_bottom"></div>