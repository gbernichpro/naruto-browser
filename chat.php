<?php
require_once('_inc/conexao.php');

/**
 * Nome do jogador logado, como gravado na tabela `usuarios`.
 *
 * O chat gravava e lia $_SESSION['username'], que NUNCA era definido em lugar
 * nenhum do projeto: o login guarda apenas $_SESSION['logado'] com o id. Com
 * isso o heartbeat consultava `chat.to = ''` para sempre (nenhuma mensagem era
 * entregue) e cada poll enchia o error_log com "Undefined array key username"
 * e com o deprecated de passar null para mysql_real_escape_string().
 *
 * O login agora popula a chave, e esta funcao resolve pelo id para curar as
 * sessoes que ja estavam abertas, sem exigir que o jogador entre de novo.
 *
 * Importante: o valor vem do banco, e nao do que o jogador digitou no login.
 * As colunas `chat.from` e `chat.to` usam collation utf8_bin, que diferencia
 * maiusculas de minusculas; gravar "Fulano" onde o cadastro diz "fulano"
 * faria a mensagem nunca ser encontrada pelo destinatario.
 *
 * @return string  nome do jogador, ou '' se nao houver ninguem logado
 */
function chat_username() {
    if (isset($_SESSION['username']) && $_SESSION['username'] !== '') {
        return $_SESSION['username'];
    }
    if (empty($_SESSION['logado'])) {
        return '';
    }

    global $mysqli_link;
    $nome = '';
    $stmt = mysqli_prepare($mysqli_link, 'SELECT usuario FROM usuarios WHERE id=?');
    if ($stmt) {
        $id = (int)$_SESSION['logado'];
        mysqli_stmt_bind_param($stmt, 'i', $id);
        if (mysqli_stmt_execute($stmt)) {
            $res = mysqli_stmt_get_result($stmt);
            if ($res && ($row = mysqli_fetch_assoc($res))) {
                $nome = (string)$row['usuario'];
            }
        }
        mysqli_stmt_close($stmt);
    }

    $_SESSION['username'] = $nome;
    return $nome;
}

if (isset($_GET['action']) && $_GET['action'] == "chatheartbeat") { chatHeartbeat(); } 
if (isset($_GET['action']) && $_GET['action'] == "sendchat") { sendChat(); } 
if (isset($_GET['action']) && $_GET['action'] == "closechat") { closeChat(); } 
if (isset($_GET['action']) && $_GET['action'] == "startchatsession") { startChatSession(); } 

if (!isset($_SESSION['chatHistory'])) {
	$_SESSION['chatHistory'] = array();	
}

if (!isset($_SESSION['openChatBoxes'])) {
	$_SESSION['openChatBoxes'] = array();	
}

function chatHeartbeat() {

	// Visitante deslogado fica com o JS do chat na tela fazendo poll a cada
	// poucos segundos. Sem esta saida, cada um desses polls dispara duas
	// consultas inuteis ao banco.
	$username = chat_username();
	if ($username === '') {
		header('Content-type: application/json');
		echo '{"items":[]}';
		exit(0);
	}

	$sql = "select * from chat where (chat.to = '".mysql_real_escape_string($username)."' AND recd = 0) order by id ASC";
	$query = mysql_query($sql);
	$items = '';

	$chatBoxes = array();

	while ($chat = mysql_fetch_array($query)) {

		if (!isset($_SESSION['openChatBoxes'][$chat['from']]) && isset($_SESSION['chatHistory'][$chat['from']])) {
			$items = $_SESSION['chatHistory'][$chat['from']];
		}

		$chat['message'] = sanitize($chat['message']);

		$items .= <<<EOD
					   {
			"s": "0",
			"f": "{$chat['from']}",
			"m": "{$chat['message']}"
	   },
EOD;

	if (!isset($_SESSION['chatHistory'][$chat['from']])) {
		$_SESSION['chatHistory'][$chat['from']] = '';
	}

	$_SESSION['chatHistory'][$chat['from']] .= <<<EOD
						   {
			"s": "0",
			"f": "{$chat['from']}",
			"m": "{$chat['message']}"
	   },
EOD;
		
		unset($_SESSION['tsChatBoxes'][$chat['from']]);
		$_SESSION['openChatBoxes'][$chat['from']] = $chat['sent'];
	}

	if (!empty($_SESSION['openChatBoxes'])) {
	foreach ($_SESSION['openChatBoxes'] as $chatbox => $time) {
		if (!isset($_SESSION['tsChatBoxes'][$chatbox])) {
			$now = time()-strtotime($time);
			$time = date('g:iA M dS', strtotime($time));

			$message = "";
			if ($now > 180) {
				$items .= <<<EOD
{
"s": "2",
"f": "$chatbox",
"m": "{$message}"
},
EOD;

	if (!isset($_SESSION['chatHistory'][$chatbox])) {
		$_SESSION['chatHistory'][$chatbox] = '';
	}

	$_SESSION['chatHistory'][$chatbox] .= <<<EOD
		{
"s": "2",
"f": "$chatbox",
"m": "{$message}"
},
EOD;
			$_SESSION['tsChatBoxes'][$chatbox] = 1;
		}
		}
	}
}

	$sql = "update chat set recd = 1 where chat.to = '".mysql_real_escape_string($username)."' and recd = 0";
	$query = mysql_query($sql);

	if ($items != '') {
		$items = substr($items, 0, -1);
	}
header('Content-type: application/json');
?>
{
		"items": [
			<?php echo $items;?>
        ]
}

<?php
			exit(0);
}

function chatBoxSession($chatbox) {
	
	$items = '';
	
	if (isset($_SESSION['chatHistory'][$chatbox])) {
		$items = $_SESSION['chatHistory'][$chatbox];
	}

	return $items;
}

function startChatSession() {
	$items = '';
	if (!empty($_SESSION['openChatBoxes'])) {
		foreach ($_SESSION['openChatBoxes'] as $chatbox => $void) {
			$items .= chatBoxSession($chatbox);
		}
	}


	if ($items != '') {
		$items = substr($items, 0, -1);
	}

header('Content-type: application/json');
?>
{
		"username": <?php echo json_encode(chat_username()); ?>,
		"items": [
			<?php echo $items;?>
        ]
}

<?php


	exit(0);
}

function sendChat() {
	$from = chat_username();
	$to = $_POST['to'];
	$message = $_POST['message'];

	$_SESSION['openChatBoxes'][$_POST['to']] = date('Y-m-d H:i:s', time());
	
	$messagesan = sanitize($message);

	if (!isset($_SESSION['chatHistory'][$_POST['to']])) {
		$_SESSION['chatHistory'][$_POST['to']] = '';
	}

	$_SESSION['chatHistory'][$_POST['to']] .= <<<EOD
					   {
			"s": "1",
			"f": "{$to}",
			"m": "{$messagesan}"
	   },
EOD;


	unset($_SESSION['tsChatBoxes'][$_POST['to']]);

	$sql = "insert into chat (chat.from,chat.to,message,sent) values ('".mysql_real_escape_string($from)."', '".mysql_real_escape_string($to)."','".mysql_real_escape_string($message)."',NOW())";
	$query = mysql_query($sql);
	echo "1";
	exit(0);
}

function closeChat() {

	unset($_SESSION['openChatBoxes'][$_POST['chatbox']]);
	
	echo "1";
	exit(0);
}

function sanitize($text) {
	$text = htmlspecialchars($text, ENT_QUOTES);
	$text = str_replace("\n\r","\n",$text);
	$text = str_replace("\r\n","\n",$text);
	$text = str_replace("\n","<br>",$text);
	return $text;
}