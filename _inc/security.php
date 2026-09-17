<?php
require_once __DIR__ . '/env.php';
/**
 * Security helpers for CSRF protection
 */

/**
 * Generates or retrieves a CSRF token for the session
 * @return string The CSRF token
 */
function get_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validates a CSRF token
 * @param string $token The token to validate
 * @return bool True if valid, false otherwise
 */
function validate_csrf_token($token) {
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Outputs a hidden CSRF input field
 * @return void
 */
function csrf_input() {
    echo '<input type="hidden" name="csrf_token" value="' . get_csrf_token() . '">';
}

/**
 * Identifica o acesso local direto usado no ambiente WAMP.
 *
 * O Turnstile depende de HTTPS e de uma cadeia de certificados configurada no
 * PHP. Em localhost ele nao acrescenta protecao e pode impedir completamente
 * o login quando o cURL do WAMP nao possui um CA bundle configurado.
 */
function naruto_is_local_request() {
    $remoteAddress = $_SERVER['REMOTE_ADDR'] ?? '';
    if (!in_array($remoteAddress, ['127.0.0.1', '::1'], true)) {
        return false;
    }

    $hostHeader = strtolower(trim($_SERVER['HTTP_HOST'] ?? ''));
    $host = parse_url('http://' . $hostHeader, PHP_URL_HOST);

    return in_array($host, ['localhost', '127.0.0.1', '::1'], true);
}

function naruto_turnstile_enabled() {
    if (naruto_is_local_request()) {
        return false;
    }

    return naruto_env('TURNSTILE_SITE_KEY', '') !== ''
        && naruto_env('TURNSTILE_SECRET_KEY', '') !== '';
}

/**
 * Validates a Cloudflare Turnstile token
 * @param string $token The turnstile response token
 * @return bool True if valid, false otherwise
 */
function validate_turnstile($token) {
    $secret = naruto_env('TURNSTILE_SECRET_KEY', 'your_secret_key_here');
    // Em desenvolvimento o captcha fica desligado enquanto nao houver chave.
    if (!naruto_turnstile_enabled() || $secret === 'your_secret_key_here') return true;
    if (empty($token)) return false;
    $url = "https://challenges.cloudflare.com/turnstile/v0/siteverify";
    $data = [
        'secret' => $secret,
        'response' => $token,
        'remoteip' => $_SERVER['REMOTE_ADDR'] ?? ''
    ];

    if (!function_exists('curl_init')) {
        error_log('Turnstile nao pode ser validado: extensao cURL ausente.');
        return false;
    }
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    
    if($response === false) {
        error_log("CURL Error: " . curl_error($ch));
    }
    
    curl_close($ch);

    $result = json_decode($response, true);
    if (empty($result['success'])) {
        error_log("Turnstile Validation Failed: " . $response);
        if (function_exists('send_error_to_discord')) {
            send_error_to_discord('TURNSTILE FAIL', "Response: " . $response . " | Token: " . substr($token, 0, 20) . "...", __FILE__, __LINE__);
        }
    }
    return !empty($result['success']);
}
