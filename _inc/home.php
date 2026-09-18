<?php require_once('trava.php'); ?>
<?php
require_once('Encrypt.php');
$c = new C_Encrypt();

if(isset($_GET['off'])){
	if($db['orgid']==-1) mysql_query("UPDATE usuarios SET orgid=0 WHERE id=".$db['id']);
	echo "<script>self.location='?p=home'</script>";
}
if($db['energia']<=0){
     mysql_query("UPDATE usuarios SET `energia`=0   WHERE id=".$db['id']);
}
$array=array("t"=>$db['taijutsu'],"n"=>$db['ninjutsu'],"g"=>$db['genjutsu']);
rsort($array);
$array2=array("t"=>$db['taijutsu'],"n"=>$db['ninjutsu'],"g"=>$db['genjutsu']);
arsort($array2);
$tam=220;
require_once('funcoes.php');
?>
<?php if($db['hunt']>0) require_once('busyhunt.php'); ?>
<?php if($db['missao']>0) require_once('busymission.php'); ?>

<?php
$attributeMax = max(1, (int)$db['taijutsu'], (int)$db['ninjutsu'], (int)$db['genjutsu']);
$taiPercent = min(100, max(0, ((int)$db['taijutsu'] / $attributeMax) * 100));
$ninPercent = min(100, max(0, ((int)$db['ninjutsu'] / $attributeMax) * 100));
$genPercent = min(100, max(0, ((int)$db['genjutsu'] / $attributeMax) * 100));
$energyPercent = min(100, max(0, ((int)$db['energia'] / max(1, (int)$db['energiamax'])) * 100));
$expPercent = min(100, max(0, ((int)$db['exp'] / max(1, (int)$db['expmax'])) * 100));
?>
<div class="box_top">Meus Atributos</div>
<div class="box_middle">Seus atributos de combate, yens atuais, nível e experiência.<div class="sep"></div>
	<?php
		if($db['renegado']=='sim'){
			$sqlx=mysql_query("SELECT id FROM usuarios WHERE renegado='sim' ORDER BY nivel DESC, yens_fat DESC, vitorias DESC, derrotas ASC LIMIT 1");
		  	$dbx=mysql_fetch_assoc($sqlx);
			if($dbx['id']==$db['id']) $nivel='Líder da Akatsuki'; else $nivel='Nukenin';
		} else {
        	$sqlx=mysql_query("SELECT id FROM usuarios WHERE vila=".$db['vila']." AND renegado='nao' ORDER BY nivel DESC, yens_fat DESC, vitorias DESC, derrotas ASC LIMIT 1");
		  	$dbx=mysql_fetch_assoc($sqlx);
		  	if($dbx['id']==$db['id']){
		  		switch($db['vila']){
					case 1: $nivel='Hokage'; break;
					case 2: $nivel='Kazekage'; break;
					case 3: $nivel='Otokage'; break;
					case 4: $nivel='Líder da Vila da Chuva'; break;
					case 5: $nivel='Raikage'; break;
					case 6: $nivel='Mizukage'; break;
					case 8: $nivel='Tsuchikage'; break;
				}
		  	} else $nivel=rankNinja($db['nivel']);
		}
		?>
	<table width="100%" cellpadding="0" cellspacing="0" class="character-overview">

		    <?php
