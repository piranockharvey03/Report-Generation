<?php
session_start();
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reports";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]));
}

// Ensure database and table exist
setupDatabaseIfNeeded($conn);

// Get dashboard statistics
function getDashboardStats($conn) {
    $stats = [];

    try {
        // Total students
        $sql = "SELECT COUNT(DISTINCT studentName) as total_students FROM student_marks";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $stats['total_students'] = (int)($row['total_students'] ?? 0);

        // Average score
        $sql = "SELECT AVG(average) as avg_score FROM student_marks WHERE average IS NOT NULL AND average > 0";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $stats['average_score'] = (float)($row['avg_score'] ?? 0);

        // Pass rate (students with average >= 40)
        $sql = "SELECT
                    COUNT(CASE WHEN average >= 40 THEN 1 END) as passed,
                    COUNT(*) as total
                FROM student_marks
                WHERE average IS NOT NULL AND average > 0";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $total = (int)($row['total'] ?? 0);
        $passed = (int)($row['passed'] ?? 0);
        $stats['pass_rate'] = $total > 0 ? round(($passed / $total) * 100, 1) : 0;

        // Current term (most recent)
        $sql = "SELECT term FROM student_marks ORDER BY created_at DESC LIMIT 1";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $stats['current_term'] = $row['term'] ?? 'No data';

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
        while($row = $result->fetch_assoc()) {
            $performance_labels[] = date('M j', strtotime($row['date']));
            $performance_data[] = round($row['avg_score'], 1);
        }

        // If no recent data, provide empty arrays
        $stats['performance_labels'] = $performance_labels;
        $stats['performance_data'] = $performance_data;

        // Grade distribution
        $sql = "SELECT
                    COUNT(CASE WHEN average >= 80 THEN 1 END) as grade_a,
                    COUNT(CASE WHEN average >= 60 AND average < 80 THEN 1 END) as grade_b,
                    COUNT(CASE WHEN average >= 40 AND average < 60 THEN 1 END) as grade_c,
                    COUNT(CASE WHEN average < 40 THEN 1 END) as grade_f
                FROM student_marks
                WHERE average IS NOT NULL AND average > 0";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $stats['grade_distribution'] = [
            (int)($row['grade_a'] ?? 0),
            (int)($row['grade_b'] ?? 0),
            (int)($row['grade_c'] ?? 0),
            (int)($row['grade_f'] ?? 0)
        ];

        // Recent activity
        $sql = "SELECT studentName, average, grade, created_at
                FROM student_marks
                ORDER BY created_at DESC
                LIMIT 5";
        $result = $conn->query($sql);
        $recent_activity = [];
        while($row = $result->fetch_assoc()) {
            $recent_activity[] = [
                'student' => $row['studentName'],
                'score' => round($row['average'], 1),
                'grade' => $row['grade'],
                'date' => date('M j, Y', strtotime($row['created_at']))
            ];
        }
        $stats['recent_activity'] = $recent_activity;

        return $stats;

    } catch (Exception $e) {
        http_response_code(500);
        return ['error' => 'Failed to fetch dashboard stats: ' . $e->getMessage()];
    }
}

