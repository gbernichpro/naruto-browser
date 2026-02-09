<?php
if($db['orgid']==0){ echo "<script>self.location='?p=home'</script>"; return; }
$sqlo = mysql_query("SELECT liderid FROM organizacoes WHERE id=".$db['orgid']);
$dbo=mysql_fetch_assoc($sqlo);

function somentenum($u){
$chars = "0123456789";
for($i=0;$i<strlen($u);$i++)
{
$ch = substr($u,$i,1);
$nol = substr_count($chars,$ch);
if($nol==0)
{
return true;
}
}
return false;
}



if(isset($_POST['don'])){

$yens = floor(antiinjection($_POST['don_yens']));
$doacaoyens  = floor(antiinjection($_POST['don_yens']));
if(ereg("^[0-9]+$",$yens)){

	vn($yens);
	if($yens>$db['yens']){ echo "<script>self.location='?p=donateorg&msg=2'</script>"; return; }
	mysql_query("UPDATE organizacoes SET reserva=reserva+$yens WHERE id=".$db['orgid']);
	mysql_query("UPDATE membros SET doado=doado+$yens WHERE orgid=".$db['orgid']." AND usuarioid=".$db['id']);
	mysql_query("UPDATE usuarios SET yens=yens-$yens WHERE id=".$db['id']);
	if($doacaoyens>999){
     $doacaoyens=floor($doacaoyens/1000);
      mysql_query("UPDATE usuarios SET pontoscla=pontoscla+$doacaoyens WHERE id=".$db['id']);
      }
	echo "<script>self.location='?p=donateorg&msg=1&yens=$yens'</script>";

}
}

?>
<div class="box_top">Doar Yens</div>
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
</div><div class="sep"></div>Para que o clã cresça, é necessário que os membros ajudem com um empurrãozinho financeiro! Doe yens para seu clã, para que o mesmo possa ampliar suas instalações. Digite a quantidade de yens que deseja doar no campo abaixo. <br><br>Obs.<small> A cada 1000 yens doados para o clã terá um ganho de 1 Pts de contribuição para comprar itens exclusivos do clã.<div class="sep"></div>
	<?php
	if(isset($_GET['msg'])){
		switch($_GET['msg']){
			case 1: if(!isset($_GET['yens'])){ echo "<script>self.location='?p=home'</script>"; break; } else $yens=$_GET['yens']; $msg='Foram doados <b>'.number_format($yens,2,',','.').' yens</b>!'; return;
			case 2: $msg='Você não possui a quantia de yens informada.'; break;
			case 3: if(!isset($_GET['pontos'])){ echo "<script>self.location='?p=home'</script>"; return; } else $junrios=$_GET['pontos']; $msg='Você obteve <b>'.number_format($junrios,2,',','.').' Pts de bonificação.</b>!'; return;
		}
	echo '<div class="aviso">'.$msg.'</div><div class="sep"></div>';}
	?>

    <div style="padding-left:5px;background:url(_img/gradient.jpg) repeat-y;font-weight:bold;color:#FFFFAA;"><img src="_img/yens.png" width="14" height="14" align="absmiddle" /> Meus yens: <?php echo number_format($db['yens'],2,',','.'); ?> yens</div><div class="sep"></div>
    <form method="post" action="?p=donateorg" onsubmit="subm.value='Carregando...';subm.disabled=true;">
    <input type="hidden" id="don" name="don" value="1" />
    <span class="destaque">Yens para Doação:</span><br />
    <input type="text" id="don_yens" name="don_yens" /><br />
    <span class="sub2">Digite a quantidade de yens para doação (apenas números).</span>
    <div class="sep"></div>
    <div align="center"><input type="submit" id="subm" name="subm" class="botao" value="Doar Yens" /></div>
    </form>
</div>
<div class="box_bottom"></div>