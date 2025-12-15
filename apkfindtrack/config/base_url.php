<?php
// Base URL Configuration
// Otomatis detect base URL dari document root
if (!defined('BASE_URL')) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    
    // Get document root and current file path
    $doc_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
    $script_file = str_replace('\\', '/', $_SERVER['SCRIPT_FILENAME']);
    
    // Calculate base path - get directory of the file that called this
    $base_path = str_replace($doc_root, '', dirname($script_file));
    $base_path = str_replace('\\', '/', $base_path);
    
    // If we're in a subdirectory (user/ or admin/), go up one level
    if (strpos($base_path, '/user/') !== false || strpos($base_path, '/admin/') !== false) {
        $base_path = dirname($base_path);
    }
    
    // If we're in layout/, go up one level
    if (strpos($base_path, '/layout/') !== false) {
        $base_path = dirname($base_path);
    }
    
    // Ensure base_path starts with /
    if (substr($base_path, 0, 1) !== '/') {
        $base_path = '/' . $base_path;
    }
    
    // Remove trailing slash
    $base_path = rtrim($base_path, '/');
    
    $base_url = $protocol . '://' . $host . $base_path;
    
    // Define BASE_URL constant
    define('BASE_URL', $base_url);
    define('BASE_PATH', $base_path);
}
?>

