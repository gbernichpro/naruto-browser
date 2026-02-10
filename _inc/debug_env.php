<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Advanced Diagnostic Naruto RPG</h1>";

// 1. Recursive Image Scan
function count_files_recursive($dir) {
    $count = 0;
    if (!is_dir($dir)) return 0;
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $path = $dir . '/' . $file;
        if (is_dir($path)) {
            $count += count_files_recursive($path);
        } else {
            $count++;
        }
    }
    return $count;
}

echo "<h2>A ativos (Imagens/Template)</h2>";
$img_count = count_files_recursive(__DIR__ . '/../_img');
$template_count = count_files_recursive(__DIR__ . '/../template');
echo "Total files in _img (recursive): $img_count<br>";
echo "Total files in template: $template_count<br>";

if ($template_count === 0) {
    echo "❌ CRITICAL: 'template' folder is EMPTY or MISSING in production!<br>";
}

// 2. Database Row Check
require_once 'conexao.php';
echo "<h2>Database Records</h2>";
$tables_to_check = ['settings', 'usuarios', 'table_itens', 'organizacoes'];
foreach ($tables_to_check as $table) {
    $res = mysqli_query($mysqli_link, "SELECT COUNT(*) FROM `$table`") or die(mysqli_error($mysqli_link));
    $row = mysqli_fetch_row($res);
    echo "Table `$table`: " . $row[0] . " rows<br>";
}

echo "<h2>Security Keys Check</h2>";
echo "TURNSTILE_SITE_KEY: " . (isset($_ENV['TURNSTILE_SITE_KEY']) ? "✅ EXISTS (" . substr($_ENV['TURNSTILE_SITE_KEY'], 0, 6) . "...)" : "❌ MISSING") . "<br>";
echo "TURNSTILE_SECRET_KEY: " . (isset($_ENV['TURNSTILE_SECRET_KEY']) ? "✅ EXISTS (" . substr($_ENV['TURNSTILE_SECRET_KEY'], 0, 6) . "...)" : "❌ MISSING") . "<br>";

echo "<h2>Check Critical Files</h2>";
$critical = ['_inc/menu_off.php', '_inc/menu_on.php', '_inc/top.php', 'index.php'];
foreach ($critical as $f) {
    $exists = file_exists(__DIR__ . '/../' . $f) ? "✅" : "❌ MISSING";
    echo "$f: $exists<br>";
}

echo "<h2>Session/Cookie Status</h2>";
echo "Session Status: " . session_status() . "<br>";
echo "PHPSESSID: " . ($_COOKIE['PHPSESSID'] ?? 'MISSING') . "<br>";
echo "logado (cookie): " . ($_COOKIE['logado'] ?? 'MISSING') . "<br>";

?>
