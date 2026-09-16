<?php
/**
 * PHP MySQLi Shim
 * Provides backward compatibility for legacy mysql_* functions using the mysqli extension.
 */

if (!function_exists('mysql_connect')) {
    global $mysqli_link;
    $mysqli_link = null;

    /**
     * Separa "host:porta" em [host, porta], como a extensao mysql original
     * aceitava. O mysqli nao entende essa sintaxe: a porta e um argumento
     * proprio. Sem isso, qualquer banco fora da porta 3306 (comum em Docker
     * e em hospedagem compartilhada) fica inacessivel.
     */
    function mysql_split_host($host) {
        $porta = null;
        // IPv6 entre colchetes: [::1]:3306
        if (preg_match('/^\[(.+)\](?::(\d+))?$/', $host, $m)) {
            return [$m[1], isset($m[2]) ? (int)$m[2] : null];
        }
        if (substr_count($host, ':') === 1) {
            list($host, $sufixo) = explode(':', $host, 2);
            // "host:/caminho/socket.sock" nao e porta.
            if (ctype_digit($sufixo)) {
                $porta = (int)$sufixo;
            } else {
                $host = $host . ':' . $sufixo;
            }
        }
        return [$host, $porta];
    }

    function mysql_connect($host, $user, $password, $new_link = false, $client_flags = 0) {
        global $mysqli_link;
        list($h, $porta) = mysql_split_host($host);
        $mysqli_link = $porta
            ? mysqli_connect($h, $user, $password, '', $porta)
            : mysqli_connect($h, $user, $password);
        return $mysqli_link;
    }

    function mysql_pconnect($host, $user, $password, $client_flags = 0) {
        global $mysqli_link;
        // mysqli doesn't have a direct equivalent for pconnect in the same way,
        // but adding p: to host enables persistent connections.
        list($h, $porta) = mysql_split_host($host);
        $mysqli_link = $porta
            ? mysqli_connect('p:' . $h, $user, $password, '', $porta)
            : mysqli_connect('p:' . $h, $user, $password);
        return $mysqli_link;
    }

    function mysql_select_db($database_name, $link_identifier = null) {
        global $mysqli_link;
        $link = ($link_identifier === null) ? $mysqli_link : $link_identifier;
        return mysqli_select_db($link, $database_name);
    }

    function mysql_query($query, $link_identifier = null) {
        global $mysqli_link;
        $link = ($link_identifier === null) ? $mysqli_link : $link_identifier;
        return mysqli_query($link, $query);
    }

    function mysql_fetch_assoc($result) {
        return mysqli_fetch_assoc($result);
    }

    function mysql_fetch_array($result, $result_type = MYSQLI_BOTH) {
        return mysqli_fetch_array($result, $result_type);
    }

    function mysql_num_rows($result) {
        return mysqli_num_rows($result);
    }

    function mysql_fetch_row($result) {
        return mysqli_fetch_row($result);
    }

    function mysql_error($link_identifier = null) {
        global $mysqli_link;
        $link = ($link_identifier === null) ? $mysqli_link : $link_identifier;
        if (!$link) return mysqli_connect_error();
        return mysqli_error($link);
    }

    function mysql_real_escape_string($unescaped_string, $link_identifier = null) {
        global $mysqli_link;
        $link = ($link_identifier === null) ? $mysqli_link : $link_identifier;

        // O codigo legado chama esta funcao com variaveis que podem ser null
        // (chave de sessao ausente, coluna NULL, $_POST que nao veio). Ate o
        // PHP 8.0 isso virava '' em silencio; do 8.1 em diante cada chamada
        // emite um deprecated, e uma pagina que faz poll enche o error_log da
        // hospedagem em minutos. O cast reproduz o comportamento antigo.
        $unescaped_string = ($unescaped_string === null) ? '' : (string)$unescaped_string;

        if (!$link) {
            // Fallback for when no connection is established yet
            return addslashes($unescaped_string);
        }
        return mysqli_real_escape_string($link, $unescaped_string);
    }

    function mysql_insert_id($link_identifier = null) {
        global $mysqli_link;
        $link = ($link_identifier === null) ? $mysqli_link : $link_identifier;
        return mysqli_insert_id($link);
    }

    function mysql_close($link_identifier = null) {
        global $mysqli_link;
        $link = ($link_identifier === null) ? $mysqli_link : $link_identifier;
        return mysqli_close($link);
    }

    function mysql_free_result($result) {
        if ($result instanceof mysqli_result) {
            return mysqli_free_result($result);
        }
        return false;
    }

    function mysql_affected_rows($link_identifier = null) {
        global $mysqli_link;
        $link = ($link_identifier === null) ? $mysqli_link : $link_identifier;
        return mysqli_affected_rows($link);
    }

    function mysql_escape_string($string) {
        return mysql_real_escape_string($string);
    }
    
    function mysql_get_server_info($link_identifier = null) {
        global $mysqli_link;
        $link = ($link_identifier === null) ? $mysqli_link : $link_identifier;
        return mysqli_get_server_info($link);
    }

    /**
     * Simplified helper for Prepared Statements
     * Usage: $result = mysqli_query_prepared("SELECT * FROM table WHERE id=?", "i", $id);
     */
    function mysqli_query_prepared($query, $types = "", ...$params) {
        global $mysqli_link;
        $stmt = mysqli_prepare($mysqli_link, $query);
        if (!$stmt) return false;
        
        if ($types && $params) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
        }
        
        mysqli_stmt_execute($stmt);
        return mysqli_stmt_get_result($stmt);
    }
}
?>
