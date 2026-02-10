<?php
/**
 * Automacao de Criacao de Tabelas no Deploy
 */
function db_auto_init($link, $db_name) {
    if (!$link) return;
    
    // Verifica se ja existem as tabelas criticas
    $result = mysqli_query($link, "SHOW TABLES LIKE 'settings'");
    if (mysqli_num_rows($result) > 0) {
        return; // Ja tem o sistema base
    }
    
    // Tenta carregar o dump completo primeiro
    $sql_file = dirname(__DIR__) . '/narutov3.sql';
    if (!file_exists($sql_file)) {
        $sql_file = dirname(__DIR__) . '/database_schema.sql';
    }

    if (file_exists($sql_file)) {
        $sql = file_get_contents($sql_file);
        // Remove comentarios SQL de linha simples para nao quebrar o explode
        $sql = preg_replace('/^\s*--.*$/m', '', $sql);
        $sql = preg_replace('/^\s*\/\*.*\*\/;?$/m', '', $sql);
        
        // Split por ; para rodar multiplos comandos
        $queries = explode(';', $sql);
        foreach ($queries as $q) {
            $q = trim($q);
            if (!empty($q)) {
                mysqli_query($link, $q);
            }
        }
        return true;
    }
    return false;
}
