<?php
// Test database connection and student_marks table
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reports";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("❌ Database connection failed: " . $conn->connect_error);
}

echo "✅ Database connection successful\n";

// Check if student_marks table exists
$tableCheck = $conn->query("SHOW TABLES LIKE 'student_marks'");
if ($tableCheck->num_rows > 0) {
    echo "✅ student_marks table exists\n";

    // Check table structure
    $structureCheck = $conn->query("DESCRIBE student_marks");
    echo "✅ Table structure:\n";
    while ($row = $structureCheck->fetch_assoc()) {
        echo "  - {$row['Field']}: {$row['Type']}\n";
    }

    // Check if there's any sample data
    $countCheck = $conn->query("SELECT COUNT(*) as count FROM student_marks");
    $count = $countCheck->fetch_assoc();
    echo "✅ Total records in student_marks table: {$count['count']}\n";

    if ($count['count'] > 0) {
        // Show a sample record
        $sampleCheck = $conn->query("SELECT id, studentName, stage, term FROM student_marks LIMIT 1");
        $sample = $sampleCheck->fetch_assoc();
        echo "✅ Sample record found: {$sample['studentName']} ({$sample['id']}) - {$sample['stage']} - {$sample['term']}\n";
    } else {
        echo "⚠️  No student records found. You may need to add some student data first.\n";
    }
} else {
    echo "❌ student_marks table does not exist\n";
    echo "You may need to run the setup_database.php script first.\n";
}

$conn->close();
echo "✅ Test completed successfully\n";
?>
