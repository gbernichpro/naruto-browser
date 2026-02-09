<BR><BR><style>
#tarefa{
background:#282828;
padding:3px;}
#desc{
background:#454545;
height:47px;
margin-top:4px;
font-size:11px;padding:3px;}
#prem{
background: url(_img/bar.png) repeat scroll 0% 0% transparent; height: 15px; padding: 3px;
}
</style>
<?php
$sqlv = mysql_query("SELECT * FROM table_tarefas where tipo='nivel'");
$tarefa = mysql_fetch_assoc($sqlv);
if(mysql_num_rows($sqlv)>0) do{
if($tarefa['tipo']=='nivel'){
$ver = mysql_query("SELECT * FROM tarefas_completadas where tarefa_id='".$tarefa['id']."' and usuario_id='".$db['id']."'");
 if(mysql_num_rows($ver)==0){
if($db['nivel']>$tarefa['valor']){
mysql_query("INSERT INTO tarefas_completadas (usuario_id,tarefa_id) VALUES (".$db['id'].",".$tarefa['id'].")");
mysql_query("UPDATE usuarios SET yens=yens+".$tarefa['premio_yens']." WHERE id=".$db['id']);
if ($tarefa['creditos'] > 0) {
mysql_query("UPDATE usuarios SET creditos=creditos+".$tarefa['creditos']." WHERE id='".$db['id']."'");
}
if ($tarefa['premio_item']!='nada') {
$iten = mysql_query("SELECT * FROM table_itens where nome='".$tarefa['premio_item']."'");
$it = mysql_fetch_assoc($iten);
mysql_query("INSERT INTO inventario (usuarioid,itemid) values ('".$db['id']."','".$it['id']."')");
}
echo "<script>self.location='?p=tarefas'</script>";}}
}


if($tarefa['tipo']=='vitorias'){
$ver = mysql_query("SELECT * FROM tarefas_completadas where tarefa_id='".$tarefa['id']."' and usuario_id='".$db['id']."'");
 if(mysql_num_rows($ver)==0){
if($db['vitorias']>$tarefa['valor']){
mysql_query("INSERT INTO tarefas_completadas (usuario_id,tarefa_id) VALUES (".$db['id'].",".$tarefa['id'].")");
mysql_query("UPDATE usuarios SET yens=yens+".$tarefa['premio_yens']." WHERE id='".$db['id']."'");
if ($tarefa['creditos'] > 0) {
mysql_query("UPDATE usuarios SET creditos=creditos+".$tarefa['creditos']." WHERE id='".$db['id']."'");
}

if ($tarefa['premio_item']!='nada') {
$iten=mysql_query("SELECT * FROM table_itens where nome='".$tarefa['premio_item']."'");
$it=mysql_fetch_assoc($iten);
mysql_query("INSERT INTO inventario (usuarioid,itemid) values ('".$db['id']."','".$it['id']."')");
}
echo "<script>self.location='?p=tarefas'</script>";}}
}

$ver = mysql_query("SELECT * FROM tarefas_completadas where tarefa_id='".$tarefa['id']."' and usuario_id='".$db['id']."'");
 if(mysql_num_rows($ver)==0){
$completa = "erro";
 }
else{
$completa = "ok";
}
?>
<div id="tarefa">
<table border="0" cellspacing="0" cellpading="0" width="100%">
<tr>
<td align="center" width="150" valign="top">
<?php
if ($db['nivel'] >= 5) {
	echo "<img src=\"_img/".$tarefa['tipo']."".$completa.".png\" border=\"0\">";
} else {
	echo "<img src=\"_img/".$tarefa['tipo']."".$completa.".png\" border=\"0\">";}
?></td>
  <td valign="top" width="210">
 <div id="desc"><?=$tarefa['nome']?></div>

</td>
<td><img src="_img/yens-on.png" width='50' border="0" onmouseover="Tip('<div class=tooltip>Voce recebera <?=$tarefa['premio_yens']?> Yens</div>');" onmouseout="UnTip()">
<?php
if ($tarefa['creditos'] > 0) {
	echo "<img src=\"_img/cred-on.png\" width='50' border=\"0\" onmouseover=\"Tip('<div class=tooltip>Voce recebera ".$tarefa['creditos']." Creditos</div>');\" onmouseout=\"UnTip()\">";
} else {
	echo "<img src=\"_img/cred-off.png\" width='50' border=\"0\">";}
?>
<?php
if ($tarefa['premio_item']!='nada') {
$iten=mysql_query("SELECT * FROM table_itens where nome='".$tarefa['premio_item']."'");
$it=mysql_fetch_assoc($iten);
	echo "<img src=\"_img/item-on.png\" width='50' border=\"0\" onmouseover=\"Tip('<div class=tooltip>Voce recebera um(a) ".$it['nome']."</div>');\" onmouseout=\"UnTip()\">";
} else {
	echo "<img src=\"_img/item-off.png\" width='50' border=\"0\">";}
?></td>
</tr></table>
</div>
<div class="sep"></div>
<?php } while($tarefa=mysql_fetch_assoc($sqlv)); ?>



</div>
<div class="box_bottom"></div>