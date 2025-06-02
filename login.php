<?php
// Replace these with your own database credentials
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
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to check if the username and password match
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    // Check if user exists
    if ($result->num_rows > 0) {
        // Successful login
        header("Location: main.html"); // Redirect to dashboard page
        exit();
    } else {
        // Incorrect login
        echo "<p style='color:red;'>Invalid username or password.</p>";
    }
}

$conn->close();
