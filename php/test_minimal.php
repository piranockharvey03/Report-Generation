<?php
// Minimal JSON test - absolutely no output before JSON
error_reporting(0);
ini_set('display_errors', 0);

// Set headers first, before any output
header('Content-Type: application/json');

// Test if headers are working
if (isset($_GET['test'])) {
    echo json_encode(['success' => true, 'message' => 'Headers work']);
    exit();
}

// Test database connection
$conn = new mysqli("localhost", "root", "", "reports");
if ($conn->connect_error) {
    echo json_encode(['error' => 'Database connection failed']);
    exit();
}

// Test if student_marks table exists
$result = $conn->query("SHOW TABLES LIKE 'student_marks'");
if ($result->num_rows == 0) {
    echo json_encode(['error' => 'student_marks table not found']);
    exit();
}

echo json_encode(['success' => true, 'message' => 'Database connection works']);
$conn->close();
?>
