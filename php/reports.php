<?php

require_once 'grade_calculator.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reports";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Collect POST data
    $studentName = trim($_POST['studentName']);
    $classTeacherName = $_POST['classTeacherName'];
    $term = $_POST['term'];
    $stage = $_POST['stage'];
    
    // Collect all subject marks
    $marks = [
        (int)($_POST['mathematics'] ?? 0),
        (int)($_POST['chemistry'] ?? 0),
        (int)($_POST['biology'] ?? 0),
        (int)($_POST['physics'] ?? 0),
        (int)($_POST['geography'] ?? 0),
        (int)($_POST['history'] ?? 0),
        (int)($_POST['business'] ?? 0),
        (int)($_POST['economics'] ?? 0),
        (int)($_POST['ict'] ?? 0),
        (int)($_POST['globalP'] ?? 0),
        (int)($_POST['literature'] ?? 0),
        (int)($_POST['french'] ?? 0),
        (int)($_POST['mutoon'] ?? 0),
        (int)($_POST['qoran'] ?? 0)
    ];

    try {
        $calculator = new GradeCalculator();
        $results = $calculator->calculateResults($marks);
        
        // Generate unique student ID
        $studentId = generateStudentId($studentName, $conn);
        
        // Insert all data into database
        $sql = "INSERT INTO student_marks (
            id, studentName, classTeacherName, term, stage,
            mathematics, chemistry, biology, physics, geography, history,
            business, economics, ict, globalP, literature, french, mutoon, qoran,
            total, average, division, grade, gpa, percentile, passed_subjects, failed_subjects,
            recommendations, created_at
        ) VALUES (
            ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?, ?, ?, ?,
            ?, NOW()
        )";
        
        $stmt = $conn->prepare($sql);
        $recommendationsJson = json_encode($results['recommendations']);
        
        $stmt->bind_param(
            "issssiissssisssiiisddddiiss",
            $studentId, $studentName, $classTeacherName, $term, $stage,
            $marks[0], $marks[1], $marks[2], $marks[3], $marks[4], $marks[5],
            $marks[6], $marks[7], $marks[8], $marks[9], $marks[10], $marks[11], $marks[12], $marks[13],
            $results['total_marks'], $results['average'], $results['division'], $results['grade'], $results['gpa'], $results['percentile'],
            $results['passed_subjects'], $results['failed_subjects'], $recommendationsJson
        );
        
        if ($stmt->execute()) {
            // Store results in session for confirmation page
            $_SESSION['last_inserted'] = [
                'student_name' => $studentName,
                'student_id' => $studentId,
                'results' => $results
            ];
            
            header("Location: ../html/confirmation.php");
            exit();
        } else {
            echo "Error inserting record: " . $conn->error;
        }
        
        $stmt->close();
        
    } catch (Exception $e) {
        echo "Error calculating grades: " . $e->getMessage();
    }
}

$conn->close();

function generateStudentId($studentName, $conn) {
    // Generate a unique student ID based on name and timestamp
    $prefix = strtoupper(substr($studentName, 0, 3));
    $timestamp = time();
    $random = rand(100, 999);
    $studentId = $prefix . $timestamp . $random;
    
    // Ensure uniqueness
    $checkSql = "SELECT id FROM student_marks WHERE id = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("s", $studentId);
    $checkStmt->execute();
    
    if ($checkStmt->get_result()->num_rows > 0) {
        // If not unique, add another random number
        $studentId .= rand(10, 99);
    }
    
    $checkStmt->close();
    return $studentId;
}
?>
