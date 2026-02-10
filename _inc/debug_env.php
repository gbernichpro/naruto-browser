<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>Diagnostic Naruto RPG</h1>";

echo "<h2>Environment</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Current Dir: " . __DIR__ . "<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";

echo "<h2>Vendor/Autoload</h2>";
$vendor = __DIR__ . '/../vendor/autoload.php';
if (file_exists($vendor)) {
    echo "✅ vendor/autoload.php exists<br>";
    require_once $vendor;
} else {
    echo "❌ vendor/autoload.php MISSING<br>";
}

echo "<h2>.env Loading</h2>";
if (class_exists('Dotenv\Dotenv')) {
    echo "✅ Dotenv class found<br>";
    try {
        $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
        if (file_exists(dirname(__DIR__) . '/.env')) {
            echo "✅ .env file exists<br>";
            $dotenv->load();
        } else {
            echo "⚠️ .env file MISSING (might use system env)<br>";
            $dotenv->safeLoad();
        }
        echo "✅ Dotenv loaded successfully<br>";
    } catch (Exception $e) {
        echo "❌ Dotenv failed: " . $e->getMessage() . "<br>";
    }
} else {
    echo "❌ Dotenv class NOT FOUND<br>";
}

echo "<h2>Database Connection</h2>";
$db_name = $_ENV['DB_NAME'] ?? getenv('DB_NAME') ?? 'MISSING';
$db_user = $_ENV['DB_USER'] ?? getenv('DB_USER') ?? 'MISSING';
$db_host = $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?? 'MISSING';
$db_pass = isset($_ENV['DB_PASS']) ? 'EXISTS' : (getenv('DB_PASS') ? 'EXISTS' : 'MISSING');

echo "DB_NAME: $db_name<br>";
echo "DB_USER: $db_user<br>";
echo "DB_HOST: $db_host<br>";
echo "DB_PASS: $db_pass<br>";

if ($db_name !== 'MISSING' && $db_user !== 'MISSING' && $db_host !== 'MISSING') {
    $conn = @mysqli_connect($db_host, $db_user, $_ENV['DB_PASS'] ?? getenv('DB_PASS') ?? '');
    if ($conn) {
        echo "✅ MySQLi Connection Success<br>";
        if (mysqli_select_db($conn, $db_name)) {
            echo "✅ Database Selection Success<br>";
            $res = mysqli_query($conn, "SHOW TABLES");
            echo "Tables found: " . mysqli_num_rows($res) . "<br>";
        } else {
            echo "❌ Database Selection Failed: " . mysqli_error($conn) . "<br>";
        }
        mysqli_close($conn);
    } else {
        echo "❌ MySQLi Connection Failed: " . mysqli_connect_error() . "<br>";
    }
}

echo "<h2>Image Audit</h2>";
$img_dir = __DIR__ . '/../_img';
if (is_dir($img_dir)) {
    echo "✅ _img directory exists<br>";
    $files = array_diff(scandir($img_dir), ['.', '..']);
    echo "Files in _img: " . count($files) . "<br>";
} else {
    echo "❌ _img directory MISSING<br>";
}
