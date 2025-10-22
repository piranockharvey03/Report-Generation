<?php
// Test script to check what's in the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reports";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    echo "🔍 Checking Database Contents\n\n";

    // Check if table exists
    $tableCheck = $conn->query("SHOW TABLES LIKE 'student_marks'");
    if ($tableCheck->num_rows == 0) {
        echo "❌ Table 'student_marks' does not exist\n";
        echo "✅ Creating table...\n";

        $createTable = "CREATE TABLE student_marks (
            id VARCHAR(20) PRIMARY KEY,
            studentName VARCHAR(100) NOT NULL,
            classTeacherName VARCHAR(100) NOT NULL,
            term VARCHAR(20) NOT NULL,
            stage VARCHAR(20) NOT NULL,
            mathematics INT NULL, chemistry INT NULL, biology INT NULL, physics INT NULL,
            geography INT NULL, history INT NULL, business INT NULL, economics INT NULL,
            ict INT NULL, globalP INT NULL, literature INT NULL, french INT NULL,
            mutoon INT NULL, qoran INT NULL, total INT NOT NULL, average DECIMAL(5,2) NOT NULL,
            division VARCHAR(10) NOT NULL, grade VARCHAR(5) NOT NULL, gpa DECIMAL(3,2) NOT NULL,
            percentile DECIMAL(5,2) NOT NULL, passed_subjects INT NOT NULL, failed_subjects INT NOT NULL,
            recommendations TEXT, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";

        if ($conn->query($createTable)) {
            echo "✅ Table created successfully\n";
        } else {
            echo "❌ Failed to create table: " . $conn->error . "\n";
        }
    } else {
        echo "✅ Table 'student_marks' exists\n";
    }

    // Count records
    $result = $conn->query("SELECT COUNT(*) as count FROM student_marks");
    if ($result) {
        $row = $result->fetch_assoc();
        echo "\n📊 Database Statistics:\n";
        echo "- Total student records: " . $row['count'] . "\n";

        if ($row['count'] > 0) {
            echo "\n📋 Student Records:\n";

            $students = $conn->query("SELECT * FROM student_marks ORDER BY created_at DESC");
            while ($student = $students->fetch_assoc()) {
                echo "\n--- Student Record ---\n";
                echo "ID: {$student['id']}\n";
                echo "Name: {$student['studentName']}\n";
                echo "Term: {$student['term']}\n";
                echo "Total: {$student['total']}\n";
                echo "Average: {$student['average']}%\n";
                echo "Grade: {$student['grade']}\n";
                echo "Created: {$student['created_at']}\n";
            }
        } else {
            echo "\n❌ No student records found in database\n";
            echo "💡 This explains why the dashboard shows no data\n";
        }
    }

    $conn->close();

} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}
?>
