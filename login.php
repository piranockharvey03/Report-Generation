<?php
// Secure database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reports";

// Create a secure connection using MySQLi
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . htmlspecialchars($conn->connect_error));
}

// Start a secure session
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize user input
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    // Fetch result
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Verify password using password hashing
        if (password_verify($password, $row['password'])) {
            // Successful login
            $_SESSION['username'] = $username; // optional: set session for user
            header("Location: main.html"); // Redirect to dashboard
            exit();
        } else {
            echo "<p style='color:red;'>Invalid username or password.</p>";
        }
    } else {
        echo "<p style='color:red;'>Invalid username or password.</p>";
    }

    $stmt->close();
}

$conn->close();
