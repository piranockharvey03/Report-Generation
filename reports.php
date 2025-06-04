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
    // Collect POST data directly
    $studentName = $_POST['studentName'];
    $classTeacherName = $_POST['classTeacherName'];
    $term = $_POST['term'];
    $stage = $_POST['stage'];
    $mathematics = $_POST['mathematics'];
    $chemistry = $_POST['chemistry'];
    $biology = $_POST['biology'];
    $physics = $_POST['physics'];
    $geography = $_POST['geography'];
    $history = $_POST['history'];
    $business = $_POST['business'];
    $economics = $_POST['economics'];
    $ict = $_POST['ict'];
    $globalP = $_POST['globalP'];
    $literature = $_POST['literature'];
    $french = $_POST['french'];
    $mutoon = $_POST['mutoon'];
    $qoran = $_POST['qoran'];

    // Create SQL query (make sure your table and column names are correct)
    $sql = "INSERT INTO student_marks (
        studentName, classTeacherName, term, stage,
        mathematics, chemistry, biology, physics, geography, history,
        business, economics, ict, globalP, literature, french, mutoon, qoran
    ) VALUES (
        '$studentName', '$classTeacherName', '$term', '$stage',
        $mathematics, $chemistry, $biology, $physics, $geography, $history,
        $business, $economics, $ict, $globalP, $literature, $french, $mutoon, $qoran
    )";

    if ($conn->query($sql) === TRUE) {
        echo "Record inserted successfully";
    } else {
        echo "Error inserting record: " . $conn->error;
    }
}

$conn->close();
