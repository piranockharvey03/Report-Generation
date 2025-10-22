<?php
// Database setup for Reports Management System - Web Version
header('Content-Type: text/plain');

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reports";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) === TRUE) {
    echo "✓ Database created successfully or already exists\n";
} else {
    echo "✗ Error creating database: " . $conn->error . "\n";
}

// Select the database
$conn->select_db($dbname);

// Create student_marks table
$sql = "CREATE TABLE IF NOT EXISTS student_marks (
    id VARCHAR(20) PRIMARY KEY,
    studentName VARCHAR(100) NOT NULL,
    classTeacherName VARCHAR(100) NOT NULL,
    term VARCHAR(20) NOT NULL,
    stage VARCHAR(20) NOT NULL,
    mathematics INT NULL,
    chemistry INT NULL,
    biology INT NULL,
    physics INT NULL,
    geography INT NULL,
    history INT NULL,
    business INT NULL,
    economics INT NULL,
    ict INT NULL,
    globalP INT NULL,
    literature INT NULL,
    french INT NULL,
    mutoon INT NULL,
    qoran INT NULL,
    total INT NOT NULL,
    average DECIMAL(5,2) NOT NULL,
    division VARCHAR(10) NOT NULL,
    grade VARCHAR(5) NOT NULL,
    gpa DECIMAL(3,2) NOT NULL,
    percentile DECIMAL(5,2) NOT NULL,
    passed_subjects INT NOT NULL,
    failed_subjects INT NOT NULL,
    recommendations TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_student_name (studentName),
    INDEX idx_term (term),
    INDEX idx_created_at (created_at)
)";

if ($conn->query($sql) === TRUE) {
    echo "✓ Table student_marks created successfully or already exists\n";
} else {
    echo "✗ Error creating table: " . $conn->error . "\n";
}

// Check if we need sample data
$checkSql = "SELECT COUNT(*) as count FROM student_marks";
$result = $conn->query($checkSql);
$row = $result->fetch_assoc();

if ($row['count'] == 0) {
    echo "✓ Inserting sample data for testing...\n";

    $sampleData = [
        ['John Doe', 'Mr. Smith', 'Term 1', 'Form 1', 85, 78, 92, 88, 76, 80, 85, 82, 90, 75, 88, 85, 82, 90],
        ['Jane Smith', 'Mr. Smith', 'Term 1', 'Form 1', 92, 88, 95, 90, 85, 87, 90, 88, 92, 85, 90, 88, 85, 92],
        ['Michael Johnson', 'Ms. Davis', 'Term 1', 'Form 2', 75, 70, 80, 78, 72, 75, 78, 76, 80, 70, 75, 78, 76, 80],
        ['Sarah Wilson', 'Ms. Davis', 'Term 1', 'Form 2', 88, 85, 90, 87, 82, 85, 87, 84, 88, 80, 85, 87, 84, 88],
        ['David Brown', 'Mr. Smith', 'Term 1', 'Form 1', 65, 60, 70, 68, 62, 65, 68, 64, 70, 60, 65, 68, 64, 70]
    ];

    foreach ($sampleData as $data) {
        $studentName = $conn->real_escape_string($data[0]);
        $classTeacherName = $conn->real_escape_string($data[1]);
        $term = $conn->real_escape_string($data[2]);
        $stage = $conn->real_escape_string($data[3]);

        // Calculate marks (skip first 4 elements which are student info)
        $marks = array_slice($data, 4);
        $total = array_sum($marks);
        $average = $total / count($marks);

        // Simple grade calculation
        if ($average >= 80) {
            $grade = 'A';
            $division = 'I';
            $gpa = 4.0;
        } elseif ($average >= 60) {
            $grade = 'B';
            $division = 'II';
            $gpa = 3.0;
        } elseif ($average >= 40) {
            $grade = 'C';
            $division = 'III';
            $gpa = 2.0;
        } else {
            $grade = 'F';
            $division = 'IV';
            $gpa = 0.0;
        }

        $percentile = min(100, max(0, $average));
        $passed_subjects = count(array_filter($marks, function($mark) { return $mark >= 40; }));
        $failed_subjects = count($marks) - $passed_subjects;

        // Generate student ID
        $studentId = generateStudentId($studentName);

        $insertSql = "INSERT INTO student_marks (
            id, studentName, classTeacherName, term, stage,
            mathematics, chemistry, biology, physics, geography, history,
            business, economics, ict, globalP, literature, french, mutoon, qoran,
            total, average, division, grade, gpa, percentile, passed_subjects, failed_subjects,
            recommendations, created_at
        ) VALUES (
            '$studentId', '$studentName', '$classTeacherName', '$term', '$stage',
            " . implode(',', $marks) . ",
            $total, $average, '$division', '$grade', $gpa, $percentile, $passed_subjects, $failed_subjects,
            'Sample recommendations', NOW()
        )";

        if ($conn->query($insertSql) === TRUE) {
            echo "✓ Added student: $studentName\n";
        } else {
            echo "✗ Error inserting $studentName: " . $conn->error . "\n";
        }
    }

    echo "✓ Sample data inserted successfully!\n";
} else {
    echo "✓ Database already contains data, skipping sample data insertion\n";
}

function generateStudentId($studentName) {
    // Generate a simple, guaranteed unique student ID within VARCHAR(20) limit
    $prefix = strtoupper(substr($studentName, 0, 3)); // Use 3 chars for better uniqueness

    // Use microtime for guaranteed uniqueness
    $microtime = str_replace('.', '', microtime(true));
    $microtime = substr($microtime, -8); // Last 8 digits

    // Generate ID: 3 chars + 8 digits = 11 characters max
    $studentId = $prefix . $microtime;

    // Safety check - ensure it never exceeds 20 characters
    if (strlen($studentId) > 20) {
        $studentId = substr($studentId, 0, 20);
    }

    return strtoupper($studentId);
}

echo "\n🎉 Database setup completed successfully!\n";
echo "📊 Dashboard will now display responsive data.\n";
echo "🔄 Redirecting to dashboard in 3 seconds...\n";

$conn->close();
?>
