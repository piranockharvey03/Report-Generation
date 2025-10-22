<?php
// Database Reset Script - Run this to fix the student_marks table
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

echo "🔄 Resetting student_marks table...\n";

// Drop existing table
$conn->query("DROP TABLE IF EXISTS student_marks");
echo "✅ Dropped existing table (if any)\n";

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
    echo "✅ Created table with correct VARCHAR(20) schema\n";
} else {
    echo "❌ Failed to create table: " . $conn->error . "\n";
    exit(1);
}

// Verify table structure
$result = $conn->query("DESCRIBE student_marks");
echo "\n📋 New table structure:\n";
while ($row = $result->fetch_assoc()) {
    echo "- {$row['Field']}: {$row['Type']} (Key: {$row['Key']})\n";
}

// Check ID column specifically
$idCheck = $conn->query("SELECT COLUMN_NAME, COLUMN_TYPE, CHARACTER_MAXIMUM_LENGTH FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='$dbname' AND TABLE_NAME='student_marks' AND COLUMN_NAME='id'");
$idInfo = $idCheck->fetch_assoc();

echo "\n🔍 ID Column Details:\n";
echo "- Type: {$idInfo['COLUMN_TYPE']}\n";
echo "- Max Length: {$idInfo['CHARACTER_MAXIMUM_LENGTH']}\n";

if ($idInfo['CHARACTER_MAXIMUM_LENGTH'] == 20) {
    echo "✅ ID column is correctly configured for 20 characters\n";
} else {
    echo "❌ ID column is still configured for {$idInfo['CHARACTER_MAXIMUM_LENGTH']} characters\n";
    exit(1);
}

// Test student ID generation
echo "\n🧪 Testing student ID generation...\n";
function generateStudentId($studentName, $conn) {
    $prefix = strtoupper(substr($studentName, 0, 3));
    $microtime = str_replace('.', '', microtime(true));
    $microtime = substr($microtime, -8);
    $studentId = $prefix . $microtime;

    if (strlen($studentId) > 20) {
        $studentId = substr($studentId, 0, 20);
    }

    return strtoupper($studentId);
}

$testNames = ["John Doe", "Jane Smith", "Test Student", "A"];
foreach ($testNames as $name) {
    $id = generateStudentId($name, $conn);
    $length = strlen($id);
    echo "Student: $name -> ID: $id (Length: $length)";

    if ($length <= 20) {
        echo " ✅ OK\n";
    } else {
        echo " ❌ TOO LONG!\n";
        exit(1);
    }
}

echo "\n🎉 Database reset completed successfully!\n";
echo "💡 You can now try submitting marks again.\n";

$conn->close();
?>
