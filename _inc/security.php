<?php
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
 * Validates a Cloudflare Turnstile token
 * @param string $token The turnstile response token
 * @return bool True if valid, false otherwise
 */
function validate_turnstile($token) {
    if (empty($token)) return false;
    
    $secret = $_ENV['TURNSTILE_SECRET_KEY'];
    if ($secret === 'your_secret_key_here') return true; // Bypass for development if not configured

    $url = "https://challenges.cloudflare.com/turnstile/v0/siteverify";
    $data = [
        'secret' => $secret,
        'response' => $token,
        'remoteip' => $_SERVER['REMOTE_ADDR']
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
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
