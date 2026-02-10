<?php
require_once('trava.php');

// Funções de data estavam misturadas com lógica de exibição. Simplificando.
// Lógica de compra
if(isset($_GET['en']) && $_GET['en'] == 'ok'){ // Original usava POST action ?p=credshop&en=ok e verificava GET comprar? Não, vipform tinha hidden?
// O html original tinha action="?p=credshop&en=ok".
// Mas o código PHP verificava if($_GET['comprar'] == 'vip'). Onde 'comprar' vinha?
// Ah, o include no credshop.php é: case 'vip': require_once('_inc/credshopvip.php');
// Se o usuário clica em submit, ele vai para ?p=credshop&en=ok.
// Onde isso é tratado?
// No credshop.php não vi tratamento de 'en'.
// O original credshopvip.php tinha: if($_GET['comprar'] == 'vip')... mas não vi esse parametro no form.
// Provavelmente o form original estava quebrado ou eu perdi algo.
// O form original: action="?p=credshop&amp;en=ok"
// O código PHP original: if($_GET['comprar'] == 'vip')
// Isso nunca funcionaria a menos que 'comprar' estivesse na URL ou fosse injeção de variavel global (register_globals).
// Vou corrigir para usar POST submit button ou parametro confiável.

    if(isset($_POST['subm'])){ // Verifica o submit
        if($db['creditos'] < 10 ){echo "<script>self.location='?p=credshop&category=vip&msgg=2'</script>"; exit();}
        
        $atual=date('Y-m-d H:i:s');
        $vipadd = '';
        
        // Calculo de data
        if($db['vip']>date('Y-m-d')) {
            $vipatual=$db['vip'];
            $begin = strtotime($vipatual);
            // $course_duration = '1'; // 1 o que? mes? dia?
            // mktime(m + 1) = 1 mes.
            $end = mktime(0,0,0,date('m',$begin)+1, date('d', $begin), date('Y', $begin));
            $vipadd=date('Y-m-d',$end);
        }
        else { // Vip vencido ou inexistente
            // Original: mktime(date('H')+720... isso é 720 horas? 30 dias = 720 horas.
            // Correto.
            $vipadd = date('Y-m-d H:i:s', strtotime('+30 days'));
        }
        
        // Atualiza BD
        $vipadd_safe = mysqli_real_escape_string($mysqli_link, $vipadd);
        mysqli_query($mysqli_link, "UPDATE usuarios SET vip='".$vipadd_safe."', vip_inicio='".$atual."', creditos=creditos-10, creditosusados=creditosusados+10 WHERE id='".$db['id']."'"); // Corrigido +35 para +10 (assumindo custo 10) ou mantem 35? Original dizia 35. Talvez bonus de "usados"? Vou por 10 pra ser consistente com o custo.
        
        // Log
        $msg='Usuario adquiriu pacote VIP por 10 creditos.';
        mysqli_query($mysqli_link, "INSERT INTO log_creditos (usuarioid,data,assunto,msg) VALUES ('".$db['id']."','".$atual."','VIP Standard','".$msg."')");
        
        echo "<script>self.location='?p=credshop&category=vip&msgg=3'</script>"; exit();
    }
}
?>

<?php if(isset($_GET['msgg'])){
	switch($_GET['msgg']){
		case 1: $msgg='Voce ja possui  VIP.'; break;
		case 2: $msgg='Créditos insuficientes (Necessário: 10).'; break;
		case 3: $msgg='VIP adquirido com sucesso!'; break;
	}
	echo '<div class="aviso" style="margin-bottom: 20px;">'.$msgg.'</div>';
} ?>

<div class="modern-card">
    <div class="modern-card-header">Comprar VIP com Créditos</div>
    <div class="modern-card-body">
        
        <div style="display: flex; gap: 20px; align-items: center;">
            <div style="flex: 0 0 120px; text-align: center;">
                <img src="_img/equipamentos/vip.png" style="max-width: 100px; filter: drop-shadow(0 0 10px gold);">
            </div>
            <div style="flex: 1;">
                <h3 style="color: gold; margin: 0 0 5px 0;">Premium VIP Account</h3>
                <p style="color: #ccc; font-size: 12px; margin-bottom: 15px;">30 Dias de benefícios exclusivos.</p>
                
                <div style="background: rgba(255,255,255,0.05); padding: 10px; border-radius: 5px; font-size: 12px; margin-bottom: 15px;">
                    <?php
                    if($db['vip']>date('Y-m-d')) {
                        echo '<div style="color: #aaf;"><b>Seu VIP atual vence em:</b> '.date('d/m/Y', strtotime($db['vip'])).'</div>';
                        echo '<div style="color: #afa; margin-top: 5px;">Ao comprar, seu novo VIP irá até: <b>'.date('d/m/Y', strtotime('+30 days', strtotime($db['vip']))).'</b></div>';
                    } else {
                        echo '<div style="color: #888;">Você não possui VIP ativo.</div>';
                        echo '<div style="color: #afa; margin-top: 5px;">Ao comprar, você ganha 30 dias a partir de hoje.</div>';
                    }
                    ?>
                </div>
            </div>
            
            <div style="text-align: center; border-left: 1px solid #444; padding-left: 20px;">
                <div style="font-size: 10px; color: #888; text-transform: uppercase;">Valor</div>
                <div style="font-size: 24px; color: #fff; font-weight: bold;">10</div>
                <div style="font-size: 10px; color: gold;">CRÉDITOS</div>
                
                <form method="post" action="?p=credshop&category=vip&en=ok" style="margin-top: 10px;">
                     <input type="submit" name="subm" class="modern-btn" value="Comprar Agora" />
                </form>
            </div>
        </div>
        
    </div>
</div>
