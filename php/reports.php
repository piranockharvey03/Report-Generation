<?php
session_start();

require_once 'grade_calculator.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reports";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Collect POST data
    $studentName = trim($_POST['studentName']);
    $classTeacherName = $_POST['classTeacherName'];
    $term = $_POST['term'];
    $stage = $_POST['stage'];
    
    // Collect all subject marks - use null for empty fields
    $subjectNames = ['mathematics', 'chemistry', 'biology', 'physics', 'geography', 
                     'history', 'business', 'economics', 'ict', 'globalP', 
                     'literature', 'french', 'mutoon', 'qoran'];
    
    $marks = [];
    $rawMarks = []; // Store raw values including nulls for database
    
    foreach ($subjectNames as $subject) {
        $value = trim($_POST[$subject] ?? '');
        if ($value !== '' && is_numeric($value)) {
            $marks[] = (int)$value;
            $rawMarks[$subject] = (int)$value;
        } else {
            $rawMarks[$subject] = null;
        }
    }

    try {
        $results = $calculator->calculateResults($marks);
        
        // Check if table exists and create if it doesn't
        $tableCheckSql = "SHOW TABLES LIKE 'student_marks'";
        $tableResult = $conn->query($tableCheckSql);

        if ($tableResult->num_rows == 0) {
            // Table doesn't exist, create it
            $createTableSql = "CREATE TABLE student_marks (
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
                INDEX idx_student_details (studentName, classTeacherName, term, stage),
                INDEX idx_student_name (studentName),
                INDEX idx_term (term),
                INDEX idx_created_at (created_at)
            )";

            if (!$conn->query($createTableSql)) {
                throw new Exception("Failed to create student_marks table: " . $conn->error);
            }
        } else {
            // Table exists, check if ID column has correct size
            $schemaCheck = $conn->query("SELECT COLUMN_TYPE FROM information_schema.COLUMNS WHERE TABLE_SCHEMA='$dbname' AND TABLE_NAME='student_marks' AND COLUMN_NAME='id'");
            if ($schemaCheck && $schemaCheck->num_rows > 0) {
                $schemaInfo = $schemaCheck->fetch_assoc();
                if (strpos($schemaInfo['COLUMN_TYPE'], 'varchar(20)') === false) {
                    // Wrong schema, drop and recreate
                    $conn->query("DROP TABLE student_marks");
                    $createTableSql = "CREATE TABLE student_marks (
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
                        INDEX idx_student_details (studentName, classTeacherName, term, stage),
                        INDEX idx_student_name (studentName),
                        INDEX idx_term (term),
                        INDEX idx_created_at (created_at)
                    )";

                    if (!$conn->query($createTableSql)) {
                        throw new Exception("Failed to recreate student_marks table: " . $conn->error);
                    }
                }
            }
        }
        
        // Check for duplicate entries BEFORE generating student ID
        $duplicateCheck = checkForDuplicateEntry($studentName, $classTeacherName, $term, $stage, $conn);
        if ($duplicateCheck['exists']) {
            $duplicateInfo = $duplicateCheck['data'];
            echo "<script>
                alert('❌ DUPLICATE ENTRY DETECTED!\\n\\nA record already exists for:\\n\\nStudent: {$duplicateInfo['studentName']}\\nTeacher: {$duplicateInfo['classTeacherName']}\\nTerm: {$duplicateInfo['term']}\\nStage: {$duplicateInfo['stage']}\\nDate: {$duplicateInfo['created_at']}\\n\\nPlease verify this is not a duplicate entry before proceeding.');
                window.location.href = '../html/main.html';
            </script>";
            exit();
        }

        // Generate unique student ID
        $studentId = generateStudentId($studentName, $classTeacherName, $term, $conn);
        error_log("Generated Student ID: '$studentId' (Length: " . strlen($studentId) . ") for student: $studentName, teacher: $classTeacherName, term: $term");

        // Validate student ID length and format
        if (strlen($studentId) > 20) {
            throw new Exception("Generated student ID is too long: " . strlen($studentId) . " characters (max 20 allowed). ID: " . $studentId);
        }

        if (strlen($studentId) == 0) {
            throw new Exception("Generated student ID is empty");
        }

        if (!preg_match('/^[A-Z0-9]+$/', $studentId)) {
            throw new Exception("Generated student ID contains invalid characters: " . $studentId);
        }
        
        // Insert all data into database
        $sql = "INSERT INTO student_marks (
            id, studentName, classTeacherName, term, stage,
            mathematics, chemistry, biology, physics, geography, history,
            business, economics, ict, globalP, literature, french, mutoon, qoran,
            total, average, division, grade, gpa, percentile, passed_subjects, failed_subjects,
            recommendations, created_at
        ) VALUES (
            ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?, ?, ?, ?,
            ?, ?, ?, ?, ?, ?, ?, ?,
            ?, NOW()
        )";
        
        $stmt = $conn->prepare($sql);
        $recommendationsJson = json_encode($results['recommendations']);

        // Bind parameters - manually counted: 28 total
        $stmt->bind_param(
            "sssss" . "iiiiiiiiiiiiii" . "i" . "d" . "ss" . "dd" . "ii" . "s",
            $studentId, $studentName, $classTeacherName, $term, $stage,
            $rawMarks['mathematics'], $rawMarks['chemistry'], $rawMarks['biology'], $rawMarks['physics'],
            $rawMarks['geography'], $rawMarks['history'], $rawMarks['business'], $rawMarks['economics'],
            $rawMarks['ict'], $rawMarks['globalP'], $rawMarks['literature'], $rawMarks['french'],
            $rawMarks['mutoon'], $rawMarks['qoran'],
            $results['total_marks'], $results['average'], $results['division'], $results['grade'], $results['gpa'], $results['percentile'],
            $results['passed_subjects'], $results['failed_subjects'], $recommendationsJson
        );
        
        if ($stmt->execute()) {
            $_SESSION['last_inserted'] = [
                'student_name' => $studentName,
                'student_id' => $studentId,
                'results' => $results
            ];

            // Success - redirect to confirmation page
            header("Location: ../html/confirmation.php");
            exit();
        } else {
            // More detailed error information
            $error = $stmt->error ?: $conn->error ?: 'Unknown database error';
            $errorCode = $conn->errno ?: 'Unknown';

            // Log detailed error information
            error_log("Database insertion failed:");
            error_log("- Error: $error");
            error_log("- Error Code: $errorCode");
            error_log("- Student ID: '$studentId' (Length: " . strlen($studentId) . ")");
            error_log("- Student Name: $studentName");
            error_log("- SQL: $sql");

            echo "<script>
                alert('❌ ERROR: Failed to save marks to database.\\n\\nError: " . addslashes($error) . "\\nError Code: " . addslashes($errorCode) . "\\nStudent ID: " . addslashes($studentId) . "\\nID Length: " . strlen($studentId) . "\\n\\nPlease try again or contact administrator.');
                window.location.href = '../html/main.html';
            </script>";
            exit();
        }
        $stmt->close();
        
    } catch (Exception $e) {
        echo "<script>
            alert('❌ ERROR: Failed to calculate grades.\\n\\nError: " . addslashes($e->getMessage()) . "');
            window.location.href = '../html/main.html';
        </script>";
        exit();
    }
}
$conn->close();

