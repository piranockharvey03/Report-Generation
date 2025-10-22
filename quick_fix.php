<?php
// Quick Fix Script - Drop and recreate the table with correct schema
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reports";

try {
    $conn = new mysqli($servername, $username, $password);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Create database
    $conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
    $conn->select_db($dbname);

    echo "🔄 Checking and fixing student_marks table...\n\n";

    // Drop existing table
    $conn->query("DROP TABLE IF EXISTS student_marks");
    echo "✅ Dropped existing table\n";

    // Create with correct schema
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
        echo "✅ Created table with VARCHAR(20) for ID column\n";
    } else {
        throw new Exception("Failed to create table: " . $conn->error);
    }

    // Verify the fix
    $result = $conn->query("DESCRIBE student_marks");
    echo "\n📋 Table structure:\n";
    while ($row = $result->fetch_assoc()) {
        echo "- {$row['Field']}: {$row['Type']} (Key: {$row['Key']})\n";
    }

    // Test student ID generation
    function generateStudentId($studentName) {
        $prefix = strtoupper(substr($studentName, 0, 3));
        $microtime = str_replace('.', '', microtime(true));
        $microtime = substr($microtime, -8);
        $studentId = $prefix . $microtime;

        if (strlen($studentId) > 20) {
            $studentId = substr($studentId, 0, 20);
        }

        return strtoupper($studentId);
    }

    echo "\n🧪 Testing ID generation:\n";
    $testNames = ["John Doe", "Jane Smith", "Test Student"];
    foreach ($testNames as $name) {
        $id = generateStudentId($name);
        echo "Student: $name -> ID: $id (Length: " . strlen($id) . ")\n";
    }

    echo "\n🎉 Database fix completed! Try submitting marks again.\n";

    $conn->close();

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
