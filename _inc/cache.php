<?php
/**
 * Simple file-based caching system
 */

$cache_dir = __DIR__ . '/../_cache';

// Fallback for production systems (like Coolify/Docker) where the root may be read-only
if (!is_dir($cache_dir)) {
    if (!@mkdir($cache_dir, 0777, true)) {
        $cache_dir = sys_get_temp_dir() . '/naruto_cache';
        if (!is_dir($cache_dir)) {
            @mkdir($cache_dir, 0777, true);
        }
    }
}

/**
 * Sets a value in the cache
 */
function cache_set($key, $value, $expiry = 3600) {
    global $cache_dir;
    $file = $cache_dir . '/' . md5($key) . '.cache';
    $data = [
        'expiry' => time() + $expiry,
        'value' => $value
    ];
    file_put_contents($file, serialize($data));
}

/**
 * Gets a value from the cache
 */
function cache_get($key) {
    global $cache_dir;
    $file = $cache_dir . '/' . md5($key) . '.cache';
    if (!file_exists($file)) return null;
    
    $data = unserialize(file_get_contents($file));
    if (time() > $data['expiry']) {
        @unlink($file);
        return null;
    }
    return $data['value'];
}

/**
 * Deletes a cache entry
 */
function cache_delete($key) {
    global $cache_dir;
    $file = $cache_dir . '/' . md5($key) . '.cache';
    if (file_exists($file)) @unlink($file);
}
