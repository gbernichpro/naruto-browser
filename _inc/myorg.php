<?php
if($db['missao']>0){ echo "<script>self.location='?p=busymission'</script>"; return; }
if($db['orgid']==0){ echo "<script>self.location='?p=home'</script>"; return; }
$id=$db['orgid'];
$sqlo=mysql_query("SELECT * FROM organizacoes WHERE id=".$id);
if(mysql_num_rows($sqlo)==0){ echo "<script>self.location='?p=home'</script>"; return; }
$dbo=mysql_fetch_assoc($sqlo);
if(isset($_GET['order'])) $order='ORDER BY missoes DESC, posicao ASC, niveluser DESC'; else $order='ORDER BY posicao ASC, niveluser DESC';
$sqlm=mysql_query("SELECT m.*,u.usuario,u.nivel niveluser,u.pontoscla, u.timestamp FROM membros m LEFT OUTER JOIN usuarios u ON m.usuarioid=u.id WHERE m.status='sim' AND m.orgid=".$id." ".$order);
$dbm=mysql_fetch_assoc($sqlm);
$sqle=mysql_query("SELECT posicao FROM membros WHERE usuarioid=".$db['id']." AND orgid=".$db['orgid']);
$dbe=mysql_fetch_assoc($sqle);
$mypos=$dbe['posicao'];
$sqlv=mysql_query("SELECT posicao FROM membros WHERE usuarioid=".$db['id']." AND orgid=".$db['orgid']." AND status='sim'");
$dbv=@mysql_fetch_assoc($sqlv);
$sqlc=mysql_query("SELECT id, sigla, nome, descricao, logo, reserva FROM organizacoes WHERE id=".$db['orgid']);
$dbc=mysql_fetch_assoc($sqlc);
if(isset($_GET['del'])){
	$id=$c->decode($_GET['del'],$chaveuniversal);
	$sqld=mysql_query("SELECT usuarioid, orgid FROM membros WHERE id=".$id);
	$dbd=mysql_fetch_assoc($sqld);
	if(mysql_num_rows($sqld)==0){ echo "<script>self.location='?p=home'</script>"; return; }
	if($dbd['orgid']<>$db['orgid']){ echo "<script>self.location='?p=home'</script>"; return; }
	if($dbo['liderid']<>$db['id']){ echo "<script>self.location='?p=home'</script>"; return; }
	mysql_query("DELETE FROM membros WHERE id=".$id);
	mysql_query("UPDATE usuarios SET orgid=0 WHERE id=".$dbd['usuarioid']);
	mysql_query("INSERT INTO mensagens (data, origem, destino, assunto, msg) VALUES ('".date('Y-m-d H:i:s')."', 0, ".$dbd['usuarioid'].", 'Você foi expulso do clã!', 'Infelizmente o Administrador do seu clã lhe expulsou do mesmo.<br />Procure um outro clã para não ficar em desvantagem.')");
	echo "<script>self.location='?p=myorg&msg=5'</script>";
}
if(isset($_GET['buy'])){
	$buy=$_GET['buy'];
	$sqlb=mysql_query("SELECT * FROM table_investimentos WHERE id=$buy");
	$dbb=@mysql_fetch_assoc($sqlb);
	if(mysql_num_rows($sqlb)==0){ echo "<script>self.location='?p=home'</script>"; return; }
	$sqlv=mysql_query("SELECT count(id) conta FROM clas_investimentos WHERE orgid=".$db['orgid']." AND invid=$buy");
	$dbv=mysql_fetch_assoc($sqlv);
	if($dbv['conta']>0){ echo "<script>self.location='?p=myorg&msg=7'</script>"; return; }
	if($dbc['reserva']<$dbb['custo']){ echo "<script>self.location='?p=myorg&msg=9'</script>"; return; }
	mysql_query("UPDATE organizacoes SET reserva=reserva-".$dbb['custo']." WHERE id=".$dbc['id']);
	mysql_query("INSERT INTO clas_investimentos (orgid, invid, taijutsu, ninjutsu, genjutsu) VALUES (".$dbc['id'].", $buy, ".$dbb['tai'].", ".$dbb['nin'].", ".$dbb['gen'].")");
	echo "<script>self.location='?p=myorg&msg=8'</script>";
}
if(isset($_GET['evolve'])){
	$evolve=$_GET['evolve'];
	$sqli=mysql_query("SELECT i.*, t.custo, t.tai, t.nin, t.gen FROM clas_investimentos i LEFT OUTER JOIN table_investimentos t ON i.invid=t.id WHERE i.id=$evolve");
	$dbi=@mysql_fetch_assoc($sqli);
	if(mysql_num_rows($sqli)==0){ echo "<script>self.location='?p=home'</script>"; return; }
	$custo=($dbi['custo']+($dbi['nivel']*20000));
	if($dbc['reserva']<$custo){ echo "<script>self.location='?p=myorg&msg=10&reserva=".$custo."'</script>"; return; }
	mysql_query("UPDATE organizacoes SET reserva=reserva-$custo WHERE id=".$dbc['id']);
	mysql_query("UPDATE clas_investimentos SET nivel=nivel+1, taijutsu=taijutsu+".$dbi['tai'].", ninjutsu=ninjutsu+".$dbi['nin'].", genjutsu=genjutsu+".$dbi['gen']." WHERE id=$evolve");
	echo "<script>self.location='?p=myorg&msg=11'</script>";
}
if(isset($_POST['donate'])){
	$valor=$_POST['don_valor'];
	vn($valor);
	$valor=floor($valor);
	if($db['yens']<$valor){ echo "<script>self.location='?p=myorg&msg=12'</script>"; return; }
	mysql_query("UPDATE usuarios SET yens=yens-$valor WHERE id=".$db['id']);
	mysql_query("UPDATE organizacoes SET reserva=reserva+$valor WHERE id=".$db['orgid']);
	mysql_query("UPDATE membros SET doado=doado+$valor WHERE usuarioid=".$db['id']);
	echo "<script>self.location='?p=myorg&msg=13'</script>";
}

