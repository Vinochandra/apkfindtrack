<?php
// Simple test untuk cek apakah PHP berjalan
echo "<h1>Test PHP</h1>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>Jika Anda melihat ini, berarti PHP berjalan dengan baik.</p>";

// Test koneksi database
$host = 'localhost';
$username = 'root';
$password = '';

echo "<h2>Test Koneksi Database</h2>";

$conn = @mysqli_connect($host, $username, $password);

if ($conn) {
    echo "<p style='color: green;'>✓ Koneksi MySQL berhasil!</p>";
    
    // Test buat database
    $database = 'apkfindtrack';
    $query = "CREATE DATABASE IF NOT EXISTS `$database` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
    
    if (mysqli_query($conn, $query)) {
        echo "<p style='color: green;'>✓ Database '$database' berhasil dibuat/ditemukan</p>";
        
        mysqli_select_db($conn, $database);
        
        // Test buat tabel users
        $query_users = "CREATE TABLE IF NOT EXISTS `users` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `nama` varchar(100) NOT NULL,
          `email` varchar(100) NOT NULL,
          `password` varchar(255) NOT NULL,
          `role` enum('admin','user') NOT NULL DEFAULT 'user',
          `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`),
          UNIQUE KEY `email` (`email`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        if (mysqli_query($conn, $query_users)) {
            echo "<p style='color: green;'>✓ Tabel 'users' berhasil dibuat</p>";
        } else {
            echo "<p style='color: red;'>✗ Error membuat tabel users: " . mysqli_error($conn) . "</p>";
        }
        
        // Test buat tabel pengeluaran
        $query_pengeluaran = "CREATE TABLE IF NOT EXISTS `pengeluaran` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `user_id` int(11) NOT NULL,
          `tanggal` date NOT NULL,
          `nopol` varchar(20) NOT NULL,
          `km` int(11) NOT NULL,
          `kegiatan` text NOT NULL,
          `nilai` decimal(15,2) NOT NULL,
          `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
          `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
          PRIMARY KEY (`id`),
          KEY `user_id` (`user_id`),
          CONSTRAINT `pengeluaran_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        if (mysqli_query($conn, $query_pengeluaran)) {
            echo "<p style='color: green;'>✓ Tabel 'pengeluaran' berhasil dibuat</p>";
        } else {
            echo "<p style='color: red;'>✗ Error membuat tabel pengeluaran: " . mysqli_error($conn) . "</p>";
        }
        
        // Cek dan buat admin
        $check_admin = mysqli_query($conn, "SELECT id FROM users WHERE email = 'admin@findtrack.com'");
        if ($check_admin && mysqli_num_rows($check_admin) == 0) {
            $nama = 'Administrator';
            $email = 'admin@findtrack.com';
            $password_hash = password_hash('admin123', PASSWORD_DEFAULT);
            $role = 'admin';
            
            $query_admin = "INSERT INTO users (nama, email, password, role) VALUES ('$nama', '$email', '$password_hash', '$role')";
            if (mysqli_query($conn, $query_admin)) {
                echo "<p style='color: green;'>✓ User admin berhasil dibuat</p>";
                echo "<p><strong>Email:</strong> admin@findtrack.com</p>";
                echo "<p><strong>Password:</strong> admin123</p>";
            } else {
                echo "<p style='color: red;'>✗ Error membuat user admin: " . mysqli_error($conn) . "</p>";
            }
        } else {
            echo "<p style='color: blue;'>ℹ User admin sudah ada</p>";
        }
        
    } else {
        echo "<p style='color: red;'>✗ Error membuat database: " . mysqli_error($conn) . "</p>";
    }
    
    mysqli_close($conn);
} else {
    echo "<p style='color: red;'>✗ Koneksi MySQL gagal: " . mysqli_connect_error() . "</p>";
}

echo "<hr>";
echo "<p><a href='login.php'>→ Lanjut ke Login</a></p>";
?>

