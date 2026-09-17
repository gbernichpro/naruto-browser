<?php
require_once __DIR__ . '/conexao.php';
header('Content-Type: text/html; charset=UTF-8');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    header('Allow: POST');
    exit('Use o botao de recompensa diaria para receber seu premio.');
}
$jogadorid = (int) ($_SESSION['logado'] ?? 0);
if ($jogadorid <= 0) {
    http_response_code(401);
    exit('Sua sessao expirou. Entre novamente para receber a recompensa.');
}
if (!is_string($_POST['csrf_token'] ?? null) || !validate_csrf_token($_POST['csrf_token'])) {
    http_response_code(403);
    exit('Atualize a pagina e tente receber a recompensa novamente.');
}
$tempo = time();
$proximaRecompensa = $tempo + 86400;
$premio = random_int(1, 5);
$incrementoYens = $premio === 1 ? 1000 : ($premio === 3 ? 3000 : 0);
$incrementoCreditos = $premio === 2 ? 1 : 0;

// O premio e o novo prazo sao gravados juntos. Dois cliques simultaneos
// nunca conseguem passar pela condicao e receber duas recompensas.
$stmt = mysqli_prepare(
    $mysqli_link,
    'UPDATE usuarios SET yens = yens + ?, yens_fat = yens_fat + ?,
        creditos = creditos + ?, premiodiario = ?
     WHERE id = ? AND premiodiario <= ?'
);
mysqli_stmt_bind_param($stmt, 'iiiiii', $incrementoYens, $incrementoYens,
    $incrementoCreditos, $proximaRecompensa, $jogadorid, $tempo);
mysqli_stmt_execute($stmt);
$recebeu = mysqli_stmt_affected_rows($stmt) === 1;
mysqli_stmt_close($stmt);

if (!$recebeu) {
    echo '<div class="aviso">Você já recebeu a recompensa diária. Aguarde o próximo prêmio.</div>';
    exit;
}

$mensagens = [
    1 => 'Parabéns! Você recebeu 1.000,00 yens no seu prêmio diário!',
    2 => 'Parabéns! Você recebeu 1 crédito no seu prêmio diário!',
    3 => 'Parabéns! Você recebeu 3.000,00 yens no seu prêmio diário!',
    4 => 'Parabéns! Você conseguiu um RAMEN no seu prêmio diário!',
    5 => 'Infelizmente você não teve sorte hoje e não obteve nenhum prêmio.',
];

if ($premio === 4) {
    $ramenEntregue = false;
    try {
        $stmtRamen = mysqli_prepare($mysqli_link, 'INSERT INTO ramen (usuarioid, ramenid) VALUES (?, 5)');
        mysqli_stmt_bind_param($stmtRamen, 'i', $jogadorid);
        $ramenEntregue = mysqli_stmt_execute($stmtRamen);
        mysqli_stmt_close($stmtRamen);
    } catch (Throwable $erroRamen) {
        error_log('Falha ao entregar recompensa diaria: ' . $erroRamen->getMessage());
    }
    if (!$ramenEntregue) {
        $stmtReverter = mysqli_prepare($mysqli_link,
            'UPDATE usuarios SET premiodiario = 0 WHERE id = ? AND premiodiario = ?');
        mysqli_stmt_bind_param($stmtReverter, 'ii', $jogadorid, $proximaRecompensa);
        mysqli_stmt_execute($stmtReverter);
        http_response_code(500);
        exit('Não foi possível entregar o ramen. Tente novamente.');
    }
}

$mensagem = $mensagens[$premio];
echo '<div class="aviso">' . htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8') . '</div>';
echo '<script>setTimeout(function(){ window.location.reload(); }, 1800);</script>';