if($dbo['exp']>=$dbo['expmax']){
	$dif=$dbo['exp']-$dbo['expmax'];
	mysql_query("UPDATE organizacoes SET exp=$dif, expmax=expmax+10, nivel=nivel+1 WHERE id=".$dbo['id']);
	$dbo['nivel']=$dbo['nivel']+1;
	$dbo['exp']=$dif;
	$dbo['expmax']=$dbo['expmax']+10;
}
$timeout=time()-900;
?>
<script>
function edita(mostra,esconde,botao){
	document.getElementById(mostra).style.display='block';
	document.getElementById(esconde).style.display='none';
	document.getElementById(botao).style.display='block';
}
function retorna(esconde,mostra,botao){
	document.getElementById(mostra).style.display='block';
	document.getElementById(esconde).style.display='none';
	document.getElementById(botao).style.display='none';
}
</script>
<div class="box_top">[<?php echo $dbo['sigla']; ?>] <?php echo $dbo['nome']; ?></div>
<div class="box_middle">
<div id="menu">
    <ul class="menu">
        <li><a href="#" class="parent" align="center"><span>Clãn</span></a>
            <ul>

                    <ul>

                            <ul>

                            </ul>
                        </li>


                            <ul>

                            </ul>
                        </li>
                    </ul>
                </li>
                <li><a href="?p=myorg" class="documents"><span>Informações</span></a></li>
                <li><a href="?p=configorg" class="documents"><span>Configurar</span></a></li>
                <li><a href="?p=addorg" class="documents"><span>Recrutar</span></a></li>
            </ul>
        </li>
        <li><a href="#" class="parent"><span>Outros</span></a>
            <ul>

                  <ul>

                    </ul>
                </li>

                    <ul>

                    </ul>
                </li>
                <li><a href="?p=donateorg" class="documents"><span>Doar yens</span></a></li>
                <li><a href="?p=warorg" class="documents"><span>Guerras do Clã</span></a></li>
                <li><a href="?p=cla_shop" class="documents"><span>Loja do clã</span></a></li>
				<li><a href="?p=investimentos" class="documents"><span>Investimentos</span></a></li>
            </ul>
        </li>

    </ul>
