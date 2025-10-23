<?php
// Simple API test
error_reporting(0);
ini_set('display_errors', 0);
ob_clean();
header('Content-Type: application/json');

echo json_encode([
    'test' => 'API is working',
    'timestamp' => time(),
    'no_errors' => 'This should be valid JSON'
]);
?>
