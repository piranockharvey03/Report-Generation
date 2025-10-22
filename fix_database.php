<?php
// Quick Database Fix Script
echo "<h1>Database Connection Test</h1>\n";

// Test 1: PHP Info
echo "<h2>Step 1: PHP Status</h2>\n";
echo "<p>PHP Version: " . phpversion() . "</p>\n";

// Test 2: MySQL Connection
echo "<h2>Step 2: MySQL Connection</h2>\n";
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reports";

try {
    $conn = new mysqli($servername, $username, $password);

    if ($conn->connect_error) {
        echo "<p style='color: red;'>❌ MySQL Connection Failed: " . $conn->connect_error . "</p>\n";
        echo "<p><strong>Solution:</strong> Start MySQL in XAMPP Control Panel</p>\n";
        echo "<p>1. Open XAMPP Control Panel</p>\n";
        echo "<p>2. Click 'Start' next to MySQL</p>\n";
        echo "<p>3. Wait for green indicator</p>\n";
    } else {
        echo "<p style='color: green;'>✅ MySQL Connected Successfully</p>\n";

        // Test 3: Database Creation
        echo "<h2>Step 3: Database Creation</h2>\n";
        if ($conn->query("CREATE DATABASE IF NOT EXISTS $dbname")) {
            echo "<p style='color: green;'>✅ Database 'reports' created/verified</p>\n";
        } else {
            echo "<p style='color: red;'>❌ Database creation failed: " . $conn->error . "</p>\n";
        }

        // Select database
        $conn->select_db($dbname);

        // Test 4: Table Creation
        echo "<h2>Step 4: Table Creation</h2>\n";
        $createTable = "CREATE TABLE IF NOT EXISTS student_marks (
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
            echo "<p style='color: green;'>✅ Table 'student_marks' created/verified</p>\n";

            // Test 5: Insert Sample Data
            echo "<h2>Step 5: Sample Data</h2>\n";
            $checkData = $conn->query("SELECT COUNT(*) as count FROM student_marks");
            $row = $checkData->fetch_assoc();

            if ($row['count'] == 0) {
                echo "<p>Inserting sample data...</p>\n";

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

                    $studentId = 'DEMO' . time() . rand(100, 999);

                    $insertSql = "INSERT INTO student_marks (id, studentName, classTeacherName, term, stage,
                        mathematics, chemistry, biology, physics, geography, history, business, economics,
                        ict, globalP, literature, french, mutoon, qoran, total, average, division, grade, gpa, percentile, passed_subjects, failed_subjects)
                        VALUES ('$studentId', '$studentName', 'Test Teacher', 'Term 1', 'Form 1',
                        " . implode(',', $marks) . ", $total, $average, 'I', '$grade', 4.0, $average, 14, 0)";

                    if ($conn->query($insertSql)) {
                        echo "<p style='color: green;'>✅ Added: $studentName</p>\n";
                    } else {
                        echo "<p style='color: red;'>❌ Failed to add: $studentName</p>\n";
                    }
                }
            } else {
                echo "<p style='color: blue;'>ℹ️ Database already has " . $row['count'] . " records</p>\n";
            }

            // Test 6: API Test
            echo "<h2>Step 6: API Test</h2>\n";
            $result = $conn->query("SELECT COUNT(*) as total_students FROM student_marks");
            $apiRow = $result->fetch_assoc();
            echo "<p style='color: green;'>✅ API Test: " . $apiRow['total_students'] . " students found</p>\n";
            echo "<p><a href='../html/teacher_dashboard.php' style='color: blue;'>→ Go to Dashboard</a></p>\n";

        } else {
            echo "<p style='color: red;'>❌ Table creation failed: " . $conn->error . "</p>\n";
        }
    }

    $conn->close();

} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Exception: " . $e->getMessage() . "</p>\n";
}

echo "<hr>\n";
echo "<p><strong>Next Steps:</strong></p>\n";
echo "<ol>\n";
echo "<li>Visit: <a href='../html/system_check.php'>System Check</a></li>\n";
echo "<li>Visit: <a href='../html/teacher_dashboard.php'>Main Dashboard</a></li>\n";
echo "<li>Visit: <a href='../html/setup.php'>Full Setup</a></li>\n";
echo "</ol>\n";
?>
