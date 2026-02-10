<?php
session_start();
echo "<h1>Teste de Sessão</h1>";
if (!isset($_SESSION['teste_contador'])) {
    $_SESSION['teste_contador'] = 0;
}
$_SESSION['teste_contador']++;
echo "Contador de Sessão: " . $_SESSION['teste_contador'] . "<br>";
echo "ID da Sessão: " . session_id() . "<br>";
echo "<a href='session_test.php'>Recarregar Página</a>";
?>
