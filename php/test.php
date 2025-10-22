<?php
echo "PHP Test Page\n";
echo "PHP Version: " . phpversion() . "\n";
echo "Current time: " . date('Y-m-d H:i:s') . "\n";

// Test database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reports";

echo "\n--- Database Connection Test ---\n";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        echo "Connection failed: " . $conn->connect_error . "\n";

        // Try to create database
        echo "Attempting to create database...\n";
        $conn = new mysqli($servername, $username, $password);

        if ($conn->query("CREATE DATABASE IF NOT EXISTS reports")) {
            echo "Database created successfully\n";
            $conn->select_db("reports");

            // Create table
            $sql = "CREATE TABLE IF NOT EXISTS student_marks (
                id VARCHAR(20) PRIMARY KEY,
                studentName VARCHAR(100) NOT NULL,
                classTeacherName VARCHAR(100) NOT NULL,
                term VARCHAR(20) NOT NULL,
                stage VARCHAR(20) NOT NULL,
                mathematics INT NULL,
                chemistry INT NULL,
                biology INT NULL,
                physics INT NULL,
                geography INT NULL,
                history INT NULL,
                business INT NULL,
                economics INT NULL,
                ict INT NULL,
                globalP INT NULL,
                literature INT NULL,
                french INT NULL,
                mutoon INT NULL,
                qoran INT NULL,
                total INT NOT NULL,
                average DECIMAL(5,2) NOT NULL,
                division VARCHAR(10) NOT NULL,
                grade VARCHAR(5) NOT NULL,
                gpa DECIMAL(3,2) NOT NULL,
                percentile DECIMAL(5,2) NOT NULL,
                passed_subjects INT NOT NULL,
                failed_subjects INT NOT NULL,
                recommendations TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";

            if ($conn->query($sql)) {
                echo "Table created successfully\n";

                // Insert sample data
                $sampleData = [
                    ['John Doe', 'Mr. Smith', 'Term 1', 'Form 1', 85, 78, 92, 88, 76, 80, 85, 82, 90, 75, 88, 85, 82, 90],
                    ['Jane Smith', 'Mr. Smith', 'Term 1', 'Form 1', 92, 88, 95, 90, 85, 87, 90, 88, 92, 85, 90, 88, 85, 92]
                ];

                foreach ($sampleData as $data) {
                    $studentName = $conn->real_escape_string($data[0]);
                    $marks = array_slice($data, 4);
                    $total = array_sum($marks);
                    $average = $total / count($marks);

                    if ($average >= 80) $grade = 'A';
                    elseif ($average >= 60) $grade = 'B';
                    elseif ($average >= 40) $grade = 'C';
                    else $grade = 'F';

                    $studentId = 'TEST' . time() . rand(100, 999);

                    $insertSql = "INSERT INTO student_marks (id, studentName, classTeacherName, term, stage,
                        mathematics, chemistry, biology, physics, geography, history, business, economics,
                        ict, globalP, literature, french, mutoon, qoran, total, average, division, grade, gpa, percentile, passed_subjects, failed_subjects)
                        VALUES ('$studentId', '$studentName', 'Test Teacher', 'Term 1', 'Form 1',
                        " . implode(',', $marks) . ", $total, $average, 'I', '$grade', 4.0, $average, 14, 0)";

                    if ($conn->query($insertSql)) {
                        echo "Sample data inserted: $studentName\n";
                    }
                }
            }
        } else {
            echo "Failed to create database\n";
        }
    } else {
        echo "Connected successfully\n";
        echo "Database: " . $conn->query("SELECT DATABASE()")->fetch_row()[0] . "\n";

        // Check table
        $result = $conn->query("SHOW TABLES LIKE 'student_marks'");
        if ($result->num_rows > 0) {
            echo "Table exists\n";

            // Count records
            $count = $conn->query("SELECT COUNT(*) as count FROM student_marks")->fetch_assoc()['count'];
            echo "Records: $count\n";
        } else {
            echo "Table does not exist\n";
        }
    }

    $conn->close();
} catch (Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}

echo "\n--- Test Complete ---\n";
?>
