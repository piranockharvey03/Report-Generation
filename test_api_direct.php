<?php
// Direct API test - no session, no output buffering issues
error_reporting(0);
ini_set('display_errors', 0);

// Test search_students.php
echo "=== Testing search_students.php ===\n";
$content = file_get_contents('http://localhost/Websites/reports/php/search_students.php?type=name&query=test');
echo "Response length: " . strlen($content) . "\n";
echo "Response starts with: " . substr($content, 0, 100) . "\n";
echo "Is valid JSON: " . (json_decode($content) ? 'YES' : 'NO') . "\n\n";

// Test get_student_details.php
echo "=== Testing get_student_details.php ===\n";
$content = file_get_contents('http://localhost/Websites/reports/php/get_student_details.php?id=TEST001');
echo "Response length: " . strlen($content) . "\n";
echo "Response starts with: " . substr($content, 0, 100) . "\n";
echo "Is valid JSON: " . (json_decode($content) ? 'YES' : 'NO') . "\n\n";

// Test update_marks.php (POST)
echo "=== Testing update_marks.php ===\n";
$data = json_encode(['studentId' => 'TEST001', 'mathematics' => 80]);
$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => 'Content-Type: application/json',
        'content' => $data
    ]
]);
$content = file_get_contents('http://localhost/Websites/reports/php/update_marks.php', false, $context);
echo "Response length: " . strlen($content) . "\n";
echo "Response starts with: " . substr($content, 0, 100) . "\n";
echo "Is valid JSON: " . (json_decode($content) ? 'YES' : 'NO') . "\n\n";

// Check for BOM or hidden characters
echo "=== Checking for hidden characters ===\n";
$content = file_get_contents('http://localhost/Websites/reports/php/search_students.php?type=name&query=test');
$firstChar = ord(substr($content, 0, 1));
echo "First character code: $firstChar\n";
echo "First 10 characters: ";
for ($i = 0; $i < 10; $i++) {
    echo ord(substr($content, $i, 1)) . " ";
}
echo "\n";
?>
