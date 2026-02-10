<?php
/**
 * PHP MySQLi Shim
 * Provides backward compatibility for legacy mysql_* functions using the mysqli extension.
 */

if (!function_exists('mysql_connect')) {
    global $mysqli_link;
    $mysqli_link = null;

    function mysql_connect($host, $user, $password, $new_link = false, $client_flags = 0) {
        global $mysqli_link;
        $mysqli_link = mysqli_connect($host, $user, $password);
        return $mysqli_link;
    }

    function mysql_pconnect($host, $user, $password, $client_flags = 0) {
        global $mysqli_link;
        // mysqli doesn't have a direct equivalent for pconnect in the same way, 
        // but adding p: to host enables persistent connections.
        $mysqli_link = mysqli_connect('p:' . $host, $user, $password);
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
