<?php
// Test script to verify student ID generation
require_once 'reports.php';

$conn = new mysqli("localhost", "root", "", "reports");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$testNames = ["John Doe", "Jane Smith", "Test Student", "A"];
foreach ($testNames as $name) {
    $id = generateStudentId($name, $conn);
    echo "Student: $name -> ID: $id (Length: " . strlen($id) . ")\n";

    if (strlen($id) > 20) {
        echo "ERROR: ID too long!\n";
    }
}

$conn->close();
?>
