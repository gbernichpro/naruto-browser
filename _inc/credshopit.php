<div class="box_top">Credshop</div>
<div class="box_middle">
<div style="background: url(../_img/_detalhes/base2.PNG);width: 720px;height: 250px;">
<table cellpadding="0" cellspacing="0" width="712" height="230"><tbody><tr><td width="150">
<img width="142" style="" src="_img/_detalhes/msg/8.png"></td><td valign="top"><br><br><br>
<div style="margin-left: -345px;margin-top: 15px;height: 0px;">
<b id="title" style="font-family: impact;font-size: 20px;font-weight: normal;color: #ffffff;"  onmouseover="style.color='#F5F5F5'" onmouseout="color.color='#ffffff'">
&raquo; Transferência!</b></div><br>
<div style="margin-left: 0px;margin-top: 35px;font-family: arial;font-size: 12px;color: #fff;"><b>
Agora ninjas vip tem a opção de transferencias de creditos então basta</br>
apenas colocar o nome do  usuario e a quantia a ser enviada , aproveite!.</br>
</b>
</div></td></tr></tbody></table></div>
<div class="sep"></div>
	<div style="background:url(_img/gradient.jpg) repeat-y;color:#FFFFAA;"><img src="_img/yens.png" width="14" height="14" /> <b>Meus Creditos: <?php echo number_format($db['creditos'],2,',','.'); ?> Creditos</b></div><div class="sep"></div>
		<div style="background:url(_img/gradient.jpg) repeat-y;color:#FFFFAA;"><img src="_img/yens.png" width="14" height="14" /> <b>Transferidos: <?php echo number_format($db['creditostransferidos'],2,',','.'); ?> Creditos</b></div><div class="sep"></div>
        <div style="background:url(_img/gradient.jpg) repeat-y;color:#FFFFAA;"><img src="_img/yens.png" width="14" height="14" /> <b>Meus Yens: <?php echo number_format($db['yens'],2,',','.'); ?> Yens</b></div><div class="sep"></div>
<?php if(isset($_GET['msg'])){
	switch($_GET['msg']){
		case 1: $msg='Sem creditos suficientes.'; break;
		case 2: $msg='Usuario nao existe.'; break;
		case 3: $msg='Quantidade invalida.'; break;
		case 4: $msg='Funcao apenas para jogadores vips.'; break;
		case 5: $msg='Credito(s) enviados com sucesso.'; return;
		case 6: $msg='Numero desconhecido invalido.'; break;
		case 7: $msg='Yens transferidos com sucesso olhe o log nas suas mensagems!.'; break;
		case 8: $msg='Nivel insuficiente para transferir yens necessario nivel 10 ou mais!.'; break;
		case 9: $msg='Você não tem conta em banco!.'; break;
		case 10: $msg='Você ja tem uma conta de transferencias!.'; break;
		case 11: $msg='Quantidade ultrapassou o limite e não foi enviado!.'; break;
		case 12: $msg='Transferencias de yens aberta com sucesso'; break ;
		case 13: $msg='Nivel insuficiente para desbloquear esta funçãp';break;
		case 14: $msg='Você não pode ultrapassar seus yens transferidos';break;
	}
	echo '<div class="aviso">'.$msg.'</div><div class="sep"></div>';
	}



?>
<ul class="menu">


	<li><a href="#">Especiais</a>

		<ul>
			<li><a href="?p=credshop&amp;category=bijuu" class="documents">Bijuus</a></li>
			<li><a href="?p=credshop&amp;category=animais" class="documents">Animais</a></li>
			<li><a href="?p=credshop&amp;category=doujutsu" class="documents">Doujutsu</a></li>
		    <li><a href="?p=credshop&amp;category=rare" class="documents">Pedra Rara</a></li>
		    <li><a href="?p=credshopit" class="documents">Transferencias</a></li>
		</ul>

	</li>
	<li><a href="#">Equipamentos</a>
       <ul>
	  <li><a href="?p=credshop&amp;category=weapons" class="documents"><span>Armas</span></a></li>
                <li><a href="?p=credshop&amp;category=armors" class="documents"><span>Vestimentas</span></a></li>
                <li><a href="?p=credshop&amp;category=boots" class="documents"><span>Calçados</span></a></li>
                <li><a href="?p=credshop&amp;category=acessorios" class="documents"><span>Acessorios</span></a></li>
            </ul>
	</li>

	<li><a href="#">Personagem</a>
       <ul>
	  <li><a href="?p=credshop&amp;category=vilas" class="documents"><span>Vila</span></a></li>
                <li><a href="?p=credshop&amp;category=upgrade" class="documents"><span>Energia</span></a></li>
                <li><a href="?p=credshop&amp;category=yens" class="documents"><span>Yens</span></a></li>
                <li><a href="?p=credshop&amp;category=vip" class="documents"><span>Vip</span></a></li>
                <li><a href="?p=credshop&amp;category=nick" class="documents"><span>Usuario</span></a></li>
                <li><a href="?p=credshop&amp;category=chars" class="documents"><span>Personagems</span></a></li>
            </ul>
	</li>


</ul>
<div style="text-align:left">
<div align="center"><form method="post" action="?p=credshoptrans" name="comprar" >
<input value="Usuario" name="usuario" type="text"><br>
<input value="Quantidade" name="quantidade" type="text">
<div class="sep"></div>
<input name="transferir" value="Transferir" class="botao" type="submit"></div>
    </form>
</div></div>



    <div class="box_bottom"></div>


