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
    <title>Admin Panel - Results Management System</title>
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
                        <h1 class="text-lg sm:text-xl font-bold">Admin Panel</h1>
                        <p class="text-slate-300 text-xs sm:text-sm">Results Management System</p>
                    </div>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-2">
                    <a href="teacher_dashboard.php" class="bg-slate-600 hover:bg-slate-700 px-3 py-2 rounded-md transition duration-200 text-sm">Teacher Dashboard</a>
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
                    <a href="teacher_dashboard.php" class="block bg-slate-600 hover:bg-slate-700 px-3 py-2 rounded-md transition duration-200 text-sm text-center">Teacher Dashboard</a>
                    <a href="../php/logout.php" class="block bg-red-600 hover:bg-red-700 px-3 py-2 rounded-md transition duration-200 text-sm text-center">Logout</a>
                </div>
            </div>
        </header>
    </div>

    <div class="container mx-auto px-4 sm:px-6 py-6 sm:py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8">
            <!-- System Management -->
            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                <h3 class="text-base sm:text-lg font-bold text-slate-800 mb-4">System Management</h3>
                <div class="space-y-3">
                    <button onclick="showSection('user-management')" class="w-full bg-blue-600 hover:bg-blue-700 text-white text-left p-3 sm:p-4 rounded-md transition duration-200 text-sm sm:text-base font-medium">
                        User Management
                    </button>
                    <button onclick="showSection('database-management')" class="w-full bg-green-600 hover:bg-green-700 text-white text-left p-3 sm:p-4 rounded-md transition duration-200 text-sm sm:text-base font-medium">
                        Database Management
                    </button>
                    <button onclick="showSection('system-settings')" class="w-full bg-purple-600 hover:bg-purple-700 text-white text-left p-3 sm:p-4 rounded-md transition duration-200 text-sm sm:text-base font-medium">
                        System Settings
                    </button>
                    <button onclick="showSection('backup-restore')" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white text-left p-3 sm:p-4 rounded-md transition duration-200 text-sm sm:text-base font-medium">
                        Backup & Restore
                    </button>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                <h3 class="text-base sm:text-lg font-bold text-slate-800 mb-4">System Overview</h3>
                <div class="grid grid-cols-2 gap-3 sm:gap-4">
                    <div class="text-center p-3 sm:p-4 bg-slate-50 rounded-lg">
                        <p class="text-xl sm:text-2xl font-bold text-blue-600" id="totalRecords">0</p>
                        <p class="text-slate-600 text-xs sm:text-sm">Total Records</p>
                    </div>
                    <div class="text-center p-3 sm:p-4 bg-slate-50 rounded-lg">
                        <p class="text-xl sm:text-2xl font-bold text-green-600" id="activeTeachers">0</p>
                        <p class="text-slate-600 text-xs sm:text-sm">Active Teachers</p>
                    </div>
                    <div class="text-center p-3 sm:p-4 bg-slate-50 rounded-lg">
                        <p class="text-xl sm:text-2xl font-bold text-purple-600" id="systemHealth">Good</p>
                        <p class="text-slate-600 text-xs sm:text-sm">System Health</p>
                    </div>
                    <div class="text-center p-3 sm:p-4 bg-slate-50 rounded-lg">
                        <p class="text-xl sm:text-2xl font-bold text-yellow-600" id="lastBackup">Never</p>
                        <p class="text-slate-600 text-xs sm:text-sm">Last Backup</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Management Sections -->
        <div id="user-management" class="hidden mt-6 sm:mt-8">
            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                <h3 class="text-base sm:text-lg font-bold text-slate-800 mb-4">User Management</h3>
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 mb-6">
                    <button onclick="showUserForm()" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 sm:px-4 sm:py-2 rounded-md transition duration-200 text-sm sm:text-base font-medium">
                        Add New Teacher
                    </button>
                    <button onclick="viewAllUsers()" class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-4 py-2 sm:px-4 sm:py-2 rounded-md transition duration-200 text-sm sm:text-base font-medium">
                        View All Users
                    </button>
                </div>

                <div id="user-form" class="hidden">
                    <form method="POST" action="../php/admin_actions.php" class="space-y-4">
                        <input type="hidden" name="action" value="add_user">
                        <div>
                            <label class="block text-slate-700 text-sm font-semibold mb-2">Username</label>
                            <input type="text" name="username" required class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-md border border-slate-300 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition duration-200 outline-none text-sm sm:text-base">
                        </div>
                        <div>
                            <label class="block text-slate-700 text-sm font-semibold mb-2">Password</label>
                            <input type="password" name="password" required class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-md border border-slate-300 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition duration-200 outline-none text-sm sm:text-base">
                        </div>
                        <div>
                            <label class="block text-slate-700 text-sm font-semibold mb-2">Teacher Name</label>
                            <input type="text" name="teacher_name" required class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-md border border-slate-300 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition duration-200 outline-none text-sm sm:text-base">
                        </div>
                        <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-md transition duration-200 text-sm sm:text-base font-medium">
                            Add Teacher
                        </button>
                    </form>
                </div>

                <div id="users-list" class="hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full bg-white border border-slate-200 rounded-lg">
                            <thead class="bg-slate-100">
                                <tr>
                                    <th class="px-3 sm:px-4 py-2 sm:py-3 text-left text-slate-800 font-semibold text-xs sm:text-sm">Username</th>
                                    <th class="px-3 sm:px-4 py-2 sm:py-3 text-left text-slate-800 font-semibold text-xs sm:text-sm">Teacher Name</th>
                                    <th class="px-3 sm:px-4 py-2 sm:py-3 text-left text-slate-800 font-semibold text-xs sm:text-sm">Last Login</th>
                                    <th class="px-3 sm:px-4 py-2 sm:py-3 text-left text-slate-800 font-semibold text-xs sm:text-sm">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="users-table-body">
                                <!-- Users will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div id="database-management" class="hidden mt-6 sm:mt-8">
            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                <h3 class="text-base sm:text-lg font-bold text-slate-800 mb-4">Database Management</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                    <button onclick="optimizeDatabase()" class="w-full bg-blue-600 hover:bg-blue-700 text-white p-3 sm:p-4 rounded-md transition duration-200 text-sm sm:text-base font-medium">
                        Optimize Database
                    </button>
                    <button onclick="clearOldRecords()" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white p-3 sm:p-4 rounded-md transition duration-200 text-sm sm:text-base font-medium">
                        Clear Old Records
                    </button>
                    <button onclick="checkIntegrity()" class="w-full bg-green-600 hover:bg-green-700 text-white p-3 sm:p-4 rounded-md transition duration-200 text-sm sm:text-base font-medium">
                        Check Data Integrity
                    </button>
                </div>
            </div>
        </div>

        <div id="system-settings" class="hidden mt-6 sm:mt-8">
            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                <h3 class="text-base sm:text-lg font-bold text-slate-800 mb-4">System Settings</h3>
                <form class="space-y-4">
                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2">School Name</label>
                        <input type="text" value="The International Academy" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-md border border-slate-300 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition duration-200 outline-none text-sm sm:text-base">
                    </div>
                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2">Academic Year</label>
                        <select class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-md border border-slate-300 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition duration-200 outline-none text-sm sm:text-base">
                            <option>2024-2025</option>
                            <option>2025-2026</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-slate-700 text-sm font-semibold mb-2">Pass Mark (%)</label>
                        <input type="number" value="40" min="0" max="100" class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-md border border-slate-300 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition duration-200 outline-none text-sm sm:text-base">
                    </div>
                    <button type="submit" class="w-full sm:w-auto bg-blue-600 hover:bg-blue-700 text-white px-4 sm:px-6 py-2 sm:py-3 rounded-md transition duration-200 text-sm sm:text-base font-medium">
                        Save Settings
                    </button>
                </form>
            </div>
        </div>

        <div id="backup-restore" class="hidden mt-6 sm:mt-8">
            <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                <h3 class="text-base sm:text-lg font-bold text-slate-800 mb-4">Backup & Restore</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                    <button onclick="createBackup()" class="w-full bg-green-600 hover:bg-green-700 text-white p-3 sm:p-4 rounded-md transition duration-200 text-sm sm:text-base font-medium">
                        Create Full Backup
                    </button>
                    <button onclick="restoreFromBackup()" class="w-full bg-blue-600 hover:bg-blue-700 text-white p-3 sm:p-4 rounded-md transition duration-200 text-sm sm:text-base font-medium">
                        Restore from Backup
                    </button>
                </div>
                <div class="mt-4">
                    <p class="text-slate-600 text-xs sm:text-sm">Last backup: <span id="lastBackupDate">Never</span></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showSection(sectionId) {
            // Hide all sections
            document.getElementById('user-management').classList.add('hidden');
            document.getElementById('database-management').classList.add('hidden');
            document.getElementById('system-settings').classList.add('hidden');
            document.getElementById('backup-restore').classList.add('hidden');

            // Show selected section
            document.getElementById(sectionId).classList.remove('hidden');
        }

        function showUserForm() {
            document.getElementById('user-form').classList.remove('hidden');
            document.getElementById('users-list').classList.add('hidden');
        }

        function viewAllUsers() {
            document.getElementById('users-list').classList.remove('hidden');
            document.getElementById('user-form').classList.add('hidden');

            // Load users
            fetch('../php/get_users.php')
                .then(response => response.json())
                .then(users => {
                    const tbody = document.getElementById('users-table-body');
                    tbody.innerHTML = '';

                    users.forEach(user => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="px-4 py-3 text-slate-700">${user.username}</td>
                            <td class="px-4 py-3 text-slate-700">${user.teacher_name || 'N/A'}</td>
                            <td class="px-4 py-3 text-slate-700">${user.last_login || 'Never'}</td>
                            <td class="px-4 py-3">
                                <button onclick="editUser('${user.username}')" class="bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded text-sm mr-2">Edit</button>
                                <button onclick="deleteUser('${user.username}')" class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded text-sm">Delete</button>
                            </td>
                        `;
                        tbody.appendChild(row);
                    });
                });
        }

        // Load initial stats
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

            loadSystemStats();
        });

        function loadSystemStats() {
            fetch('../php/get_system_stats.php')
                .then(response => response.json())
                .then(stats => {
                    document.getElementById('totalRecords').textContent = stats.total_records || 0;
                    document.getElementById('activeTeachers').textContent = stats.active_teachers || 0;
                    document.getElementById('lastBackup').textContent = stats.last_backup || 'Never';
                });
        }

        function createBackup() {
            alert('Backup functionality would be implemented here');
        }

        function restoreFromBackup() {
            alert('Restore functionality would be implemented here');
        }

        function optimizeDatabase() {
            alert('Database optimization would be implemented here');
        }

        function clearOldRecords() {
            if (confirm('Are you sure you want to clear old records? This action cannot be undone.')) {
                alert('Clear old records functionality would be implemented here');
            }
        }

        function checkIntegrity() {
            alert('Data integrity check would be implemented here');
        }
    </script>
</body>

</html>
