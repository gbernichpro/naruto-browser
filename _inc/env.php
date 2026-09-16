<?php
/**
 * Leitura de configuracao de ambiente.
 *
 * Fica em _inc/ (e nao em install/) porque o jogo depende disso em toda
 * requisicao, enquanto a pasta install/ pode ser apagada do servidor depois
 * que a instalacao termina.
 */

if (!defined('NARUTO_ROOT')) {
    define('NARUTO_ROOT', dirname(__DIR__));
}

if (!function_exists('naruto_env')) {
    /**
     * Le uma variavel de configuracao.
     *
     * Consulta $_ENV (preenchido pelo phpdotenv a partir do .env) e tambem
     * getenv(), que e de onde vem a configuracao em Docker/Coolify e em
     * paineis de hospedagem.
     *
     * Os dois caminhos sao necessarios: o padrao variables_order=GPCS do
     * php.ini de producao NAO popula $_ENV com as variaveis reais do
     * processo, entao ler so $_ENV faz o jogo cair com "Database Connection
     * Failed" em qualquer deploy que configure o banco por variavel de
     * ambiente em vez de arquivo .env.
     */
    function naruto_env($key, $default = null) {
        if (isset($_ENV[$key]) && $_ENV[$key] !== '') {
            return $_ENV[$key];
        }
        $v = getenv($key);
        if ($v !== false && $v !== '') {
            return $v;
        }
        return $default;
    }
}

if (!function_exists('naruto_env_bool')) {
    /**
     * Le uma variavel de ambiente booleana ("true", "1", "yes", "on").
     */
    function naruto_env_bool($key, $default = false) {
        $v = naruto_env($key);
        if ($v === null) {
            return $default;
        }
        return in_array(strtolower((string)$v), ['1', 'true', 'yes', 'on'], true);
    }
}

if (!function_exists('naruto_writable_dir')) {
    /**
     * Diretorio gravavel para cache, logs e trava do instalador, com fallback
     * para o temp do sistema quando a raiz do projeto e somente leitura.
     */
    function naruto_writable_dir() {
        $dir = NARUTO_ROOT . '/_cache';
        if (!is_dir($dir) && !@mkdir($dir, 0775, true) && !is_dir($dir)) {
            $dir = sys_get_temp_dir() . '/naruto_cache';
            if (!is_dir($dir)) {
                @mkdir($dir, 0775, true);
            }
        }
        return $dir;
    }
}

if (!function_exists('naruto_lock_file')) {
    /**
     * Caminho da trava de instalacao.
     */
    function naruto_lock_file() {
        return naruto_writable_dir() . '/installed.lock';
    }
}
