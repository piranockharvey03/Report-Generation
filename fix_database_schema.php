<?php
// Database Schema Fix Script
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reports";

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
$conn->select_db($dbname);

// Check current table structure
echo "Checking current table structure...\n";
$result = $conn->query("DESCRIBE student_marks");
if ($result && $result->num_rows > 0) {
    echo "Current student_marks table structure:\n";
    while ($row = $result->fetch_assoc()) {
        echo "- {$row['Field']}: {$row['Type']} (Null: {$row['Null']}, Key: {$row['Key']})\n";
    }

    // Check if id column exists and its size
    $idCheck = $conn->query("SELECT COLUMN_NAME, COLUMN_TYPE, CHARACTER_MAXIMUM_LENGTH FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='$dbname' AND TABLE_NAME='student_marks' AND COLUMN_NAME='id'");
    if ($idCheck && $idCheck->num_rows > 0) {
        $idInfo = $idCheck->fetch_assoc();
        echo "\nID Column Details:\n";
        echo "- Type: {$idInfo['COLUMN_TYPE']}\n";
        echo "- Max Length: {$idInfo['CHARACTER_MAXIMUM_LENGTH']}\n";

        if ($idInfo['CHARACTER_MAXIMUM_LENGTH'] < 20) {
            echo "\n❌ PROBLEM: ID column is too small! Current max length: {$idInfo['CHARACTER_MAXIMUM_LENGTH']}\n";
            echo "🔧 Fixing by altering table...\n";

            // Drop and recreate table with correct schema
            $conn->query("DROP TABLE IF EXISTS student_marks");

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
                recommendations TEXT, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_student_name (studentName), INDEX idx_term (term), INDEX idx_created_at (created_at)
            )";

            if ($conn->query($createTable)) {
                echo "✅ Table recreated successfully with VARCHAR(20) for ID column\n";
            } else {
                echo "❌ Failed to recreate table: " . $conn->error . "\n";
            }
        } else {
            echo "\n✅ ID column size is correct: {$idInfo['CHARACTER_MAXIMUM_LENGTH']} characters\n";
        }
    }
} else {
    echo "Table doesn't exist, creating new table...\n";
    // Create table with correct schema
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
        recommendations TEXT, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_student_name (studentName), INDEX idx_term (term), INDEX idx_created_at (created_at)
    )";

    if ($conn->query($createTable)) {
        echo "✅ Table created successfully with VARCHAR(20) for ID column\n";
    } else {
        echo "❌ Failed to create table: " . $conn->error . "\n";
    }
}

// Test student ID generation
echo "\nTesting student ID generation...\n";
function generateStudentId($studentName, $conn) {
    $prefix = strtoupper(substr($studentName, 0, 2));
    $timestamp = substr(time(), -6);
    $counter = rand(1000, 9999);
    $studentId = $prefix . $timestamp . $counter;

    if (strlen($studentId) > 20) {
        $studentId = substr($studentId, 0, 20);
    }

    return strtoupper($studentId);
}

$testNames = ["John Doe", "Jane Smith", "Test Student"];
foreach ($testNames as $name) {
    $id = generateStudentId($name, $conn);
    echo "Student: $name -> ID: $id (Length: " . strlen($id) . ")\n";

    if (strlen($id) > 20) {
        echo "  ❌ ERROR: ID too long!\n";
    } else {
        echo "  ✅ ID length OK\n";
    }
}

echo "\n🎉 Database schema fix completed!\n";
$conn->close();
?>
