<?php
// Debug script to test update_marks API
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing update_marks API...\n\n";

// Test database connection
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
if ($tableCheck->num_rows == 0) {
    die("❌ student_marks table does not exist");
}

echo "✅ student_marks table exists\n";

// Check table structure
$structureCheck = $conn->query("DESCRIBE student_marks");
echo "✅ Table structure:\n";
while ($row = $structureCheck->fetch_assoc()) {
    echo "  - {$row['Field']}: {$row['Type']}\n";
}

// Check if there are any records
$countCheck = $conn->query("SELECT COUNT(*) as count FROM student_marks");
$count = $countCheck->fetch_assoc();
echo "\n✅ Total records in student_marks table: {$count['count']}\n";

if ($count['count'] == 0) {
    echo "⚠️  No student records found. You need to add students first.\n";
} else {
    // Show a sample record
    $sampleCheck = $conn->query("SELECT id, studentName FROM student_marks LIMIT 1");
    $sample = $sampleCheck->fetch_assoc();
    echo "✅ Sample student ID for testing: {$sample['id']} ({$sample['studentName']})\n";
}

// Test grade calculator
echo "\nTesting grade calculator...\n";
if (file_exists('grade_calculator.php')) {
    echo "✅ grade_calculator.php exists\n";
    require_once 'grade_calculator.php';

    try {
        $calculator = new GradeCalculator();
        $testMarks = [80, 75, 90, 85];
        $results = $calculator->calculateResults($testMarks);
        echo "✅ Grade calculator works\n";
        echo "  Test results: Average = {$results['average']}, Grade = {$results['grade']}\n";
    } catch (Exception $e) {
        echo "❌ Grade calculator error: " . $e->getMessage() . "\n";
    }
} else {
    echo "❌ grade_calculator.php not found\n";
}

$conn->close();
echo "\n✅ Debug test completed\n";
?>
