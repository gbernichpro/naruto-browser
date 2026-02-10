<?php
/**
 * Automacao de Criacao de Tabelas no Deploy
 */
function db_auto_init($link, $db_name) {
    if (!$link) return;
    
    // Verifica se ja existem tabelas
    $result = mysqli_query($link, "SHOW TABLES FROM `$db_name`");
    if (mysqli_num_rows($result) > 0) {
        return; // Ja tem dados
    }
    
    // Se estiver vazio, tenta criar a estrutura basica
    $sql_file = dirname(__DIR__) . '/database_schema.sql';
    if (file_exists($sql_file)) {
        $sql = file_get_contents($sql_file);
        // Split por ; para rodar multiplos comandos (simples)
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
