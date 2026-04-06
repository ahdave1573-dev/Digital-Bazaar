<?php
// Error Reporting (Live check karne ke liye ON karein, production me OFF rakhein)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Local vs Live Database Configuration
if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1') {
    // Local (XAMPP)
    $host = "localhost";
    $username = "root";
    $password = "";
    $database = "digitalbazaar";
} else {
    // Live (InfinityFree)
    $host = "sql203.infinityfree.com"; 
    $username = "if0_41567374";       // Real username detected from error
    $password = "YARYGpUtEf3kCe"; // IMPORTANT: Enter your InfinityFree MySQL password here
    $database = "if0_41567374_db";     // Your database name
}

// Connection Create Karna
$conn = mysqli_connect($host, $username, $password, $database);

// Connection Check Karna
if(!$conn) {
    die("<div style='background:#fee2e2; color:#b91c1c; padding:20px; border-radius:10px; font-family:sans-serif; margin:20px;'>
            <h3>⚠️ Database Connection Failed</h3>
            <p>Error: " . mysqli_connect_error() . "</p>
            <hr>
            <p><strong>Note:</strong> If you are on a live server (InfinityFree), please update <code>db.php</code> with your live database credentials.</p>
         </div>");
}
?>
