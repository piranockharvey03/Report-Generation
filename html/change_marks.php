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
    <title>Change Student Marks - Results Management System</title>
    <link rel="icon" type="image/x-icon" href="images/Education And College Logo Design Template Vector, School Logo, Education Logo, Institute Logo PNG and Vector with Transparent Background for Free Download.jpeg">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-slate-50 min-h-screen">
    <!-- Header -->
    <header class="bg-slate-800 text-white shadow-lg py-4">
        <div class="container mx-auto px-6">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <img class="w-10 h-10" src="../images/Education And College Logo Design Template Vector, School Logo, Education Logo, Institute Logo PNG and Vector with Transparent Background for Free Download.jpeg" alt="school icon">
                    <div>
                        <h1 class="text-xl font-bold">Results Management System</h1>
                        <p class="text-slate-300 text-sm">Change Student Marks</p>
                    </div>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-2">
                    <a href="teacher_dashboard.php" class="bg-slate-600 hover:bg-slate-700 px-3 py-2 rounded-md transition duration-200 text-sm">Dashboard</a>
                    <a href="main.html" class="bg-blue-600 hover:bg-blue-700 px-3 py-2 rounded-md transition duration-200 text-sm">New Entry</a>
                    <a href="generatereport.php" class="bg-green-600 hover:bg-green-700 px-3 py-2 rounded-md transition duration-200 text-sm">Reports</a>
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
                    <a href="main.html" class="block bg-blue-600 hover:bg-blue-700 px-3 py-2 rounded-md transition duration-200 text-sm text-center">New Entry</a>
                    <a href="generatereport.php" class="block bg-green-600 hover:bg-green-700 px-3 py-2 rounded-md transition duration-200 text-sm text-center">Reports</a>
                    <a href="index.html" class="block bg-red-600 hover:bg-red-700 px-3 py-2 rounded-md transition duration-200 text-sm text-center">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <div class="container mx-auto px-6 py-8">
        <main class="max-w-4xl mx-auto">
            <!-- Search Section -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-2xl font-bold text-slate-800 mb-4">Search Student</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="searchType" class="block text-slate-700 text-sm font-semibold mb-2">Search By:</label>
                        <select id="searchType" class="w-full px-4 py-2 rounded-md border border-slate-300 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition duration-200 outline-none">
                            <option value="admission">Admission Number</option>
                            <option value="name">Student Name</option>
                            <option value="class">Class</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label for="searchQuery" class="block text-slate-700 text-sm font-semibold mb-2">Search:</label>
                        <div class="flex">
                            <input type="text" id="searchQuery" placeholder="Enter search term..." class="w-full px-4 py-2 rounded-l-md border border-slate-300 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition duration-200 outline-none">
                            <button id="searchButton" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-r-md transition duration-200">
                                Search
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Results Section -->
            <div id="searchResults" class="hidden bg-white rounded-lg shadow-md p-6 mb-8">
                <h3 class="text-lg font-bold text-slate-800 mb-4">Search Results</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Adm No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Class</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Term</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Year</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="resultsBody" class="bg-white divide-y divide-slate-200">
                            <!-- Results will be populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Edit Form (initially hidden) -->
            <div id="editForm" class="hidden bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-slate-800 mb-6">Update Student Marks</h2>
                <div id="studentInfo" class="mb-6 p-4 bg-slate-50 rounded-md">
                    <!-- Student info will be populated here -->
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Subjects will be populated by JavaScript -->
                </div>
                
                <div class="mt-8 flex justify-end space-x-4">
                    <button id="cancelEdit" class="px-6 py-2 border border-slate-300 rounded-md text-slate-700 hover:bg-slate-100 transition duration-200">
                        Cancel
                    </button>
                    <button id="saveChanges" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">
                        Save Changes
                    </button>
                </div>
            </div>
        </main>
    </div>

    <script>
        $(document).ready(function() {
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
            $('#searchButton').click(function() {
                const searchType = $('#searchType').val();
                const searchQuery = $('#searchQuery').val().trim();
                
                if (!searchQuery) {
                    alert('Please enter a search term');
                    return;
                }
                
                // Show loading state
                $('#searchResults').removeClass('hidden');
                $('#resultsBody').html('<tr><td colspan="6" class="px-6 py-4 text-center">Searching...</td></tr>');
                
                // Simulate API call (replace with actual API call)
                setTimeout(() => {
                    // This is a mock response - replace with actual API call
                    const mockData = [
                        {
                            id: 1,
                            admission_no: 'ADM001',
                            name: 'John Doe',
                            class: 'Form 1',
                            term: 'Term 1',
                            year: '2025'
                        },
                        // Add more mock data as needed
                    ];
                    
                    if (mockData.length > 0) {
                        let rows = '';
                        mockData.forEach(student => {
                            rows += `
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">${student.admission_no}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">${student.name}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">${student.class}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">${student.term}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">${student.year}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <button class="text-blue-600 hover:text-blue-900 edit-student" data-id="${student.id}">Edit Marks</button>
                                    </td>
                                </tr>
                            `;
                        });
                        $('#resultsBody').html(rows);
                    } else {
                        $('#resultsBody').html('<tr><td colspan="6" class="px-6 py-4 text-center">No results found</td></tr>');
                    }
                }, 500);
            });
            
            // Handle edit button click
            $(document).on('click', '.edit-student', function() {
                const studentId = $(this).data('id');
                
                // In a real implementation, fetch the student's data and marks here
                $('#studentInfo').html(`
                    <h3 class="font-semibold text-lg">John Doe (ADM001)</h3>
                    <p class="text-slate-600">Form 1 - Term 1, 2025</p>
                `);
                
                // Mock subjects and marks
                const subjects = [
                    { id: 1, name: 'Mathematics', mark: 85 },
                    { id: 2, name: 'English', mark: 78 },
                    { id: 3, name: 'Kiswahili', mark: 90 },
                    { id: 4, name: 'Physics', mark: 82 },
                    { id: 5, name: 'Chemistry', mark: 88 },
                    { id: 6, name: 'Biology', mark: 91 },
                    { id: 7, name: 'History', mark: 75 },
                    { id: 8, name: 'Geography', mark: 80 }
                ];
                
                let subjectInputs = '';
                subjects.forEach(subject => {
                    subjectInputs += `
                        <div class="mb-4">
                            <label for="subject-${subject.id}" class="block text-slate-700 text-sm font-semibold mb-2">
                                ${subject.name}:
                            </label>
                            <input type="number" id="subject-${subject.id}" value="${subject.mark}" 
                                   class="w-full px-4 py-2 rounded-md border border-slate-300 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition duration-200 outline-none"
                                   min="0" max="100">
                        </div>
                    `;
                });
                
                $('.grid').html(subjectInputs);
                
                // Show the edit form and hide search results
                $('#searchResults').addClass('hidden');
                $('#editForm').removeClass('hidden');
                
                // Scroll to the form
                $('html, body').animate({
                    scrollTop: $('#editForm').offset().top - 20
                }, 500);
            });
            
            // Handle cancel button
            $('#cancelEdit').click(function() {
                $('#editForm').addClass('hidden');
                $('#searchResults').removeClass('hidden');
            });
            
            // Handle save changes
            $('#saveChanges').click(function() {
                if (confirm('Are you sure you want to update these marks?')) {
                    // In a real implementation, collect all the marks and send to the server
                    alert('Marks updated successfully!');
                    $('#editForm').addClass('hidden');
                    $('#searchQuery').val('');
                    $('#searchResults').addClass('hidden');
                }
            });
            
            // Allow pressing Enter in search field
            $('#searchQuery').keypress(function(e) {
                if (e.which === 13) {
                    $('#searchButton').click();
                }
            });
        });
    </script>
</body>
</html>
