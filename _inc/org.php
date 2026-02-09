<?php
if($db['orgid']>0){ echo "<script>self.location='?p=myorg'</script>"; return; }
$sqlo=mysql_query("SELECT id,sigla,nome,nivel,logo,minimo FROM organizacoes ORDER BY nivel DESC");
$dbo=mysql_fetch_assoc($sqlo);
?>
<div class="box_top">Clas</div>
<div class="box_middle"><div style="background: url(../_img/_detalhes/base2.PNG);width: 720px;height: 250px;">
<table cellpadding="0" cellspacing="0" width="712" height="230"><tbody><tr><td width="150">
<img width="142" style="" src="_img/_detalhes/msg/32.png"></td><td valign="top"><br><br><br>
<div style="margin-left: -345px;margin-top: 15px;height: 0px;">
<b id="title" style="font-family: impact;font-size: 20px;font-weight: normal;color: #ffffff;"  onmouseover="style.color='#F5F5F5'" onmouseout="color.color='#ffffff'">
&raquo; Clã!</b></div><br>
<div style="margin-left: 0px;margin-top: 35px;font-family: arial;font-size: 12px;color: #fff;"><b>
Os clãs são locais em que os ninjas cooperam entre si para melhorar</br>
suas habilidades de batalha. Abaixo estão listados os clãs existentes</br>
na sua vila, até o momento. Escolha um deles e faça sua candidatura a</br>
membro. Sua entrada depende da aprovação do líder ou dos moderadores do</br>
clã. Se preferir, crie seu próprio clã!
</br>
</div></td></tr></tbody></table></div><div class="sep"></div>
<?php if(isset($_GET['msg'])){
	switch($_GET['msg']){
		case 1: $msg='Você deixou o clã em que estava.'; break;
		case 2: $msg='Requisição realizada com sucesso!<br />Aguarde a confirmação do administrador ou dos moderadores do clã.'; break;
		case 3: $msg='Você já requisitou ingresso à este clã.<br />Favor aguarde a confirmação.'; break;
		case 4: $msg='Seu nível é muito baixo para entrar neste clã.'; break;
		case 5: $msg='O clã foi destruído!'; break;
	}
echo '<div class="aviso">'.$msg.'</div><div class="sep"></div>';
}
?>
	<div align="center"><a href="?p=org">Clãs</a> | <a href="?p=createorg">Criar Clã</a></div>
    <div class="sep"></div>
    <?php if(mysql_num_rows($sqlo)==0) echo '<div class="aviso">Nenhuma organização na sua vila até o momento.<br />Seja o primeiro a criar uma, clicando <a href="?p=createorg">aqui</a>!</div>'; else { ?>
    <table width="100%" cellpadding="0" cellspacing="1">
        <?php $cor='#323232'; do{ ?>
       	<tr class="table_dados" style="background:#323232;" onmouseover="style.background='#2C2C2C'" onmouseout="style.background='#323232'">
        	<td height="35"><?php if($dbo['logo']<>'') echo '<img src="'.$dbo['logo'].'" height="35" width="49" />'; else echo '<span class="sub2">Sem Imagem</span>'; ?></td>
        	<td width="40%"><b>Clã <?php echo $dbo['nome']; ?></b><br /><span class="sub2">Sigla <?php echo $dbo['sigla']; ?></span></td>
            <td width="30%"><b>Nível <?php echo $dbo['nivel']; ?></b><br /><span class="sub2">Recrutamento: Nível <?php echo $dbo['minimo']; ?>+</span></td>
            <td width="15%"><a href="?p=vieworg&amp;id=<?php echo $dbo['id']; ?>">Visualizar</a></td>
        </tr>
        <?php if($cor=='#323232') $cor='#2C2C2C'; else $cor='#323232'; } while($dbo=mysql_fetch_assoc($sqlo)); ?>
    </table>
    <?php } ?>
</div>
<div class="box_bottom"></div>
