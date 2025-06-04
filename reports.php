<?php
// Database connection settings
$host = "localhost";
$dbname = "reports";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Prepare and bind
    $stmt = $conn->prepare("
        INSERT INTO student_marks (
            studentName, classTeacherName, term, stage,
            mathematics, chemistry, biology, physics, geography, history,
            business, economics, ict, globalP, literature, french, mutoon, qoran
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "ssssiiiiiiiiiiiiii",
        $_POST['studentName'],
        $_POST['classTeacherName'],
        $_POST['term'],
        $_POST['stage'],
        $_POST['mathematics'],
        $_POST['chemistry'],
        $_POST['biology'],
        $_POST['physics'],
        $_POST['geography'],
        $_POST['history'],
        $_POST['business'],
        $_POST['economics'],
        $_POST['ict'],
        $_POST['globalP'],
        $_POST['literature'],
        $_POST['french'],
        $_POST['mutoon'],
        $_POST['qoran']
    );

    $stmt->execute();
    $stmt->close();
}

$conn->close();
