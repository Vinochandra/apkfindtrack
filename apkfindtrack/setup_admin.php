<?php
/**
 * Setup Admin User
 * Jalankan file ini sekali untuk membuat user admin default
 * Setelah selesai, hapus file ini untuk keamanan
 */

require_once 'config/db.php';

// Check if admin already exists
$check = mysqli_query($conn, "SELECT id FROM users WHERE email = 'admin@findtrack.com'");
if (mysqli_num_rows($check) > 0) {
    echo "Admin user sudah ada!<br>";
    echo "Email: admin@findtrack.com<br>";
    echo "Password: admin123<br>";
    exit();
}

// Create admin user
$nama = 'Administrator';
$email = 'admin@findtrack.com';
$password = 'admin123';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$role = 'admin';

$query = "INSERT INTO users (nama, email, password, role) VALUES ('$nama', '$email', '$hashed_password', '$role')";

if (mysqli_query($conn, $query)) {
    echo "Admin user berhasil dibuat!<br>";
    echo "Email: admin@findtrack.com<br>";
    echo "Password: admin123<br>";
    echo "<br><a href='login.php'>Login disini</a>";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>

