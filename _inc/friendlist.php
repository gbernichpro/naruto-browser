<?php require_once('chat.php'); ?>
<link type="text/css" rel="stylesheet" media="all" href="_css/chat.css" />
<link type="text/css" rel="stylesheet" media="all" href="_css/screen.css" />
<script type="text/javascript" src="_js/chat.js"></script>
<?php
$sqla = mysql_query("SELECT a.amigoid,u.usuario,u.nivel,u.config_atualizacoes,u.timestamp FROM amigos a LEFT OUTER JOIN usuarios u ON a.amigoid=u.id WHERE a.usuarioid=".$db['id']." AND a.status='sim' ORDER BY nivel DESC, yens_fat DESC, vitorias DESC, derrotas ASC");
$dba=mysql_fetch_assoc($sqla);
$sqlupdate = '';
$timeout = time()-900;
?>
<div class="sidebar-box">
    <div class="sidebar-box-header">Lista de Amigos</div>
    <div class="sidebar-box-content">
        <?php if(mysql_num_rows($sqla)==0): ?>
            <div class="sub2">Nenhum amigo encontrado.<br />Visite um perfil para adicionar.</div>
        <?php else: ?>
            <table width="100%" cellspacing="0" cellpadding="2">
                <?php do{ if($dba['config_atualizacoes']=='sim') $sqlupdate.=' OR usuarioid='.$dba['amigoid']; ?>
                <tr>
                    <td width="20"><img src="_img/<?php if($dba['timestamp']>=$timeout) echo 'online'; else echo 'offline'; ?>.png" width="12" height="12" /></td>
                    <td><a href="javascript:void(0)" onclick="javascript:chatWith('<?php echo $dba['usuario']; ?>')" style="font-size: 11px;"><?php echo $dba['usuario']; ?></a></td>
                    <td align="right" style="color: #888;">[<?php echo $dba['nivel']; ?>]</td>
                </tr>
                <?php } while($dba=mysql_fetch_assoc($sqla)); ?>
            </table>
            <div class="sidebar-sep"></div>
            <div align="center"><a href="?p=friends" style="font-size: 10px; color: #ff0000;">GERENCIAR LISTA</a></div>
        <?php endif; ?>
    </div>
</div>
<?php @mysql_free_result($sqla); ?>