<?php
$target_dir = "uploads/";
$test_file = $target_dir . "test.txt";

if (!is_dir($target_dir)) {
    echo "❌ ERROR: The 'uploads' folder does not exist.<br>";
} else {
    echo "✅ SUCCESS: The 'uploads' folder exists.<br>";
}

if (is_writable($target_dir)) {
    echo "✅ SUCCESS: The folder is writable.<br>";
    
    if (file_put_contents($test_file, "Test successful at " . date('Y-m-d H:i:s'))) {
        echo "✅ SUCCESS: A test file was created inside 'uploads/'.";
    } else {
        echo "❌ ERROR: Could not create file even though folder is writable.";
    }
} else {
    echo "❌ ERROR: The folder is NOT writable. Check permissions.";
}
?>