$id=$_SESSION['logado'];
$sqlgrupos=mysql_query("SELECT u.id, u.reg, u.nivel, u.personagem, u.orgid, u.exp, u.expmax, u.energia, u.energiamax, u.yens, u.renegado, u.vila,  u.taijutsu, u.ninjutsu, u.genjutsu, u.config_skin, u.doujutsu_nivel, u.doujutsu, c.nome, c.sigla, sum(i.taijutsu) cla_taijutsu, sum(i.ninjutsu) cla_ninjutsu, sum(i.genjutsu) cla_genjutsu FROM usuarios u LEFT OUTER JOIN organizacoes c ON u.orgid=c.id LEFT OUTER JOIN clas_investimentos i ON u.orgid=i.orgid WHERE u.id=$id") or die (mysql_error());
if(@mysql_num_rows($sqlgrupos)>0)
$dbgrupos=@mysql_fetch_assoc($sqlgrupos);
                            ?>
    	<tr style="background:url(_img/gradient2.jpg) repeat-y;color:#FFFFAA;">
        	<td align="right" style="padding-right:10px;"><img src="_img/yens.png" width="14" height="14" align="absmiddle" /> <b>Meus Yens:</b></td>
            <td colspan="2" align="left"><b><?php echo number_format($db['yens'],2,',','.'); ?> yens</b></td>
        </tr>
        <tr>
        	<td colspan="2"><div class="sep"></div>
        </tr>
<tr style="background:url(_img/gradient2.jpg) repeat-y;">
        	<td width="20%" align="right" style="padding-right:10px;"><b>Registro:</b></td>
      <td colspan="2" align="left"><?php $reg=explode(' ',$db['reg']); $datareg=explode('-',$reg[0]); echo $datareg[2].'/'.$datareg[1].'/'.$datareg[0].', às '.$reg[1]; ?></td>
      </tr>
        <tr>
        	<td align="right" style="padding-right:10px;"><b>Personagem:</b></td>
          <td colspan="2" align="left"><?php fpersonagem($db['personagem']); ?></td>
        </tr>
        <tr style="background:url(_img/gradient2.jpg) repeat-y;">
        	<td align="right" style="padding-right:10px;"><b>Vila:</b></td>
      <td colspan="2" align="left"><?php echo $txtvila; ?></td>
        </tr>
        <tr>
        	<td align="right" style="padding-right:10px;"><b>Clã:</b></td>
          <td colspan="2" align="left"><?php if($db['orgid']==-1) echo '<div class="aviso">O clã em que você estava foi destruído.<br />Recomendamos que procure um outro clã.<br /><a href="?p=home&off=1">Clique aqui para desativar esta mensagem.</a></div>'; else if($db['orgid']==0) echo '-'; else echo '<a href="?p=myorg">'.$db['orgnome'].'</a>'; ?></td>
        </tr>
        <tr style="background:url(_img/gradient2.jpg) repeat-y;">
        	<td align="right" style="padding-right:10px;"><b>Nível:</b></td>
          <td colspan="2" align="left"><?php echo $nivel; ?><b> [<?php echo $db['nivel']; ?>]</b></td>
        </tr>
        <tr>
        	<td colspan="3"><div class="sep"></div></td>
        </tr>
        <tr class="stat-row stat-tai">
            <td class="stat-label">TAI</td>
            <td><div class="stat-track"><span style="--progress: <?php echo round($taiPercent, 2); ?>%"></span></div></td>
            <td class="stat-value"><b><?php echo $db['taijutsu']; ?></b> <span id="atrtai"><?php echo $db['orgnivel']; ?></span><?php if($dbgrupos['cla_taijutsu']!=0) echo ' +'.$dbgrupos['cla_taijutsu']; ?></td>
        </tr>
        <tr class="stat-row stat-nin">
            <td class="stat-label">NIN</td>
            <td><div class="stat-track"><span style="--progress: <?php echo round($ninPercent, 2); ?>%"></span></div></td>
            <td class="stat-value"><b><?php echo $db['ninjutsu']; ?></b> <span id="atrnin"><?php echo $db['orgnivel']; ?></span><?php if($dbgrupos['cla_ninjutsu']!=0) echo ' +'.$dbgrupos['cla_ninjutsu']; ?></td>
        </tr>
        <tr class="stat-row stat-gen">
            <td class="stat-label">GEN</td>
            <td><div class="stat-track"><span style="--progress: <?php echo round($genPercent, 2); ?>%"></span></div></td>
            <td class="stat-value"><b><?php echo $db['genjutsu']; ?></b> <span id="atrgen"><?php echo $db['orgnivel']; ?></span><?php if($dbgrupos['cla_genjutsu']!=0) echo ' +'.$dbgrupos['cla_genjutsu']; ?></td>
        </tr>
        <tr class="stat-row stat-energy">
            <td class="stat-label">HP</td>
            <td><div class="stat-track"><span style="--progress: <?php echo round($energyPercent, 2); ?>%"></span></div></td>
            <td class="stat-value"><b><?php echo $db['energia']; ?></b> / <?php echo $db['energiamax']; ?></td>
        </tr>
        <tr class="stat-row stat-exp">
            <td class="stat-label">EXP</td>
            <td><div class="stat-track"><span style="--progress: <?php echo round($expPercent, 2); ?>%"></span></div></td>
            <td class="stat-value"><b><?php echo $db['exp']; ?></b> / <?php echo $db['expmax']; ?></td>
        </tr>
    </table>
    <?php if(($db['hunt']==0)&&($db['missao']==0)&&($db['treino']==0)){ ?>
    <div class="sep"></div>
    <div align="center"><input type="button" class="botao" value="Realizar Treino" onClick="location.href='?p=train'" /></div>
    <?php } ?>
