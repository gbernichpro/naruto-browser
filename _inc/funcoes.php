<?php
function fpersonagem($p){
	switch($p){
		case 'naruto': echo 'Uzumaki Naruto'; break;
		case 'sasuke': echo 'Uchiha Sasuke'; break;
		case 'sakura': echo 'Haruno Sakura'; break;
		case 'kakashi': echo 'Hatake Kakashi'; break;
		case 'itachi': echo 'Uchiha Itachi'; break;
		case 'kisame': echo 'Hoshigaki Kisame'; break;
		case 'konohamaru': echo 'Sarutobi Konohamaru'; break;
		case 'neji': echo 'Hyuuga Neji'; break;
		case 'kiba': echo 'Inuzuka Kiba'; break;
		case 'ino': echo 'Yamanaka Ino'; break;
		case 'tenten': echo 'Tenten'; break;
		case 'lee': echo 'Rock Lee'; break;
		case 'hinata': echo 'Hyuuga Hinata'; break;
		case 'temari': echo 'Temari'; break;
		case 'shino': echo 'Aburame Shino'; break;
		case 'kankurou': echo 'Kankurou'; break;
		case 'tayuya': echo 'Tayuya'; break;
		case 'gaara': echo 'Sabaku no Gaara'; break;
		case 'shikamaru': echo 'Nara Shikamaru'; break;
		case 'chouji': echo 'Akimichi Chouji'; break;
		case 'haku': echo 'Koori Haku'; break;
		case 'kabuto': echo 'Yakushi Kabuto'; break;
		case 'kidoumaru': echo 'Kidoumaru'; break;
		case 'iruka': echo 'Iruka'; break;
		case 'sai': echo 'Sai'; break;
		case 'zabuza': echo 'Momochi Zabuza'; break;
		case 'jiroubo': echo 'Jiroubo'; break;
		case 'sakon': echo 'Sakon/Ukon'; break;
		case 'kimimaro': echo 'Kimimaro'; break;
		case 'gai': echo 'Maito Gai'; break;
		case 'kurenai': echo 'Yuuhi Kurenai'; break;
		case 'asuma': echo 'Sarutobi Asuma'; break;
		case 'hagane': echo 'Hagane Kotetsu'; break;
		case 'hayate': echo 'Gekkou Hayate'; break;
		case 'izumo': echo 'Kamizuki Izumo'; break;
		case 'danzou': echo 'Danzou Shimura'; break;
		case 'ao': echo 'Ao'; break;
		case 'tobi': echo 'Tobi'; break;
		case 'shisui': echo 'Uchiha Shisui'; break;
		case 'jiraya': echo 'Jiraya'; break;
		case 'madara': echo 'madara'; break;
		case 'yagura': echo 'yagura'; break;
		case 'terceiro raikage': echo 'terceiro raikage'; break;
		case 'minato': echo 'minato'; break;
		
	}
}
function rankNinja($nivel){
	if($nivel>60) $rk='ANBU';
	if($nivel<=60) $rk='Sannin';
	if($nivel<=40) $rk='Jounnin';
	if($nivel<=20) $rk='Chuunin';
	if($nivel<=5) $rk='Gennin';
	return $rk;
}
function dano($taijutsu1,$taijutsu2){
	if(($taijutsu2+10)<=$taijutsu1) $bonus=rand(1,5); else $bonus=rand(1,10);
	if($taijutsu1<($taijutsu2-25)) return 0; else
	return round(($taijutsu1*((($taijutsu1*5)/$taijutsu2)/4))/4)+$bonus;
}
function danoo($taijutsu1,$taijutsu2){
	if(($taijutsu2+2)<=$taijutsu1) $bonus=rand(1,5); else $bonus=rand(1,5);
	if($taijutsu1<($taijutsu2-25)) return 0; else
	return round(($taijutsu1*((($taijutsu1*2)/$taijutsu2)))/2)+$bonus;
}
function danojutsu($ninjutsu1,$ninjutsu2,$forca){
	if($ninjutsu1<($ninjutsu2-25)) return 0; else
	return round(($ninjutsu1*((($ninjutsu1*5)/$ninjutsu2)/4))/4)+$forca+rand(1,10);
}
function numberFormat($num){
     return preg_replace("/(?<=\d)(?=(\d{3})+(?!\d))/",".", $num);
} 
function format_number($number){
	return str_replace(".","<span class=\"\">.</span>", numberFormat($number));
}
function format_time($sek){
    $std = 3600;
    $min = 60;
    $anzahl_std = 0;
	$anzahl_min = 0;
    while($std <= $sek){
		$sek -= $std;
		++$anzahl_std;
	}
	while($min <= $sek){
		$sek -= $min;
		++$anzahl_min;
	}
	return sprintf("%01s", $anzahl_std).":".sprintf("%02s", $anzahl_min).":".sprintf("%02s", $sek);
}
function format_time_now($sek){
	$day = 86400;
	$std = 3600;
	$min = 60;
	$anzahl_day = 0;
	$anzahl_std = 0;
	$anzahl_min = 0;

	while($day <= $sek){
		$sek -= $day;
		++$anzahl_day;
	}
	while($std <= $sek){
		$sek -= $std;
		++$anzahl_std;
	}
	while($min <= $sek){
		$sek -= $min;
		++$anzahl_min;
	}

	if($anzahl_day == 1) $dias = "dia";
	elseif($anzahl_day > 1) $dias = "dias";
	if($anzahl_day > 0) return sprintf("%01s", $anzahl_day)." ".$dias." ".sprintf("%01s", $anzahl_std).":".sprintf("%02s", $anzahl_min).":".sprintf("%02s", $sek);	
	else return sprintf("%01s", $anzahl_std).":".sprintf("%02s", $anzahl_min).":".sprintf("%02s", $sek);
}
function format_date($stamp, $show_sek=false){
	$today_day = date("d", $time = time());
	$tomorrow_day = date("d", $time+86400);
	$return = "";
	if($today_day == date("d", $stamp)) $return = "hoje";
	elseif($tomorrow_day == date("d", $stamp)) $return = "amanhã";
	else $return = "em ".date("d.m", $stamp);
	if($show_sek) $return .= " às ".date("G:i:s", $stamp);
	else $return .= " às ".date("G:i", $stamp);
	return $return;
}
function ofers_paz($from_org,$from_sigla,$to_org,$to_sigla,$war){
	global $db;

	mysql_query("INSERT INTO `org_wars_paz` (`to_id`,`from_id`,`war_id`,`temp`) VALUES ('".$to_org."','".$from_org."','".$war."','".time()."')");

	//ENVIAR MENSAGEM AOS MEMBROS DO CLÃ
	$select = mysql_query("SELECT * FROM `usuarios` WHERE `orgid`='".$from_org."'") or die(mysql_error());
	$date = date('Y-m-d H:i:s');
	$assunto = "Seu clã fez uma oferta de paz ao clã ".$to_sigla.".";
	$mensagem = "Para ver o status da guerra clique <a href=\"?p=warorg&m=wars&view=\">aqui</a>.";
	while($row = mysql_fetch_assoc($select)){
		mysql_query("INSERT INTO `mensagens` (`data`,`origem`,`destino`,`assunto`,`msg`) VALUES ('".$date."','0','".$row['id']."','".$assunto."','".$mensagem."')") or die(mysql_error());
	}

	$select = mysql_query("SELECT * FROM `usuarios` WHERE `orgid`='".$to_org."'") or die(mysql_error());
	$date = date('Y-m-d H:i:s');
	$assunto = "O clã ".$from_sigla." fez uma oferta de paz ao seu clã.";
	$mensagem = "Para ver o status da guerra clique <a href=\"?p=warorg&m=wars&view=\">aqui</a>.";
	while($row = mysql_fetch_assoc($select)){
		mysql_query("INSERT INTO `mensagens` (`data`,`origem`,`destino`,`assunto`,`msg`) VALUES ('".$date."','0','".$row['id']."','".$assunto."','".$mensagem."')") or die(mysql_error());
	}
}
function acept_paz($from_org,$from_sigla,$to_org,$to_sigla,$war,$delet=""){
	$vit = array(); 
	$sql = mysql_query("SELECT * FROM `org_wars_declare` WHERE `war_id`='".$war."'");
	while($exe = mysql_fetch_assoc($sql)){
		if($delet == $exe['to_org']){
		}else{
			$lado1 = $exe['total_1']+$exe['exp_1'];
			$lado2 = $exe['total_2']+$exe['exp_2'];
			if($lado1 == $lado2){
				$result = "ind";
			}elseif($lado1 > $lado2){
				mysql_query("UPDATE `organizacoes` SET `war_v`=`war_v`+'1' WHERE `id`='".$exe['to_org']."'");
				$result = "vit";
			}else{
				mysql_query("UPDATE `organizacoes` SET `war_d`=`war_d`+'1' WHERE `id`='".$exe['to_org']."'");
				$result = "der";	
			}
			$vit[$exe['to_ally']] = $result;

			mysql_query("INSERT INTO `org_wars_arq` (`to_org`,`from_org`,`title`,`text`,`exp_1`,`exp_2`,`vit_1`,`vit_2`,`der_1`,`der_2`,`total_1`,`total_2`,`time`,`end_time`,`war_id`,`result`)  VALUES ('".$exe['to_org']."','".$exe['from_org']."','".$exe['title']."','".$exe['text']."','".$exe['exp_1']."','".$exe['exp_2']."','".$exe['vit_1']."','".$exe['vit_2']."','".$exe['der_1']."','".$exe['der_2']."','".$exe['total_1']."','".$exe['total_2']."','".$exe['time']."','".time()."','".$exe['war_id']."','".$result."')");

			mysql_query("DELETE FROM `org_wars` WHERE `war_id`='".$war."'");
			mysql_query("DELETE FROM `org_wars_declare` WHERE `war_id`='".$war."'");
			mysql_query("DELETE FROM `org_wars_paz` WHERE `war_id`='".$war."'");
		}
	}
	if($delet == $from_org){
		if($vit[$to_org] == "ind"){
			$text = " empatou na guerra contra seu clã.";
		}elseif($vit[$to_org] == "vit"){
			$text = " perdeu a guerra contra seu clã.";
		}elseif($vit[$to_org] == "der"){
			$text = " ganhou a guerra contra seu clã.";
		}else{
			$text = "(indefinido)";
		}

		//ENVIAR MENSAGEM AOS MEMBROS DO CLÃ VENCEDOR CASO O OUTRO CLÃ SEJA APAGADO
		$select = mysql_query("SELECT * FROM `usuarios` WHERE `orgid`='".$to_org."'") or die(mysql_error());
		$date = date('Y-m-d H:i:s');
		$assunto = "O clã ".$from_sigla."".$text;
		$mensagem = "Para ver o status da guerra clique <a href=\"?p=warorg&m=wars&view=\">aqui</a>.";
		while($row = mysql_fetch_assoc($select)){
			mysql_query("INSERT INTO `mensagens` (`data`,`origem`,`destino`,`assunto`,`msg`) VALUES ('".$date."','0','".$row['id']."','".$assunto."','".$mensagem."')") or die(mysql_error());
		}
	}else{
		//ENVIAR MENSAGEM AOS MEMBROS DO CLÃ
		$select = mysql_query("SELECT * FROM `usuarios` WHERE `orgid`='".$from_org."'") or die(mysql_error());
		$date = date('Y-m-d H:i:s');
		$assunto = $from_sigla." aceitou a oferta de paz de ".$to_sigla.".";
		$mensagem = "Para ver o arquivo da guerra clique <a href=\"#\">aqui</a>.";
		while($row = mysql_fetch_assoc($select)){
			mysql_query("INSERT INTO `mensagens` (`data`,`origem`,`destino`,`assunto`,`msg`) VALUES ('".$date."','0','".$row['id']."','".$assunto."','".$mensagem."')") or die(mysql_error());
		}

		$select = mysql_query("SELECT * FROM `usuarios` WHERE `orgid`='".$to_org."'") or die(mysql_error());
		$date = date('Y-m-d H:i:s');
		$assunto = "O clã ".$from_sigla." aceitou a oferta de paz.";
		$mensagem = "Para ver o arquivo da guerra clique <a href=\"#\">aqui</a>.";
		while($row = mysql_fetch_assoc($select)){
			mysql_query("INSERT INTO `mensagens` (`data`,`origem`,`destino`,`assunto`,`msg`) VALUES ('".$date."','0','".$row['id']."','".$assunto."','".$mensagem."')") or die(mysql_error());
		}
	}	   
}
function reject_paz($from_org,$from_sigla,$to_org,$to_sigla,$war){
	mysql_query("DELETE FROM `org_wars_paz` WHERE `war_id`='".$war."'");

	//ENVIAR MENSAGEM AOS MEMBROS DO CLÃ
	$select = mysql_query("SELECT * FROM `usuarios` WHERE `orgid`='".$from_org."'") or die(mysql_error());
	$date = date('Y-m-d H:i:s');
	$assunto = $from_sigla." rejeitou o pedido de paz de ".$to_sigla.".";
	$mensagem = "Para ver o status da guerra clique <a href=\"?p=warorg&m=wars&view=\">aqui</a>.";
	while($row = mysql_fetch_assoc($select)){
		mysql_query("INSERT INTO `mensagens` (`data`,`origem`,`destino`,`assunto`,`msg`) VALUES ('".$date."','0','".$row['id']."','".$assunto."','".$mensagem."')") or die(mysql_error());
	}

	$select = mysql_query("SELECT * FROM `usuarios` WHERE `orgid`='".$to_org."'") or die(mysql_error());
	$date = date('Y-m-d H:i:s');
	$assunto = "O clã ".$from_sigla." rejeitou a oferta de paz.";
	$mensagem = "Para ver o status da guerra clique <a href=\"?p=warorg&m=wars&view=\">aqui</a>.";
	while($row = mysql_fetch_assoc($select)){
		mysql_query("INSERT INTO `mensagens` (`data`,`origem`,`destino`,`assunto`,`msg`) VALUES ('".$date."','0','".$row['id']."','".$assunto."','".$mensagem."')") or die(mysql_error());
	}
}
?>