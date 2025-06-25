<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reports";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $studentName = $_POST['studentName'];
    $studentid = $_POST['studentid'];

    // Query to check if the studentname and studentid match
    $sql = "SELECT * FROM student_marks WHERE studentName = '$studentName' AND id = '$studentid'";
    $result = $conn->query($sql);

    // Check if student exists
    if ($result->num_rows > 0) {
        // Successful login
        header("Location:../html/generatereport.php"); // Redirect to reports page
        exit();
    } else {
        // Incorrect login
        echo "<p style='color:red;'>Invalid username or password.</p>";
    }
}

$conn->close();