</div>
<div class="box_bottom"></div>
<?php
if(isset($_POST['ram_id'])){
	$id=$c->decode($_POST['ram_id'],$chaveuniversal);
	$tipo=$c->decode($_POST['ram_tipo'],$chaveuniversal);
	vn($id); vn($tipo);
	$sqlr=mysql_query("SELECT count(id) conta FROM ramen WHERE usuarioid=".$db['id']." AND id=".$id);
	$dbr=mysql_fetch_assoc($sqlr);
	if($dbr['conta']>0){
		$energia=$db['energia'];
		switch($tipo){
			case 1: $hp=50; break;
			case 2: $hp=100; break;
			case 3: $hp=250; break;
			case 4: $hp=500; break;
			case 5: $hp=1000; break;
		}
		if($energia+$hp>=$db['energiamax']) $energia=$db['energiamax']; else $energia=$energia+$hp;
		mysql_query("DELETE FROM ramen WHERE id=".$id);
		mysql_query("UPDATE usuarios SET energia=$energia WHERE id=".$db['id']);
		echo "<script>self.location='?p=home&msg=1&e=".$hp."'</script>"; return;
	}
}
$sqlr=mysql_query("SELECT * FROM ramen WHERE usuarioid=".$db['id']." ORDER BY RAND() LIMIT 3");
if(mysql_num_rows($sqlr)>0){ $dbr=mysql_fetch_assoc($sqlr); require_once('inventario.php'); } ?>
<?php
$sqlee=mysql_query("SELECT i.upgrade,t.imagem,t.nome,t.descricao, i.* FROM animais i LEFT OUTER JOIN table_animais t ON i.itemid=t.id WHERE i.usuarioid=".$db['id']." AND status='on' ORDER BY categoria");
if(mysql_num_rows($sqlee)>0){ $dbee=mysql_fetch_assoc($sqlee); require_once('animaiss.php'); }
                ?>
                <?php
$sqleee=mysql_query("SELECT i.upgrade,i.expira, t.* FROM selos i LEFT OUTER JOIN table_selos t ON i.itemid=t.id WHERE i.usuarioid=".$db['id']." AND status='on' ORDER BY categoria");
if(mysql_num_rows($sqleee)>0){ $dbeee=mysql_fetch_assoc($sqleee); require_once('meuselo.php'); }
                ?>

  <?php
$sqleeee=mysql_query("SELECT i.upgrade,i.expira, t.* FROM portao i LEFT OUTER JOIN table_portoes t ON i.itemid=t.id WHERE i.usuarioid=".$db['id']." AND status='on' ORDER BY categoria");
if(mysql_num_rows($sqleeee)>0){ $dbeeee=mysql_fetch_assoc($sqleeee); require_once('meuportao.php'); }
                ?>

