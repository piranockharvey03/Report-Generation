<?php
// Database update script to add new columns for enhanced grading system
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reports";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Updating database schema...\n";

// Add new columns if they don't exist
$columns = [
    'grade' => 'VARCHAR(5) DEFAULT NULL',
    'gpa' => 'DECIMAL(3,2) DEFAULT NULL',
    'percentile' => 'DECIMAL(5,2) DEFAULT NULL',
    'passed_subjects' => 'INT DEFAULT 0',
    'failed_subjects' => 'INT DEFAULT 0',
    'recommendations' => 'TEXT DEFAULT NULL'
];

foreach ($columns as $column => $definition) {
    $sql = "ALTER TABLE student_marks ADD COLUMN IF NOT EXISTS $column $definition";
    if ($conn->query($sql) === TRUE) {
        echo "✓ Added column: $column\n";
    } else {
        echo "✗ Error adding column $column: " . $conn->error . "\n";
    }
}

// Update existing records with calculated values
$sql = "SELECT id, mathematics, chemistry, biology, physics, geography, history, business, economics, ict, globalP, literature, french, mutoon, qoran, average FROM student_marks WHERE grade IS NULL";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "\nUpdating existing records...\n";

    while ($row = $result->fetch_assoc()) {
        $marks = [
            $row['mathematics'], $row['chemistry'], $row['biology'], $row['physics'],
            $row['geography'], $row['history'], $row['business'], $row['economics'],
            $row['ict'], $row['globalP'], $row['literature'], $row['french'],
            $row['mutoon'], $row['qoran']
        ];

        // Calculate new values (simplified calculation for existing records)
        $total = array_sum($marks);
        $average = $total / 14;
        $passed_subjects = count(array_filter($marks, fn($mark) => $mark >= 40));
        $failed_subjects = 14 - $passed_subjects;

        // Simple grade calculation
        $grade = 'C'; // Default
        if ($average >= 80) $grade = 'A';
        elseif ($average >= 60) $grade = 'B';

        // Simple GPA calculation
        $gpa = 2.0; // Default
        if ($average >= 80) $gpa = 3.5;
        elseif ($average >= 60) $gpa = 2.5;

        $update_sql = "UPDATE student_marks SET
            grade = '$grade',
            gpa = $gpa,
            percentile = $average,
            passed_subjects = $passed_subjects,
            failed_subjects = $failed_subjects
        WHERE id = '{$row['id']}'";

        if ($conn->query($update_sql) === TRUE) {
            echo "✓ Updated record ID: {$row['id']}\n";
        } else {
            echo "✗ Error updating record {$row['id']}: " . $conn->error . "\n";
        }
    }
}

echo "\nDatabase update completed!\n";
echo "Total records processed: " . $result->num_rows . "\n";

$conn->close();
?>
