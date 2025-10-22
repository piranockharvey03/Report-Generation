<?php
// Test the dashboard API directly
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reports";

header('Content-Type: application/json');

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
        // Create table
        $createTable = "CREATE TABLE student_marks (
            id VARCHAR(20) PRIMARY KEY,
            studentName VARCHAR(100) NOT NULL,
            classTeacherName VARCHAR(100) NOT NULL,
            term VARCHAR(20) NOT NULL,
            stage VARCHAR(20) NOT NULL,
            mathematics INT NULL, chemistry INT NULL, biology INT NULL, physics INT NULL,
            geography INT NULL, history INT NULL, business INT NULL, economics INT NULL,
            ict INT NULL, globalP INT NULL, literature INT NULL, french INT NULL,
            mutoon INT NULL, qoran INT NULL, total INT NOT NULL, average DECIMAL(5,2) NOT NULL,
            division VARCHAR(10) NOT NULL, grade VARCHAR(5) NOT NULL, gpa DECIMAL(3,2) NOT NULL,
            percentile DECIMAL(5,2) NOT NULL, passed_subjects INT NOT NULL, failed_subjects INT NOT NULL,
            recommendations TEXT, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

        if (!$conn->query($createTable)) {
            throw new Exception("Failed to create table: " . $conn->error);
        }
    }

    // Get basic stats
    $stats = array(
        'total_students' => 0,
        'average_score' => 0,
        'pass_rate' => 0,
        'current_term' => 'No data',
        'performance_labels' => array('No Data'),
        'performance_data' => array(0),
        'grade_distribution' => array(0, 0, 0, 0),
        'recent_activity' => array()
    );

    // Count students
    $result = $conn->query("SELECT COUNT(*) as count FROM student_marks");
    if ($result) {
        $row = $result->fetch_assoc();
        if ($row) {
            $stats['total_students'] = (int)$row['count'];
        }
    }

    // Average score
    $result = $conn->query("SELECT AVG(average) as avg FROM student_marks WHERE average > 0");
    if ($result) {
        $row = $result->fetch_assoc();
        if ($row && $row['avg'] !== null) {
            $stats['average_score'] = (float)$row['avg'];
        }
    }

    // Pass rate
    if ($stats['total_students'] > 0) {
        $result = $conn->query("SELECT COUNT(*) as passed FROM student_marks WHERE average >= 40");
        if ($result) {
            $row = $result->fetch_assoc();
            if ($row) {
                $passed = (int)$row['passed'];
                $stats['pass_rate'] = round(($passed / $stats['total_students']) * 100, 1);
            }
        }
    }

    // Current term
    $result = $conn->query("SELECT term FROM student_marks ORDER BY created_at DESC LIMIT 1");
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row) {
            $stats['current_term'] = $row['term'];
        }
    }

    echo json_encode($stats);

    $conn->close();

} catch (Exception $e) {
    echo json_encode([
        'error' => $e->getMessage(),
        'total_students' => 0,
        'average_score' => 0,
        'pass_rate' => 0,
        'current_term' => 'Error',
        'performance_labels' => array('Error'),
        'performance_data' => array(0),
        'grade_distribution' => array(0, 0, 0, 0),
        'recent_activity' => array()
    ]);
}
?>
