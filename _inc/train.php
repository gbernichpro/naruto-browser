<?php
require_once('trava.php');
require_once('verificar.php');

if(isset($_POST['train_taijutsu'])){
	$taijutsu = (int)$_POST['train_taijutsu'];
	$ninjutsu = (int)$_POST['train_ninjutsu'];
	$genjutsu = (int)$_POST['train_genjutsu'];
	$restante = (isset($_POST['restante']) ? (float)$_POST['restante'] : 0);

	if($restante < 0){ 
		echo "<script>alert('Saldo insuficiente!'); self.location='?p=train'</script>"; 
		exit(); 
	}

	if($taijutsu <= 0 || $ninjutsu <= 0 || $genjutsu <= 0){ 
		echo "<script>self.location='?p=home'</script>"; 
		exit(); 
	}

	$total = 0;
	// Cálculo seguro no backend
	if($taijutsu > $db['taijutsu']) {
		for($i = $db['taijutsu']; $i < $taijutsu; $i++) {
			$total += round(($i * 2) + ($i * $i) + ($i * 0.2));
		}
	}
	if($ninjutsu > $db['ninjutsu']) {
		for($i = $db['ninjutsu']; $i < $ninjutsu; $i++) {
			$total += round(($i * 2) + ($i * $i) + ($i * 0.2));
		}
	}
	if($genjutsu > $db['genjutsu']) {
		for($i = $db['genjutsu']; $i < $genjutsu; $i++) {
			$total += round(($i * 2) + ($i * $i) + ($i * 0.2));
		}
	}

	if($total > $db['yens']){ 
		echo "<script>alert('Yens insuficientes!'); self.location='?p=train&msg=2'</script>"; 
		exit(); 
	}

    // Usando Prepared Statement para segurança
    $stmt = mysqli_prepare($mysqli_link, "UPDATE usuarios SET yens=yens-?, taijutsu=?, ninjutsu=?, genjutsu=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "ddiii", $total, $taijutsu, $ninjutsu, $genjutsu, $db['id']);
    
	if(!mysqli_stmt_execute($stmt)) {
		die("Erro ao atualizar treino: " . mysqli_error($mysqli_link));
	}

	echo "<script>self.location='?p=train&msg=1&yens=".$total."'</script>";
	exit();
}
?>
<script>
var taijutsu=<?php echo $db['taijutsu']; ?>;
var taipadrao=taijutsu;
var ninjutsu=<?php echo $db['ninjutsu']; ?>;
var ninpadrao=ninjutsu;
var genjutsu=<?php echo $db['genjutsu']; ?>;
var genpadrao=genjutsu;
var total=0;
var yens=<?php echo $db['yens']; ?>;
function visibility(){
	setataijutsu=document.getElementById('taidown');
	setaninjutsu=document.getElementById('nindown');
	setagenjutsu=document.getElementById('gendown');
	if(taijutsu<=taipadrao) setataijutsu.style.visibility='hidden'; else setataijutsu.style.visibility='visible';
	if(ninjutsu<=ninpadrao) setaninjutsu.style.visibility='hidden'; else setaninjutsu.style.visibility='visible';
	if(genjutsu<=genpadrao) setagenjutsu.style.visibility='hidden'; else setagenjutsu.style.visibility='visible';
	if((taijutsu<=taipadrao)&&(ninjutsu<=ninpadrao)&&(genjutsu<=genpadrao)) document.getElementById('train_button').style.display='none'; else document.getElementById('train_button').style.display='block';
}
function float2moeda(num) {
   x = 0;
   if(num<0) {
      num = Math.abs(num);
      x = 1;
   }
      if(isNaN(num)) num = "0";
      cents = Math.floor((num*100+0.5)%100);
   num = Math.floor((num*100+0.5)/100).toString();
   if(cents < 10) cents = "0" + cents;
      for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++)
         num = num.substring(0,num.length-(4*i+3))+'.'
               +num.substring(num.length-(4*i+3));
			   ret = num + ',' + cents;
			   if (x == 1) ret = ' - ' + ret;return ret;
}
function soma(ind,direcao){
	if(ind=='tai'){
		if(direcao=='up')
			somar=Math.round((taijutsu*2)+(taijutsu*taijutsu)+(taijutsu*0.2));
		else {
			somar=Math.round(((taijutsu-1)*2)+((taijutsu-1)*(taijutsu-1))+((taijutsu-1)*0.2));
			somar=(somar)*(-1);
		}
	}
	if(ind=='nin'){
		if(direcao=='up')
			somar=Math.round((ninjutsu*2)+(ninjutsu*ninjutsu)+(ninjutsu*0.2));
		else {
			somar=Math.round(((ninjutsu-1)*2)+((ninjutsu-1)*(ninjutsu-1))+((ninjutsu-1)*0.2));
			somar=(somar)*(-1);
		}
	}
	if(ind=='gen'){
		if(direcao=='up')
			somar=Math.round((genjutsu*2)+(genjutsu*genjutsu)+(genjutsu*0.2));
		else {
			somar=Math.round(((genjutsu-1)*2)+((genjutsu-1)*(genjutsu-1))+((genjutsu-1)*0.2));
			somar=(somar)*(-1);
		}
	}
	total=total+somar;
	restante=yens-total;
	document.getElementById('totaltrain').innerHTML=float2moeda(total);
	document.getElementById('resttrain').innerHTML=float2moeda(restante);
	document.getElementById('restante').value=restante;
}
function newvalues(att){
	if(att=='tai'){
		valor=Math.round((taijutsu*2)+(taijutsu*taijutsu)+(taijutsu*0.2));
		document.getElementById('taivalue').innerHTML=float2moeda(valor)+' yens';
	} else
	if(att=='nin'){
		valor=Math.round((ninjutsu*2)+(ninjutsu*ninjutsu)+(ninjutsu*0.2));
		document.getElementById('ninvalue').innerHTML=float2moeda(valor)+' yens';
	} else
	if(att=='gen'){
		valor=Math.round((genjutsu*2)+(genjutsu*genjutsu)+(genjutsu*0.2));
		document.getElementById('genvalue').innerHTML=float2moeda(valor)+' yens';
	}
}
function sortNumber(a,b){
	return b - a;
}
function atualizabarras(){
	max=100;
	array=new Array(taijutsu,ninjutsu,genjutsu);
	array.sort(sortNumber);
	
    // Calculate percentage based on max value in the set
    var current_max = array[0];
    
    document.getElementById('taibar_cont').style.width = Math.round((taijutsu / current_max) * 100) + '%';
    document.getElementById('ninbar_cont').style.width = Math.round((ninjutsu / current_max) * 100) + '%';
    document.getElementById('genbar_cont').style.width = Math.round((genjutsu / current_max) * 100) + '%';
}
function change(at,dir){
    // Update hidden inputs in the form
    var form = document.getElementById('form_train');
	if(dir>0)
		soma(at,'up');
	else
		soma(at,'down');
        
	if(at=='tai'){
		el=document.getElementById('tai');
		taijutsu=taijutsu+dir;
        el.innerHTML=taijutsu;
		form.train_taijutsu.value=taijutsu;
		visibility();
		newvalues('tai');
	} else
	if(at=='nin'){
		el=document.getElementById('nin');
		ninjutsu=ninjutsu+dir;
        el.innerHTML=ninjutsu;
		form.train_ninjutsu.value=ninjutsu;
		visibility();
		newvalues('nin');
	} else
	if(at=='gen'){
		el=document.getElementById('gen');
		genjutsu=genjutsu+dir;
        el.innerHTML=genjutsu;
		form.train_genjutsu.value=genjutsu;
		visibility();
		newvalues('gen');
	}
	if(restante<0)
		document.getElementById('train_button').style.display='none';
	atualizabarras();
}
</script>
<?php
$max=194;
function equacao($atr){
	$resultado=round(($atr*2)+($atr*$atr)+($atr*0.2));
	return $resultado;
}
$src="_img/bars/center_bar_tai.png";
$src1="_img/bars/center_bar_nin.png";
$src2="_img/bars/center_bar_gen.png";
$src3="_img/bars/center_bar_en.png";
$src4="_img/bars/center_bar_exp.png";
$array=array("t"=>$db['taijutsu'],"n"=>$db['ninjutsu'],"g"=>$db['genjutsu']);
rsort($array);
$array2=array("t"=>$db['taijutsu'],"n"=>$db['ninjutsu'],"g"=>$db['genjutsu']);
arsort($array2);
?>
<div class="modern-card">
    <div class="modern-card-header">Centro de Treinamento Ninja</div>
    <div class="modern-card-body">
        <div style="display: flex; gap: 20px; align-items: center; background: rgba(0,0,0,0.2); padding: 20px; border-radius: 8px; border: 1px solid var(--border-subtle); margin-bottom: 20px;">
            <img width="80" src="_img/_detalhes/msg/1.png" style="filter: drop-shadow(0 0 10px rgba(255,100,0,0.3));">
            <div>
                <h3 style="color: var(--primary-red); margin: 0 0 10px 0; font-family: var(--font-header);">Hora de Fortalecer seu Shinobi!</h3>
                <p style="color: var(--text-dim); margin: 0; font-size: 13px; line-height: 1.5;">
                    Utilize as setas para distribuir seus Yens e aumentar seus atributos. 
                    O custo aumenta progressivamente. Clique em <b>Treinar</b> quando estiver satisfeito com a distribuição.
                </p>
            </div>
        </div>
