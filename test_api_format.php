<?php
// Test the update_marks API with proper data format
error_reporting(0);
ini_set('display_errors', 0);

// Simulate the API call
$_SERVER['REQUEST_METHOD'] = 'POST';
$_SESSION['username'] = 'test_user';

// Test data in the correct format
$testData = [
    'studentId' => 'TEST_UPDATE_001',
    'mathematics' => 85,
    'chemistry' => 90,
    'biology' => 88,
    'physics' => 92
];

$jsonData = json_encode($testData);
echo "Test JSON data: $jsonData\n";

// Write to php://input (simulating the AJAX request)
$input = json_decode($jsonData, true);
echo "Decoded data: " . json_encode($input) . "\n";
echo "StudentId: " . ($input['studentId'] ?? 'MISSING') . "\n";
echo "Has studentId: " . (isset($input['studentId']) ? 'YES' : 'NO') . "\n";
?>
