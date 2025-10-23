<?php
// Test the actual update_marks API with minimal data
error_reporting(0);
ini_set('display_errors', 0);

// Capture any output that might corrupt JSON
ob_start();

header('Content-Type: application/json');

// Simulate the API call
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST = [];
$_SESSION = ['username' => 'test'];

// Test data
$testData = '{"studentId":"TEST001","mathematics":80,"chemistry":75}';

// Write to input stream
$input = json_decode($testData, true);

// Include the actual API file
require_once 'update_marks.php';

// Get any buffered output
$buffered = ob_get_clean();

if (!empty($buffered)) {
    echo "BUFFERED OUTPUT (this is corrupting JSON):\n";
    echo $buffered . "\n";
    echo "=== END BUFFERED ===\n";
}

echo "JSON Response would be corrupted by the above output\n";
?>
