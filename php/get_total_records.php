<?php
// Simple API to get total records count
header('Content-Type: application/json');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reports";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        throw new Exception("Database connection failed: " . $conn->connect_error);
    }

    // Ensure database exists
    $conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
    $conn->select_db($dbname);

    // Ensure table exists
    $tableCheck = $conn->query("SHOW TABLES LIKE 'student_marks'");
    if ($tableCheck->num_rows == 0) {
        echo json_encode([
            'total_students' => 0,
            'message' => 'Database table not found'
        ]);
        exit;
    }

    // Count total records
    $result = $conn->query("SELECT COUNT(*) as count FROM student_marks");
    $totalRecords = 0;

    if ($result) {
        $row = $result->fetch_assoc();
        $totalRecords = (int)$row['count'];
    }

    echo json_encode([
        'total_students' => $totalRecords,
        'success' => true
    ]);

} catch (Exception $e) {
    echo json_encode([
        'total_students' => 0,
        'error' => $e->getMessage(),
        'success' => false
    ]);
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?>
