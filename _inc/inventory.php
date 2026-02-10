<?php
require_once('Encrypt.php');
$c=new C_Encrypt();

if(isset($_GET['action'])){
	if(!isset($_GET['id'])){ echo "<script>self.location='?p=home'</script>"; return; }
	$id=$_GET['id'];
	$sqli=mysql_query("SELECT usuarioid,categoria FROM inventario WHERE id=".$id);
	$dbi=mysql_fetch_assoc($sqli);
	if($dbi['usuarioid']<>$db['id']){ echo "<script>self.location='?p=home'</script>"; return; }
	$categoria=$dbi['categoria'];
	$act=$_GET['action'];
	vn($id);
	mysql_query("UPDATE inventario SET status='off' WHERE usuarioid=".$db['id']." AND categoria='".$categoria."'");
	if($act=='on') mysql_query("UPDATE inventario SET status='on' WHERE id=".$id);
	if($act=='on') echo "<script>self.location='?p=inventory&msg=2'</script>"; else echo "<script>self.location='?p=inventory&msg=3'</script>";
}


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
		echo "<script>self.location='?p=inventory&msg=1&e=".$hp."'</script>"; return;
	}
}
$sqlr=mysql_query("SELECT * FROM ramen WHERE usuarioid=".$db['id']);
$dbr=mysql_fetch_assoc($sqlr);
$sqli=mysql_query("SELECT i.id,i.status,i.upgrade,t.categoria,t.descricao,t.taijutsu,t.ninjutsu,t.genjutsu,t.nome,t.imagem,t.valor FROM inventario i LEFT OUTER JOIN table_itens t ON i.itemid=t.id WHERE i.usuarioid=".$db['id']." AND venda='nao' ORDER BY status ASC");
$dbi=mysql_fetch_assoc($sqli);
?>

	<div style="background:url(_img/gradient.jpg) repeat-y;color:#FFFFAA;"><img src="_img/yens.png" width="14" height="14" /> <b>Meus Yens: <?php echo number_format($db['yens'],2,',','.'); ?> yens</b></div>
	<?php
	if(isset($_GET['msg'])){
		switch($_GET['msg']){
			case 1: $msg='Ramen utilizado com sucesso!<br />Sua energia foi regenerada em <b>'.$_GET['e'].' pontos.</b>'; break;
			case 2: $msg='Item equipado com sucesso!'; break;
			case 3: $msg='Item desequipado com sucesso!'; break;
			case 4: if(!isset($_GET['value'])) $value=0; else $value=$_GET['value']; $msg='Item vendido com sucesso! Foram creditados '.number_format($value,2,',','.').' yens em sua conta.'; return;
		    case 5: $msg='Senha incorreta impossivel vender item!'; break;
		}
	echo '<div class="sep"></div><div class="aviso">'.$msg.'</div>';
	}
	?>
    <div class="inventory-section" style="padding: 10px;">
        <div style="background: rgba(0,0,0,0.3); border-radius: 12px; border: 1px solid rgba(255,255,255,0.05); padding: 20px; margin-bottom: 25px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="font-family: var(--font-header); font-size: 18px; color: #fff; margin: 0; display: flex; align-items: center; gap: 10px;">
                    <img src="_img/yens.png" width="20" height="20"> Seu Inventário
                </h3>
                <div style="background: rgba(255,215,0,0.15); color: #ffd700; padding: 5px 15px; border-radius: 20px; font-size: 12px; font-weight: bold; border: 1px solid rgba(255,215,0,0.2);">
                    <?php echo number_format($db['yens'], 2, ',', '.'); ?> Yens
                </div>
            </div>
            
            <div class="inventory-nav" style="display: flex; gap: 10px; margin-bottom: 20px; overflow-x: auto; padding-bottom: 5px;">
                <a href="?p=inventory" class="modern-btn" style="padding: 8px 15px; font-size: 11px; white-space: nowrap;">⚡ Itens</a>
                <a href="?p=parchments" class="modern-btn" style="padding: 8px 15px; font-size: 11px; white-space: nowrap; background: rgba(255,255,255,0.05);">📜 Pergaminhos</a>
                <a href="?p=animais" class="modern-btn" style="padding: 8px 15px; font-size: 11px; white-space: nowrap; background: rgba(255,255,255,0.05);">🦁 Animais</a>
                <a href="?p=selos" class="modern-btn" style="padding: 8px 15px; font-size: 11px; white-space: nowrap; background: rgba(255,255,255,0.05);">💠 Selos</a>
                <a href="?p=portao" class="modern-btn" style="padding: 8px 15px; font-size: 11px; white-space: nowrap; background: rgba(255,255,255,0.05);">⛩️ Portões</a>
            </div>

            <?php if(isset($_GET['msg'])): 
                switch($_GET['msg']){
                    case 1: $msg='Ramen utilizado com sucesso! Sua energia foi regenerada em <b>'.$_GET['e'].' pontos.</b>'; break;
                    case 2: $msg='Item equipado com sucesso!'; break;
                    case 3: $msg='Item desequipado com sucesso!'; break;
                    case 4: $value = $_GET['value'] ?? 0; $msg='Item vendido com sucesso! Foram creditados '.number_format($value,2,',','.').' yens.'; break;
                    case 5: $msg='Senha incorreta, impossível vender o item!'; break;
                }
            ?>
                <div class="aviso" style="margin-bottom: 20px;"><?php echo $msg; ?></div>
            <?php endif; ?>

            <!-- RAMEN SECTION -->
            <?php if(mysql_num_rows($sqlr) > 0): ?>
            <h4 style="font-family: var(--font-header); font-size: 13px; color: var(--text-dim); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px;">Consumíveis</h4>
            <div class="inventory-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 15px; margin-bottom: 30px;">
                <?php while($dbr = mysql_fetch_assoc($sqlr)): 
                    switch($dbr['ramenid']){
                        case 1: $nome='Gohan'; $reg=50; break;
                        case 2: $nome='Sushi'; $reg=100; break;
                        case 3: $nome='Peixe Empanado'; $reg=250; break;
                        case 4: $nome='Sashimi'; $reg=500; break;
                        case 5: $nome='Ramen'; $reg='1.000'; break;
                    }
                ?>
                <div class="modern-card" style="background: rgba(255,255,255,0.02); padding: 12px; display: flex; align-items: center; gap: 12px;">
                    <img src="_img/ramen/ramen<?php echo $dbr['ramenid']; ?>.png" style="width: 50px; height: 50px; border-radius: 5px; background: rgba(0,0,0,0.2); padding: 5px;">
                    <div style="flex: 1;">
                        <div style="font-size: 13px; font-weight: bold; margin-bottom: 3px;"><?php echo $nome; ?></div>
                        <div style="font-size: 10px; color: #5f5;">+<?php echo $reg; ?> Energia</div>
                    </div>
                    <form method="post" action="?p=inventory" onsubmit="var b=this.querySelector('input[type=submit]'); if(b) { b.value='...'; b.disabled=true; }">
                        <input type="hidden" name="ram_id" value="<?php echo $c->encode($dbr['id'],$chaveuniversal); ?>" />
                        <input type="hidden" name="ram_tipo" value="<?php echo $c->encode($dbr['ramenid'],$chaveuniversal); ?>" />
                        <input type="submit" class="modern-btn" style="padding: 6px 10px; font-size: 10px;" value="Usar">
                    </form>
                </div>
                <?php endwhile; ?>
            </div>
            <?php endif; ?>

            <!-- EQUIPMENT SECTION -->
            <?php if(mysql_num_rows($sqli) > 0): ?>
            <h4 style="font-family: var(--font-header); font-size: 13px; color: var(--text-dim); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px;">Equipamentos</h4>
            <div class="inventory-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 15px;">
                <?php while($dbi = mysql_fetch_assoc($sqli)): ?>
                <div class="modern-card <?php echo $dbi['status'] == 'on' ? 'equipped' : ''; ?>" style="background: <?php echo $dbi['status'] == 'on' ? 'rgba(50,255,50,0.05)' : 'rgba(255,255,255,0.02)'; ?>; border: 1px solid <?php echo $dbi['status'] == 'on' ? 'rgba(50,255,50,0.2)' : 'rgba(255,255,255,0.05)'; ?>;">
                    <div style="padding: 15px;">
                        <div style="display: flex; gap: 12px; margin-bottom: 12px;">
                            <div style="position: relative;">
                                <img src="_img/equipamentos/<?php echo $dbi['imagem']; ?>.png" style="width: 60px; height: 60px; background: rgba(0,0,0,0.3); border-radius: 8px; padding: 5px;">
                                <?php if($dbi['status'] == 'on'): ?>
                                    <div style="position: absolute; -top: 5px; -right: 5px; background: #5f5; width: 10px; height: 10px; border-radius: 50%; border: 2px solid #000;"></div>
                                <?php endif; ?>
                            </div>
                            <div style="flex: 1;">
                                <div style="font-size: 14px; font-weight: bold; margin-bottom: 5px;">
                                    <?php echo $dbi['nome']; ?><?php if($dbi['upgrade']>0) echo ' <span style="color: #ffd700;">+'.$dbi['upgrade'].'</span>'; ?>
                                </div>
                                <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                                    <?php if($dbi['taijutsu']>0) echo '<span style="font-size: 9px; background: rgba(255,50,50,0.1); color: #f66; padding: 2px 5px; border-radius: 3px;">T+'.($dbi['taijutsu']+$dbi['upgrade']).'</span>'; ?>
                                    <?php if($dbi['ninjutsu']>0) echo '<span style="font-size: 9px; background: rgba(50,255,50,0.1); color: #6f6; padding: 2px 5px; border-radius: 3px;">N+'.($dbi['ninjutsu']+$dbi['upgrade']).'</span>'; ?>
                                    <?php if($dbi['genjutsu']>0) echo '<span style="font-size: 9px; background: rgba(50,50,255,0.1); color: #66f; padding: 2px 5px; border-radius: 3px;">G+'.($dbi['genjutsu']+$dbi['upgrade']).'</span>'; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 10px; font-size: 10px;">
                            <div style="color: var(--text-dim);">Venda: <b style="color: #ffd700;"><?php echo number_format(($dbi['valor']/2),2,',','.'); ?></b></div>
                            <div style="display: flex; gap: 8px;">
                                <a href="?p=inventory&action=<?php echo $dbi['status'] == 'off' ? 'on' : 'off'; ?>&id=<?php echo $dbi['id']; ?>" class="modern-btn" style="padding: 4px 10px; background: <?php echo $dbi['status'] == 'off' ? 'rgba(50,255,50,0.2)' : 'rgba(255,50,50,0.2)'; ?>; color: #fff;">
                                    <?php echo $dbi['status'] == 'off' ? 'Equipar' : 'Retirar'; ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div style="background: rgba(0,0,0,0.2); padding: 8px 15px; display: flex; justify-content: center; gap: 15px; font-size: 10px; border-top: 1px solid rgba(255,255,255,0.03);">
                        <a href="?p=sellitem&id=<?php echo $dbi['id']; ?>" style="color: var(--text-dim); text-decoration: none;">Vender</a>
                        <a href="?p=blacksmith&id=<?php echo $dbi['id']; ?>" style="color: #ffd700; text-decoration: none;">Aprimorar</a>
                        <a href="?p=senditem" style="color: var(--text-dim); text-decoration: none;">Enviar</a>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <?php endif; ?>

            <?php if(mysql_num_rows($sqlr) == 0 && mysql_num_rows($sqli) == 0): ?>
                <div class="aviso" style="margin-top: 20px;">Seu inventário está vazio.</div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php 
@mysql_free_result($sqlr);
@mysql_free_result($sqli); 
?>
    <?php if((mysql_num_rows($sqlr)==0)&&(mysql_num_rows($sqli)==0)){ ?>
    <div class="sep"></div>
    <div class="aviso">Nenhum item em seu inventário.</div>
    <?php } ?>
</div>
</div><div class="box_bottom"></div>
<?php
@mysql_free_result($sqlr);
@mysql_free_result($sqli);
?>
