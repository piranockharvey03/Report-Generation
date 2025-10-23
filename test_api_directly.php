<?php
// Test the update_marks API directly
require_once 'update_marks.php';

// Simulate POST data
$_SERVER['REQUEST_METHOD'] = 'POST';
$_SESSION['username'] = 'test_user';

// Test data
$testData = [
    'studentId' => 'TEST001',
    'mathematics' => 80,
    'chemistry' => 75,
    'biology' => 90,
    'physics' => 85,
    'geography' => 70,
    'history' => 65,
    'business' => 88,
    'economics' => 72,
    'ict' => 95,
    'globalP' => 78,
    'literature' => 82,
    'french' => 76,
    'mutoon' => 85,
    'qoran' => 90
];

// Write test data to php://input
$input = file_get_contents('php://input');
file_put_contents('php://input', json_encode($testData));

// Test the API
echo "Testing API with data: " . json_encode($testData) . "\n";
?>
