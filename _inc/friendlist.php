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
<div class="box5_top" align="center">Lista de Amigos</div>
<div class="box5_middle"><?php if(mysql_num_rows($sqla)==0) echo '<div class="sub2">Nenhum amigo encontrado.<br />Para adicionar um amigo, <br />visite seu perfil e clique em <b>Adicionar Amigo</b>.</div>'; else { ?>
	<table width="80%" cellspacing="0" cellpadding="0" align="center">
        <?php do{ if($dba['config_atualizacoes']=='sim') $sqlupdate.=' OR usuarioid='.$dba['amigoid']; ?>
        <tr class="table_dados">
        	<td><img src="_img/<?php if($dba['timestamp']>=$timeout) echo 'online'; else echo 'offline'; ?>.png" width="14" height="14" style="margin-right:2px;" /></td>
        	<td style="text-align:center;padding-left:2px;"><a href="javascript:void(0)" onclick="javascript:chatWith('<?php echo $dba['usuario']; ?>')" title="Clique aqui para conversar com seu amigo!"><?php echo $dba['usuario']; ?></a></td>
            <td><b>[<?php echo $dba['nivel']; ?>]</b></td>
        </tr>
        <?php } while($dba=mysql_fetch_assoc($sqla)); ?>
    </table>

    <div class="sep"></div>
    <div class="sub2"><a href="?p=friends">Gerenciar Lista</a></div>
    <?php } ?>
</div>
<div class="box5_bottom"></div>
<?php
@mysql_free_result($sqla);
?>