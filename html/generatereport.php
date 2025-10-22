<?php
// Start session for validation
session_start();

// Add cache control headers to prevent browser caching
header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
header("Pragma: no-cache"); // HTTP 1.0
header("Expires: 0"); // Proxies

// Check if user is logged in
if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
    // User is not logged in, redirect to login page
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Generation - Results Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="images/Education And College Logo Design Template Vector, School Logo, Education Logo, Institute Logo PNG and Vector with Transparent Background for Free Download.jpeg">
    <style>
        /* Print Styles */
        @media print {
            /* Hide non-printable elements */
            .print\:hidden { display: none !important; }
            header, footer, .bg-slate-800 { display: none !important; }
            
            /* Reset page styles */
            body { 
                background: white !important; 
                margin: 0;
                padding: 20px;
                font-family: 'Times New Roman', serif;
                color: #000;
            }
            
            /* Remove all backgrounds and shadows */
            * {
                background: white !important;
                box-shadow: none !important;
                text-shadow: none !important;
            }
            
            /* Professional report header */
            .report-card {
                page-break-inside: avoid;
            }
            
            .report-header {
                text-align: center;
                border-bottom: 3px double #000;
                padding-bottom: 15px;
                margin-bottom: 20px;
            }
            
            .school-logo {
                width: 80px;
                height: 80px;
                margin: 0 auto 10px;
            }
            
            .school-name {
                font-size: 28px;
                font-weight: bold;
                color: #000 !important;
                margin: 10px 0;
                text-transform: uppercase;
                letter-spacing: 1px;
            }
            
            .report-title {
                font-size: 20px;
                font-weight: bold;
                color: #000 !important;
                margin: 10px 0;
                text-transform: uppercase;
            }
            
            /* Student information section */
            .student-info-grid {
                display: table;
                width: 100%;
                margin: 20px 0;
                border: 2px solid #000;
            }
            
            .info-row {
                display: table-row;
                border-bottom: 1px solid #000;
            }
            
            .info-label {
                display: table-cell;
                padding: 8px 12px;
                font-weight: bold;
                width: 30%;
                border-right: 1px solid #000;
                background: #f5f5f5 !important;
            }
            
            .info-value {
                display: table-cell;
                padding: 8px 12px;
                width: 70%;
            }
            
            /* Performance summary */
            .performance-summary {
                margin: 20px 0;
                border: 2px solid #000;
                padding: 15px;
            }
            
            .performance-title {
                font-size: 16px;
                font-weight: bold;
                margin-bottom: 10px;
                text-align: center;
                text-transform: uppercase;
            }
            
            .performance-grid {
                display: table;
                width: 100%;
            }
            
            .perf-item {
                display: table-cell;
                text-align: center;
                padding: 10px;
                border-right: 1px solid #000;
            }
            
            .perf-item:last-child {
                border-right: none;
            }
            
            .perf-label {
                font-size: 11px;
                font-weight: bold;
                display: block;
                margin-bottom: 5px;
            }
            
            .perf-value {
                font-size: 18px;
                font-weight: bold;
                display: block;
            }
            
            /* Results table */
            .results-table {
                width: 100%;
                border-collapse: collapse;
                margin: 20px 0;
                border: 2px solid #000;
            }
            
            .results-table th {
                background: #000 !important;
                color: #fff !important;
                padding: 10px;
                text-align: center;
                font-weight: bold;
                border: 1px solid #000;
                font-size: 12px;
                text-transform: uppercase;
            }
            
            .results-table td {
                border: 1px solid #000;
                padding: 8px;
                text-align: center;
                font-size: 11px;
            }
            
            .results-table tr:nth-child(even) {
                background: #f9f9f9 !important;
            }
            
            .subject-name {
                text-align: left !important;
                font-weight: 600;
            }
            
            /* Grade badges for print */
            .grade-badge {
                display: inline-block;
                padding: 3px 8px;
                border: 1px solid #000;
                border-radius: 3px;
                font-weight: bold;
                font-size: 10px;
            }
            
            /* Footer section */
            .report-footer {
                margin-top: 40px;
                padding-top: 20px;
                border-top: 2px solid #000;
            }
            
            .signature-section {
                display: table;
                width: 100%;
                margin-top: 30px;
            }
            
            .signature-block {
                display: table-cell;
                width: 33%;
                text-align: center;
                padding: 10px;
            }
            
            .signature-line {
                border-top: 1px solid #000;
                margin-top: 40px;
                padding-top: 5px;
                font-size: 11px;
            }
            
            /* Remove rounded corners and colors */
            .rounded-lg, .rounded-2xl, .rounded-xl, .rounded-md, .rounded-full { 
                border-radius: 0 !important; 
            }
            
            .text-slate-300, .text-slate-400, .text-slate-500, .text-slate-600, 
            .text-slate-700, .text-slate-800, .text-green-600, .text-blue-600, 
            .text-yellow-600, .text-orange-600, .text-red-600 { 
                color: #000 !important; 
            }
            
            /* Hide decorative elements */
            .shadow-lg, .shadow-md, .shadow-xl, .shadow { 
                box-shadow: none !important; 
            }
            
            /* Page breaks */
            .page-break-before { page-break-before: always; }
            .page-break-after { page-break-after: always; }
            .no-page-break { page-break-inside: avoid; }
            
            /* Print date */
            .print-date {
                text-align: right;
                font-size: 10px;
                margin-bottom: 10px;
            }
        }
    </style>