function checkForDuplicateEntry($studentName, $classTeacherName, $term, $stage, $conn) {
    // Check if a student with the same details already exists
    $checkSql = "SELECT studentName, classTeacherName, term, stage, created_at
                 FROM student_marks
                 WHERE studentName = ? AND classTeacherName = ? AND term = ? AND stage = ?
                 ORDER BY created_at DESC LIMIT 1";

    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("ssss", $studentName, $classTeacherName, $term, $stage);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $duplicate = $result->fetch_assoc();
        $stmt->close();
        return [
            'exists' => true,
            'data' => $duplicate
        ];
    }

    $stmt->close();
    return ['exists' => false];
}

function generateStudentId($studentName, $classTeacherName, $term, $conn) {
    // Enhanced student ID generation - includes term and teacher for better uniqueness
    $namePrefix = strtoupper(substr(preg_replace('/[^A-Z]/', '', $studentName), 0, 2));
    $teacherPrefix = strtoupper(substr(preg_replace('/[^A-Z]/', '', $classTeacherName), 0, 2));
    $termPrefix = strtoupper(substr($term, 0, 2));

    // Use timestamp for uniqueness
    $timestamp = time();
    $timestamp = substr($timestamp, -6); // Last 6 digits of timestamp

    // Generate ID: 2+2+2+6 = 12 characters (well under 20 limit)
    $studentId = $namePrefix . $teacherPrefix . $termPrefix . $timestamp;

    // Ensure it never exceeds 20 characters
    if (strlen($studentId) > 20) {
        $studentId = substr($studentId, 0, 20);
    }

    // Ensure it only contains valid characters
    $studentId = preg_replace('/[^A-Z0-9]/', '', $studentId);

    // Final safety check
    if (strlen($studentId) > 20) {
        $studentId = substr($studentId, 0, 20);
    }

    if (strlen($studentId) == 0) {
        // Ultimate fallback - use hash of all details
        $studentId = substr(md5($studentName . $classTeacherName . $term . time()), 0, 20);
    }

    return strtoupper($studentId);
}
?>
