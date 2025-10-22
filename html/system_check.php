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
    <title>System Check - Results Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center">
    <div class="max-w-2xl w-full bg-white rounded-lg shadow-md p-8">
        <h1 class="text-2xl font-bold text-slate-800 mb-6 text-center">System Check</h1>

        <div id="checks" class="space-y-4">
            <div class="p-4 border rounded-lg">
                <h3 class="font-semibold text-slate-800 mb-2">Checking PHP...</h3>
                <div id="phpCheck" class="text-sm">Checking...</div>
            </div>

            <div class="p-4 border rounded-lg">
                <h3 class="font-semibold text-slate-800 mb-2">Checking Database...</h3>
                <div id="dbCheck" class="text-sm">Checking...</div>
            </div>

            <div class="p-4 border rounded-lg">
                <h3 class="font-semibold text-slate-800 mb-2">Dashboard Status</h3>
                <div id="dashboardCheck" class="text-sm">Checking...</div>
            </div>

            <div class="p-4 border rounded-lg">
                <h3 class="font-semibold text-slate-800 mb-2">Setup Guide</h3>
                <div class="text-sm">
                    <a href="../PHP_SETUP_GUIDE.md" target="_blank" class="text-blue-600 hover:text-blue-800 underline">
                        📖 Complete PHP Setup Guide
                    </a>
                </div>
            </div>
        <div class="mt-6 text-center">
            <button onclick="runChecks()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md transition duration-200">
                Run All Checks
            </button>
            <a href="test_db.php" class="ml-4 bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-md transition duration-200">
                Quick Database Test
            </a>
            <a href="../fix_database.php" target="_blank" class="ml-4 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-md transition duration-200">
                Fix Database Now
            </a>
        </div>

        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-md">
            <h3 class="font-semibold text-blue-800 mb-2">🚀 Quick Fix Instructions:</h3>
            <ol class="text-sm text-blue-700 space-y-1">
                <li>1. Open XAMPP Control Panel</li>
                <li>2. Click "Start" next to Apache (if not green)</li>
                <li>3. Click "Start" next to MySQL (if not green)</li>
                <li>4. Click "Fix Database Now" above</li>
                <li>5. Refresh this page to check status</li>
            </ol>
        </div>
    </div>

    <script>
        async function runChecks() {
            try {
                const phpResponse = await fetch('php/test.php');
                const phpText = await phpResponse.text();
                document.getElementById('phpCheck').innerHTML = '<span class="text-green-600">✓ PHP is working</span>';
            } catch (e) {
                document.getElementById('phpCheck').innerHTML = '<span class="text-red-600">✗ PHP Error: ' + e.message + '</span>';
            }

            // Check Database
            try {
                const dbResponse = await fetch('../fix_database.php');
                const dbContent = await dbResponse.text();
                if (dbContent.includes('✅') && dbContent.includes('Database')) {
                    document.getElementById('dbCheck').innerHTML = '<span class="text-green-600">✓ Database working! Check fix_database.php output</span>';
                } else if (dbContent.includes('❌')) {
                    document.getElementById('dbCheck').innerHTML = '<span class="text-red-600">✗ Database issues found. Check fix_database.php</span>';
                } else {
                    document.getElementById('dbCheck').innerHTML = '<span class="text-yellow-600">⚠ Check fix_database.php for details</span>';
                }
            } catch (e) {
                document.getElementById('dbCheck').innerHTML = '<span class="text-red-600">✗ Database Connection Failed: ' + e.message + '</span>';
            }

            // Check Dashboard
            try {
                const dashboardResponse = await fetch('teacher_dashboard.php');
                const dashboardContent = await dashboardResponse.text();
                if (dashboardContent.includes('Chart.js') && dashboardContent.includes('loadDashboardData')) {
                    document.getElementById('dashboardCheck').innerHTML = '<span class="text-green-600">✓ Dashboard interface working</span>';
                } else {
                    document.getElementById('dashboardCheck').innerHTML = '<span class="text-yellow-600">⚠ Dashboard accessible but may have issues</span>';
                }
            } catch (e) {
                document.getElementById('dashboardCheck').innerHTML = '<span class="text-red-600">✗ Dashboard not accessible: ' + e.message + '</span>';
            }
        }

        // Auto-run checks on page load
        window.addEventListener('load', runChecks);
    </script>
</body>
</html>
