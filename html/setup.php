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
    <title>Database Setup - Results Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8">
        <h1 class="text-2xl font-bold text-slate-800 mb-6 text-center">Database Setup</h1>

        <div id="status" class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-md">
            <p class="text-blue-800">Setting up database...</p>
        </div>

        <div class="text-center">
            <button id="setupBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md transition duration-200">
                Initialize Database
            </button>
        </div>
    </div>
    <script>
        document.getElementById('setupBtn').addEventListener('click', function() {
            this.disabled = true;
            this.textContent = 'Setting up...';

            fetch('setup_database.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('status').innerHTML =
                        '<p class="text-green-800">' + data.replace(/\n/g, '<br>') + '</p>';

                    setTimeout(() => {
                        window.location.href = 'system_check.php';
                    }, 3000);
                })
                .catch(error => {
                    document.getElementById('status').innerHTML =
                        '<p class="text-red-800">Error: ' + error.message + '</p>';
                    document.getElementById('setupBtn').disabled = false;
                    document.getElementById('setupBtn').textContent = 'Retry';
                });
        });

        // Auto-setup on page load
        window.addEventListener('load', function() {
            document.getElementById('setupBtn').click();
        });
    </script>
</body>
</html>
