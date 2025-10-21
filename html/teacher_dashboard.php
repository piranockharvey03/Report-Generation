<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard - Results Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="images/Education And College Logo Design Template Vector, School Logo, Education Logo, Institute Logo PNG and Vector with Transparent Background for Free Download.jpeg">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-slate-50 min-h-screen">
    <!-- Header -->
        <header class="py-4">
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
                    <a href="main.html" class="bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-md transition duration-200">Enter Marks</a>
                    <a href="generatereport.php" class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded-md transition duration-200">Generate Reports</a>
                    <a href="index.html" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-md transition duration-200">Logout</a>
                </div>
            </div>
        </header>
    </div>
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-600">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-slate-600 text-sm">Total Records</p>
                        <p class="text-2xl font-bold text-slate-800" id="totalRecords">0</p>
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
                        <p class="text-2xl font-bold text-slate-800" id="averageScore">0%</p>
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
                        <p class="text-2xl font-bold text-slate-800" id="passRate">0%</p>
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
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Quick Actions -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="main.html" class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center py-3 px-4 rounded-md transition duration-200">
                            Enter New Marks
                        </a>
                        <a href="generatereport.php" class="block w-full bg-green-600 hover:bg-green-700 text-white text-center py-3 px-4 rounded-md transition duration-200">
                            Generate Reports
                        </a>
                        <a href="class_analytics.php" class="block w-full bg-yellow-600 hover:bg-yellow-700 text-white text-center py-3 px-4 rounded-md transition duration-200">
                            Class Analytics
                        </a>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Recent Activity</h3>
                    <div class="space-y-3" id="recentActivity">
                        <div class="flex items-center space-x-3 p-3 bg-slate-50 rounded-md">
                            <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                            <div class="flex-1">
                                <p class="text-sm text-slate-600">No recent activity</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Charts and Analytics -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Performance Overview</h3>
                    <canvas id="performanceChart" width="400" height="200"></canvas>
                </div>

                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Grade Distribution</h3>
                    <canvas id="gradeChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize charts when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadDashboardData();
        });

        function loadDashboardData() {
            // Fetch dashboard statistics
            fetch('get_dashboard_stats.php')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('totalRecords').textContent = data.total_students || 0; // Updated variable name
                    document.getElementById('averageScore').textContent = Math.round(data.average_score || 0) + '%';
                    document.getElementById('passRate').textContent = Math.round(data.pass_rate || 0) + '%';
                    document.getElementById('currentTerm').textContent = data.current_term || 'N/A';

                    // Update charts
                    updateCharts(data);
                })
                .catch(error => {
                    console.error('Error loading dashboard data:', error);
                });
        }

        function updateCharts(data) {
            // Performance Chart
            const performanceCtx = document.getElementById('performanceChart').getContext('2d');
            new Chart(performanceCtx, {
                type: 'line',
                data: {
                    labels: data.performance_labels || ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                    datasets: [{
                        label: 'Average Performance',
                        data: data.performance_data || [65, 70, 75, 80],
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100
                        }
                    }
                }
            });

            // Grade Distribution Chart
            const gradeCtx = document.getElementById('gradeChart').getContext('2d');
            new Chart(gradeCtx, {
                type: 'doughnut',
                data: {
                    labels: ['A (80-100%)', 'B (60-79%)', 'C (40-59%)', 'F (Below 40%)'],
                    datasets: [{
                        data: data.grade_distribution || [25, 35, 25, 15],
                        backgroundColor: [
                            'rgb(34, 197, 94)',
                            'rgb(245, 158, 11)',
                            'rgb(239, 68, 68)',
                            'rgb(107, 114, 128)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }
    </script>
</body>

</html>
