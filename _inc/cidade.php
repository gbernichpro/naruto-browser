
<div id="city" style="display:none;">
<div class="box_top">Mapa da Vila [<a href="#" onClick="document.getElementById('city').style.display='none'">Fechar</a>]</div>
<div class="box_middle">
	<script>
	function nome(nom){
		document.getElementById('divnome').innerHTML=nom;
	}
	function nomeout(){
		document.getElementById('divnome').innerHTML='<b>Passe o mouse sobre um ponto no mapa para visualizar sua descrição.</b>';
	}
	</script>
	<?php
	$hr = date(" H ");
if($hr >= 06 && $hr<12) { $livre1 = 'dia'; 
}
else if ($hr >= 12 && $hr <18 ) { $livre1 = 'tarde'; 
}
else { $livre1 = 'noite'; }
	?>
	<div class="modalExemplo" align="center" style="background:#282828;padding-top:0px;padding-bottom:0px;margin-top:0px;margin-bottom:0px;">
		<div style="background:url(_img/city/<?php echo $livre1; ?>.png) no-repeat center;width:100%;max-width:778px;height:346px;">
			<a href="?p=ramen"><img src="_img/city/point.gif" border="0" style="position:relative; left:350px; top:220px;"   width="13" height="13" onmouseover="nome('<b>Ichiraku Bar</b>: Entre e experimente o melhor ramen da vila!')" onmouseout="nomeout()" /></a>
			<a href="?p=missions"><img src="_img/city/point.gif" border="0" style="position:relative; left:70px; top:170px;" width="13" height="13" onmouseover="nome('<b>Sala do Kage</b>: Realize missões para o kage da vila, e ganhe recompensas!')" onmouseout="nomeout()" /></a>
			<a href="?p=school"><img src="_img/city/point.gif" border="0" style="position:relative; left:-100px; top:280px;" width="13" height="13" onmouseover="nome('<b>Escola Ninja</b>: Aprenda e aperfeiçoe jutsus com nossos ótimos senseis!')" onmouseout="nomeout()" /></a>
			<a href="?p=hunt"><img src="_img/city/point.gif" border="0"  style="position:relative; left:270px; top:100px;"   width="13" height="13" onmouseover="nome('<b>Batalha</b>: Procure e enfrente ninjas de todo o mundo shinobi!')" onmouseout="nomeout()" /></a>
			<a href="?p=hunt"><img src="_img/city/point.gif" border="0"  style="position:relative; left:230px; top:80px;" width="13" height="13" onmouseover="nome('<b>Caças</b>: a melhor forma de se conseguir yens e exp.')"  onmouseout="nomeout()" /></a>

			<a href="?p=<?php if($db['orgid']>0) echo 'my'; ?>org"><img src="_img/city/point.gif" border="0" style="position:relative; left:250px; top:150px;" width="13" height="13" onmouseover="nome('<?php if($db['orgid']>0) echo '<b>Meu Clã</b>: Visite seu clã ninja atual!'; else echo '<b>Clãs</b>: Visualize os clãs existentes na vila!'; ?>')" onmouseout="nomeout()" /></a>
			<a href="?p=shop"><img src="_img/city/point.gif" border="0" style="position:relative; left:110px; top:233px;" width="13" height="13" onmouseover="nome('<b>Comércio</b>: Temos uma grande variedade de equipamentos para sua longa jornada!')" onmouseout="nomeout()" /></a>
        </div>
      <div class="sep"></div>
	  <div id="divnome" class="aviso"><b>Passe o mouse sobre um ponto no mapa para visualizar sua descrição.</b></div>
	</div>
</div>
<div class="box_bottom"></div>
</div> 