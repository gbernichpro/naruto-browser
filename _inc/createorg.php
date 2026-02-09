<?php if($db['orgid']>0){ echo "<script>self.location='?p=myorg'</script>"; return; } ?>
<?php if($db['nivel']<1){ ?>
<div class="box_top">Criar Organizacao</div>
<div class="box_middle"><div class="aviso">Seu nível é muito baixo para ser líder de uma organização.<br />Volte quando estiver no nível 5.</div></div>
<div class="box_bottom"></div>
<?php
 } else { ?>
<?php


	if(isset($_POST['org'])){
		$valor = 30000;
		if($db['yens']<$valor){ echo "<script>self.location='?p=createorg&msg=1'</script>"; return; }
		$nome = antiinjection($_POST['org_nome']);
		$sigla = substr(strtoupper($_POST['org_sigla']),0,4);
		mysql_query("INSERT INTO organizacoes (vila,nome,sigla,data,liderid) VALUES (".$db['vila'].",'".antiinjection($nome)."','".antiinjection($sigla)."','".date('Y-m-d H:i:s')."',".$db['id'].")");
		$orgid = mysql_insert_id();
		mysql_query("INSERT INTO membros (orgid,usuarioid,posicao,rank,doado,status) VALUES ($orgid,".$db['id'].",1,'Líder',0,'sim')");
		mysql_query("UPDATE usuarios SET orgid='".antiinjection($orgid)."' , yens=yens-".$valor."  WHERE id=".$db['id']);
		echo "<script>self.location='?p=myorg'</script>";
	}
?>
<div class="box_top">Criar Organização</div>
<?php
	if(isset($_GET['msg'])){
		switch($_GET['msg']){
			case 1: echo "<script>top.$.prompt('Yens Insuficientes!');</script>"; break;

		}
	}
	?>
<div class="box_middle"><div style="background: url(../_img/_detalhes/base2.PNG);width: 720px;height: 250px;">
<table cellpadding="0" cellspacing="0" width="712" height="230"><tbody><tr><td width="150">
<img width="142" style="" src="_img/_detalhes/msg/33.png"></td><td valign="top"><br><br><br>
<div style="margin-left: -345px;margin-top: 15px;height: 0px;">
<b id="title" style="font-family: impact;font-size: 20px;font-weight: normal;color: #ffffff;"  onmouseover="style.color='#F5F5F5'" onmouseout="color.color='#ffffff'">
&raquo; Crie Seu Clã</b></div><br>
<div style="margin-left: 0px;margin-top: 35px;font-family: arial;font-size: 12px;color: #fff;"><b>
Para criar uma organização, primeiramente é necessário ter espírito</br>
de liderança. Um bom líder conseguirá administrar uma boa organização,</br>
e assim, conquistar os melhores ninjas! Além disso, também é preciso ter</br>
uma boa reserva de yens! Os custos para a criação são de 30.000,00 yens.
</br>
</div></td></tr></tbody></table></div><div class="sep"></div><div style="padding-left:5px;background:url(_img/gradient.jpg) repeat-y;font-weight:bold;color:#FFFFAA;"><img src="_img/yens.png" width="14" height="14" align="absmiddle" /> Meus yens: <?php echo number_format($db['yens'],2,',','.'); ?> yens</div><div class="sep"></div>
	<form method="post" action="?p=createorg" onsubmit="subm.value='Carregando...';subm.disabled=true;">
    <input type="hidden" id="org" name="org" value="1">
    <fieldset><legend>Criar Organização</legend>
    	<span class="destaque">Nome da Organização:</span><br />
        <input type="text" id="org_nome" name="org_nome"><br />
        <span class="sub2">Digite o nome da nova organização.</span><br /><br />
        <span class="destaque">Sigla:</span><br />
        <input type="text" id="org_sigla" name="org_sigla" maxlength="4" size="6"><br />
        <span class="sub2">Digite uma sigla de até 4 caracteres para a organização.</span>
        <div class="sep"></div>
        <div align="center"><input type="submit" id="subm" name="subm" class="botao" value="Criar Organização" /></div>
    </fieldset>
    </form>
</div>
<div class="box_bottom"></div>
<?php } ?>