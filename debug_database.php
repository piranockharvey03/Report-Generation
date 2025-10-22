<?php
// Debug script to check database contents
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reports";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("❌ Database connection failed: " . $conn->connect_error);
    }

    echo "<h1>🔍 Database Debug Information</h1>";
    echo "<pre>";

    // Check if database exists
    $dbCheck = $conn->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbname'");
    if ($dbCheck->num_rows == 0) {
        echo "❌ Database '$dbname' does not exist\n";
        echo "✅ Creating database...\n";
        $conn->query("CREATE DATABASE $dbname");
        echo "✅ Database created successfully\n";
    } else {
        echo "✅ Database '$dbname' exists\n";
    }

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
        $totalRecords = (int)$row['count'];
        echo "\n📊 Database Statistics:\n";
        echo "- Total records in student_marks table: <strong>$totalRecords</strong>\n";

        if ($totalRecords > 0) {
            echo "\n📋 Recent Records:\n";
            $recent = $conn->query("SELECT id, studentName, term, total, average, grade, created_at FROM student_marks ORDER BY created_at DESC LIMIT 5");
            while ($record = $recent->fetch_assoc()) {
                echo "  • {$record['studentName']} (ID: {$record['id']}) - {$record['total']} marks, {$record['average']}%, Grade: {$record['grade']}\n";
            }
        } else {
            echo "\n❌ No student records found in database\n";
            echo "💡 This explains why the dashboard shows 0 records\n";
        }
    } else {
        echo "\n❌ Failed to count records: " . $conn->error . "\n";
    }

    // Test the API
    echo "\n🌐 Testing API Response:\n";
    $apiUrl = "http://localhost:8080/php/get_total_records.php";
    echo "API URL: $apiUrl\n";

    // Simulate API call
    $apiResult = $conn->query("SELECT COUNT(*) as count FROM student_marks");
    if ($apiResult) {
        $apiRow = $apiResult->fetch_assoc();
        $apiCount = (int)$apiRow['count'];
        echo "API would return: {\"total_students\": $apiCount, \"success\": true}\n";
    }

    echo "\n✅ Debug check completed\n";
    echo "</pre>";

    $conn->close();

} catch (Exception $e) {
    echo "<h1>❌ Error</h1>";
    echo "<pre>Error: " . $e->getMessage() . "</pre>";
}
?>
