<?php
// get_student_details.php - API endpoint for getting student details and marks
session_start();

// Suppress all PHP errors and warnings to prevent JSON corruption
error_reporting(0);
ini_set('display_errors', 0);

// Add cache control headers to prevent browser caching
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

// Set content type to JSON BEFORE any output
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reports";

// Prevent any output before JSON
ob_clean();
ob_start();

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit();
}

try {
    // Get student ID from query parameter
    $studentId = trim($_GET['id'] ?? '');

    if (empty($studentId)) {
        http_response_code(400);
        echo json_encode(['error' => 'Student ID is required']);
        exit();
    }

    // Get student details and marks
    $sql = "SELECT * FROM student_marks WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $studentId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Student not found']);
        exit();
    }

    $student = $result->fetch_assoc();
    $stmt->close();

    // Structure the response data
    $studentData = [
        'id' => $student['id'],
        'name' => $student['studentName'],
        'teacher' => $student['classTeacherName'],
        'class' => $student['stage'],
        'term' => $student['term'],
        'year' => date('Y', strtotime($student['created_at'])),
        'marks' => [
            'mathematics' => $student['mathematics'],
            'chemistry' => $student['chemistry'],
            'biology' => $student['biology'],
            'physics' => $student['physics'],
            'geography' => $student['geography'],
            'history' => $student['history'],
            'business' => $student['business'],
            'economics' => $student['economics'],
            'ict' => $student['ict'],
            'globalP' => $student['globalP'],
            'literature' => $student['literature'],
            'french' => $student['french'],
            'mutoon' => $student['mutoon'],
            'qoran' => $student['qoran']
        ],
        'results' => [
            'total' => $student['total'],
            'average' => $student['average'],
            'division' => $student['division'],
            'grade' => $student['grade'],
            'gpa' => $student['gpa'],
            'percentile' => $student['percentile'],
            'passed_subjects' => $student['passed_subjects'],
            'failed_subjects' => $student['failed_subjects']
        ]
    ];

    echo json_encode([
        'success' => true,
        'student' => $studentData
    ]);

} catch (Exception $e) {
    error_log("Get student details error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Failed to get student details']);
} finally {
    $conn->close();
}
?>
