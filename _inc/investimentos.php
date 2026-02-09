<?php
    if($db['orgid']==0){ echo "<script>self.location='?p=home'</script>"; return; }
	$sql = mysql_query("SELECT u.config_skin, u.orgid, m.posicao FROM usuarios u LEFT OUTER JOIN membros m ON m.usuarioid=u.id WHERE u.id='".antiinjection($_SESSION['logado'])."'");
	$db = mysql_fetch_assoc($sql);
	$sqlc = mysql_query("SELECT reserva FROM organizacoes WHERE id=".$db['orgid']);
	$dbc = mysql_fetch_assoc($sqlc);
	$sqli = mysql_query("SELECT i.*, t.*, i.id idinvestimento FROM clas_investimentos i LEFT OUTER JOIN table_investimentos t ON i.invid=t.id WHERE i.orgid=".$db['orgid']);
	$dbi = mysql_fetch_assoc($sqli);
	?>
    <div class="box_top"></div>
    <div class="box_middle"><div id="menu">
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
    Abaixo estão listados os investimentos de seu clã. Você deve utilizar os yens doados pelos membros para melhorar seu clã.<div class="sep"></div>
    <div style="padding-left:5px;background:url(_img/skins/<?php echo $db['config_skin']; ?>/gradient2.jpg) repeat-y;height:20px;line-height:20px;color:#FFFFAA;"><img src="_img/yens.png" width="14" height="14" align="absmiddle" /> <b>Reserva do Clã: <?php echo number_format($dbc['reserva'],2,',','.'); ?> yens</b></div>
    <?php $exclui=''; if(mysql_num_rows($sqli)==0) echo '<div class="aviso">Nenhum investimento foi feito no clã até o momento.</div>'; else do{ if($exclui=='') $exclui.='id<>'.$dbi['invid'].' '; else $exclui.='AND id<>'.$dbi['invid'].' '; ?>
    <div class="sep"></div>
    <table width="100%" cellpading="0" cellspacing="1" style="background:url(_img/skins/<?php echo $db['config_skin']; ?>/gradient5.jpg) repeat-x #161616;" onmouseover="style.background='url(_img/skins/<?php echo $db['config_skin']; ?>/gradient4.jpg) repeat-x #310000'" onmouseout="style.background='url(_img/skins/<?php echo $db['config_skin']; ?>/gradient5.jpg) repeat-x #161616'">
        <tr>
            <td width="120"><a href="javascript:void(0);" onClick="carregar(2);"><img src="_img/skins/<?php echo $db['config_skin']; ?>/clas/clan0<?php echo $dbi['nivel']; ?>.png" border="0" /></a></td>
            <td>
            <div align="center" style="height:20px;line-height:20px;"><b><?php echo $dbi['nome']; ?></b></div>
            <div class="sep2"></div>
            <div align="center"><span class="sub2"><?php echo $dbi['descricao']; ?></span></div>
            <div class="sep2"></div>
            <div align="center" style="color:#FFFFAA;"><b>
            <?php if($dbi['taijutsu']>0) echo '+'.$dbi['taijutsu'].' pontos no Taijutsu de todos os membros'; ?>
            <?php if($dbi['ninjutsu']>0) echo '+'.$dbi['ninjutsu'].' pontos no Ninjutsu de todos os membros'; ?>
            <?php if($dbi['genjutsu']>0) echo '+'.$dbi['genjutsu'].' pontos no Genjutsu de todos os membros'; ?>
            </b></div>
            <div class="sep2"></div>
            <div align="center"><b>Nível <?php echo $dbi['nivel']; ?></b></div>
            <div class="sep2"></div>
            <div align="center" style="color:#FFFFAA;"><b>Custo para Aprimorar:</b> <?php echo number_format(($dbi['custo']+($dbi['nivel']*20000)),2,',','.'); ?> yens</div>
            <div class="sep2"></div>
             <div align="center"><?php if($db['posicao']==3) echo '<div class="aviso">Apenas o líder e moderadores podem investir no clã .</div>'; else { ?><input type="button" class="botao" value="Aprimorar" onclick="javascript:location.href='?p=myorg&evolve=<?php echo $dbi['idinvestimento']; ?>'" /><?php } ?></div>
            </td>
        </tr>
    </table>
    <?php } while($dbi=mysql_fetch_assoc($sqli)); ?>
     <div class="sep"></div>
    Crie novos locais de treinamento para os membros de seu clã! Listamos algumas das construções na qual você pode investir. Após construí-los, é possível aprimorar cada um deles, aumentando os benefícios que estes investimentos oferecem.
    <?php
	if($exclui<>'')
		$sqlt=mysql_query("SELECT * FROM table_investimentos WHERE $exclui");
	else
		$sqlt=mysql_query("SELECT * FROM table_investimentos");
	$dbt=mysql_fetch_assoc($sqlt);
	?>
    <?php if(mysql_num_rows($sqlt)==0) echo '<div class="aviso">Você já realizou todos os investimentos possíveis em seu clã.</div>'; else do{ ?>
    <div class="sep"></div>
    <table width="100%" cellpading="0" cellspacing="1" style="background:url(_img/skins/<?php echo $db['config_skin']; ?>/gradient5.jpg) repeat-x #161616;" onmouseover="style.background='url(_img/skins/<?php echo $db['config_skin']; ?>/gradient4.jpg) repeat-x #310000'" onmouseout="style.background='url(_img/skins/<?php echo $db['config_skin']; ?>/gradient5.jpg) repeat-x #161616'">
        <tr>
            <td width="120"><a href="javascript:void(0);" onClick="carregar(2);"><img src="_img/skins/<?php echo $db['config_skin']; ?>/clas/clan0<?php echo $dbt['id']; ?>.png" border="0" /></a></td>
            <td>
            <div align="center" style="height:20px;line-height:20px;"><b><?php echo $dbt['nome']; ?></b></div>
            <div class="sep2"></div>
            <div align="center"><span class="sub2"><?php echo $dbt['descricao']; ?></span></div>
            <div class="sep2"></div>
            <div align="center" style="color:#FFFFAA;"><b>
            <?php if($dbt['tai']>0) echo '+'.$dbt['tai'].' pontos no Taijutsu de todos os membros'; ?>
            <?php if($dbt['nin']>0) echo '+'.$dbt['nin'].' pontos no Ninjutsu de todos os membros'; ?>
            <?php if($dbt['gen']>0) echo '+'.$dbt['gen'].' pontos no Genjutsu de todos os membros'; ?>
            </b></div>
            <div class="sep2"></div>
            <div align="center"><b>Inicia no Nível 1</b></div>
            <div class="sep2"></div>
            <div align="center" style="color:#FFFFAA;"><b>Custo:</b> <?php echo number_format($dbt['custo'],2,',','.'); ?> yens</div>
            <div class="sep2"></div>
            <div align="center"><?php if($db['posicao']==3) echo '<div class="aviso">Apenas o líder e moderadores podem investir no clã .</div>'; else { ?><input type="button" class="botao" value="Construir" onclick="javascript:location.href='?p=myorg&buy=<?php echo $dbt['id']; ?>'" /><?php } ?></div>
            </td>
        </tr>
    </table>
    
    <?php } while($dbt=mysql_fetch_assoc($sqlt)); ?>
	</div><div class="box_bottom"></div>