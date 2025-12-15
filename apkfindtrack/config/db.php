<?php
// Koneksi Database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'apkfindtrack';

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");

// Ensure approval columns exist on pengeluaran table
$checkCols = mysqli_query($conn, "SHOW COLUMNS FROM pengeluaran LIKE 'approved'");
if ($checkCols && mysqli_num_rows($checkCols) === 0) {
    // Add columns for approval workflow
    mysqli_query($conn, "ALTER TABLE pengeluaran ADD COLUMN approved TINYINT(1) NOT NULL DEFAULT 0 AFTER 
    nilai");
    mysqli_query($conn, "ALTER TABLE pengeluaran ADD COLUMN approved_at DATETIME NULL AFTER approved");
    mysqli_query($conn, "ALTER TABLE pengeluaran ADD COLUMN approved_by INT NULL AFTER approved_at");
}
unset($checkCols);
?>

