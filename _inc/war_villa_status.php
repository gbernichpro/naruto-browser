<?php
$mz=mysql_fetch_assoc(mysql_query("select * from nal_war"));
if ($mz == true){
$idrank=mysql_query("SELECT * FROM nal_war ORDER BY id DESC LIMIT 1");
for ($s=0;$s<mysql_num_rows($idrank);$s++){
$sei=mysql_fetch_assoc($idrank);
$ssj=$sei['status'];
$idinwar=$sei['id'];
$premioinwar=$sei['premio'];
$fiminwar=$sei['fim'];
$vencedorinwar=$sei['vencedor'];
}
$idrankz=mysql_query("SELECT * FROM nal_war_vilas WHERE warid=$idinwar ORDER BY score DESC LIMIT 1");
for ($z=0;$z<mysql_num_rows($idrankz);$z++){
$seiz=mysql_fetch_assoc($idrankz);
$idinwar666=$seiz['vilaid'];
$idinwar_fase1=$seiz['vilaid'];
if ($idinwar_fase1!="7"){
$idinwar2="vila=".$seiz['vilaid']." AND renegado='nao'";
}else{
$idinwar2="renegado='sim'";
}
}
$HoraDate=date('Y-m-d H:i:s');
$abertoi=time()+604800;
$HoraAberto=date('Y-m-d H:i:s', $abertoi);
if ($HoraDate >= $fiminwar and $ssj=="inscricao"){
mysql_query("update nal_war set fim='$HoraAberto' where id=".$idinwar);
mysql_query("update nal_war set status='aberto' where id=".$idinwar);
}
if ($HoraDate >= $fiminwar and $ssj=="aberto"){
$i=0;
$premiow=100000;
$sqlvalor=mysql_query("SELECT * FROM usuarios where inwar='sim' ORDER BY inwar_score DESC LIMIT 3");
while($i< mysql_num_rows($sqlvalor)){
$posicao=$i+1;
$valor=mysql_fetch_assoc($sqlvalor);
$msg='Parabêns você foi um dos melhores na Guerra de Vilas ficando em '.$posicao.'° Lugar , Você recebeu um premio extra de '.$premiow.' pela vitoria.(e tambem o premio da vila + uma medalha ninja)';
mysql_query("INSERT INTO mensagens (data,origem,destino,assunto,msg) VALUES ('".date('Y-m-d H:i:s')."','0',".$valor['id'].",'Vencedor da Guerra de vilas','".$msg."')") or die(mysql_error());
mysql_query("UPDATE usuarios SET yens=yens+'$premiow',yens_fat=yens_fat+'$premiow' WHERE id=".$valor['id']);

if(mysql_num_rows($sqlvalor)<2){
//inserir medalha
mysql_query("INSERT INTO medalhas (usuarioid,medalha,descricao,tipo) Values ('".$valor['id']."','Guerra de Vilas','Ficou em ".$posicao."° lugar na ".$idinwar."° guerra de vilas.','3')");

}
if(mysql_num_rows($sqlvalor)==2){
//inserir medalha
mysql_query("INSERT INTO medalhas (usuarioid,medalha,descricao,tipo) Values ('".$valor['id']."','Guerra de Vilas','Ficou em ".$posicao."° lugar na ".$idinwar."° guerra de vilas.','2')");

}
if(mysql_num_rows($sqlvalor)==3){
//inserir medalha
mysql_query("INSERT INTO medalhas (usuarioid,medalha,descricao,tipo) Values ('".$valor['id']."','Guerra de Vilas','Ficou em ".$posicao."° lugar na ".$idinwar."° guerra de vilas.','1')");

}








$premiow=$premiow / 2;
$i++;
}
mysql_query("update usuarios set yens=yens+'$premioinwar' , yens_fat=yens_fat+'$premioinwar' where ".$idinwar2." AND inwar='sim'");
mysql_query("update usuarios set inwar='nao' , inwar_score='0'");
mysql_query("update nal_war set vencedor='$idinwar666' where id=".$idinwar);
mysql_query("update nal_war set status='fim' where id=".$idinwar);
}
}
?>