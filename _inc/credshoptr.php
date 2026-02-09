<fieldset>
<legend><b>Transferir yens</b></legend>
<?php



	echo "<form method=\"POST\" action=\"_inc/transfer.php\">";
	echo "<table><tr><td width=\"30%\"><b>Usuário:</b></td><td width=\"70%\"><input type=\"text\" name=\"usuario\" size=\"20\"/></td></tr>";
	echo "<tr><td width=\"30%\"><b>Quantia:</b></td><td width=\"70%\"><input type=\"text\" name=\"creditos\" size=\"20\"/></td></tr>";
	echo "<input type=\"submit\" name=\"submit\" value=\"Enviar\"></td></tr></table>";
	echo "</form><BR><font size=\"1\"><a href=\"forgottrans.php\">Esqueceu sua senha de transferência?</a> - <a href=\"account.php\">Alterar senha de transferência</a></font>";
	echo "</fieldset>";
	echo "<center><font size=1><a href=\"#\" onclick=\"javascript:window.open('loggold.php', '_blank','top=100, left=100, height=350, width=450, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');\">Transferências realizadas nos últimos 14 dias.</a></font></center>";
exit;


?>
  
  

