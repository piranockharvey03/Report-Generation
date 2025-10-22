<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard - Results Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="images/Education And College Logo Design Template Vector, School Logo, Education Logo, Institute Logo PNG and Vector with Transparent Background for Free Download.jpeg">
</head>

<body class="bg-slate-50 min-h-screen">
    <!-- Header -->
    <header class="bg-slate-800 text-white py-4">
        <div class="container mx-auto px-6 flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <img class="w-10 h-10" src="../images/Education And College Logo Design Template Vector, School Logo, Education Logo, Institute Logo PNG and Vector with Transparent Background for Free Download.jpeg" alt="school icon">
                <div>
                    <h1 class="text-xl font-bold">Results Management System</h1>
                    <p class="text-slate-300 text-sm">Teacher Dashboard</p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <a href="teacher_dashboard.php" class="bg-slate-600 hover:bg-slate-700 px-4 py-2 rounded-md transition duration-200">Dashboard</a>
                <a href="main.html" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-md transition duration-200">New Entry</a>
                <a href="change_marks.php" class="bg-purple-600 hover:bg-purple-700 px-4 py-2 rounded-md transition duration-200">Change Marks</a>
                <a href="generatereport.php" class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded-md transition duration-200">Reports</a>
                <a href="index.html" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-md transition duration-200">Logout</a>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-6 py-8">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-600">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-slate-600 text-sm">Total Records</p>
                        <p class="text-2xl font-bold text-slate-800" id="totalRecords">5</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-600">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-slate-600 text-sm">Average Score</p>
                        <p class="text-2xl font-bold text-slate-800" id="averageScore">82%</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-600">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-slate-600 text-sm">Pass Rate</p>
                        <p class="text-2xl font-bold text-slate-800" id="passRate">100%</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-600">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-slate-600 text-sm">This Term</p>
                        <p class="text-2xl font-bold text-slate-800" id="currentTerm">Term 1</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <!-- Left Column - Quick Actions -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-4 mb-4">
                    <h3 class="text-sm font-bold text-slate-800 mb-3">Quick Actions</h3>
                    <div class="space-y-2">
                        <a href="main.html" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-md transition duration-200 text-sm">
                            New Marks
                        </a>
                        <a href="change_marks.php" class="block w-full text-center bg-purple-600 hover:bg-purple-700 text-white px-3 py-2 rounded-md transition duration-200 text-sm">
                            Change Marks
                        </a>
                        <a href="generatereport.php" class="block w-full text-center bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-md transition duration-200 text-sm">
                            Reports
                        </a>
                    </div>
                </div>

            </div>

            <!-- Right Column - Statistics Only -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Performance Overview</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="text-center p-4 bg-slate-50 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600">5</div>
                            <div class="text-sm text-slate-600">Total Students</div>
                        </div>
                        <div class="text-center p-4 bg-slate-50 rounded-lg">
                            <div class="text-2xl font-bold text-green-600">82%</div>
                            <div class="text-sm text-slate-600">Average Score</div>
                        </div>
                        <div class="text-center p-4 bg-slate-50 rounded-lg">
                            <div class="text-2xl font-bold text-yellow-600">100%</div>
                            <div class="text-sm text-slate-600">Pass Rate</div>
                        </div>
                        <div class="text-center p-4 bg-slate-50 rounded-lg">
                            <div class="text-2xl font-bold text-purple-600">Term 1</div>
                            <div class="text-sm text-slate-600">Current Term</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Banner -->
    <div class="fixed bottom-4 left-4 bg-yellow-500 text-white px-6 py-3 rounded-md shadow-lg">
        <div class="flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
            <span class="text-sm">Demo Mode: Connect database for live data</span>
            <a href="system_check.php" class="ml-4 bg-yellow-600 hover:bg-yellow-700 px-3 py-1 rounded text-xs transition duration-200">
                Fix Setup
            </a>
        </div>
    </div>

    <script>
        // Initialize charts when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initializeCharts();
        });

        function initializeCharts() {
            // Charts removed - now showing statistics cards only
            console.log('Charts removed - using statistics cards instead');
        }

        function toggleCharts() {
            // No charts to toggle - functionality removed
            console.log('Chart toggle functionality removed');
        }
