<?php
$sqltexto="SELECT texto,hora FROM atualizacoes WHERE usuarioid=".$db['id'];
$sqltexto.=$sqlupdate;
$sqltexto.=" ORDER BY id DESC LIMIT 10";
$sqlu=mysql_query($sqltexto);
$dbu=mysql_fetch_assoc($sqlu);
//mysql_query("INSERT INTO atualizacoes (usuarioid,hora) VALUES (3,".time(date('Y-m-d H:i:s')).")");
require_once('formatar_tempo.php');
?>
<div class="modern-card">
    <div class="modern-card-header">Atualizações</div>
    <div class="modern-card-body">
        <p style="color: var(--text-dim); margin-bottom: 15px; font-size: 13px;">Últimos 10 comandos realizados por você e seus amigos. Na página de configuração, você pode definir as permissões de suas atualizações.</p>
        <div style="background: rgba(0,0,0,0.3); border-radius: 4px; border: 1px solid var(--border-subtle); overflow: hidden;">
            <table width="100%" cellpadding="10" cellspacing="0">
                <?php if(mysql_num_rows($sqlu)==0) echo '<tr><td colspan="3"><div class="aviso">Nenhuma atualização encontrada!</div></td></tr>'; else do{ ?>
                <tr class="tabela_dados" style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                    <td style="background:rgba(255,255,255,0.02); text-align:center; width: 40px;" valign="top"><img src="_img/refresh.png" style="opacity: 0.5;" /></td>
                    <td style="color: #fff; line-height: 1.4;"><?php echo $dbu['texto']; ?></td>
                    <td style="text-align:right; color: var(--text-dim); font-size: 11px;" width="25%" valign="top">
                        <?php echo formatar_tempo($dbu['hora']); ?>
                    </td>
                </tr>
                <?php } while($dbu=mysql_fetch_assoc($sqlu)); ?>
            </table>
        </div>
        <div style="margin-top: 20px; text-align: center;">
            <input type="button" class="modern-btn" style="padding: 8px 20px; font-size: 12px;" value="Mais Atualizações" onclick="location.href='?p=updates'" />
        </div>
    </div>
</div>
<?php
@mysql_free_result($sqlu);
?>