<?php
// Debug API endpoint - can be called directly from browser
error_reporting(0);
ini_set('display_errors', 0);
ob_clean();
header('Content-Type: application/json');

echo json_encode([
    'success' => true,
    'message' => 'Debug API is working!',
    'timestamp' => date('Y-m-d H:i:s'),
    'server_info' => [
        'php_version' => PHP_VERSION,
        'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
        'request_method' => $_SERVER['REQUEST_METHOD'],
        'content_type' => $_SERVER['CONTENT_TYPE'] ?? 'Not set'
    ],
    'test_json' => 'This is valid JSON - no corruption here!'
]);
?>
