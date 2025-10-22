<?php
// Duplicate Entry Detection and Management Tool
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../html/index.html");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "reports";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("❌ Database connection failed: " . $conn->connect_error);
}

$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        if ($action === 'check_duplicates') {
            // Check for duplicate entries
            $duplicates = findDuplicateEntries($conn);
            if (empty($duplicates)) {
                $message = "✅ No duplicate entries found in the database.";
            } else {
                $message = "⚠️ Found " . count($duplicates) . " potential duplicate entries.";
            }
        } elseif ($action === 'cleanup_duplicates' && isset($_POST['duplicate_ids'])) {
            $idsToDelete = $_POST['duplicate_ids'];
            $deleted = cleanupDuplicates($conn, $idsToDelete);
            $message = "✅ Successfully removed " . $deleted . " duplicate entries.";
        }
    }
}

function findDuplicateEntries($conn) {
    $duplicates = [];

    // Find entries with same student name, teacher, term, and stage
    $sql = "SELECT studentName, classTeacherName, term, stage, COUNT(*) as count
            FROM student_marks
            GROUP BY studentName, classTeacherName, term, stage
            HAVING COUNT(*) > 1
            ORDER BY studentName, classTeacherName, term";

    $result = $conn->query($sql);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            // Get all records for this combination
            $detailSql = "SELECT id, studentName, classTeacherName, term, stage, created_at
                         FROM student_marks
                         WHERE studentName = ? AND classTeacherName = ? AND term = ? AND stage = ?
                         ORDER BY created_at ASC";

            $stmt = $conn->prepare($detailSql);
            $stmt->bind_param("ssss", $row['studentName'], $row['classTeacherName'], $row['term'], $row['stage']);
            $stmt->execute();
            $detailResult = $stmt->get_result();

            $records = [];
            while ($record = $detailResult->fetch_assoc()) {
                $records[] = $record;
            }

            // Mark all but the first as duplicates
            for ($i = 1; $i < count($records); $i++) {
                $duplicates[] = $records[$i];
            }

            $stmt->close();
        }
    }

    return $duplicates;
}

function cleanupDuplicates($conn, $idsToDelete) {
    $deleted = 0;

    foreach ($idsToDelete as $id) {
        $deleteSql = "DELETE FROM student_marks WHERE id = ?";
        $stmt = $conn->prepare($deleteSql);
        $stmt->bind_param("s", $id);

        if ($stmt->execute()) {
            $deleted++;
        }

        $stmt->close();
    }

    return $deleted;
}

