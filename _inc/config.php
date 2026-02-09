 <div class="box_top">Configuracoes</div>
<div class="box_middle">

	  <ul class="menu">


	<li><a href="?p=config">Inicio</a>


	</li>
	<li><a href="#">Senhas</a>
       <ul>
	  <li><a href="?p=config&amp;type=pass" class="documents"><span>Senha</span></a></li>
	  <li><a href="?p=config&amp;type=pass_loja" class="documents"><span>Minha loja</span></a></li>
            </ul>
	</li>

	<li><a href="#">Outros</a>
       <ul>
	  <li><a href="?p=config&amp;type=batt" class="documents"><span>Batalha</span></a></li>
      <li><a href="?p=config&amp;type=conn" class="documents"><span>Conectividade</span></a></li>
      <li><a href="?p=config&amp;type=char" class="documents"><span>Personagem</span></a></li>
      <li><a href="?p=config&amp;type=avat" class="documents"><span>Avatar</span></a></li>

       </ul>
	</li>


</ul> <!-- end .menu -->

    <div class="sep"></div>
    <script>
function show(div,opt){
	if(opt=='brasil')
		document.getElementById(div).style.display='block';
	else
		document.getElementById(div).style.display='none';
}
</script>
    <?php
	if(!isset($_GET['type'])) require_once('config_inicial.php'); else {
		switch($_GET['type']){
			case 'pass': require_once('config_pass.php'); return;
			case 'pass_loja': require_once('config_pass_loja.php'); break;
			case 'batt': require_once('config_batt.php'); break;
			case 'conn': require_once('config_conn.php'); break;
			case 'char': require_once('config_char.php'); break;
			case 'avat': require_once('config_avat.php'); break;
		}
	}
	?>
</div>
<div class="box_bottom"></div>