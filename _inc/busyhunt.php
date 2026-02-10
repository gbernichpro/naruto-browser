<?php
$atual=date('Y-m-d H:i:s');
if($db['hunt']>0){
	if($atual<$db['hunt_fim']){
		$fim=$db['hunt_fim'];
		// Correção para mysqli
		$sqltempo=mysqli_fetch_assoc(mysqli_query($mysqli_link, "SELECT timediff('$fim','$atual') as fim"));
		$fim=$sqltempo['fim'];
		$msgconc='Sua caça foi concluída! Clique <a href="?p=rewardhunt">aqui</a> para receber as recompensas!';
		$msg='Você está em uma caça neste momento.<br />Faltam <b><span id="hunt_tempo">'.$fim.'</span></b> para terminar a caça.';
	} else $msgconc='Sua caça foi concluída! Clique <a href="?p=rewardhunt">aqui</a> para receber as recompensas!';
} else { echo "<script>self.location='?p=home'</script>"; exit(); }
?>
<script language="javascript" type="text/javascript">
var conc=0;
function calculafim(div,divtotal){
	if(conc==0){
	var navegador=navigator.appName;
	var tmp = document.getElementById(div).innerHTML.split(":");
	if(tmp.length < 3) return; // Proteção contra erro de split
	var s = tmp[2];
	var m = tmp[1];
	var h = tmp[0];
	s--;
	if (s < 00){ s = 59;	m--; }
	if (m < 00){ m = 59;	h--; };
	s = new String(s); if (s.length < 2) s = "0" + s;
	m = new String(m); if (m.length < 2) m = "0" + m;
	h = new String(h); if (h.length < 2) h = "0" + h;
	
	var temp = h + ":" + m + ":" + s;
	
	document.getElementById(div).innerHTML = temp;
	// document.getElementById(div).value = temp; // Elemento span não tem value, isso pode gerar erro silencioso no JS
	atualiza(temp);
	document.title='['+temp+'] Naruto <?php echo defined('NARUTO_NOME') ? NARUTO_NOME : 'Game'; ?>';
	}
}
<?php if($atual<$db['hunt_fim']) echo "window.setInterval('calculafim(\"hunt_tempo\",\"mensagem\")',1000);"; ?>

function atualiza(tempo_atual){
  	if(tempo_atual < "00:00:01"){
  		self.location="?p=rewardhunt";
  		conc=1;
	}
}
</script>

<div class="modern-card">
    <div class="modern-card-header">Status da Missão</div>
    <div class="modern-card-body" style="text-align: center; padding: 35px;">
        <div style="background: rgba(0,0,0,0.3); padding: 25px; border-radius: 8px; border: 1px solid #444; display: inline-block;">
            <img src="_img/_detalhes/msg/33.png" style="margin-bottom: 15px; width: 140px;">
            <h2 style="color: #fff; margin: 0 0 15px 0; font-size: 24px;">Ocupado!</h2>
            <div id="mensagem" style="font-size: 16px; color: #ddd;">
            	<?php
            	if(($atual<$db['hunt_fim']&&($db['hunt']>0)))
            		echo $msg;
            	else
            		echo $msgconc;
            	?>
            </div>
        </div>
    </div>
</div>
