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
    <title>Quick Database Test - Results Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-lg shadow-md p-6">
        <h1 class="text-xl font-bold text-slate-800 mb-4 text-center">Database Connection Test</h1>

        <div id="testResults" class="space-y-3">
            <div class="p-3 border rounded">
                <p class="text-sm font-medium text-slate-800">Testing MySQL Connection...</p>
                <p id="mysqlTest" class="text-sm text-slate-600">Testing...</p>
            </div>

            <div class="p-3 border rounded">
                <p class="text-sm font-medium text-slate-800">Testing Database Creation...</p>
                <p id="dbTest" class="text-sm text-slate-600">Testing...</p>
            </div>

            <div class="p-3 border rounded">
                <p class="text-sm font-medium text-slate-800">Testing Sample Data...</p>
                <p id="dataTest" class="text-sm text-slate-600">Testing...</p>
            </div>
        </div>

        <div class="mt-4 text-center">
            <button onclick="runTests()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-200">
                Run Tests
            </button>
            <a href="setup.php" class="ml-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md transition duration-200">
                Full Setup
            </a>
        </div>
    </div>

    <script>
        async function runTests() {
            // Test MySQL connection
            try {
                const response = await fetch('php/test.php');
                const content = await response.text();
                if (content.includes('PHP Version')) {
                    document.getElementById('mysqlTest').innerHTML = '<span class="text-green-600">✓ PHP is working</span>';
                } else {
                    document.getElementById('mysqlTest').innerHTML = '<span class="text-red-600">✗ PHP issue detected</span>';
                }
            } catch (e) {
                document.getElementById('mysqlTest').innerHTML = '<span class="text-red-600">✗ Connection failed: ' + e.message + '</span>';
            }

            // Test database creation
            try {
                const response = await fetch('setup_database.php');
                const content = await response.text();
                if (content.includes('Database created') || content.includes('already exists')) {
                    document.getElementById('dbTest').innerHTML = '<span class="text-green-600">✓ Database setup working</span>';
                } else {
                    document.getElementById('dbTest').innerHTML = '<span class="text-red-600">✗ Database setup failed</span>';
                }
            } catch (e) {
                document.getElementById('dbTest').innerHTML = '<span class="text-red-600">✗ Setup failed: ' + e.message + '</span>';
            }

            // Test API
            try {
                const response = await fetch('php/get_dashboard_stats_fixed.php');
                const data = await response.json();
                if (data.total_students !== undefined) {
                    document.getElementById('dataTest').innerHTML = '<span class="text-green-600">✓ API working: ' + data.total_students + ' students</span>';
                } else if (data.error) {
                    document.getElementById('dataTest').innerHTML = '<span class="text-red-600">✗ API error: ' + data.error + '</span>';
                }
            } catch (e) {
                document.getElementById('dataTest').innerHTML = '<span class="text-red-600">✗ API failed: ' + e.message + '</span>';
            }
        }

        // Auto-run tests
        window.addEventListener('load', runTests);
    </script>
</body>
</html>
