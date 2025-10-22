<?php
// Simple Dashboard Stats API - Fixed Version
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

    try {
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

        // Grade distribution
        if ($stats['total_students'] > 0) {
            $result = $conn->query("SELECT
                SUM(CASE WHEN average >= 80 THEN 1 ELSE 0 END) as grade_a,
                SUM(CASE WHEN average >= 60 AND average < 80 THEN 1 ELSE 0 END) as grade_b,
                SUM(CASE WHEN average >= 40 AND average < 60 THEN 1 ELSE 0 END) as grade_c,
                SUM(CASE WHEN average < 40 THEN 1 ELSE 0 END) as grade_f
                FROM student_marks WHERE average > 0");
            if ($result) {
                $row = $result->fetch_assoc();
                if ($row) {
                    $stats['grade_distribution'] = array(
                        (int)$row['grade_a'],
                        (int)$row['grade_b'],
                        (int)$row['grade_c'],
                        (int)$row['grade_f']
                    );
                }
            }

            // Performance data for chart (last 4 weeks)
            $sql = "SELECT DATE_FORMAT(created_at, '%Y-%m-%d') as date, AVG(average) as avg_score
                    FROM student_marks
                    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 4 WEEK)
                    AND average IS NOT NULL AND average > 0
                    GROUP BY DATE(created_at)
                    ORDER BY date";
            $result = $conn->query($sql);
            $performance_data = [];
            $performance_labels = [];
            if ($result) {
                while($row = $result->fetch_assoc()) {
                    $performance_labels[] = date('M j', strtotime($row['date']));
                    $performance_data[] = round($row['avg_score'], 1);
                }
            }
            $stats['performance_data'] = $performance_data;
        }

        echo json_encode($stats);

    } finally {
        $conn->close();
    }