<?php
$sqle=mysql_query("SELECT i.upgrade, t.* FROM inventario i LEFT OUTER JOIN table_itens t ON i.itemid=t.id WHERE i.usuarioid=".$db['id']." AND status='on' ORDER BY categoria");
if(mysql_num_rows($sqle)>0){ $dbe=mysql_fetch_assoc($sqle); require_once('equipamentos.php'); }
if($db['doujutsu']>0) require_once('meudoujutsu.php'); ?>




<div class="modern-card">
    <div class="modern-card-header">Desbloqueio de personagens</div>
    <div class="modern-card-body">
        <div align="center">
            <?php require_once('shop_characters.php');?>
        </div>
    </div>
</div>


<div class="modern-card">
    <div class="modern-card-header">Minhas Estatísticas</div>
    <div class="modern-card-body">
        <p style="color: var(--text-dim); margin-bottom: 15px; font-size: 13px;">Todas as estatísticas de sua conta.</p>
        <div style="background: rgba(0,0,0,0.3); border-radius: 4px; border: 1px solid var(--border-subtle); overflow: hidden;">
            <table width="100%" border="0" cellpadding="10" cellspacing="0">
                <tr style="background: rgba(255,255,255,0.02);">
                    <td width="50%"><b>Meus Yens</b></td>
                    <td style="color: #fff;"><?php echo number_format($db['yens'],2,',','.'); ?> yens</td>
                </tr>
                <tr>
                    <td><b>Yens Faturados</b></td>
                    <td style="color: #fff;"><?php echo number_format($db['yens_fat'],2,',','.'); ?> yens</td>
                </tr>
                <tr style="background: rgba(255,255,255,0.02);">
                    <td><b>Yens Perdidos</b></td>
                    <td style="color: #fff;"><?php echo number_format($db['yens_perd'],2,',','.'); ?> yens</td>
                </tr>
                <tr>
                    <td><b>Batalhas</b></td>
                    <td style="color: #fff;"><?php echo $db['batalhas']; ?> batalhas</td>
                </tr>
                <tr style="background: rgba(255,255,255,0.02);">
                    <td><b>Score</b></td>
                    <td style="color: var(--primary-red); font-weight: bold;"><?php echo number_format($db['score']); ?> Pontos</td>
                </tr>
                <tr>
                    <td><b>Vitórias</b></td>
                    <td style="color: #0f0;"><?php echo $db['vitorias']; ?> vitórias</td>
                </tr>
                <tr style="background: rgba(255,255,255,0.02);">
                    <td><b>Derrotas</b></td>
                    <td style="color: #f00;"><?php echo $db['derrotas']; ?> derrotas</td>
                </tr>
                <tr>
                    <td><b>Empates</b></td>
                    <td style="color: #888;"><?php echo $db['empates']; ?> empates</td>
                </tr>
                <tr style="background: rgba(255,255,255,0.02);">
                    <td><b>Experiência Total</b></td>
                    <td style="color: #fff;"><?php echo number_format($db['exptotal']); ?> pontos</td>
                </tr>
            </table>
        </div>
    </div>
</div>
<?php require_once('atualizacoes.php'); ?>
<script>
if(((document.getElementById('atrtai').innerHTML)*1)>0) document.getElementById('atrtai').innerHTML='+'+document.getElementById('atrtai').innerHTML; else document.getElementById('atrtai').innerHTML='';
if(((document.getElementById('atrnin').innerHTML)*1)>0) document.getElementById('atrnin').innerHTML='+'+document.getElementById('atrnin').innerHTML; else document.getElementById('atrnin').innerHTML='';
if(((document.getElementById('atrgen').innerHTML)*1)>0) document.getElementById('atrgen').innerHTML='+'+document.getElementById('atrgen').innerHTML; else document.getElementById('atrgen').innerHTML='';
</script>
<?php
@mysql_free_result($sqlr);
?>
