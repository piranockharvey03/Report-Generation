<?php
// Direct test of update_marks logic
error_reporting(0);
ini_set('display_errors', 0);
ob_clean();
header('Content-Type: application/json');

// Simulate session
$_SESSION['username'] = 'test';

// Test database connection
$conn = new mysqli("localhost", "root", "", "reports");
if ($conn->connect_error) {
    echo json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]);
    exit();
}

// Check if student_marks table exists
$result = $conn->query("SHOW TABLES LIKE 'student_marks'");
if ($result->num_rows == 0) {
    echo json_encode(['error' => 'student_marks table not found']);
    exit();
}

// Check if test student exists, if not create one
$testId = 'TEST_UPDATE_001';
$checkStudent = $conn->query("SELECT id FROM student_marks WHERE id = '$testId'");
if ($checkStudent->num_rows == 0) {
    // Create a test student
    $insertSql = "INSERT INTO student_marks (
        id, studentName, classTeacherName, term, stage,
        mathematics, chemistry, biology, physics, geography, history,
        business, economics, ict, globalP, literature, french, mutoon, qoran,
        total, average, division, grade, gpa, percentile, passed_subjects, failed_subjects,
        recommendations, created_at
    ) VALUES (
        '$testId', 'Test Student', 'Test Teacher', 'Term 1', 'Form 1',
        50, 60, 70, 80, 55, 65, 75, 85, 45, 55, 65, 75, 85, 95,
        75.5, 'Second', 'B', 3.2, 65.0, 8, 6, '[]', NOW()
    )";

    if (!$conn->query($insertSql)) {
        echo json_encode(['error' => 'Failed to create test student: ' . $conn->error]);
        exit();
    }
}

echo json_encode([
    'success' => true,
    'message' => 'Test setup complete',
    'test_student_id' => $testId,
    'instructions' => 'Use this student ID to test the update functionality'
]);

$conn->close();
?>
