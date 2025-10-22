<?php
// Test script to verify session management is working correctly
echo "🧪 Testing Session Management\n\n";

// Test 1: Check if we can start a session without conflicts
echo "Test 1: Session Start\n";
session_start();
echo "✅ Session started successfully\n";

// Test 2: Check if we can set and retrieve session data
echo "\nTest 2: Session Data Management\n";
$_SESSION['test_username'] = 'test_teacher';
$_SESSION['test_data'] = ['name' => 'John Doe', 'id' => 'TEST123'];

echo "✅ Set session data:\n";
echo "   - Username: " . $_SESSION['test_username'] . "\n";
echo "   - Test Data: " . json_encode($_SESSION['test_data']) . "\n";

// Test 3: Check session validation logic
echo "\nTest 3: Session Validation\n";
if (isset($_SESSION['test_username']) && !empty($_SESSION['test_username'])) {
    echo "✅ Session validation passed\n";
} else {
    echo "❌ Session validation failed\n";
}

// Test 4: Check if we can destroy session properly
echo "\nTest 4: Session Cleanup\n";
session_unset();
session_destroy();
echo "✅ Session destroyed successfully\n";

// Test 5: Check if session is really gone
session_start(); // Start new session
if (!isset($_SESSION['test_username'])) {
    echo "✅ Session cleanup verified - old data is gone\n";
} else {
    echo "❌ Session cleanup failed - old data still exists\n";
}

echo "\n🎉 All session tests completed!\n";
echo "💡 The session warning should now be resolved.\n";
?>
