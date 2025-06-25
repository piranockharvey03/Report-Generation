<?php

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

    // Calculate total and average
    $marks = [
        $mathematics,
        $chemistry,
        $biology,
        $physics,
        $geography,
        $history,
        $business,
        $economics,
        $ict,
        $globalP,
        $literature,
        $french,
        $mutoon,
        $qoran
    ];

    $total = array_sum($marks);
    $average = $total / count($marks);

    // Determine division
    function calculateDivision($average)
    {
        if ($average >= 80) return "Division I";
        elseif ($average >= 60) return "Division II";
        elseif ($average >= 40) return "Division III";
        else return "Fail";
    }

    $division = calculateDivision($average);

    // Insert all data including total, average, and division
    $sql = "INSERT INTO student_marks (
        studentName, classTeacherName, term, stage,
        mathematics, chemistry, biology, physics, geography, history,
        business, economics, ict, globalP, literature, french, mutoon, qoran,
        total, average, division
    ) VALUES (
        '$studentName', '$classTeacherName', '$term', '$stage',
        $mathematics, $chemistry, $biology, $physics, $geography, $history,
        $business, $economics, $ict, $globalP, $literature, $french, $mutoon, $qoran,
        $total, $average, '$division'
    )";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../html/main.html");
        exit();
    } else {
        echo "Error inserting record: " . $conn->error;
    }
}

$conn->close();
