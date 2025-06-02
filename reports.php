<?php

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reports";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $studentName = $_POST['studentName'];
    $classTeacherName = $_POST['classTeacherName'];
    $term = $_POST['term'];
    $stage = $_POST['stage'];
    $subjects = ['mathematics', 'chemistry', 'biology', 'physics', 'geography', 'history', 'business', 'economics', 'ict', 'globalP', 'literature', 'french', 'mutoon', 'qoran'];

    $sql = "INSERT INTO students(student_name, class_teacher, term, stage) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $studentName, $classTeacherName, $term, $stage);

    if ($stmt->execute()) {
        $student_id = $stmt->insert_id;

        foreach ($subjects as $subject) {
            if (isset($_POST[$subject])) {
                $marks = $_POST[$subject];
                $sql_marks = "INSERT INTO student_marks(student_id, subject, marks) VALUES (?, ?, ?)";
                $stmt_marks = $conn->prepare($sql_marks);
                $stmt_marks->bind_param("isi", $student_id, $subject, $marks);
                $stmt_marks->execute();
            }
        }

        echo "Marks added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
}

$conn->close();