$duplicates = [];
if (isset($_GET['check']) && $_GET['check'] === 'true') {
    $duplicates = findDuplicateEntries($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Duplicate Entry Management - Results Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen">
    <!-- Header -->
    <div class="bg-slate-800 text-white shadow-lg">
        <header class="py-4">
            <div class="container mx-auto px-4 sm:px-6 flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <div>
                        <h1 class="text-lg sm:text-xl font-bold">Duplicate Entry Management</h1>
                        <p class="text-slate-300 text-xs sm:text-sm">Results Management System</p>
                    </div>
                </div>
                <a href="../html/teacher_dashboard.php" class="bg-blue-600 hover:bg-blue-700 px-3 py-2 rounded-md transition duration-200 text-sm">
                    Back to Dashboard
                </a>
            </div>
        </header>
    </div>

    <div class="container mx-auto px-4 sm:px-6 py-6 sm:py-8">
        <?php if ($message): ?>
            <div class="mb-6 p-4 rounded-md <?php echo strpos($message, '✅') !== false ? 'bg-green-50 border border-green-200' : 'bg-yellow-50 border border-yellow-200'; ?>">
                <p class="text-slate-800"><?php echo $message; ?></p>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-md">
                <p class="text-red-800"><?php echo $error; ?></p>
            </div>
        <?php endif; ?>

        <!-- Duplicate Check Form -->
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 mb-6">
            <h3 class="text-base sm:text-lg font-bold text-slate-800 mb-4">🔍 Check for Duplicate Entries</h3>
            <p class="text-slate-600 text-sm mb-4">
                This tool helps identify students with multiple entries for the same term and teacher combination.
            </p>

            <form method="POST" class="space-y-4">
                <input type="hidden" name="action" value="check_duplicates">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-200 text-sm font-medium">
                    🔍 Scan for Duplicates
                </button>
            </form>
        </div>

        <?php if (!empty($duplicates)): ?>
        <!-- Duplicate Results -->
        <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 mb-6">
            <h3 class="text-base sm:text-lg font-bold text-slate-800 mb-4">⚠️ Duplicate Entries Found</h3>

            <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                <p class="text-yellow-800 text-sm">
                    <strong>⚠️ Warning:</strong> Only remove duplicates if you're certain they are not legitimate separate entries.
                    Consider checking with the teacher before deletion.
                </p>
            </div>

            <form method="POST" onsubmit="return confirm('Are you sure you want to delete the selected duplicate entries? This action cannot be undone.')">
                <input type="hidden" name="action" value="cleanup_duplicates">

                <div class="overflow-x-auto">
                    <table class="w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-lg">
                        <thead class="bg-slate-100">
                            <tr>
                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-slate-800 font-semibold">
                                    <input type="checkbox" id="selectAll" onchange="toggleAllCheckboxes()" class="rounded">
                                </th>
                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-slate-800 font-semibold">Student ID</th>
                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-slate-800 font-semibold">Student Name</th>
                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-slate-800 font-semibold">Teacher</th>
                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-slate-800 font-semibold">Term</th>
                                <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-slate-800 font-semibold">Date Added</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($duplicates as $duplicate): ?>
                            <tr class="border-b border-slate-200">
                                <td class="px-2 sm:px-4 py-2 sm:py-3">
                                    <input type="checkbox" name="duplicate_ids[]" value="<?php echo htmlspecialchars($duplicate['id']); ?>" class="duplicate-checkbox rounded">
                                </td>
                                <td class="px-2 sm:px-4 py-2 sm:py-3 text-slate-700 font-mono text-xs"><?php echo htmlspecialchars($duplicate['id']); ?></td>
                                <td class="px-2 sm:px-4 py-2 sm:py-3 text-slate-700 font-medium"><?php echo htmlspecialchars($duplicate['studentName']); ?></td>
                                <td class="px-2 sm:px-4 py-2 sm:py-3 text-slate-700"><?php echo htmlspecialchars($duplicate['classTeacherName']); ?></td>
                                <td class="px-2 sm:px-4 py-2 sm:py-3 text-slate-700"><?php echo htmlspecialchars($duplicate['term']); ?></td>
                                <td class="px-2 sm:px-4 py-2 sm:py-3 text-slate-700 text-xs"><?php echo htmlspecialchars($duplicate['created_at']); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 flex justify-between items-center">
                    <button type="button" onclick="selectAllDuplicates()" class="bg-slate-600 hover:bg-slate-700 text-white px-3 py-1 rounded text-xs transition duration-200">
                        Select All Duplicates
                    </button>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md transition duration-200 text-sm font-medium">
                        🗑️ Remove Selected Duplicates
                    </button>
                </div>
            </form>
        </div>
        <?php endif; ?>

        <!-- Information Section -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 sm:p-6">
            <h3 class="text-base sm:text-lg font-bold text-blue-800 mb-3">ℹ️ About Duplicate Prevention</h3>
            <div class="text-blue-700 text-sm space-y-2">
                <p><strong>🔒 Automatic Prevention:</strong> The system now automatically checks for duplicates before saving new entries.</p>
                <p><strong>🔍 Detection Criteria:</strong> Duplicates are identified by matching student name, teacher name, term, and stage.</p>
                <p><strong>🆔 Unique IDs:</strong> Each entry gets a unique ID that includes student name, teacher, term, and timestamp.</p>
                <p><strong>⚡ Performance:</strong> Database indexes ensure fast duplicate checking even with large datasets.</p>
            </div>
        </div>
    </div>

    <script>
        function toggleAllCheckboxes() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.duplicate-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
        }

        function selectAllDuplicates() {
            const checkboxes = document.querySelectorAll('.duplicate-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = true;
            });
        }
    </script>
</body>
</html>
