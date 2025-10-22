<?php
// ULTIMATE DATABASE FIX - Visit this URL in your browser: http://localhost:8080/ultimate_fix.php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reports";

try {
    $conn = new mysqli($servername, $username, $password);
    if ($conn->connect_error) {
        die("❌ Database connection failed: " . $conn->connect_error);
    }

    echo "<h1>🔧 Ultimate Database Fix</h1>";
    echo "<pre>";

    // Create database
    $conn->query("CREATE DATABASE IF NOT EXISTS $dbname");
    $conn->select_db($dbname);
    echo "✅ Database ready\n";

    // FORCE drop and recreate table
    echo "🔄 Dropping existing table...\n";
    $conn->query("DROP TABLE IF EXISTS student_marks");

    echo "✅ Creating table with correct schema...\n";
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
        echo "✅ Table created successfully\n";
    } else {
        throw new Exception("Failed to create table: " . $conn->error);
    }

    // Verify schema
    echo "\n📋 Verifying table schema:\n";
    $result = $conn->query("DESCRIBE student_marks");
    while ($row = $result->fetch_assoc()) {
        echo "- {$row['Field']}: {$row['Type']} (Key: {$row['Key']})\n";
    }

    // Test ID generation
    function generateStudentId($studentName) {
        $prefix = strtoupper(substr($studentName, 0, 3));
        $timestamp = time();
        $timestamp = substr($timestamp, -8);
        $studentId = $prefix . $timestamp;

        if (strlen($studentId) > 20) {
            $studentId = substr($studentId, 0, 20);
        }

        $studentId = preg_replace('/[^A-Z0-9]/', '', $studentId);

        if (strlen($studentId) > 20) {
            $studentId = substr($studentId, 0, 20);
        }

        if (strlen($studentId) == 0) {
            $studentId = substr(md5($studentName . time()), 0, 20);
        }

        return strtoupper($studentId);
    }

    echo "\n🧪 Testing ID generation:\n";
    $testNames = ["John Doe", "Jane Smith", "Test Student", "محمد أحمد", "José María"];
    foreach ($testNames as $name) {
        $id = generateStudentId($name);
        $status = (strlen($id) <= 20) ? "✅" : "❌";
        echo "Student: $name -> ID: $id (Length: " . strlen($id) . ") $status\n";
    }

    echo "\n🎉 DATABASE FIX COMPLETED SUCCESSFULLY!\n";
    echo "💡 Now try submitting your marks again. The error should be resolved.\n";
    echo "</pre>";

    $conn->close();

} catch (Exception $e) {
    echo "<h1>❌ Error</h1>";
    echo "<pre>Error: " . $e->getMessage() . "</pre>";
}
?>
