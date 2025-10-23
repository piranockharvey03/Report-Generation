<?php
// update_marks.php - API endpoint for updating student marks
error_reporting(0);
ini_set('display_errors', 0);

// Ensure absolutely no output before JSON
ob_clean();
if (ob_get_level()) {
    ob_clean();
}

// Set headers BEFORE any potential output
header('Content-Type: application/json');
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

// Check if grade_calculator.php exists
if (!file_exists('grade_calculator.php')) {
    http_response_code(500);
    echo json_encode(['error' => 'Grade calculator not found']);
    exit();
}

require_once 'grade_calculator.php';

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reports";

// Prevent any output before JSON
ob_clean();
ob_start();

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit();
}

try {
    // Only accept POST requests
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        exit();
    }

    // Get JSON input
    $rawInput = file_get_contents('php://input');
    $input = json_decode($rawInput, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON format: ' . json_last_error_msg()]);
        exit();
    }

    if (!$input || !isset($input['studentId'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data - missing studentId or invalid JSON']);
        exit();
    }

    $studentId = trim($input['studentId']);

    if (empty($studentId)) {
        http_response_code(400);
        echo json_encode(['error' => 'Student ID cannot be empty']);
        exit();
    }

    // Verify student exists
    $checkSql = "SELECT * FROM student_marks WHERE id = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("s", $studentId);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows === 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Student not found with ID: ' . $studentId]);
        exit();
    }

    $existingData = $result->fetch_assoc();
    $checkStmt->close();

    // Validate that at least one subject mark is provided
    $subjectNames = ['mathematics', 'chemistry', 'biology', 'physics', 'geography',
                     'history', 'business', 'economics', 'ict', 'globalP',
                     'literature', 'french', 'mutoon', 'qoran'];

    $hasValidMark = false;
    foreach ($subjectNames as $subject) {
        if (isset($input[$subject]) && is_numeric($input[$subject]) &&
            $input[$subject] >= 0 && $input[$subject] <= 100) {
            $hasValidMark = true;
            break;
        }
    }

    if (!$hasValidMark) {
        http_response_code(400);
        echo json_encode(['error' => 'At least one valid subject mark (0-100) must be provided']);
        exit();
    }

    // Initialize calculator
    $calculator = new GradeCalculator();

    // Collect subject marks from input
    $subjectNames = ['mathematics', 'chemistry', 'biology', 'physics', 'geography',
                     'history', 'business', 'economics', 'ict', 'globalP',
                     'literature', 'french', 'mutoon', 'qoran'];

    $marks = [];
    $rawMarks = [];

    foreach ($subjectNames as $subject) {
        $value = isset($input[$subject]) ? trim($input[$subject]) : null;

        if ($value !== null && $value !== '' && is_numeric($value)) {
            $marks[] = (int)$value;
            $rawMarks[$subject] = (int)$value;
        } else {
            $rawMarks[$subject] = null;
        }
    }

    // Calculate results
    try {
        $results = $calculator->calculateResults($marks);
    } catch (Exception $e) {
        error_log("Grade calculation error: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['error' => 'Grade calculation failed: ' . $e->getMessage()]);
        exit();
    }

    // Ensure recommendations can be JSON encoded
    try {
        $recommendationsJson = json_encode($results['recommendations']);
        if ($recommendationsJson === false) {
            $recommendationsJson = json_encode([]);
        }
    } catch (Exception $e) {
        $recommendationsJson = json_encode([]);
    }

    // Update the database record
    $updateSql = "UPDATE student_marks SET
                  mathematics = ?, chemistry = ?, biology = ?, physics = ?,
                  geography = ?, history = ?, business = ?, economics = ?,
                  ict = ?, globalP = ?, literature = ?, french = ?,
                  mutoon = ?, qoran = ?, total = ?, average = ?,
                  division = ?, grade = ?, gpa = ?, percentile = ?,
                  passed_subjects = ?, failed_subjects = ?, recommendations = ?
                  WHERE id = ?";

    $stmt = $conn->prepare($updateSql);
    if (!$stmt) {
        error_log("Prepare statement failed: " . $conn->error);
        http_response_code(500);
        echo json_encode(['error' => 'Database prepare failed: ' . $conn->error]);
        exit();
    }

    $recommendationsJson = json_encode($results['recommendations']);

    // Bind parameters
    $stmt->bind_param(
        "iiiiiiiiiiiiiiidssddiis",
        $rawMarks['mathematics'], $rawMarks['chemistry'], $rawMarks['biology'], $rawMarks['physics'],
        $rawMarks['geography'], $rawMarks['history'], $rawMarks['business'], $rawMarks['economics'],
        $rawMarks['ict'], $rawMarks['globalP'], $rawMarks['literature'], $rawMarks['french'],
        $rawMarks['mutoon'], $rawMarks['qoran'],
        $results['total_marks'], $results['average'], $results['division'], $results['grade'],
        $results['gpa'], $results['percentile'],
        $results['passed_subjects'], $results['failed_subjects'], $recommendationsJson,
        $studentId
    );

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Marks updated successfully',
            'student_id' => $studentId,
            'results' => $results
        ]);
    } else {
        error_log("Execute failed: " . $stmt->error);
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update marks: ' . $stmt->error]);
    }

    $stmt->close();

} catch (Exception $e) {
    error_log("Update marks error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Update failed: ' . $e->getMessage()]);
} finally {
    $conn->close();
}
?>
