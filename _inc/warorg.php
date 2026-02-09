<?php
if($db['orgid'] == 0)
	exit("<script>self.location='?p=home'</script>");

$id = $db['orgid'];
$sqlo = mysql_query("SELECT * FROM `organizacoes` WHERE `id`='".$id."'");
if(mysql_num_rows($sqlo) == 0)
	exit("<script>self.location='?p=home'</script>");
$dbo = mysql_fetch_assoc($sqlo);

if(empty($_GET['m']))
	$_GET['m'] = 'wars';
$allow_modes = array("declare","wars"/*,"archive"*/);
?>
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
	<table width="100%" style="text-align:center">
		<tr>
			<td><a href="?p=warorg&amp;m=declare">Declarar Guerra</a></td>
			<td><a href="?p=warorg&amp;m=wars">Guerra Atuais</a></td>
			<!--td><a href="?p=warorg&amp;m=archive">Arquivos de Guerra</a></td-->
		</tr>
	</table>
	<table width="100%">
		<tr>
			<td width="100%"><?php
				if(in_array($_GET['m'], $allow_modes))
					require_once("warorg_".$_GET['m'].".php");
			?></td>
		</tr>
	</table>
</div>
<div class="box_bottom"></div>