</div><div class="sep"></div>
	<?php
    if(isset($_GET['msg'])){
	switch($_GET['msg']){
		case 1: $msg='Seu clã já está no limite de membros.'; break;
		case 2: $msg='Missão não encontrada.<br />Talvez ela já tenha sido iniciada.'; break;
		case 3: $msg='Você já foi selecionado para uma missão.'; break;
		case 4: $msg='Você foi selecionado para a missão!<br />Aguarde a formação do restante do time.'; break;
		case 5: $msg='Membro excluído!'; break;
		case 6: $msg='Você não é o líder do clã.'; break;
		case 7: $msg='Seu clã já investiu neste item.'; break;
		case 8: $msg='Investimento realizado com sucesso!'; break;
		case 9: $msg='A reserva de yens do clã não é suficiente para realizar este investimento.'; break;
		case 10: $msg='A reserva de yens do clã não é suficiente para aprimorar este investimento.'; break;
		case 11: $msg='Aprimoramento realizado!'; break;
		case 12: $msg='Você não possui essa quantidade de yens para doar.'; break;
		case 13: $msg='Yens doados para o clã!'; break;
	}
	echo '<div class="aviso">'.$msg.'</div><div class="sep"></div>';
	}
	?>
	<?php
	switch($dbc['vila']){
		case 1: $txtvila='Vila da Folha'; break;
		case 2: $txtvila='Vila da Areia'; break;
		case 3: $txtvila='Vila do Som'; break;
		case 4: $txtvila='Vila da Chuva'; break;
		case 5: $txtvila='Vila da Nuvem'; break;
		case 6: $txtvila='Vila da Névoa'; break;
		case 7: $txtvila='Akatsuki'; break;
		case 8: $txtvila='Vila da Pedra'; break;
		case 9: $txtvila='Vila da Cachoeira'; break;
		case 10: $txtvila='Vila da Neve'; break;
		case 11: $txtvila='Vila da Grama'; break;
	}
	?>
	<table width="100%" cellpadding="0" cellspacing="0">
    	<tr style="background:url(_img/gradient2.jpg) repeat-y;">
        	<td width="20%" style="padding-right:20px;"><b>Clã:</b></td>
            <td><?php echo $dbo['nome']; ?></td>
            <td rowspan="9" style="background:#282828;text-align:center;" width="200"><a href="?p=configorg"><img src="<?php if($dbo['logo']=='') echo '_img/org/no_logo.png'; else echo $dbo['logo']; ?>" width="195" height="140" border="0" /></a></td>
    	</tr>
        <tr>
        	<td style="padding-right:20px;"><b>Sigla:</b></td>
            <td>[<?php echo $dbo['sigla']; ?>]</td>
        </tr>
        <tr style="background:url(_img/gradient2.jpg) repeat-y;">
        	<td style="padding-right:20px;"><b>Clã Da:</b></td>
            <td><?php echo $txtvila; ?></td>
        </tr>
        <tr>
        	<td style="padding-right:20px;"><b>Líder:</b></td>
            <td><a href="?p=view&amp;view=<?php echo $dbm['usuario']; ?>"><?php echo $dbm['usuario']; ?></a></td>
        </tr>
        <tr style="background:url(_img/gradient2.jpg) repeat-y;">
        	<td style="padding-right:20px;"><b>Fundação:</b></td>
            <td><?php $ex=explode(' ',$dbo['data']); $data=explode('-',$ex[0]); echo $data[2].'/'.$data[1].'/'.$data[0].', às '.$ex[1]; ?></td>
        </tr>
        <tr>
        	<td style="padding-right:20px;"><b>Nível:</b></td>
            <td>Nível <?php if($dbo['nivel']>60){ mysql_query("UPDATE organizacoes SET nivel=60, exp=0, expmax=99999 WHERE id=".$dbo['id']); $dbo['nivel']=60; } echo $dbo['nivel']; ?><?php if($dbo['nivel']<=59){ ?> <span class="sub2">[<?php echo $dbo['expmax']-$dbo['exp']; ?> pontos para o nível <?php echo $dbo['nivel']+1; ?>]</span><?php } else { ?> <span class="sub2">[Nível máximo!]</span><?php } ?></td>
        </tr>
         <tr style="background:url(_img/gradient2.jpg) repeat-y;">
        <td style="padding-right:20px;"><b>Reserva:</b></td>
            <td><b>Reserva do Clã:</b> <?php echo number_format($dbo['reserva'],2,',','.'); ?> yens</td>
        </tr>


        	<td style="padding-right:20px;"><b>Nível Recruta:</b></td>
            <td>Nível <?php echo $dbo['minimo']; ?></td>
        </tr>
        <tr>
        	<tr style="background:url(_img/gradient2.jpg) repeat-y;">
        	<td style="padding-right:20px;"><b>Membros:</b></td>
            <td><?php echo mysql_num_rows($sqlm); ?>/<?php echo 5+($dbo['nivel']*5); ?></td>
        </tr>

        	<td style="padding-right:20px;"><b>Clã Vitorias:</b></td>
            <td><?php echo $dbo['war_v']; ?> Guerras</td>
        </tr>
		<tr>
        	<tr style="background:url(_img/gradient2.jpg) repeat-y;">
        	<td style="padding-right:20px;"><b>Clã Derrotas:</b></td>
            <td><?php echo $dbo['war_d']; ?> Guerras</td>
        </tr>

        	<td style="padding-right:20px;"><b>Total de Guerras:</b></td>
            <td><?php echo $dbo['war_v']+$dbo['war_d']; ?> Guerras</td>
        </tr>

        <?php /*<tr style="background:url(_img/gradient.jpg) repeat-y;">
        	<td style="padding-right:20px;"><b>Valor Doado:</b></td>
            <td><?php echo number_format($dbo['deposito'],2,',','.'); ?> yens</td>
        </tr>*/ ?>
    </table>
    <div class="sep"></div>
  	<div class="apresentacao"><?php if($dbo['descricao']<>'') echo str_replace(array('<p>','</p>'),array('','<br />'),$dbo['descricao']); else echo 'Nenhuma descrição para este clã.'; ?></div>
