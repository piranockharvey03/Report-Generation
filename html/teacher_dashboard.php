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
    <title>Teacher Dashboard - Results Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="images/Education And College Logo Design Template Vector, School Logo, Education Logo, Institute Logo PNG and Vector with Transparent Background for Free Download.jpeg">
</head>

<body class="bg-slate-50 min-h-screen pb-20">
    <?php
    // Check for login success notification
    $showLoginNotification = isset($_SESSION['login_success']) && $_SESSION['login_success'] === true;
    $userDisplayName = $_SESSION['user_display_name'] ?? $_SESSION['username'] ?? 'Teacher';

    // Clear the login success flag after showing notification
    if ($showLoginNotification) {
        unset($_SESSION['login_success']);
    }
    ?>

    <!-- Login Success Notification -->
    <?php if ($showLoginNotification): ?>
    <div id="login-notification" class="fixed top-4 left-4 right-4 sm:left-4 sm:right-4 md:left-auto md:right-4 bg-green-500 text-white px-4 py-3 rounded-md shadow-lg z-50 max-w-sm md:max-w-md lg:max-w-lg transform transition-all duration-300 ease-in-out opacity-100 translate-y-0">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2 flex-1 min-w-0">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-medium truncate">Welcome back, <?php echo htmlspecialchars($userDisplayName); ?>!</p>
                    <p class="text-xs opacity-90 truncate">Login successful • <?php echo date('M j, Y \a\t g:i A'); ?></p>
                </div>
            </div>
            <button onclick="closeLoginNotification()" class="ml-4 flex-shrink-0 p-1 rounded-full hover:bg-green-600 transition duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    </div>
    <?php endif; ?>

    <!-- Header -->
    <header class="bg-slate-800 text-white py-4">
        <div class="container mx-auto px-6">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <img class="w-8 h-8 sm:w-10 sm:h-10" src="../images/Education And College Logo Design Template Vector, School Logo, Education Logo, Institute Logo PNG and Vector with Transparent Background for Free Download.jpeg" alt="school icon">
                    <div>
                        <h1 class="text-lg sm:text-xl font-bold">Results Management System</h1>
                        <p class="text-slate-300 text-xs sm:text-sm">Teacher Dashboard</p>
                    </div>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-1 xl:space-x-2">
                    <a href="teacher_dashboard.php" class="bg-slate-600 hover:bg-slate-700 px-2 py-2 xl:px-3 xl:py-2 rounded-md transition duration-200 text-xs xl:text-sm whitespace-nowrap">Dashboard</a>
                    <a href="main.html" class="bg-blue-600 hover:bg-blue-700 px-2 py-2 xl:px-3 xl:py-2 rounded-md transition duration-200 text-xs xl:text-sm whitespace-nowrap">New Entry</a>
                    <a href="change_marks.php" class="bg-purple-600 hover:bg-purple-700 px-2 py-2 xl:px-3 xl:py-2 rounded-md transition duration-200 text-xs xl:text-sm whitespace-nowrap">Change Marks</a>
                    <a href="generatereport.php" class="bg-green-600 hover:bg-green-700 px-2 py-2 xl:px-3 xl:py-2 rounded-md transition duration-200 text-xs xl:text-sm whitespace-nowrap">Reports</a>
                    <a href="../php/logout.php" class="bg-red-600 hover:bg-red-700 px-2 py-2 xl:px-3 xl:py-2 rounded-md transition duration-200 text-xs xl:text-sm whitespace-nowrap">Logout</a>
                </div>

                <!-- Mobile Navigation Button -->
                <button id="mobile-menu-button" class="md:hidden p-2 rounded-md hover:bg-slate-700 transition duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            <!-- Teacher Info Section -->
            <div class="mt-3 pt-3 border-t border-slate-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <span class="text-slate-300 text-sm">Logged in as:</span>
                        <span class="text-white font-medium text-sm"><?php echo htmlspecialchars($_SESSION['teacher_name'] ?? $_SESSION['username'] ?? 'Teacher'); ?></span>
                    </div>
                    <div class="text-xs text-slate-400">
                        <?php echo date('l, F j, Y'); ?>
                    </div>
                </div>
            </div>

            <!-- Mobile Navigation Menu -->
            <div id="mobile-menu" class="md:hidden hidden mt-4 pb-4 border-t border-slate-700">
                <div class="flex flex-col space-y-2 pt-4">
                    <a href="teacher_dashboard.php" class="block bg-slate-600 hover:bg-slate-700 px-3 py-2 rounded-md transition duration-200 text-sm text-center">Dashboard</a>
                    <a href="main.html" class="block bg-blue-600 hover:bg-blue-700 px-3 py-2 rounded-md transition duration-200 text-sm text-center">New Entry</a>
                    <a href="change_marks.php" class="block bg-purple-600 hover:bg-purple-700 px-3 py-2 rounded-md transition duration-200 text-sm text-center">Change Marks</a>
                    <a href="generatereport.php" class="block bg-green-600 hover:bg-green-700 px-3 py-2 rounded-md transition duration-200 text-sm text-center">Reports</a>
                    <a href="../php/logout.php" class="block bg-red-600 hover:bg-red-700 px-3 py-2 rounded-md transition duration-200 text-sm text-center">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 sm:px-6 py-6 sm:py-8">
        <!-- Main Content -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-4 sm:gap-6">
            <!-- Left Column - Quick Actions -->
            <div class="xl:col-span-1 order-2 xl:order-1">
                <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 mb-6">
                    <h3 class="text-sm sm:text-base font-bold text-slate-800 mb-4">Quick Actions</h3>
                    <div class="space-y-2 sm:space-y-3">
                        <a href="main.html" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-md transition duration-200 text-sm sm:text-base font-medium">
                            New Marks
                        </a>
                        <a href="change_marks.php" class="block w-full text-center bg-purple-600 hover:bg-purple-700 text-white px-4 py-3 rounded-md transition duration-200 text-sm sm:text-base font-medium">
                            Change Marks
                        </a>
                        <a href="generatereport.php" class="block w-full text-center bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-md transition duration-200 text-sm sm:text-base font-medium">
                            Reports
                        </a>
                    </div>
                </div>

            </div>

            <!-- Right Column - Database Statistics -->
            <div class="xl:col-span-2 order-1 xl:order-2">
                <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 mb-6">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4">
                        <h3 class="text-base sm:text-lg font-bold text-slate-800 mb-2 sm:mb-0">Database Overview</h3>
                        <div class="flex gap-2">
                            <button onclick="loadDashboardData()" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs transition duration-200">
                                🔄 Refresh
                            </button>
                            <button onclick="testAPI()" class="bg-slate-600 hover:bg-slate-700 text-white px-3 py-1 rounded text-xs transition duration-200">
                                🧪 Test API
                            </button>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-3 sm:gap-4">
                        <div class="text-center p-3 sm:p-4 bg-slate-50 rounded-lg">
                            <div class="text-2xl sm:text-3xl font-bold text-blue-600" id="totalRecords">Loading...</div>
                            <div class="text-sm sm:text-base text-slate-600">Total Records</div>
                            <div class="text-xs text-slate-500 mt-1">Students added to database</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Banner -->
    <div class="fixed bottom-4 left-4 right-4 sm:left-4 sm:right-4 md:left-auto md:right-4 bg-blue-500 text-white px-4 py-3 rounded-md shadow-lg z-50 max-w-sm md:max-w-md lg:max-w-lg">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2 flex-1 min-w-0">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-sm truncate">PHP Setup Required for Live Data</span>
            </div>
            <a href="setup.php" class="ml-4 bg-blue-600 hover:bg-blue-700 px-3 py-1 rounded text-xs transition duration-200 flex-shrink-0 whitespace-nowrap">
                Setup PHP
            </a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Close login notification function
            window.closeLoginNotification = function() {
                const notification = document.getElementById('login-notification');
                if (notification) {
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateY(-10px)';
                    setTimeout(function() {
                        notification.style.display = 'none';
                    }, 300);
                }
            };

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

            // Auto-hide login notification after 5 seconds with smooth animation
            setTimeout(function() {
                const notification = document.getElementById('login-notification');
                if (notification) {
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateY(-10px)';
                    setTimeout(function() {
                        notification.style.display = 'none';
                    }, 300);
                }
            }, 5000);

            // Load dashboard data
            loadDashboardData();
        });

        function loadDashboardData() {
            console.log('Loading dashboard data...');

            // Try multiple API approaches
            tryLoadAPI('./php/get_total_records.php', 'Primary API')
                .catch(() => tryLoadAPI('php/get_total_records.php', 'Fallback API'))
                .catch(() => tryLoadAPI('../php/get_total_records.php', 'Alternative API'))
                .catch(error => {
                    console.error('All API attempts failed:', error);
                    document.getElementById('totalRecords').textContent = 'Server Error';
                });
        }

        function tryLoadAPI(apiUrl, apiName) {
            console.log(`Trying ${apiName}: ${apiUrl}`);

            return fetch(apiUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                console.log(`${apiName} Response:`, response.status, response.statusText);
                console.log(`${apiName} Content-Type:`, response.headers.get('content-type'));

                if (response.status === 404) {
                    throw new Error(`API not found: ${response.status} ${response.statusText}`);
                }

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }

                return response.json();
            })
            .then(data => {
                console.log(`${apiName} Data received:`, data);

                if (data && typeof data.total_students !== 'undefined') {
                    const count = data.total_students || 0;
                    document.getElementById('totalRecords').textContent = count;
                    console.log(`✅ Total records set to: ${count}`);
                } else if (data && typeof data.count !== 'undefined') {
                    // Handle different response format
                    const count = data.count || 0;
                    document.getElementById('totalRecords').textContent = count;
                    console.log(`✅ Total records set to: ${count} (alternative format)`);
                } else {
                    console.error(`❌ Invalid response format from ${apiName}:`, data);
                    document.getElementById('totalRecords').textContent = 'Format Error';
                }
            });
        }

        function showNoDataState() {
            document.getElementById('totalRecords').textContent = '0';
            console.log('No data state - showing 0 records');
        }

        function showErrorState(error) {
            document.getElementById('totalRecords').textContent = 'Error';
            console.error('Dashboard API Error:', error);
        }

        function testAPI() {
            console.log('Testing API with multiple paths...');

            // Create a detailed test
            const testDiv = document.createElement('div');
            testDiv.style.cssText = 'position:fixed;top:10px;right:10px;background:#f0f9ff;padding:15px;border:2px solid #0ea5e9;z-index:9999;font-family:monospace;font-size:11px;max-width:400px;max-height:400px;overflow:auto;border-radius:8px;';
            testDiv.innerHTML = '<div><strong>🔧 API Debug Test</strong></div><div>Trying multiple API paths...</div>';
            document.body.appendChild(testDiv);

            const apiPaths = [
                './php/get_total_records.php',
                'php/get_total_records.php',
                '../php/get_total_records.php',
                '/php/get_total_records.php'
            ];

            let completedTests = 0;

            apiPaths.forEach((path, index) => {
                setTimeout(() => {
                    testDiv.innerHTML += `<div><br>Testing path ${index + 1}: <code>${path}</code></div>`;

                    fetch(path)
                        .then(response => {
                            testDiv.innerHTML += `<div>  ✅ Status: ${response.status} ${response.statusText}</div>`;
                            testDiv.innerHTML += `<div>  📄 Content-Type: ${response.headers.get('content-type') || 'none'}</div>`;
                            return response.text();
                        })
                        .then(text => {
                            testDiv.innerHTML += `<div>  📨 Response: ${text.substring(0, 100)}${text.length > 100 ? '...' : ''}</div>`;
                            try {
                                const data = JSON.parse(text);
                                testDiv.innerHTML += `<div>  ✅ JSON OK: ${JSON.stringify(data).substring(0, 50)}...</div>`;
                                if (data.total_students !== undefined) {
                                    testDiv.innerHTML += `<div>  🎯 Found total_students: ${data.total_students}</div>`;
                                }
                            } catch (e) {
                                testDiv.innerHTML += `<div>  ❌ JSON Error: ${e.message}</div>`;
                            }
                        })
                        .catch(error => {
                            testDiv.innerHTML += `<div>  ❌ Error: ${error.message}</div>`;
                        })
                        .finally(() => {
                            completedTests++;
                            if (completedTests === apiPaths.length) {
                                testDiv.innerHTML += '<div><br>🏁 All tests completed</div>';
                            }
                        });
                }, index * 1000); // Stagger tests by 1 second
            });

            // Remove after 30 seconds
            setTimeout(() => {
                if (testDiv.parentNode) {
                    testDiv.parentNode.removeChild(testDiv);
                }
            }, 30000);
        }
    </script>
</body>

</html>
