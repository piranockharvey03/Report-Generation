<?php
// Test script to verify logout functionality
echo "Testing logout functionality...\n";

// Start a session and set a test variable
session_start();
$_SESSION['username'] = 'test_teacher';
$_SESSION['test_value'] = 'should_be_destroyed';

echo "Before logout:\n";
echo "- Session username: " . ($_SESSION['username'] ?? 'NOT SET') . "\n";
echo "- Session test_value: " . ($_SESSION['test_value'] ?? 'NOT SET') . "\n";

// Include the logout script (this will destroy the session and redirect)
require_once 'logout.php';

// This code should never execute due to the redirect
echo "After logout (this should not print):\n";
echo "- Session username: " . ($_SESSION['username'] ?? 'NOT SET') . "\n";
echo "- Session test_value: " . ($_SESSION['test_value'] ?? 'NOT SET') . "\n";
?>
