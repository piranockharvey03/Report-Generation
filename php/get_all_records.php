<?php
// Get all student records
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

    // Check if database and table exist
    $dbExists = $conn->select_db($dbname);
    if (!$dbExists) {
        $conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
        $conn->select_db($dbname);
    }

    // Check if table exists
    $tableExists = $conn->query("SHOW TABLES LIKE 'student_marks'");
    if ($tableExists->num_rows == 0) {
        echo json_encode([
            'error' => 'No records found - database table does not exist',
            'records' => []
        ]);
        exit;
    }

    // Get all records ordered by creation date (newest first)
    $result = $conn->query("SELECT id, studentName, term, total, average, grade, division, created_at FROM student_marks ORDER BY created_at DESC");

    $records = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $records[] = [
                'id' => $row['id'],
                'studentName' => $row['studentName'],
                'term' => $row['term'],
                'total' => (int)$row['total'],
                'average' => (float)$row['average'],
                'grade' => $row['grade'],
                'division' => $row['division'],
                'created_at' => $row['created_at']
            ];
        }
    }

    echo json_encode([
        'success' => true,
        'records' => $records,
        'total' => count($records)
    ]);

    $conn->close();

} catch (Exception $e) {
    echo json_encode([
        'error' => $e->getMessage(),
        'records' => []
    ]);
}
?>
