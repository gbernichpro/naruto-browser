<?php


$mz2=mysql_fetch_assoc(mysql_query("select * from nal_torneio"));
if ($mz2 == true){
$idrank2=mysql_query("SELECT * FROM nal_torneio ORDER BY id DESC LIMIT 1");
for ($s2=0;$s2<mysql_num_rows($idrank2);$s2++){
$sei2=mysql_fetch_assoc($idrank2);
$ssj2=$sei2['status'];
$idinwar2=$sei2['id'];
$premioinwar2=$sei2['premio'];
$fiminwar2=$sei2['fim'];
$nivelmin=$sei2['nivelmin'];
$nivelmax=$sei2['nivelmax'];
$vencedorinwar2=$sei2['vencedor'];
$expe=$sei2['exp'];
$insc=$sei2['inscricoes'];
}
$HoraDate22=date('Y-m-d H:i:s');
$abertoi2=time()+7200;
$HoraAberto2=date('Y-m-d H:i:s', $abertoi2);
if ($HoraDate22 >= $fiminwar2 and $ssj2=="inscricao"){
mysql_query("update nal_torneio set status='aberto' where id=".$idinwar2);
}





}
?>