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
    <title>Marks Entry Confirmation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="images/Education And College Logo Design Template Vector, School Logo, Education Logo, Institute Logo PNG and Vector with Transparent Background for Free Download.jpeg">
</head>

<body class="bg-slate-50 min-h-screen pb-20">
    <!-- Header -->
    <div class="bg-slate-800 text-white shadow-lg">
        <header class="py-4">
            <div class="container mx-auto px-4 sm:px-6 flex justify-between items-center">
                <div class="flex items-center space-x-3 sm:space-x-4">
                    <img class="w-8 h-8 sm:w-10 sm:h-10" src="../images/Education And College Logo Design Template Vector, School Logo, Education Logo, Institute Logo PNG and Vector with Transparent Background for Free Download.jpeg" alt="school icon">
                    <div>
                        <h1 class="text-lg sm:text-xl font-bold">Marks Entry Confirmation</h1>
                        <p class="text-slate-300 text-xs sm:text-sm">Results Management System</p>
                    </div>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-2">
                    <a href="main.html" class="bg-slate-600 hover:bg-slate-700 px-3 py-2 rounded-md transition duration-200 text-sm">Enter More Marks</a>
                    <a href="teacher_dashboard.php" class="bg-blue-600 hover:bg-blue-700 px-3 py-2 rounded-md transition duration-200 text-sm">Dashboard</a>
                    <a href="../php/logout.php" class="bg-red-600 hover:bg-red-700 px-3 py-2 rounded-md transition duration-200 text-sm">Logout</a>
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
                <div class="flex flex-col space-y-2 pt-4 px-6">
                    <a href="main.html" class="block bg-slate-600 hover:bg-slate-700 px-3 py-2 rounded-md transition duration-200 text-sm text-center">Enter More Marks</a>
                    <a href="teacher_dashboard.php" class="block bg-blue-600 hover:bg-blue-700 px-3 py-2 rounded-md transition duration-200 text-sm text-center">Dashboard</a>
                    <a href="../php/logout.php" class="block bg-red-600 hover:bg-red-700 px-3 py-2 rounded-md transition duration-200 text-sm text-center">Logout</a>
                </div>
            </div>
        </header>
    </div>

    <div class="container mx-auto px-4 sm:px-6 py-6 sm:py-8">
        <?php
        // Session already started at the top of the file - no need to start again

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
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
            <!-- Student Information -->
            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                <h3 class="text-base sm:text-lg font-bold text-slate-800 mb-4">Student Information</h3>
                <div class="space-y-3">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center py-2 border-b border-slate-100">
                        <span class="text-slate-600 text-sm sm:text-base mb-1 sm:mb-0">Student Name:</span>
                        <span class="font-semibold text-slate-800 text-sm sm:text-base"><?php echo htmlspecialchars($studentName); ?></span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center py-2 border-b border-slate-100">
                        <span class="text-slate-600 text-sm sm:text-base mb-1 sm:mb-0">Student ID:</span>
                        <span class="font-semibold text-slate-800 text-sm sm:text-base font-mono"><?php echo htmlspecialchars($studentId); ?></span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center py-2 border-b border-slate-100">
                        <span class="text-slate-600 text-sm sm:text-base mb-1 sm:mb-0">Total Marks:</span>
                        <span class="font-semibold text-slate-800 text-sm sm:text-base"><?php echo $results['total_marks']; ?> / <?php echo array_sum(array_column($results['subject_grades'], 'marks')); ?> marks</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center py-2 border-b border-slate-100">
                        <span class="text-slate-600 text-sm sm:text-base mb-1 sm:mb-0">Average Score:</span>
                        <span class="font-semibold text-slate-800 text-sm sm:text-base"><?php echo $results['average']; ?>%</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center py-2 border-b border-slate-100">
                        <span class="text-slate-600 text-sm sm:text-base mb-1 sm:mb-0">GPA:</span>
                        <span class="font-semibold text-slate-800 text-sm sm:text-base"><?php echo $results['gpa']; ?>/4.0</span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center py-2">
                        <span class="text-slate-600 text-sm sm:text-base mb-1 sm:mb-0">Grade:</span>
                        <span class="font-semibold text-sm sm:text-base px-2 py-1 rounded <?php
                            $grade = $results['grade'];
                            if (in_array($grade, ['A+', 'A'])) {
                                echo 'bg-green-100 text-green-800';
                            } elseif (in_array($grade, ['B+', 'B'])) {
                                echo 'bg-blue-100 text-blue-800';
                            } elseif (in_array($grade, ['C+', 'C'])) {
                                echo 'bg-yellow-100 text-yellow-800';
                            } elseif ($grade === 'D') {
                                echo 'bg-orange-100 text-orange-800';
                            } else {
                                echo 'bg-red-100 text-red-800';
                            }
                        ?>"><?php echo htmlspecialchars($results['grade']); ?></span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center py-2">
                        <span class="text-slate-600 text-sm sm:text-base mb-1 sm:mb-0">Division:</span>
                        <span class="font-semibold text-slate-800 text-sm sm:text-base"><?php echo htmlspecialchars($results['division']); ?></span>
                    </div>
                </div>
            </div>

            <!-- Performance Summary -->
            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                <h3 class="text-base sm:text-lg font-bold text-slate-800 mb-4">Performance Summary</h3>
                <div class="grid grid-cols-2 gap-3 sm:gap-4">
                    <div class="text-center p-3 sm:p-4 bg-slate-50 rounded-lg">
                        <div class="text-xl sm:text-2xl font-bold text-green-600"><?php echo $results['passed_subjects']; ?></div>
                        <div class="text-xs sm:text-sm text-slate-600">Passed</div>
                    </div>
                    <div class="text-center p-3 sm:p-4 bg-slate-50 rounded-lg">
                        <div class="text-xl sm:text-2xl font-bold text-red-600"><?php echo $results['failed_subjects']; ?></div>
                        <div class="text-xs sm:text-sm text-slate-600">Failed</div>
                    </div>
                    <div class="text-center p-3 sm:p-4 bg-slate-50 rounded-lg col-span-2">
                        <div class="text-xl sm:text-2xl font-bold text-purple-600"><?php echo $results['percentile']; ?>th</div>
                        <div class="text-xs sm:text-sm text-slate-600">Percentile Rank</div>
                    </div>
                    <div class="text-center p-3 sm:p-4 bg-slate-50 rounded-lg col-span-2">
                        <div class="text-lg sm:text-xl font-bold <?php echo $results['passed_subjects'] >= (count($results['subject_grades']) / 2) ? 'text-green-600' : 'text-red-600'; ?>">
                            <?php echo round(($results['passed_subjects'] / count($results['subject_grades'])) * 100, 1); ?>%
                        </div>
                        <div class="text-xs sm:text-sm text-slate-600">Pass Rate</div>
                    </div>
                </div>

                <div class="mt-4 sm:mt-6 text-center">
                    <div class="inline-flex items-center px-3 sm:px-4 py-2 rounded-full text-sm font-bold <?php
                        $grade = $results['grade'];
                        if (in_array($grade, ['A+', 'A'])) {
                            echo 'bg-green-100 text-green-800';
                        } elseif (in_array($grade, ['B+', 'B'])) {
                            echo 'bg-blue-100 text-blue-800';
                        } elseif (in_array($grade, ['C+', 'C'])) {
                            echo 'bg-yellow-100 text-yellow-800';
                        } elseif ($grade === 'D') {
                            echo 'bg-orange-100 text-orange-800';
                        } else {
                            echo 'bg-red-100 text-red-800';
                        }
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
        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center items-center">
            <button onclick="window.print()" class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-md transition duration-200 text-sm sm:text-base font-medium">
                🖨️ Print Confirmation
            </button>
            <a href="main.html" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-md transition duration-200 text-center text-sm sm:text-base font-medium">
                📝 Enter Another Student's Marks
            </a>
            <a href="teacher_dashboard.php" class="w-full sm:w-auto bg-purple-600 hover:bg-purple-700 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-md transition duration-200 text-center text-sm sm:text-base font-medium">
                🏠 Return to Dashboard
            </a>
            <a href="../php/logout.php" class="w-full sm:w-auto bg-red-600 hover:bg-red-700 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-md transition duration-200 text-center text-sm sm:text-base font-medium">
                🚪 Logout
            </a>
        </div>

        <!-- Footer Note -->
        <div class="mt-6 sm:mt-8 text-center">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 sm:p-4">
                <p class="text-slate-700 text-xs sm:text-sm">
                    <strong>💡 Note:</strong> Student ID has been generated and saved. Please keep this ID safe for future reference.
                </p>
            </div>
        </div>
    </div>

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

                // Handle window resize for responsive behavior
                window.addEventListener('resize', function() {
                    if (window.innerWidth >= 768) {
                        // Hide mobile menu on larger screens
                        if (mobileMenu) {
                            mobileMenu.classList.add('hidden');
                        }
                    }
                });
            }

            // Auto-print functionality
            window.onload = function() {
                // Optional: Auto-print the confirmation
                // window.print();
            };
        });
    </script>
</body>

</html>
