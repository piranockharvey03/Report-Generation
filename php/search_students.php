<?php
// search_students.php - API endpoint for searching students
session_start();

// Suppress all PHP errors and warnings to prevent JSON corruption
error_reporting(0);
ini_set('display_errors', 0);

// Add cache control headers to prevent browser caching
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

// Set content type to JSON BEFORE any output
header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

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
    // Get search parameters
    $searchType = $_GET['type'] ?? '';
    $searchQuery = trim($_GET['query'] ?? '');

    if (empty($searchType) || empty($searchQuery)) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing search parameters']);
        exit();
    }

    // Build search query based on type
    $allowedTypes = ['admission', 'name', 'class'];
    if (!in_array($searchType, $allowedTypes)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid search type']);
        exit();
    }

    // Map search types to database fields
    $fieldMap = [
        'admission' => 'id',
        'name' => 'studentName',
        'class' => 'stage'
    ];

    $field = $fieldMap[$searchType];

    // Search query with LIKE for partial matches
    $sql = "SELECT id, studentName, classTeacherName, term, stage, created_at
            FROM student_marks
            WHERE $field LIKE ?
            ORDER BY created_at DESC
            LIMIT 50";

    $stmt = $conn->prepare($sql);
    $searchParam = "%$searchQuery%";
    $stmt->bind_param("s", $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();

    $students = [];
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $students[] = [
                'id' => $row['id'],
                'admission_no' => $row['id'],
                'name' => $row['studentName'],
                'class' => $row['stage'],
                'teacher' => $row['classTeacherName'],
                'term' => $row['term'],
                'year' => date('Y', strtotime($row['created_at']))
            ];
        }
    }

    echo json_encode([
        'success' => true,
        'students' => $students,
        'count' => count($students)
    ]);

} catch (Exception $e) {
    error_log("Search error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Search failed']);
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    $conn->close();
}
?>
