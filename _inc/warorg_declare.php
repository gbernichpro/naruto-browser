<?php
if(isset($_GET['act']) && $_GET['act'] == "dcl" && empty($_GET['r'])){
	$limit = 3;
	$nivel_min = (($dbo['nivel']-$limit)<=0)?1:($dbo['nivel']-$limit);
	$nivel_max = ($dbo['nivel']+$limit);
	if($dbo['reserva'] < 30000)
		$error = "Para declarar uma guerra o clã deve ter 30.000,00 Yens";
	if(empty($error) && strlen(trim($_POST['subject'])) < 3 || strlen(trim($_POST['subject'])) > 50)
		$error = "O titulo da guerra deve ter entre 3 e 50 caracteres.";
	$check = mysql_fetch_assoc(mysql_query("SELECT COUNT(`id`) AS `exist`,`id`,`sigla`,`nivel` FROM `organizacoes` WHERE `sigla`='".mysql_real_escape_string($_POST['org'])."'"));
	if(empty($error) && $check['exist'] != 1)
		$error = "Clã não encontrado.";
	if(empty($error) && $check['id'] == $dbo['id'])
		$error = "Você não pode declarar uma guerra ao seu clã.";
	if(empty($error) && $dbo['liderid'] != $db['id'])
		$error = "Para declarar uma guerra, você deve ser o líder do clã.";
	if(empty($error) && $check['nivel'] < $nivel_min || $check['nivel'] > $nivel_max)
		$error = "Você só pode atacar clãns entre o nível ".$nivel_min." e o nível ".$nivel_max.".";
	$check2 = mysql_query("SELECT `id` FROM `org_wars_declare` WHERE `to_org`='".$dbo['id']."' AND `from_org`='".$check['id']."'") or die(mysql_error());
	if(empty($error) && mysql_num_rows($check2) != 0)
		$error = "Já existe uma guerra declarada contra este clã!";
	if(empty($error) && strlen(trim($_POST['mensagem'])) < 10 || strlen(trim($_POST['mensagem'])) > 500)
		$error = "A mensagem da guerra deve ter entre 10 e 500 caracteres.";
	if(empty($error)){
		mysql_query("UPDATE `organizacoes` SET `reserva`=`reserva`-'30000' WHERE `id`='".$db['orgid']."' LIMIT 1") or die(mysql_error());
		mysql_query("INSERT INTO `org_wars_declare` (`to_org`,`from_org`,`title`,`text`,`time`) VALUES ('".$dbo['id']."','".$check['id']."','".$_POST['subject']."','".$_POST['mensagem']."','".time()."')") or die(mysql_error());
		$id0 = mysql_insert_id();
		mysql_query("INSERT INTO `org_wars_declare` (`to_org`,`from_org`,`title`,`text`,`time`) VALUES ('".$check['id']."','".$dbo['id']."','".$_POST['subject']."','".$_POST['mensagem']."','".time()."')") or die(mysql_error());
		$id1 = mysql_insert_id();

		$war_id = $id0.";".$id1;
		mysql_query("INSERT INTO `org_wars` (`org`,`war_id`) VALUES ('".$dbo['id']."','".$war_id."')") or die(mysql_error());
		mysql_query("INSERT INTO `org_wars` (`org`,`war_id`) VALUES ('".$check['id']."','".$war_id."')") or die(mysql_error());
		mysql_query("UPDATE `org_wars_declare` SET `war_id`='".$war_id."' WHERE `id`='".$id0."'") or die(mysql_error());
		mysql_query("UPDATE `org_wars_declare` SET `war_id`='".$war_id."' WHERE `id`='".$id1."'") or die(mysql_error());

		$select = mysql_query("SELECT * FROM `usuarios` WHERE `orgid`='".$dbo['id']."' OR `orgid`='".$check['id']."'") or die(mysql_error());
		$date = date('Y-m-d H:i:s');
		$assunto = "O clã ".$dbo['sigla']." declarou guerra ao clã ".$check['sigla'].".";
		$mensagem = "<b>Titulo da guerra:</b> ".$_POST['subject']."<br />";
		$mensagem .= "<b>Mensagem:</b><br />".$_POST['mensagem'];
		while($row = mysql_fetch_assoc($select)){
			mysql_query("INSERT INTO `mensagens` (`data`,`origem`,`destino`,`assunto`,`msg`) VALUES ('".$date."','0','".$row['id']."','".$assunto."','".$mensagem."')") or die(mysql_error());
		}
		exit("<script>self.location='?p=warorg&m=declare&act=dcl&r=done'</script>");
	}
}
?>
<fieldset>
	<legend>Fazer declaração de guerra</legend>
	<form method="post" action="?p=warorg&amp;m=declare&act=dcl" onsubmit="var b=this.querySelector('input[type=submit]'); if(b) { b.value='Carregando...'; b.disabled=true; }">
	<table width="100%">
		<?php if(!empty($_GET['r'])){ ?><tr><td colspan="2"><div class="apresentacao">A Guerra foi declara com sucesso!</div></td></tr><?php } ?>
		<?php if(!empty($error)){ ?><tr><td colspan="2"><div class="apresentacao"><?=$error;?></div></td></tr><?php } ?>
		<tr>
			<td>Titulo da guerra:</td>
			<td><input type="text" name="subject" maxlength="50" onfocus="className='input'" onblur="className=''" /></td>
		</tr>
		<tr>
			<td>Clã inimigo: (sigla)</td>
			<td><input type="text" name="org" maxlength="50" onfocus="className='input'" onblur="className=''" /></td>
		</tr>
		<tr><td colspan="2">Mensagem da guerra:</td></tr>
		<tr><td colspan="2"><textarea name="mensagem" rows="10" onfocus="className='input'" onblur="className=''" style="width:98%;"></textarea></td></tr>
		</tr>
		<tr>
			<td>* A declaração de guerra tem um custo de 30.000,00 Yens!</td>
			<th align="right"><input type="submit" id="subm" name="subm" class="botao" value="Declarar" /></th>
		</tr>
	</table>
</fieldset>