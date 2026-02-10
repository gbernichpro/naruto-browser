<?php
// DEBUG: Arquivo de depuração para sistema de caça
// Verificar se este arquivo está sendo criado
file_put_contents(__DIR__ . '/hunt_debug.log', date('Y-m-d H:i:s') . " - hunt.php foi acessado\n", FILE_APPEND);

if(isset($_POST['hunt_tipo'])){
    file_put_contents(__DIR__ . '/hunt_debug.log', date('Y-m-d H:i:s') . " - POST detectado: " . print_r($_POST, true) . "\n", FILE_APPEND);
}

if(isset($_SESSION['prepare'])){
    file_put_contents(__DIR__ . '/hunt_debug.log', date('Y-m-d H:i:s') . " - SESSION prepare definida: " . $_SESSION['prepare'] . "\n", FILE_APPEND);
}
?>
