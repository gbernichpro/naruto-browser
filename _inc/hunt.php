<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('trava.php');
require_once('verificar.php');
require_once('Encrypt.php');
$c=new C_Encrypt();

if(isset($_POST['hunt_tipo'])){
	$tipo=$c->decode($_POST['hunt_tipo'],$chaveuniversal);
	vn($tipo);
	switch($tipo){
		case 1:
			$hunt = isset($_POST['hunt_1']) ? $_POST['hunt_1'] : '';
			// Proteção SQLi manual com mysqli
			$hunt = mysqli_real_escape_string($mysqli_link, $hunt);
			
			if($hunt==''){echo "<script>self.location='?p=hunt&msg=6'</script>"; exit(); }
			if(date('Y-m-d H:i:s')>=$db['vip']) if($db['yens']<5){ echo "<script>self.location='?p=hunt&msg=3'</script>"; exit(); }
			if(date('Y-m-d H:i:s')>=$db['vip']) mysqli_query($mysqli_link, "UPDATE usuarios SET yens=yens-5 WHERE id=".$db['id']);
			
			$nome=$hunt;
			$sqlh = mysqli_query($mysqli_link, "SELECT id,vila,renegado,avatar,energia,preso,penalidade_fim,missao,loginip FROM usuarios WHERE usuario='".$nome."'");
			$dbh=@mysqli_fetch_assoc($sqlh);
			
			if(@mysqli_num_rows($sqlh)==0){ echo "<script>self.location='?p=hunt&msg=1'</script>"; exit(); }
			if($dbh['energia']<25){ echo "<script>self.location='?p=hunt&msg=2'</script>"; exit(); }
			/*if($dbh['loginip']==$db['loginip']){ echo "<script>self.location='?p=hunt&msg=16'</script>"; exit(); }*/
			if($dbh['preso']=='sim'){ echo "<script>self.location='?p=hunt&msg=4'</script>"; exit(); }
			if($dbh['missao']==999){ echo "<script>self.location='?p=hunt&msg=10'</script>"; exit(); }
			if($dbh['avatar']==0){ echo "<script>self.location='?p=hunt&msg=12'</script>"; exit(); }
			if(date('Y-m-d H:i:s')<$dbh['penalidade_fim']){ echo "<script>self.location='?p=hunt&msg=7'</script>"; exit(); }
			
			$_SESSION['prepare']=$dbh['id'];
			
			$sqlv=mysqli_query($mysqli_link, "SELECT data FROM relatorios WHERE usuarioid=".$db['id']." AND inimigoid=".$dbh['id']." ORDER BY id DESC LIMIT 1");
			$dbv = @mysqli_fetch_assoc($sqlv);
			$soma = mktime(date('H')-12, date('i'), date('s'));
			$penalidade = date('Y-m-d H:i:s',$soma);
			if($penalidade<$dbv['data']){ echo "<script>self.location='?p=hunt&msg=9'</script>"; exit(); }
			
			$sqlv = mysqli_query($mysqli_link, "SELECT data FROM relatorios WHERE usuarioid=".$dbh['id']." OR inimigoid=".$dbh['id']." ORDER BY id DESC LIMIT 1");
			$dbv = @mysqli_fetch_assoc($sqlv);
			$soma = mktime(date('H'), date('i')-30, date('s'));
			$penalidade = date('Y-m-d H:i:s',$soma);
			if($penalidade<$dbv['data']){ echo "<script>self.location='?p=hunt&msg=8'</script>"; exit(); }
			
			$_SESSION['hunt'] = 1;
			echo "<script>self.location='?p=prepare'</script>"; exit();
			
		case 2:
			$hunt = $c->decode($_POST['hunt_2'],$chaveuniversal);
			if($hunt==0){echo "<script>self.location='?p=hunt&msg=6'</script>"; exit(); }
			if(($hunt<1)or($hunt>7)){ echo "<script>self.location='?p=home'</script>"; exit(); }
			if(date('Y-m-d H:i:s')>=$db['vip']) if($db['yens']<5){ echo "<script>self.location='?p=hunt&msg=3'</script>"; exit(); }
			if(date('Y-m-d H:i:s')>=$db['vip']) mysqli_query($mysqli_link, "UPDATE usuarios SET yens=yens-5 WHERE id=".$db['id']);
			$vila=$hunt;
			/*if($hunt==$db['vila']){ echo "<script>self.location='?p=hunt&msg=11'</script>"; exit(); }*/
			if($hunt==7)
				$sqlh = mysqli_query($mysqli_link, "SELECT id,energia,preso FROM usuarios WHERE avatar>0 AND energia>=25 AND renegado='sim' AND id<>".$db['id']." AND missao<>999 AND loginip<>'".$db['loginip']."' ORDER BY RAND() LIMIT 1");
			else
				$sqlh = mysqli_query($mysqli_link, "SELECT id,energia,preso FROM usuarios WHERE avatar>0 AND energia>=25 AND vila=".$vila." AND renegado='nao' AND id<>".$db['id']." AND missao<>999 AND loginip<>'".$db['loginip']."' ORDER BY RAND() LIMIT 1");
			
			$dbh = mysqli_fetch_assoc($sqlh);
			if(mysqli_num_rows($sqlh)==0){ echo "<script>self.location='?p=hunt&msg=5'</script>"; exit(); }
			if($dbh['preso']=='sim'){ echo "<script>self.location='?p=hunt&msg=4'</script>"; exit(); }
			$_SESSION['prepare'] = $dbh['id'];
			$_SESSION['hunt'] = 2;
			echo "<script>self.location='?p=prepare'</script>"; exit();
			
		case 3:
			$hunt=$c->decode($_POST['hunt_3'],$chaveuniversal);
			if($hunt==0){ echo "<script>self.location='?p=hunt&msg=6'</script>"; exit(); }
			if(($hunt<1)or($hunt>3)){ echo "<script>self.location='?p=home'</script>"; exit(); }
			if(date('Y-m-d H:i:s')>=$db['vip']) if($db['yens']<5){ echo "<script>self.location='?p=hunt&msg=3'</script>"; exit(); }
			if(date('Y-m-d H:i:s')>=$db['vip']) mysqli_query($mysqli_link, "UPDATE usuarios SET yens=yens-5 WHERE id=".$db['id']);
			$nivel=$hunt;
			switch($nivel){
				case 1: $filtro='nivel<'.$db['nivel']; break;
				case 2: $filtro='nivel='.$db['nivel']; break;
				case 3: $filtro='nivel>'.$db['nivel']; break;
			}
			if($db['renegado']=='nao')
				$sqlh=mysqli_query($mysqli_link, "SELECT id,energia,preso FROM usuarios WHERE avatar>0 AND energia>=25 AND ".$filtro." AND vila<>".$db['vila']." AND id<>".$db['id']." AND missao<>999 ORDER BY RAND() LIMIT 1");
			else
				$sqlh=mysqli_query($mysqli_link, "SELECT id,energia,preso FROM usuarios WHERE avatar>0 AND energia>=25 AND ".$filtro." AND renegado='nao' AND id<>".$db['id']." AND missao<>999 ORDER BY RAND() LIMIT 1");
			
			$dbh=mysqli_fetch_assoc($sqlh);
			if(mysqli_num_rows($sqlh)==0){ echo "<script>self.location='?p=hunt&msg=5'</script>"; exit(); }
			if($dbh['preso']=='sim'){ echo "<script>self.location='?p=hunt&msg=4'</script>"; exit(); }
			$_SESSION['prepare']=$dbh['id'];
			$_SESSION['hunt']=3;
			echo "<script>self.location='?p=prepare'</script>"; exit();
			
		case 4:
			$hunt=$c->decode($_POST['hunt_4'],$chaveuniversal);
			/*if(($_POST['hunt_captcha']=='')or($_POST['hunt_captcha']<>$_SESSION['captcha'])){ echo "<script>self.location='?p=hunt&msg=15'</script>"; exit(); }*/
			if($db['hunt_restantes']<$hunt){ echo "<script>self.location='?p=home'</script>"; exit(); }
			$horas=0;
			if(($hunt<1)or($hunt>12)){ echo "<script>self.location='?p=home'</script>"; exit(); }
			if($hunt<6){ $minutos=$hunt; } else { $aux=$hunt; do{ $aux=$aux-6; $horas=$horas+1; } while($aux>=6); $minutos=$aux; }
			$soma = mktime(date('H')+$horas, date('i')+($minutos*5), date('s'));
			$huntfim = date('Y-m-d H:i:s',$soma);
			$_SESSION['hunt']=4;
			
			// Execução do UPDATE com mysqli e tratamento de erro
			$query = "UPDATE usuarios SET hunt=4, hunt_fim='".mysqli_real_escape_string($mysqli_link, $huntfim)."', hunt_restantes=hunt_restantes-1 WHERE id=".$db['id'];
			if(!mysqli_query($mysqli_link, $query)) {
				die("Erro ao iniciar caça: " . mysqli_error($mysqli_link));
			}
			
			echo "<script>self.location='?p=busyhunt'</script>"; exit();
			
		case 5:
			$hunt=$c->decode($_POST['hunt_5'],$chaveuniversal);
			if($hunt==0){ echo "<script>self.location='?p=hunt&msg=6'</script>"; exit(); }
			if(date('Y-m-d H:i:s')>=$db['vip']){ echo "<script>self.location='?p=hunt&msg=14'</script>"; exit(); }
			if($db['renegado']=='sim') $minhavila=9; else $minhavila=$db['vila'];
			$timeout = time()-900;
			do{
				$vilaid=rand(1,9);
			} while($vilaid==$minhavila);
			if($hunt==1) $tempo='AND timestamp>='.$timeout;
			if($hunt==2) $tempo='AND timestamp<'.$timeout;
			
			if($vilaid==9)
				$sqlh = mysqli_query($mysqli_link, "SELECT id,energia,preso,vila,renegado,penalidade_fim FROM usuarios WHERE energia>=25 AND avatar>0 AND id<>".$db['id']." AND missao<>999 AND loginip<>'".$db['loginip']."' ".$tempo." AND renegado='sim' ORDER BY RAND() LIMIT 1");
			else
				$sqlh = mysqli_query($mysqli_link, "SELECT id,energia,preso,vila,renegado,penalidade_fim FROM usuarios WHERE energia>=25 AND avatar>0 AND id<>".$db['id']." AND missao<>999 AND loginip<>'".$db['loginip']."' ".$tempo." AND renegado='nao' AND vila=".$vilaid." ORDER BY RAND() LIMIT 1");
			
			$dbh = mysqli_fetch_assoc($sqlh);
			if(mysqli_num_rows($sqlh)==0){ echo "<script>self.location='?p=hunt&msg=5'</script>"; exit(); }
			if($dbh['energia']<25){ echo "<script>self.location='?p=hunt&msg=2'</script>"; exit(); }
			if($dbh['preso']=='sim'){ echo "<script>self.location='?p=hunt&msg=4'</script>"; exit(); }
			if(($dbh['renegado']=='sim')&&($db['renegado']=='sim')){ echo "<script>self.location='?p=hunt&msg=11'</script>"; exit(); }
			if(date('Y-m-d H:i:s')<$dbh['penalidade_fim']){ echo "<script>self.location='?p=hunt&msg=7'</script>"; exit(); }
			
			$_SESSION['prepare'] = $dbh['id'];
			
			$sqlv = mysqli_query($mysqli_link, "SELECT data FROM relatorios WHERE usuarioid='".$db['id']."' AND inimigoid='".$dbh['id']."' ORDER BY id DESC LIMIT 1");
			$dbv = mysqli_fetch_assoc($sqlv);
			$soma = mktime(date('H')-12, date('i'), date('s'));
			$penalidade = date('Y-m-d H:i:s',$soma);
			if($penalidade<$dbv['data']){ echo "<script>self.location='?p=hunt&msg=9'</script>"; exit(); }
			
			$sqlv = mysqli_query($mysqli_link, "SELECT data FROM relatorios WHERE usuarioid=".$dbh['id']." OR inimigoid=".$dbh['id']." ORDER BY id DESC LIMIT 1");
			$dbv = mysqli_fetch_assoc($sqlv);
			$soma = mktime(date('H'), date('i')-30, date('s'));
			$penalidade = date('Y-m-d H:i:s',$soma);
			if($penalidade<$dbv['data']){ echo "<script>self.location='?p=hunt&msg=8'</script>"; exit(); }
			
			$_SESSION['hunt'] = 5;
			echo "<script>self.location='?p=prepare'</script>"; exit();
	}
}
?>
<div class="modern-card">
    <div class="modern-card-header">Arena de Batalhas Ninjas</div>
    <div class="modern-card-body">
        <div style="display: flex; gap: 20px; align-items: center; background: rgba(0,0,0,0.2); padding: 20px; border-radius: 8px; border: 1px solid var(--border-subtle); margin-bottom: 20px;">
            <img width="80" src="_img/_detalhes/msg/29.png" style="filter: drop-shadow(0 0 10px rgba(255,50,0,0.4));">
            <div>
                <h3 style="color: var(--primary-red); margin: 0 0 10px 0; font-family: var(--font-header);">Prepare-se para o Combate!</h3>
                <p style="color: var(--text-dim); margin: 0; font-size: 13px; line-height: 1.5;">
                    As batalhas são a forma mais rápida de ganhar experiência e Yens. 
                    Escolha seu alvo com sabedoria, ninja!
                </p>
            </div>
        </div>
        <div style="padding: 10px; border-bottom: 1px solid rgba(255,255,255,0.05); margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center;">
            <span style="color: var(--text-dim); font-size: 12px;"><img src="_img/yens.png" width="14" align="absmiddle" /> Meus Yens:</span>
            <b style="color: #fff;"><?php echo number_format($db['yens'],2,',','.'); ?> yens</b>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <!-- Target Specific -->
            <div class="modern-card" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05);">
                <div class="modern-card-header" style="font-size: 12px; height: 35px; padding: 0 15px;">Ninja Específico</div>
                <div class="modern-card-body">
                    <p style="font-size: 11px; color: var(--text-dim); margin-bottom: 10px;">Busque por um nome. VIP: Grátis | Outros: 5,00 yens.</p>
                    <form method="post" action="?p=hunt">
                        <input type="hidden" name="hunt_tipo" value="<?php echo $c->encode('1',$chaveuniversal); ?>" />
                        <input type="text" name="hunt_1" placeholder="Nome do Alvo" style="width: 100%; margin-bottom: 10px; background: rgba(0,0,0,0.5); border: 1px solid #444; color: #fff; border-radius: 4px; padding: 5px;" />
                        <input type="submit" class="modern-btn" style="width: 100%; font-size: 11px; padding: 8px;" value="Caçar" />
                    </form>
                </div>
            </div>

            <!-- Village Hunt -->
            <div class="modern-card" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05);">
                <div class="modern-card-header" style="font-size: 12px; height: 35px; padding: 0 15px;">Ninja por Vila</div>
                <div class="modern-card-body">
                    <p style="font-size: 11px; color: var(--text-dim); margin-bottom: 10px;">Ideal para guerras. VIP: Grátis | Outros: 5,00 yens.</p>
                    <form method="post" action="?p=hunt">
                        <input type="hidden" name="hunt_tipo" value="<?php echo $c->encode('2',$chaveuniversal); ?>" />
                        <select name="hunt_2" style="width: 100%; margin-bottom: 10px; background: rgba(0,0,0,0.5); border: 1px solid #444; color: #fff; border-radius: 4px; padding: 5px;">
                            <option value="<?php echo $c->encode('0',$chaveuniversal); ?>" selected="selected">-- Selecione --</option>
                            <option value="<?php echo $c->encode('1',$chaveuniversal); ?>">Vila da Folha</option>
                            <option value="<?php echo $c->encode('2',$chaveuniversal); ?>">Vila da Areia</option>
                            <option value="<?php echo $c->encode('3',$chaveuniversal); ?>">Vila do Som</option>
                            <option value="<?php echo $c->encode('4',$chaveuniversal); ?>">Vila da Chuva</option>
                            <option value="<?php echo $c->encode('5',$chaveuniversal); ?>">Vila da Nuvem</option>
                            <option value="<?php echo $c->encode('6',$chaveuniversal); ?>">Vila da Névoa</option>
                            <option value="<?php echo $c->encode('7',$chaveuniversal); ?>">Akatsuki</option>
                        </select>
                        <input type="submit" class="modern-btn" style="width: 100%; font-size: 11px; padding: 8px;" value="Caçar" />
                    </form>
                </div>
            </div>

            <!-- Level Hunt -->
            <div class="modern-card" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05);">
                <div class="modern-card-header" style="font-size: 12px; height: 35px; padding: 0 15px;">Ninja por Nível</div>
                <div class="modern-card-body">
                    <p style="font-size: 11px; color: var(--text-dim); margin-bottom: 10px;">Busque nível adequado. VIP: Grátis | Outros: 5,00 yens.</p>
                    <form method="post" action="?p=hunt">
                        <input type="hidden" name="hunt_tipo" value="<?php echo $c->encode('3',$chaveuniversal); ?>" />
                        <select name="hunt_3" style="width: 100%; margin-bottom: 10px; background: rgba(0,0,0,0.5); border: 1px solid #444; color: #fff; border-radius: 4px; padding: 5px;">
                            <option value="<?php echo $c->encode('0',$chaveuniversal); ?>" selected="selected">-- Selecione --</option>
                            <?php if($db['nivel']>1){ ?><option value="<?php echo $c->encode('1',$chaveuniversal); ?>">Ninjas Mais Fracos</option><?php } ?>
                            <option value="<?php echo $c->encode('2',$chaveuniversal); ?>">Ninjas Equivalentes</option>
                            <option value="<?php echo $c->encode('3',$chaveuniversal); ?>">Ninjas Mais Fortes</option>
                        </select>
                        <input type="submit" class="modern-btn" style="width: 100%; font-size: 11px; padding: 8px;" value="Caçar" />
                    </form>
                </div>
            </div>

            <!-- Status Hunt (VIP) -->
            <?php if(date('Y-m-d H:i:s')<$db['vip']){ ?>
            <div class="modern-card" style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05);">
                <div class="modern-card-header" style="font-size: 12px; height: 35px; padding: 0 15px;">Ninja por Status (VIP)</div>
                <div class="modern-card-body">
                    <p style="font-size: 11px; color: var(--text-dim); margin-bottom: 10px;">Busque Online/Offline. Exclusivo para jogadores VIP.</p>
                    <form method="post" action="?p=hunt">
                        <input type="hidden" name="hunt_tipo" value="<?php echo $c->encode('5',$chaveuniversal); ?>" />
                        <select name="hunt_5" style="width: 100%; margin-bottom: 10px; background: rgba(0,0,0,0.5); border: 1px solid #444; color: #fff; border-radius: 4px; padding: 5px;">
                            <option value="<?php echo $c->encode('0',$chaveuniversal); ?>" selected="selected">-- Selecione --</option>
                            <option value="<?php echo $c->encode('1',$chaveuniversal); ?>">Online</option>
                            <option value="<?php echo $c->encode('2',$chaveuniversal); ?>">Offline</option>
                        </select>
                        <input type="submit" class="modern-btn" style="width: 100%; font-size: 11px; padding: 8px;" value="Caçar" />
                    </form>
                </div>
            </div>
            <?php } ?>
        </div>

        <!-- Hunt by Time -->
        <div class="modern-card" style="margin-top: 20px; background: rgba(255,255,0,0.03); border: 1px solid rgba(255,255,0,0.1);">
            <div class="modern-card-header" style="font-size: 12px; height: 35px; padding: 0 15px;">Caça por Tempo (AFK)</div>
            <div class="modern-card-body" style="display: flex; justify-content: space-between; align-items: center;">
                <div style="flex: 1;">
                    <p style="font-size: 12px; color: #fff; margin-bottom: 5px;">Ideal para ganhar experiência rapidamente e sem riscos.</p>
                    <p style="font-size: 11px; color: var(--text-dim);"><img src="_img/clock.png" align="absmiddle" style="width: 12px;" /> Tempo Restante: <b><?php echo $db['hunt_restantes']*5; ?> minutos</b>.</p>
                </div>
                <div style="flex: 0 0 200px;">
                    <?php if($db['hunt_restantes']==0): ?>
                        <div class="aviso" style="font-size: 11px;">Minutos diários esgotados.</div>
                    <?php else: ?>
                    <form method="post" action="?p=hunt">
                        <input type="hidden" name="hunt_tipo" value="<?php echo $c->encode('4',$chaveuniversal); ?>" />
                        <select name="hunt_4" style="width: 100%; margin-bottom: 10px; background: rgba(0,0,0,0.5); border: 1px solid #444; color: #fff; border-radius: 4px; padding: 5px;">
                            <?php $i=1; do{ ?>
                            <option value="<?php echo $c->encode($i,$chaveuniversal); ?>"><?php echo $i*5; ?> minutos</option>
                            <?php $i++; } while($i<=$db['hunt_restantes']); ?>
                        </select>
                        <input type="submit" class="modern-btn" style="width: 100%; font-size: 11px; padding: 8px;" value="Iniciar Caça" />
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
if(isset($sqlh) && $sqlh) mysqli_free_result($sqlh);
?>
<script type="text/javascript">
(function() {
    // Função de limpeza executada imediatamente para garantir compatibilidade com jQuery antigo
    var cleanEvents = function() {
        if (typeof jQuery !== 'undefined') {
            if (jQuery.fn.off) {
                jQuery('form[action="?p=hunt"]').off('submit');
            } else {
                jQuery('form[action="?p=hunt"]').unbind('submit');
            }
            // Usa .attr() em vez de .prop() para jQuery antigo (< 1.6)
            jQuery('input[type=submit]').attr('disabled', false).removeClass('disabled').val('Caçar');
            jQuery('input[value="Iniciar Caça"]').attr('disabled', false).val('Iniciar Caça');
        }
        var forms = document.getElementsByTagName('form');
        for (var i = 0; i < forms.length; i++) {
            if (forms[i].action.indexOf('?p=hunt') !== -1) {
                forms[i].onsubmit = null;
            }
        }
    };
    
    // Executar agora e no load completo
    cleanEvents();
    if (window.addEventListener) {
        window.addEventListener('load', cleanEvents, false);
    } else if (window.attachEvent) {
        window.attachEvent('onload', cleanEvents);
    }
})();
</script>