</head>

<body class="bg-slate-50 min-h-screen">
    <!-- Header -->
    <div class="bg-slate-800 text-white shadow-lg">
        <header class="py-4">
            <div class="container mx-auto px-6">
                <div class="flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        <img class="w-10 h-10" src="../images/Education And College Logo Design Template Vector, School Logo, Education Logo, Institute Logo PNG and Vector with Transparent Background for Free Download.jpeg" alt="school icon">
                        <div>
                            <h1 class="text-xl font-bold">Report Generation Portal</h1>
                            <p class="text-slate-300 text-sm">Results Management System</p>
                        </div>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden md:flex items-center space-x-2">
                        <a href="teacher_dashboard.php" class="bg-slate-600 hover:bg-slate-700 px-3 py-2 rounded-md transition duration-200 text-sm">Dashboard</a>
                        <a href="main.html" class="bg-blue-600 hover:bg-blue-700 px-3 py-2 rounded-md transition duration-200 text-sm">Enter Marks</a>
                        <a href="index.html" class="bg-red-600 hover:bg-red-700 px-3 py-2 rounded-md transition duration-200 text-sm">Logout</a>
                    </div>

                    <!-- Mobile Navigation Button -->
                    <button id="mobile-menu-button" class="md:hidden p-2 rounded-md hover:bg-slate-700 transition duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>

                <!-- Mobile Navigation Menu -->
                <div id="mobile-menu" class="md:hidden hidden mt-4 pb-4 border-t border-slate-700">
                    <div class="flex flex-col space-y-2 pt-4">
                        <a href="teacher_dashboard.php" class="block bg-slate-600 hover:bg-slate-700 px-3 py-2 rounded-md transition duration-200 text-sm text-center">Dashboard</a>
                        <a href="main.html" class="block bg-blue-600 hover:bg-blue-700 px-3 py-2 rounded-md transition duration-200 text-sm text-center">Enter Marks</a>
                        <a href="index.html" class="block bg-red-600 hover:bg-red-700 px-3 py-2 rounded-md transition duration-200 text-sm text-center">Logout</a>
                    </div>
                </div>
            </div>
        </header>
    </div>

    <div class="container mx-auto px-6 py-8">
        <main class="max-w-4xl mx-auto">
            <!-- Page Header -->
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-slate-800 mb-2">Generate Student Reports</h2>
                <p class="text-slate-600">Search for student results by name and view detailed academic performance.</p>
            </div>

            <!-- Search Form -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Search Student Results
                </h3>

                <form method="POST" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="studentName" class="block text-slate-700 text-sm font-semibold mb-2">Student's Full Name:</label>
                            <input type="text" id="studentName" name="studentName" placeholder="Enter student name" required class="w-full px-4 py-3 rounded-md border border-slate-300 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition duration-200 outline-none">
                        </div>
                        <div>
                            <label for="studentId" class="block text-slate-700 text-sm font-semibold mb-2">Student ID (Optional):</label>
                            <input type="text" id="studentId" name="studentId" placeholder="Enter student ID" class="w-full px-4 py-3 rounded-md border border-slate-300 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition duration-200 outline-none">
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-md transition duration-200 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Generate Report
                        </button>
                        <button type="button" onclick="window.print()" class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-md transition duration-200 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                            Print Report
                        </button>
                    </div>
                </form>
            </div>

            <!-- Results Display Area -->
            <?php
            // Database connection - establish once at the top
            $host = "localhost";
            $user = "root";
            $password = "";
            $database = "reports";

            $conn = new mysqli($host, $user, $password, $database);
            $connectionEstablished = true;

            // Check connection
            if ($conn->connect_error) {
                echo '<div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                    <h3 class="text-red-800 font-bold mb-2">Database Connection Error</h3>
                    <p class="text-red-600">Unable to connect to the database. Please try again later.</p>
                </div>';
                $connectionEstablished = false;
            }

            if ($connectionEstablished && $_SERVER['REQUEST_METHOD'] == 'POST') {
                $studentName = $_POST['studentName'] ?? '';
                $studentId = $_POST['studentId'] ?? '';

                if (empty($studentName)) {
                    echo '<div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                        <h3 class="text-red-800 font-bold mb-2">Error</h3>
                        <p class="text-red-600">Please enter a student name to search.</p>
                    </div>';
                } else {
                    // Search with or without student ID
                    if (!empty($studentId)) {
                        $stmt = $conn->prepare("SELECT * FROM student_marks WHERE studentName = ? AND id = ?");
                        $stmt->bind_param("ss", $studentName, $studentId);
                    } else {
                        $stmt = $conn->prepare("SELECT * FROM student_marks WHERE studentName = ? ORDER BY created_at DESC LIMIT 1");
                        $stmt->bind_param("s", $studentName);
                    }

                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        ?>
                            <!-- Print Date (only visible when printing) -->
                            <div class="print-date" style="display: none;">Printed on: <?php echo date('F d, Y'); ?></div>
                            
                            <!-- Professional Report Header (only visible when printing) -->
                            <div class="report-header" style="display: none;">
                                <div class="school-name">The International Academy</div>
                                <div class="report-title">Student Academic Report Card</div>
                                <div style="font-size: 14px; margin-top: 10px;">Academic Year: <?php echo date('Y'); ?></div>
                            </div>
                            
                            <div class="bg-white rounded-lg shadow-md p-6 mb-8 report-card">
                                <div class="flex justify-between items-center mb-6">
                                    <h3 class="text-2xl font-bold text-slate-800">Student Academic Results</h3>
                                    <div class="flex gap-3">
                                        <button onclick="window.print()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md transition duration-200 flex items-center print:hidden">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                            </svg>
                                            Print Report
                                        </button>
                                    </div>
                                </div>

                                <!-- Student Information Cards -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                                    <div class="bg-slate-50 rounded-lg p-4">
                                        <h4 class="font-bold text-slate-800 mb-3">Student Information</h4>
                                        <div class="space-y-2">
                                            <div class="flex justify-between">
                                                <span class="text-slate-600">Name:</span>
                                                <span class="font-semibold text-slate-800"><?php echo isset($row['studentName']) ? htmlspecialchars($row['studentName']) : 'N/A'; ?></span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-slate-600">Student ID:</span>
                                                <span class="font-semibold text-slate-800"><?php echo isset($row['id']) ? htmlspecialchars($row['id']) : 'N/A'; ?></span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-slate-600">Class Teacher:</span>
                                                <span class="font-semibold text-slate-800"><?php echo isset($row['classTeacherName']) ? htmlspecialchars($row['classTeacherName']) : 'N/A'; ?></span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-slate-600">Term:</span>
                                                <span class="font-semibold text-slate-800"><?php echo isset($row['term']) ? htmlspecialchars($row['term']) : 'N/A'; ?></span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-slate-600">Stage:</span>
                                                <span class="font-semibold text-slate-800"><?php echo isset($row['stage']) ? htmlspecialchars($row['stage']) : 'N/A'; ?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-slate-50 rounded-lg p-4">
                                        <h4 class="font-bold text-slate-800 mb-3">Overall Performance</h4>
                                        <div class="space-y-2">
                                            <div class="flex justify-between">
                                                <span class="text-slate-600">Total Marks:</span>
                                                <span class="font-semibold text-slate-800"><?php echo isset($row['total']) ? htmlspecialchars($row['total']) . '/1400' : 'N/A'; ?></span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-slate-600">Average:</span>
                                                <span class="font-semibold text-slate-800"><?php echo isset($row['average']) ? round($row['average'], 2) . '%' : 'N/A'; ?></span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-slate-600">GPA:</span>
                                                <span class="font-semibold text-slate-800"><?php echo isset($row['gpa']) ? htmlspecialchars($row['gpa']) . '/4.0' : 'N/A'; ?></span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-slate-600">Grade:</span>
                                                <span class="font-semibold <?php
                                                    $grade = isset($row['grade']) ? $row['grade'] : 'C';
                                                    if ($grade === 'A+' || $grade === 'A') {
                                                        echo 'text-green-600';
                                                    } elseif ($grade === 'B+' || $grade === 'B') {
                                                        echo 'text-blue-600';
                                                    } elseif ($grade === 'C+' || $grade === 'C') {
                                                        echo 'text-yellow-600';
                                                    } elseif ($grade === 'D') {
                                                        echo 'text-orange-600';
                                                    } else {
                                                        echo 'text-red-600';
                                                    }
                                                ?>"><?php echo htmlspecialchars($grade); ?></span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-slate-600">Division:</span>
                                                <span class="font-semibold text-slate-800"><?php echo isset($row['division']) ? htmlspecialchars($row['division']) : 'N/A'; ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Subject-wise Results Table -->
                                <div class="bg-white border border-slate-200 rounded-lg overflow-hidden">
                                    <div class="bg-slate-100 px-6 py-4 border-b border-slate-200">
                                        <h4 class="font-bold text-slate-800">Subject-wise Performance</h4>
                                    </div>
                                    <div class="overflow-x-auto">
                                        <table class="w-full results-table">
                                            <thead class="bg-slate-50">
                                                <tr>
                                                    <th class="px-4 py-3 text-left text-slate-800 font-semibold">Subject</th>
                                                    <th class="px-4 py-3 text-center text-slate-800 font-semibold">Marks</th>
                                                    <th class="px-4 py-3 text-center text-slate-800 font-semibold">Grade</th>
                                                    <th class="px-4 py-3 text-center text-slate-800 font-semibold">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-200">
                                                <?php
                                                // Define grade calculation function
                                                function getGrade($marks) {
                                                    if ($marks >= 90) return 'A+';
                                                    elseif ($marks >= 80) return 'A';
                                                    elseif ($marks >= 70) return 'B+';
                                                    elseif ($marks >= 60) return 'B';
                                                    elseif ($marks >= 50) return 'C+';
                                                    elseif ($marks >= 40) return 'C';
                                                    elseif ($marks >= 30) return 'D';
                                                    else return 'F';
                                                }

                                                // Only show results if we have data
                                                if (isset($row) && is_array($row)) {
                                                    $subjects = [
                                                        'Mathematics' => $row['mathematics'] ?? 0,
                                                        'Chemistry' => $row['chemistry'] ?? 0,
                                                        'Biology' => $row['biology'] ?? 0,
                                                        'Physics' => $row['physics'] ?? 0,
                                                        'Geography' => $row['geography'] ?? 0,
                                                        'History' => $row['history'] ?? 0,
                                                        'Business' => $row['business'] ?? 0,
                                                        'Economics' => $row['economics'] ?? 0,
                                                        'ICT' => $row['ict'] ?? 0,
                                                        'Global Perspectives' => $row['globalP'] ?? 0,
                                                        'Literature' => $row['literature'] ?? 0,
                                                        'French' => $row['french'] ?? 0,
                                                        'Mutoon' => $row['mutoon'] ?? 0,
                                                        'Qor\'an' => $row['qoran'] ?? 0
                                                    ];

                                                    foreach ($subjects as $subject => $marks) {
                                                        $grade = getGrade($marks);
                                                        $status = $marks >= 40 ? 'Pass' : 'Fail';
                                                        ?>
                                                        <tr>
                                                            <td class="px-4 py-3 text-slate-700 subject-name"><?php echo htmlspecialchars($subject); ?></td>
                                                            <td class="px-4 py-3 text-center text-slate-700"><?php echo htmlspecialchars($marks); ?>/100</td>
                                                            <td class="px-4 py-3 text-center">
                                                                <span class="grade-badge inline-block px-2 py-1 rounded-full text-xs font-semibold <?php
                                                                    if ($grade === 'A+' || $grade === 'A') {
                                                                        echo 'bg-green-100 text-green-800';
                                                                    } elseif ($grade === 'B+' || $grade === 'B') {
                                                                        echo 'bg-blue-100 text-blue-800';
                                                                    } elseif ($grade === 'C+' || $grade === 'C') {
                                                                        echo 'bg-yellow-100 text-yellow-800';
                                                                    } elseif ($grade === 'D') {
                                                                        echo 'bg-orange-100 text-orange-800';
                                                                    } else {
                                                                        echo 'bg-red-100 text-red-800';
                                                                    }
                                                                ?>"><?php echo htmlspecialchars($grade); ?></span>
                                                            </td>
                                                            <td class="px-4 py-3 text-center">
                                                                <span class="grade-badge inline-block px-2 py-1 rounded-full text-xs font-semibold <?php echo $status == 'Pass' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                                                    <?php echo htmlspecialchars($status); ?>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                
                                <!-- Professional Report Footer (only visible when printing) -->
                                <div class="report-footer" style="display: none;">
                                    <div style="margin-bottom: 20px; text-align: center; font-size: 12px;">
                                        <strong>Grading Scale:</strong> A+ (90-100) | A (80-89) | B+ (70-79) | B (60-69) | C+ (50-59) | C (40-49) | D (30-39) | F (0-29)
                                    </div>
                                    <div class="signature-section">
                                        <div class="signature-block">
                                            <div class="signature-line">Class Teacher</div>
                                        </div>
                                        <div class="signature-block">
                                            <div class="signature-line">Head of Department</div>
                                        </div>
                                        <div class="signature-block">
                                            <div class="signature-line">Principal</div>
                                        </div>
                                    </div>
                                    <div style="text-align: center; margin-top: 30px; font-size: 10px; font-style: italic;">
                                        This is an official academic report generated by The International Academy Results Management System.
                                    </div>
                                </div>
                            </div>
                            <?php
                    } else {
                        ?>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                            <h3 class="text-yellow-800 font-bold mb-2">No Results Found</h3>
                            <p class="text-yellow-600">No academic records found for "<?php echo htmlspecialchars($studentName); ?>". Please check the name and try again.</p>
                        </div>
                        <?php
                    }
                    $stmt->close();
                }
            }

            // Close database connection only if it was established
            if (isset($conn) && $conn instanceof mysqli) {
                $conn->close();
            }
            ?>
        </main>
    </div>

    <footer class="bg-slate-800 text-white py-6 mt-8">
        <div class="container mx-auto px-6 text-center">
            <p class="text-slate-300">&copy; 2025 The International Academy - Results Management System. All rights reserved.</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');

            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });

                // Close mobile menu when clicking outside
                document.addEventListener('click', function(e) {
                    if (!mobileMenuButton.contains(e.target) && !mobileMenu.contains(e.target)) {
                        mobileMenu.classList.add('hidden');
                    }
                });

                // Close mobile menu when clicking on a link
                mobileMenu.querySelectorAll('a').forEach(link => {
                    link.addEventListener('click', function() {
                        mobileMenu.classList.add('hidden');
                    });
                });
            }
        });

        function exportToPDF() {
            alert('PDF export functionality would be implemented here');
        }

        function exportToExcel() {
            alert('Excel export functionality would be implemented here');
        }
    </script>
</body>

</html>