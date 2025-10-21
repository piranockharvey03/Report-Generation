<?php
session_start();
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reports";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed']));
}

$stats = [];

// Total records
$sql = "SELECT COUNT(*) as total_records FROM student_marks";
$result = $conn->query($sql);
$stats['total_records'] = $result->fetch_assoc()['total_records'] ?? 0;

// Active teachers (users who logged in within last 30 days)
$sql = "SELECT COUNT(*) as active_teachers FROM users WHERE last_login >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
$result = $conn->query($sql);
$stats['active_teachers'] = $result->fetch_assoc()['active_teachers'] ?? 0;

// Last backup (this would be stored in a system table)
$stats['last_backup'] = 'Never'; // Placeholder

// System health
$sql = "SELECT COUNT(*) as issues FROM student_marks WHERE average IS NULL OR total IS NULL";
$result = $conn->query($sql);
$issues = $result->fetch_assoc()['issues'] ?? 0;
$stats['system_health'] = $issues == 0 ? 'Excellent' : 'Good';

echo json_encode($stats);

$conn->close();
?>
