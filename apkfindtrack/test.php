<?php
// Test file untuk cek koneksi dan path
require_once 'config/db.php';
require_once 'config/base_url.php';

echo "<h1>Test FindTrack</h1>";
echo "<p><strong>BASE_URL:</strong> " . BASE_URL . "</p>";
echo "<p><strong>BASE_PATH:</strong> " . BASE_PATH . "</p>";

if ($conn) {
    echo "<p style='color: green;'><strong>✓ Database Connected!</strong></p>";
    echo "<p>Database: apkfindtrack</p>";
    
    // Test query
    $test = mysqli_query($conn, "SHOW TABLES");
    if ($test) {
        echo "<p><strong>Tables:</strong></p><ul>";
        while ($row = mysqli_fetch_array($test)) {
            echo "<li>" . $row[0] . "</li>";
        }
        echo "</ul>";
    }
} else {
    echo "<p style='color: red;'><strong>✗ Database Connection Failed!</strong></p>";
    echo "<p>Error: " . mysqli_connect_error() . "</p>";
}

echo "<hr>";
echo "<p><a href='login.php'>Go to Login</a></p>";
echo "<p><a href='setup_admin.php'>Setup Admin</a></p>";
?>

