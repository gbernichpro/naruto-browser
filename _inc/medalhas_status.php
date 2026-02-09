<?php

$buscamedalhas=mysql_query("SELECT * FROM medalhas where usuarioid='".$db['id']."' and medalha='Staff'");

if($db['tipodeconta']=='admin'){
if(mysql_num_rows($buscamedalhas)<1){mysql_query("INSERT INTO medalhas (usuarioid,medalha,descricao,tipo) Values ('".$db['id']."','Staff','Faz parte da equipe da staff do naruto','3')");
echo'<script>
  new $.Zebra_Dialog("<strong>Medalha Adiquirida</strong><br>" +
    "Parabéns você conquistou uma medalha: Staff<br>Vá até seu perfil visualizar.", {
    "buttons":  false,
    "modal": false,
    "position": ["right - 20", "top + 20"],
    "auto_close": 7000
});

</script>';
}
}

$buscamedalhas2=mysql_query("SELECT * FROM medalhas where usuarioid='".$db['id']."' and medalha='Ativo'");

if($db['nivel']>=20){
if(mysql_num_rows($buscamedalhas2)<1){
mysql_query("INSERT INTO medalhas (usuarioid,medalha,descricao,tipo) Values ('".$db['id']."','Ativo','Jogador ativo no naruto','1')");
echo'<script>
  new $.Zebra_Dialog("<strong>Medalha Adiquirida</strong><br>" +
    "Parabéns você conquistou uma medalha: Ativo: Jogador ativo no naruto<br>Vá até seu perfil visualizar.", {
    "buttons":  false,
    "modal": false,
    "position": ["right - 20", "top + 20"],
    "auto_close": 7000
});

</script>';
}
}


?>