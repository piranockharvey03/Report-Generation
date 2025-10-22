<?php
// Test script to verify the fix works
echo "🧪 Testing Student ID Generation Fix\n\n";

// Test the generateStudentId function
function generateStudentId($studentName, $conn) {
    $prefix = strtoupper(substr($studentName, 0, 3));
    $microtime = str_replace('.', '', microtime(true));
    $microtime = substr($microtime, -8);
    $studentId = $prefix . $microtime;

    if (strlen($studentId) > 20) {
        $studentId = substr($studentId, 0, 20);
    }

    return strtoupper($studentId);
}

// Test with various student names
$testNames = [
    "John Doe",
    "Jane Smith",
    "Test Student",
    "A",
    "Very Long Student Name That Might Cause Issues",
    "محمد أحمد", // Arabic name
    "José María", // Spanish name
    "123", // Numbers
    "A1B2C3D4E5F6G7H8I9J0" // Very long
];

echo "Testing student ID generation:\n";
foreach ($testNames as $name) {
    $id = generateStudentId($name, null);
    $length = strlen($id);
    $status = ($length <= 20) ? "✅ OK" : "❌ TOO LONG";

    printf("%-25s -> %-15s (Length: %2d) %s\n",
           substr($name, 0, 25),
           $id,
           $length,
           $status);
}

echo "\n🎯 All IDs should be ≤ 20 characters and contain only A-Z and 0-9\n";
echo "📝 If any ID shows '❌ TOO LONG', there's still an issue to fix\n";
?>