function setupDatabaseIfNeeded($conn) {
    // Check if database exists
    $result = $conn->query("SHOW DATABASES LIKE 'reports'");
    if ($result->num_rows == 0) {
        // Create database
        $conn->query("CREATE DATABASE reports");
        $conn->select_db("reports");
    } else {
        $conn->select_db("reports");
    }

    // Check if table exists
    $result = $conn->query("SHOW TABLES LIKE 'student_marks'");
    if ($result->num_rows == 0) {
        // Create table
        $sql = "CREATE TABLE student_marks (
            id VARCHAR(20) PRIMARY KEY,
            studentName VARCHAR(100) NOT NULL,
            classTeacherName VARCHAR(100) NOT NULL,
            term VARCHAR(20) NOT NULL,
            stage VARCHAR(20) NOT NULL,
            mathematics INT NULL,
            chemistry INT NULL,
            biology INT NULL,
            physics INT NULL,
            geography INT NULL,
            history INT NULL,
            business INT NULL,
            economics INT NULL,
            ict INT NULL,
            globalP INT NULL,
            literature INT NULL,
            french INT NULL,
            mutoon INT NULL,
            qoran INT NULL,
            total INT NOT NULL,
            average DECIMAL(5,2) NOT NULL,
            division VARCHAR(10) NOT NULL,
            grade VARCHAR(5) NOT NULL,
            gpa DECIMAL(3,2) NOT NULL,
            percentile DECIMAL(5,2) NOT NULL,
            passed_subjects INT NOT NULL,
            failed_subjects INT NOT NULL,
            recommendations TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_student_name (studentName),
            INDEX idx_term (term),
            INDEX idx_created_at (created_at)
        )";

        $conn->query($sql);

        // Insert sample data
        insertSampleData($conn);
    }
}

function insertSampleData($conn) {
    $sampleData = [
        ['John Doe', 'Mr. Smith', 'Term 1', 'Form 1', 85, 78, 92, 88, 76, 80, 85, 82, 90, 75, 88, 85, 82, 90],
        ['Jane Smith', 'Mr. Smith', 'Term 1', 'Form 1', 92, 88, 95, 90, 85, 87, 90, 88, 92, 85, 90, 88, 85, 92],
        ['Michael Johnson', 'Ms. Davis', 'Term 1', 'Form 2', 75, 70, 80, 78, 72, 75, 78, 76, 80, 70, 75, 78, 76, 80],
        ['Sarah Wilson', 'Ms. Davis', 'Term 1', 'Form 2', 88, 85, 90, 87, 82, 85, 87, 84, 88, 80, 85, 87, 84, 88],
        ['David Brown', 'Mr. Smith', 'Term 1', 'Form 1', 65, 60, 70, 68, 62, 65, 68, 64, 70, 60, 65, 68, 64, 70]
    ];

    foreach ($sampleData as $data) {
        $studentName = $data[0];
        $classTeacherName = $data[1];
        $term = $data[2];
        $stage = $data[3];

        // Calculate marks (skip first 4 elements which are student info)
        $marks = array_slice($data, 4);
        $total = array_sum($marks);
        $average = $total / count($marks);

        // Simple grade calculation
        if ($average >= 80) {
            $grade = 'A';
            $division = 'I';
            $gpa = 4.0;
        } elseif ($average >= 60) {
            $grade = 'B';
            $division = 'II';
            $gpa = 3.0;
        } elseif ($average >= 40) {
            $grade = 'C';
            $division = 'III';
            $gpa = 2.0;
        } else {
            $grade = 'F';
            $division = 'IV';
            $gpa = 0.0;
        }

        $percentile = min(100, max(0, $average));
        $passed_subjects = count(array_filter($marks, function($mark) { return $mark >= 40; }));
        $failed_subjects = count($marks) - $passed_subjects;

        // Generate student ID
        $studentId = generateStudentId($studentName, $conn);

        $insertSql = "INSERT INTO student_marks (
            id, studentName, classTeacherName, term, stage,
            mathematics, chemistry, biology, physics, geography, history,
            business, economics, ict, globalP, literature, french, mutoon, qoran,
            total, average, division, grade, gpa, percentile, passed_subjects, failed_subjects,
            recommendations, created_at
        ) VALUES (
            '$studentId', '$studentName', '$classTeacherName', '$term', '$stage',
            " . implode(',', $marks) . ",
            $total, $average, '$division', '$grade', $gpa, $percentile, $passed_subjects, $failed_subjects,
            'Sample recommendations', NOW()
        )";

        $conn->query($insertSql);
    }
}

function generateStudentId($studentName, $conn) {
    $prefix = strtoupper(substr($studentName, 0, 3));
    $timestamp = time();
    $random = rand(100, 999);
    return $prefix . $timestamp . $random;
}

echo json_encode(getDashboardStats($conn));

$conn->close();
?>