<div class="sep"></div>
<?php if($db['missao']==0) require_once('missoes_o.php'); ?>
<div class="sub2">Para cancelar qualquer missão em que esteja, clique <a href="?p=missions&cancel=true">aqui</a>.<br />Este link também irá cancelar as missões de clã em que você se inscreveu.</div>

<div class="sep"></div>
<div id="atualiza" style='text-align:center;font-size:13px;'></div><div class="sep"></div>

    <table width="100%" cellpadding="0" cellspacing="1">
    	<?php /*<tr class="table_titulo">
        	<td width="5%">#</td>
            <td width="25%" style="text-align:left">Membro</td>
            <td width="22%">Título</td>
            <td width="28%">Posição</td>
            <?php /*<td width="20%" align="right">Yens Doados</td>
            <td>&nbsp;</td>
        </tr>*/ ?>
        <?php $i=1; do{ ?>
        <tr class="table_dados" style="background:#323232;" onmouseover="style.background='#2C2C2C'" onmouseout="style.background='#323232'">
        	<td width="5%"><?php echo $i; ?><br /><img src="_img/<?php if($dbm['timestamp']>=$timeout) echo 'online'; else echo 'offline'; ?>.png" /></td>
          	<td width="28%" style="text-align:left"><a href="?p=view&amp;view=<?php echo strtolower($dbm['usuario']); ?>"><?php echo str_replace(array('<','>'),array('&lt;','&gt;'),$dbm['usuario']); ?></a> <span class="sub2">[<?php echo $dbm['niveluser']; ?>]</span><br /><span class="sub2"><?php if($dbm['missoes']==0) echo 'Nenhuma missão'; else { echo $dbm['missoes'].' miss'; if($dbm['missoes']==1) echo 'ão'; else echo 'ões'; } ?></span></td>
            <td width="28%" style="text-align:left"><?php echo $dbm['rank']; ?></b><br /><?php echo number_format($dbm['doado'],2,',','.'); ?> <span class="sub2">yens doados</span><br /><?php if($dbm['pontoscla']>0){ echo number_format($dbm['pontoscla'],2,',','.'); echo "<span class='sub2'>Pts</span>"; } ?></td>

            <td width="22%">
            <?php if($mypos<>3){ ?>
            	<span id="org_showrank<?php echo $i; ?>"><?php echo $dbm['rank']; ?><br /><span class="sub2"><a href="javascript:void(0);" onclick="edita('org_rank<?php echo $i; ?>','org_showrank<?php echo $i; ?>','org_buttonrank<?php echo $i; ?>')">Editar</a></span></span>
				<input type="text" id="org_rank<?php echo $i; ?>" name="org_rank<?php echo $i; ?>" style="width:70px;display:none;float:left;" value="<?php echo $dbm['rank']; ?>" />
                <input type="button" class="botao2" id="org_buttonrank<?php echo $i; ?>" value="OK" style="display:none;" onclick="javascript:carregaAjax('atualiza','search_org.php?id=<?php echo $dbm['id']; ?>&rank='+document.getElementById('org_rank<?php echo $i; ?>').value,'n');retorna('org_rank<?php echo $i; ?>','org_showrank<?php echo $i; ?>','org_buttonrank<?php echo $i; ?>');document.getElementById('org_showrank<?php echo $i; ?>').innerHTML=document.getElementById('org_rank<?php echo $i; ?>').value+'<br /><span class=sub2><a href=javascript:void(0); onclick=edita(\'org_rank<?php echo $i; ?>\',\'org_showrank<?php echo $i; ?>\',\'org_buttonrank<?php echo $i; ?>\')>Editar</a></span>';" />
            <?php } else { ?>
            	<span id="org_showrank<?php echo $i; ?>"><?php echo $dbm['rank']; ?></span>
            <?php } ?>
            </td>
            <td width="28%">
            <?php if($mypos==3){ ?>
            	<?php
				switch($dbm['posicao']){
					case 1: echo 'Administrador'; break;
					case 2: echo 'Moderador'; break;
					case 3: echo 'Membro'; break;
				} ?>
			<?php } else { ?>
				<select id="org_pos<?php echo $i; ?>" name="org_pos<?php echo $i; ?>" style="display:none;float:left;">
                	<option value="Moderador"<?php if($dbm['posicao']==2) echo ' selected="selected"'; ?>>Moderador</option>
                	<option value="Membro"<?php if($dbm['posicao']==3) echo ' selected="selected"'; ?>>Membro</option>
            	</select>
            	<input type="button" class="botao2" id="org_buttonpos<?php echo $i; ?>" value="OK" style="display:none;" onclick="javascript:carregaAjax('atualiza','search_org.php?id=<?php echo $dbm['id']; ?>&pos='+document.getElementById('org_pos<?php echo $i; ?>').value,'n');retorna('org_pos<?php echo $i; ?>','org_showpos<?php echo $i; ?>','org_buttonpos<?php echo $i; ?>');document.getElementById('org_showpos<?php echo $i; ?>').innerHTML=document.getElementById('org_pos<?php echo $i; ?>').value+'<br /><span class=sub2><a href=javascript:void(0); onclick=edita(\'org_pos<?php echo $i; ?>\',\'org_showpos<?php echo $i; ?>\',\'org_buttonpos<?php echo $i; ?>\')>Editar</a></span>';" />
            	<span id="org_showpos<?php echo $i; ?>"><?php
				switch($dbm['posicao']){
					case 1: echo 'Administrador'; break;
					case 2: echo 'Moderador'; break;
					case 3: echo 'Membro'; break;
				} ?><?php if($dbo['liderid']<>$dbm['usuarioid']){ ?><br /><span class="sub2"><a href="javascript:void(0);" onclick="edita('org_pos<?php echo $i; ?>','org_showpos<?php echo $i; ?>','org_buttonpos<?php echo $i; ?>')">Editar</a></span><?php } ?></span>
            <?php } ?>
			</td>
            <?php /*<td align="right"><?php echo number_format($dbm['doado'],2,',','.'); ?></td>*/ ?>
            <td><?php if(($dbo['liderid']==$db['id'])&&($dbm['id']<>$dbo['liderid'])){ ?><a href="?p=myorg&del=<?php echo $c->encode($dbm['id'],$chaveuniversal); ?>">Apagar</a><?php } ?></td>
        </tr>
        <?php $i++; } while($dbm=mysql_fetch_assoc($sqlm)); ?>
    </table>
	<?php if($db['orgid']>0){ ?>
    <div class="sep"></div>
    <div align="center"><?php if($mypos<3){ ?><input type="button" class="botao" value="Ordenar por <?php if(!isset($_GET['order'])) echo 'Missões'; else echo 'Posição'; ?>" onclick="location.href='?p=myorg<?php if(!isset($_GET['order'])) echo '&order=missions'; ?>'" />&nbsp;<?php } ?><input type="button" class="botao" onclick="location.href='?p=<?php if($mypos==1) echo 'destroyorg'; else echo 'leaveorg'; ?>';" value="<?php if($mypos==1) echo 'Destruir'; else echo 'Deixar'; ?> Clã" /></div>
    <?php } ?>
</div>
<div class="box_bottom"></div>
<?php
@mysql_free_result($sqlo);
@mysql_free_result($sqlm);
?>