<div class="sep"></div>
	<?php if(isset($_GET['msg'])){
		switch($_GET['msg']){
			case 1: if(isset($_GET['yens'])) $yens=$_GET['yens']; else $yens=0; $msg='Treino realizado com sucesso! Foram gastos <b>'.number_format($yens,2,',','.').' yens</b> para realizar o treino.'; break;
			case 2: $msg='Yens insuficientes!'; break;
		}
	echo '<div class="aviso">'.$msg.'</div><div class="sep"></div>';
	} ?>
        <div style="background: rgba(0,0,0,0.3); border-radius: 8px; border: 1px solid var(--border-subtle); padding: 15px;">
            <div style="padding: 10px; border-bottom: 1px solid rgba(255,255,255,0.05); margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center;">
                <span style="color: var(--text-dim); font-size: 12px;"><img src="_img/yens.png" width="14" align="absmiddle" /> Meus Yens:</span>
                <b style="color: #fff;"><?php echo number_format($db['yens'],2,',','.'); ?> yens</b>
            </div>

            <table width="100%" cellpadding="0" cellspacing="0">
                <!-- Taijutsu Row -->
                <tr style="height: 50px;">
                    <td width="40" align="center"><img src="template/ico_tai.png" width="20" style="filter: drop-shadow(0 0 5px rgba(255,0,0,0.5));"></td>
                    <td style="padding: 0 15px;">
                        <div style="height: 8px; background: rgba(255,255,255,0.05); border-radius: 4px; overflow: hidden; border: 1px solid rgba(255,255,255,0.1);">
                            <div id="taibar_cont" style="height: 100%; transition: width 0.3s ease; background: linear-gradient(90deg, #ff4d4d, #ff0000); width: <?php echo ($db['taijutsu']/$array[0])*100; ?>%;"></div>
                        </div>
                    </td>
                    <td width="60" align="center">
                        <div style="display: flex; gap: 5px; justify-content: center;">
                            <img src="_img/foda/up.png" style="cursor:pointer; transition: transform 0.1s;" onclick="change('tai',1);" onmousedown="this.style.transform='scale(0.9)'" onmouseup="this.style.transform='scale(1)'">
                            <img id="taidown" src="_img/foda/down.png" style="cursor:pointer; visibility:hidden; transition: transform 0.1s;" onclick="change('tai',-1);" onmousedown="this.style.transform='scale(0.9)'" onmouseup="this.style.transform='scale(1)'">
                        </div>
                    </td>
                    <td width="80" align="center"><b style="color: #fff; font-size: 16px;">| <span id="tai"><?php echo $db['taijutsu']; ?></span> |</b></td>
                    <td width="120" align="right"><b style="color: var(--primary-red); font-size: 12px;"><div id="taivalue"><?php echo number_format(equacao($db['taijutsu']),2,',','.'); ?> yens</div></b></td>
                </tr>
                <!-- Ninjutsu Row -->
                <tr style="height: 50px;">
                    <td align="center"><img src="template/ico_nin.png" width="20" style="filter: drop-shadow(0 0 5px rgba(0,100,255,0.5));"></td>
                    <td style="padding: 0 15px;">
                        <div style="height: 8px; background: rgba(255,255,255,0.05); border-radius: 4px; overflow: hidden; border: 1px solid rgba(255,255,255,0.1);">
                            <div id="ninbar_cont" style="height: 100%; transition: width 0.3s ease; background: linear-gradient(90deg, #4d94ff, #0066ff); width: <?php echo ($db['ninjutsu']/$array[0])*100; ?>%;"></div>
                        </div>
                    </td>
                    <td align="center">
                        <div style="display: flex; gap: 5px; justify-content: center;">
                            <img src="_img/foda/up.png" style="cursor:pointer;" onclick="change('nin',1);">
                            <img id="nindown" src="_img/foda/down.png" style="cursor:pointer; visibility:hidden;" onclick="change('nin',-1);">
                        </div>
                    </td>
                    <td align="center"><b style="color: #fff; font-size: 16px;">| <span id="nin"><?php echo $db['ninjutsu']; ?></span> |</b></td>
                    <td align="right"><b style="color: var(--primary-red); font-size: 12px;"><div id="ninvalue"><?php echo number_format(equacao($db['ninjutsu']),2,',','.'); ?> yens</div></b></td>
                </tr>
                <!-- Genjutsu Row -->
                <tr style="height: 50px;">
                    <td align="center"><img src="template/ico_gen.png" width="20" style="filter: drop-shadow(0 0 5px rgba(150,0,255,0.5));"></td>
                    <td style="padding: 0 15px;">
                        <div style="height: 8px; background: rgba(255,255,255,0.05); border-radius: 4px; overflow: hidden; border: 1px solid rgba(255,255,255,0.1);">
                            <div id="genbar_cont" style="height: 100%; transition: width 0.3s ease; background: linear-gradient(90deg, #b366ff, #8000ff); width: <?php echo ($db['genjutsu']/$array[0])*100; ?>%;"></div>
                        </div>
                    </td>
                    <td align="center">
                        <div style="display: flex; gap: 5px; justify-content: center;">
                            <img src="_img/foda/up.png" style="cursor:pointer;" onclick="change('gen',1);">
                            <img id="gendown" src="_img/foda/down.png" style="cursor:pointer; visibility:hidden;" onclick="change('gen',-1);">
                        </div>
                    </td>
                    <td align="center"><b style="color: #fff; font-size: 16px;">| <span id="gen"><?php echo $db['genjutsu']; ?></span> |</b></td>
                    <td align="right"><b style="color: var(--primary-red); font-size: 12px;"><div id="genvalue"><?php echo number_format(equacao($db['genjutsu']),2,',','.'); ?> yens</div></b></td>
                </tr>
            </table>
        </div>

        <div style="margin-top: 20px; display: flex; flex-direction: column; gap: 10px;">
            <div style="padding: 10px; background: rgba(255,0,0,0.1); border-radius: 4px; border: 1px solid rgba(255,0,0,0.2); display: flex; justify-content: space-between;">
                <span style="color: var(--text-dim); font-size: 12px;">Custo Total do Treino:</span>
                <b style="color: var(--primary-red); font-size: 14px;"><img src="_img/yens_neg.png" align="absmiddle" /> <span id="totaltrain">0,00</span> yens</b>
            </div>
            <div style="padding: 10px; background: rgba(0,255,0,0.05); border-radius: 4px; border: 1px solid rgba(0,255,0,0.1); display: flex; justify-content: space-between;">
                <span style="color: var(--text-dim); font-size: 12px;">Saldo Restante:</span>
                <b style="color: #0f0; font-size: 14px;"><img src="_img/yens.png" width="14" align="absmiddle" /> <span id="resttrain"><?php echo number_format($db['yens'],2,',','.'); ?></span> yens</b>
            </div>
        </div>

        <div id="train_button" style="display:none; margin-top: 25px; text-align: center;">
            <form method="post" action="?p=train" id="form_train" onsubmit="var b=this.querySelector('input[type=submit]'); if(b) { b.value='Carregando...'; b.disabled=true; }">
                <input type="hidden" id="train_taijutsu" name="train_taijutsu" value="<?php echo $db['taijutsu']; ?>" />
                <input type="hidden" id="train_ninjutsu" name="train_ninjutsu" value="<?php echo $db['ninjutsu']; ?>" />
                <input type="hidden" id="train_genjutsu" name="train_genjutsu" value="<?php echo $db['genjutsu']; ?>" />
                <input type="hidden" id="restante" name="restante" value="" />
                <input type="submit" id="subm" name="subm" class="modern-btn" style="padding: 12px 40px; font-size: 16px; min-width: 200px;" value="Confirmar Treino" />
            </form>
        </div>
    </div>
</div>