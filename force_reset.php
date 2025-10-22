<?php
// Force database reset and table creation
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reports";

try {
    $conn = new mysqli($servername, $username, $password);

    if ($conn->connect_error) {
        die("❌ Database connection failed: " . $conn->connect_error);
    }

    echo "<h1>🔧 Database Reset & Fix</h1>";
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
    echo "\n📋 Table structure:\n";
    $result = $conn->query("DESCRIBE student_marks");
    while ($row = $result->fetch_assoc()) {
        echo "- {$row['Field']}: {$row['Type']} (Key: {$row['Key']})\n";
    }

    echo "\n📊 Current database contents:\n";
    $result = $conn->query("SELECT COUNT(*) as count FROM student_marks");
    if ($result) {
        $row = $result->fetch_assoc();
        echo "- Total records: " . $row['count'] . "\n";

        if ($row['count'] > 0) {
            echo "\n📋 Student Records:\n";
            $students = $conn->query("SELECT id, studentName, term, total, average, grade, created_at FROM student_marks ORDER BY created_at DESC");
            while ($student = $students->fetch_assoc()) {
                echo "  • {$student['studentName']} (ID: {$student['id']}) - {$student['total']} marks, {$student['average']}%, Grade: {$student['grade']} - {$student['term']}\n";
            }
        } else {
            echo "❌ No student records found\n";
        }
    }

    echo "\n🎉 DATABASE RESET COMPLETED!\n";
    echo "💡 If you had entries before, they may have been lost during the reset.\n";
    echo "💡 Try entering your data again - it should now work properly.\n";
    echo "</pre>";

    $conn->close();

} catch (Exception $e) {
    echo "<h1>❌ Error</h1>";
    echo "<pre>Error: " . $e->getMessage() . "</pre>";
}
?>
