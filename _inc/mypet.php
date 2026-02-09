<div class="box_top">Kuchiyose no Jutsu</div>
<div class="box_middle">
	<div class="sep"></div>
    <table width="100%" cellpading="0" cellspacing="1" style="background:url(_img/skins/naruto/gradient5.jpg) repeat-x #161616;" onmouseover="style.background='url(_img/skins/naruto/gradient4.jpg) repeat-x #320000'" onmouseout="style.background='url(_img/skins/naruto/gradient5.jpg) repeat-x #161616'">
        <tr>
            <td width="220"><img src="_img/skins/naruto/kuchiyose/<?php echo $dbt['imagem']; ?>.png" /></td>
            <td>
            <?php
            $tai=$dbt['taijutsu']-$dbp['taijutsu'];
            $nin=$dbt['ninjutsu']-$dbp['ninjutsu'];
            $gen=$dbt['genjutsu']-$dbp['genjutsu'];
            ?>
            <div align="center" style="height:20px;line-height:20px;"><b><?php echo $dbt['nome']; ?></b></div>
            <div class="sep2"></div>
            <div style="float:left;width:170px;text-align:left;margin-right:10px;"><?php echo $dbt['descricao']; ?></div>
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
                <span style="margin-right:50px;"><b>Nível Necessário [<?php echo $dbt['nivel']; ?>]</b></span>
                <span><b>Tempo de Vida:</b> <?php echo $dbt['vida']; ?> dias</span>
            </div>
            <div class="sep2"></div>
            <div align="center" style="color:#FFFFAA;"><b>Valor:</b> <?php echo number_format($dbt['valor'],2,',','.'); ?> yens</div>
            <div class="sep2"></div>
            <div align="center">
            <?php
            if($dbt['vip']=='sim')
                $libera='nao';
            else
                $libera='sim';
            if($libera=='sim')
                echo '<input type="button" class="botao" value="Aprender" onclick="location.href=\'?p=kuchiyose&learn='.$dbt['id'].'\'" />';
            else
                echo '<div class="aviso">Item exclusivo para jogadores VIP.</div>';
            ?>
            </div>
            </td>
        </tr>
    </table>
</div>
<div class="box_bottom"></div>