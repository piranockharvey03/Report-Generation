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
    die(json_encode(['error' => 'Database connection failed']));
}

// Get dashboard statistics
function getDashboardStats($conn) {
    $stats = [];

    // Total students
    $sql = "SELECT COUNT(DISTINCT studentName) as total_students FROM student_marks";
    $result = $conn->query($sql);
    $stats['total_students'] = $result->fetch_assoc()['total_students'] ?? 0;

    // Average score
    $sql = "SELECT AVG(average) as avg_score FROM student_marks WHERE average IS NOT NULL";
    $result = $conn->query($sql);
    $stats['average_score'] = $result->fetch_assoc()['avg_score'] ?? 0;

    // Pass rate (students with average >= 40)
    $sql = "SELECT
                COUNT(CASE WHEN average >= 40 THEN 1 END) as passed,
                COUNT(*) as total
            FROM student_marks
            WHERE average IS NOT NULL";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $stats['pass_rate'] = $row['total'] > 0 ? ($row['passed'] / $row['total']) * 100 : 0;

    // Current term (most recent)
    $sql = "SELECT term FROM student_marks ORDER BY created_at DESC LIMIT 1";
    $result = $conn->query($sql);
    $stats['current_term'] = $result->fetch_assoc()['term'] ?? 'N/A';

    // Performance data for chart (last 4 weeks)
    $sql = "SELECT DATE_FORMAT(created_at, '%Y-%m-%d') as date, AVG(average) as avg_score
            FROM student_marks
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 4 WEEK)
            GROUP BY DATE(created_at)
            ORDER BY date";
    $result = $conn->query($sql);
    $performance_data = [];
    $performance_labels = [];
    while($row = $result->fetch_assoc()) {
        $performance_labels[] = date('M j', strtotime($row['date']));
        $performance_data[] = round($row['avg_score'], 1);
    }
    $stats['performance_labels'] = $performance_labels;
    $stats['performance_data'] = $performance_data;

    // Grade distribution
    $sql = "SELECT
                COUNT(CASE WHEN average >= 80 THEN 1 END) as grade_a,
                COUNT(CASE WHEN average >= 60 AND average < 80 THEN 1 END) as grade_b,
                COUNT(CASE WHEN average >= 40 AND average < 60 THEN 1 END) as grade_c,
                COUNT(CASE WHEN average < 40 THEN 1 END) as grade_f
            FROM student_marks
            WHERE average IS NOT NULL";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $stats['grade_distribution'] = [
        $row['grade_a'] ?? 0,
        $row['grade_b'] ?? 0,
        $row['grade_c'] ?? 0,
        $row['grade_f'] ?? 0
    ];

    return $stats;
}

echo json_encode(getDashboardStats($conn));

$conn->close();
?>
