<?php
/**
 * Endpoint de healthcheck para Docker / Coolify.
 *
 * Responde 200 quando o PHP esta vivo E o banco responde; 503 caso contrario.
 * Nao carrega _inc/conexao.php de proposito: conexao.php da die() e redireciona
 * para o instalador, o que transformaria uma falha de banco em uma resposta
 * 200 de pagina HTML e o container pareceria saudavel estando quebrado.
 *
 * Nao expoe host, usuario nem mensagem de erro do MySQL: o retorno e so o
 * suficiente para o orquestrador decidir se reinicia o container.
 */

require_once __DIR__ . '/_inc/env.php';

header('Content-Type: text/plain; charset=utf-8');
header('Cache-Control: no-store');

if (is_file(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
    if (class_exists('Dotenv\Dotenv')) {
        try {
            Dotenv\Dotenv::createImmutable(__DIR__)->safeLoad();
        } catch (Throwable $e) {
            // configuracao ausente e tratada abaixo
        }
    }
}

if (function_exists('mysqli_report')) {
    mysqli_report(MYSQLI_REPORT_OFF);
}

$host = naruto_env('DB_HOST', 'localhost');
$port = (int)naruto_env('DB_PORT', 3306);
$user = naruto_env('DB_USER');
$pass = naruto_env('DB_PASS', '');
$name = naruto_env('DB_NAME');

if ($user === null || $name === null) {
    http_response_code(503);
    echo "unconfigured\n";
    exit;
}

$link = @mysqli_connect($host, $user, $pass, $name, $port);
if (!$link) {
    http_response_code(503);
    echo "db-unreachable\n";
    exit;
}

$res = @mysqli_query($link, 'SELECT 1');
mysqli_close($link);

if (!$res) {
    http_response_code(503);
    echo "db-error\n";
    exit;
}

http_response_code(200);
echo "ok\n";
