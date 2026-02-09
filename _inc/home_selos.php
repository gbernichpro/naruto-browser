<?php
$sqlp = mysql_query("SELECT p.exp, p.expira, t.* FROM selos p LEFT OUTER JOIN table_selos t ON p.seloid=t.id WHERE p.usuarioid='".$db['id']."'");
$dbp = mysql_fetch_assoc($sqlp);
if(mysql_num_rows($sqlp)>0){ ?>
<div class="box_top">Selo</div>
<div class="box_middle">Abaixo estão as informações do seu selo.<div class="sep"></div>
    <table width="100%" cellpading="0" cellspacing="1" style="background:url(_img/skins/naruto/gradient5.jpg) repeat-x #161616;" onmouseover="style.background='url(_img/skins/naruto/gradient4.jpg) repeat-x #320000'" onmouseout="style.background='url(_img/skins/naruto/gradient5.jpg) repeat-x #161616'">
        <tr>
            <td width="220"><img src="_img/skins/naruto/selos/<?php echo $dbp['imagem']; ?>.png" /></td>
            <td>
            <?php
            $tai=$dbp['taijutsu'];
            $nin=$dbp['ninjutsu'];
            $gen=$dbp['genjutsu'];
            ?>
            <div align="center" style="height:20px;line-height:20px;"><b><?php echo $dbp['nome']; ?></b></div>
            <div class="sep2"></div>
            <div style="float:left;width:170px;text-align:left;margin-right:10px;"><?php echo $dbp['descricao']; ?></div>
            <div style="float:left;width:150px;color:#FFFFFF;text-align:center;font-size:13px;font-weight:bold;">
            <div style="background:url(_img/skins/naruto/shop/atributo<?php if($tai<0) echo '_r'; if($tai==0) echo '_a'; ?>.png) no-repeat center;line-height:42px;width:50px;float:left;"><?php if($tai>0) echo '+'.$tai; else echo $tai; ?></div>
            <div style="background:url(_img/skins/naruto/shop/atributo<?php if($nin<0) echo '_r'; if($nin==0) echo '_a'; ?>.png) no-repeat center;line-height:42px;width:50px;float:left;"><?php if($nin>0) echo '+'.$nin; else echo $nin; ?></div>
            <div style="background:url(_img/skins/naruto/shop/atributo<?php if($gen<0) echo '_r'; if($gen==0) echo '_a'; ?>.png) no-repeat center;line-height:42px;width:50px;float:left;"><?php if($gen>0) echo '+'.$gen; else echo $gen; ?></div>
            <br />
            <div style="font-size:8px;font-weight:normal;font-family:Arial;">
                <div style="width:50px;float:left;">TAIJUTSU</div>
                <div style="width:50px;float:left;">NINJUTSU</div>
                <div style="width:50px;float:left;">GENJUTSU</div>
            </div>
            </div>
            <div class="clear"></div>
            <div class="sep2"></div>
            <div align="center" style="color:#FFFFAA;">
            </div>
            <div class="sep2"></div>
            <div align="center" style="color:#FFFFAA;">
            <?php
			$expira=$dbp['expira'];
			$ex=explode(' ',$expira);
			$data=explode('-',$ex[0]);
			$hora=explode(':',$ex[1]);
			?>
            <span><b>Selo Ativo até </b> <?php echo $data[2].'/'.$data[1].'/'.$data[0].', às '.$hora[0]; ?> horas</span>
            </div>
            </td>
        </tr>
    </table>
</div>
<div class="box_bottom"></div>
<?php } ?>