<?php
// Clear Database Script - Removes all student marks data
header('Content-Type: text/plain');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reports";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if database exists
    $dbExists = $conn->select_db($dbname);
    if (!$dbExists) {
        echo "❌ Database 'reports' does not exist.\n";
        echo "Please run setup first.\n";
        exit();
    }

    // Check current data
    $result = $conn->query("SELECT COUNT(*) as count FROM student_marks");
    $row = $result->fetch_assoc();
    $currentCount = $row['count'];

    if ($currentCount == 0) {
        echo "✅ Database is already empty (0 records).\n";
        echo "Dashboard should show: 0 students, 0% average, 0% pass rate.\n";
    } else {
        echo "📊 Current database has $currentCount records.\n";
        echo "🗑️ Clearing all data...\n";

        // Delete all records
        if ($conn->query("DELETE FROM student_marks") === TRUE) {
            echo "✅ All student marks data cleared successfully!\n";
            echo "🎯 Dashboard will now show accurate zero values.\n";
        } else {
            echo "❌ Error clearing data: " . $conn->error . "\n";
        }
    }

    // Verify the clear worked
    $result = $conn->query("SELECT COUNT(*) as count FROM student_marks");
    $row = $result->fetch_assoc();
    $newCount = $row['count'];

    echo "\n📋 Verification:\n";
    echo "Records before: $currentCount\n";
    echo "Records after: $newCount\n";

    if ($newCount == 0) {
        echo "✅ SUCCESS: Database is now empty!\n";
        echo "🔄 Refresh your dashboard to see: 0 students, 0% average, 0% pass rate\n";
    }

    $conn->close();

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n🔗 Next steps:\n";
echo "1. Refresh your dashboard: http://localhost:8000/html/teacher_dashboard.php\n";
echo "2. Check system status: http://localhost:8000/html/system_check.php\n";
echo "3. If issues persist, run: http://localhost:8000/html/setup.php\n";
?>
