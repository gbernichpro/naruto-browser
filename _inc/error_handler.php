<?php
require_once __DIR__ . '/env.php';
/**
 * Sistema de notificação de erros via Discord Webhook
 */

function send_error_to_discord($error_type, $error_message, $file, $line) {
    $webhook_url = naruto_env('DISCORD_WEBHOOK_URL', '');
    
    // Se não estiver configurado ou for o placeholder, não envia
    if (empty($webhook_url) || $webhook_url === 'your_discord_webhook_url_here') {
        return;
    }
    
    $embed = [
        'embeds' => [
            [
                'title' => '🚨 Erro no Naruto RPG',
                'color' => 15158332, // Vermelho
                'fields' => [
                    [
                        'name' => 'Tipo',
                        'value' => $error_type,
                        'inline' => true
                    ],
                    [
                        'name' => 'Arquivo',
                        'value' => basename($file),
                        'inline' => true
                    ],
                    [
                        'name' => 'Linha',
                        'value' => $line,
                        'inline' => true
                    ],
                    [
                        'name' => 'Mensagem',
                        'value' => substr($error_message, 0, 1000),
                        'inline' => false
                    ]
                ],
                'timestamp' => date('c'),
                'footer' => [
                    'text' => 'Naruto RPG Error Monitor'
                ]
            ]
        ]
    ];
    
    $ch = curl_init($webhook_url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($embed));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    curl_close($ch);
}

// Handler global de erros
function custom_error_handler($errno, $errstr, $errfile, $errline) {
    // Ignora erros suprimidos com @
    if (!(error_reporting() & $errno)) {
        return false;
    }
    
    $error_types = [
        E_ERROR => 'ERRO FATAL',
        E_WARNING => 'AVISO',
        E_NOTICE => 'NOTÍCIA',
        E_USER_ERROR => 'ERRO DO USUÁRIO',
        E_USER_WARNING => 'AVISO DO USUÁRIO',
        E_USER_NOTICE => 'NOTÍCIA DO USUÁRIO'
    ];
    
    $error_type = $error_types[$errno] ?? 'ERRO DESCONHECIDO';
    
    // Só envia para Discord se for erro crítico
    if (in_array($errno, [E_ERROR, E_USER_ERROR, E_CORE_ERROR, E_COMPILE_ERROR])) {
        send_error_to_discord($error_type, $errstr, $errfile, $errline);
    }
    
    return false; // Permite que o handler padrão do PHP também processe
}

// Handler de exceções não capturadas
function custom_exception_handler($exception) {
    send_error_to_discord(
        'EXCEÇÃO NÃO CAPTURADA',
        $exception->getMessage(),
        $exception->getFile(),
        $exception->getLine()
    );
}

// Registra os handlers
set_error_handler('custom_error_handler');
set_exception_handler('custom_exception_handler');
