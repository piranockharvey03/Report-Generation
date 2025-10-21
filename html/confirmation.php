<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marks Entry Confirmation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="images/Education And College Logo Design Template Vector, School Logo, Education Logo, Institute Logo PNG and Vector with Transparent Background for Free Download.jpeg">
</head>

<body class="bg-slate-50 min-h-screen">
    <!-- Header -->
    <div class="bg-slate-800 text-white shadow-lg">
        <header class="py-4">
            <div class="container mx-auto px-6 flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <img class="w-10 h-10" src="../images/Education And College Logo Design Template Vector, School Logo, Education Logo, Institute Logo PNG and Vector with Transparent Background for Free Download.jpeg" alt="school icon">
                    <div>
                        <h1 class="text-xl font-bold">Marks Entry Confirmation</h1>
                        <p class="text-slate-300 text-sm">Results Management System</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="main.html" class="bg-slate-600 hover:bg-slate-700 px-4 py-2 rounded-md transition duration-200">Enter More Marks</a>
                    <a href="teacher_dashboard.php" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-md transition duration-200">Dashboard</a>
                </div>
            </div>
        </header>
    </div>

    <div class="container mx-auto px-6 py-8">
        <?php
        session_start();

        if (!isset($_SESSION['last_inserted'])) {
            header("Location: main.html");
            exit();
        }

        $data = $_SESSION['last_inserted'];
        $studentName = $data['student_name'];
        $studentId = $data['student_id'];
        $results = $data['results'];

        // Clear the session data
        unset($_SESSION['last_inserted']);
        ?>

        <!-- Success Message -->
        <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-8">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800">Marks Entered Successfully!</h3>
                    <p class="mt-1 text-sm text-green-700">
                        Student: <?php echo htmlspecialchars($studentName); ?> |
                        ID: <?php echo htmlspecialchars($studentId); ?> |
                        Average: <?php echo $results['average']; ?>%
                    </p>
                </div>
            </div>
        </div>

        <!-- Results Summary -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Student Information -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Student Information</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-slate-600">Student Name:</span>
                        <span class="font-semibold text-slate-800"><?php echo htmlspecialchars($studentName); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Student ID:</span>
                        <span class="font-semibold text-slate-800"><?php echo htmlspecialchars($studentId); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Total Marks:</span>
                        <span class="font-semibold text-slate-800"><?php echo $results['total_marks']; ?>/1400</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Average Score:</span>
                        <span class="font-semibold text-slate-800"><?php echo $results['average']; ?>%</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">GPA:</span>
                        <span class="font-semibold text-slate-800"><?php echo $results['gpa']; ?>/4.0</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Grade:</span>
                        <span class="font-semibold <?php
                            echo match($results['grade']) {
                                'A+', 'A' => 'text-green-600',
                                'B+', 'B' => 'text-blue-600',
                                'C+', 'C' => 'text-yellow-600',
                                'D' => 'text-orange-600',
                                default => 'text-red-600'
                            };
                        ?>"><?php echo htmlspecialchars($results['grade']); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Division:</span>
                        <span class="font-semibold text-slate-800"><?php echo htmlspecialchars($results['division']); ?></span>
                    </div>
                </div>
            </div>

            <!-- Performance Summary -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Performance Summary</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-slate-600">Subjects Passed:</span>
                        <span class="font-semibold text-green-600"><?php echo $results['passed_subjects']; ?>/14</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Subjects Failed:</span>
                        <span class="font-semibold text-red-600"><?php echo $results['failed_subjects']; ?>/14</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Percentile Rank:</span>
                        <span class="font-semibold text-slate-800"><?php echo $results['percentile']; ?>th</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Pass Rate:</span>
                        <span class="font-semibold <?php echo $results['passed_subjects'] >= 8 ? 'text-green-600' : 'text-red-600'; ?>">
                            <?php echo round(($results['passed_subjects'] / 14) * 100, 1); ?>%
                        </span>
                    </div>
                </div>

                <!-- Grade Badge -->
                <div class="mt-6 text-center">
                    <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold <?php
                        echo match($results['grade']) {
                            'A+', 'A' => 'bg-green-100 text-green-800',
                            'B+', 'B' => 'bg-blue-100 text-blue-800',
                            'C+', 'C' => 'bg-yellow-100 text-yellow-800',
                            'D' => 'bg-orange-100 text-orange-800',
                            default => 'bg-red-100 text-red-800'
                        };
                    ?>">
                        Final Grade: <?php echo htmlspecialchars($results['grade']); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recommendations -->
        <?php if (!empty($results['recommendations'])): ?>
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Teacher Recommendations</h3>
            <div class="space-y-2">
                <?php foreach ($results['recommendations'] as $recommendation): ?>
                    <div class="flex items-start space-x-3">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-slate-700"><?php echo htmlspecialchars($recommendation); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Action Buttons -->
        <div class="flex flex-wrap gap-4 justify-center">
            <button onclick="window.print()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-md transition duration-200">
                Print Confirmation
            </button>
            <a href="main.html" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md transition duration-200 text-center">
                Enter Another Student's Marks
            </a>
            <a href="teacher_dashboard.php" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-md transition duration-200 text-center">
                Return to Dashboard
            </a>
        </div>

        <!-- Footer Note -->
        <div class="mt-8 text-center">
            <p class="text-slate-600 text-sm">
                Note: Student ID has been generated and saved. Please keep this ID safe for future reference.
            </p>
        </div>
    </div>

    <script>
        // Auto-print functionality
        window.onload = function() {
            // Optional: Auto-print the confirmation
            // window.print();
        };
    </script>
</body>

</html>
