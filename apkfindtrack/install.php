<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalasi FindTrack</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .card {
            background-color: #2a2a2a;
            border: 1px solid #3a3a3a;
            border-radius: 10px;
            padding: 40px;
            width: 100%;
            max-width: 600px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
            color: #ffffff;
        }
        h2 { color: #ffffff; margin-bottom: 30px; }
        .msg { padding: 12px; margin: 8px 0; background: #1a1a1a; border-radius: 5px; }
        .success { color: #75b798; }
        .error { color: #f1aeb5; }
        .info { color: #86cfda; }
        .btn { display: block; width: 100%; padding: 12px; background: #0d6efd; color: white; text-align: center; text-decoration: none; border-radius: 5px; margin-top: 20px; }
        .btn:hover { background: #0b5ed7; }
    </style>
</head>
<body>
    <div class="card">
        <h2>🔧 Instalasi FindTrack</h2>
        <?php
        // Koneksi tanpa database dulu
        $host = 'localhost';
        $username = 'root';
        $password = '';
        
        $conn = @mysqli_connect($host, $username, $password);
        
        $database = 'apkfindtrack';
        $success = true;
        $messages = [];
        
        if (!$conn) {
            $messages[] = "✗ Koneksi gagal: " . mysqli_connect_error();
            $success = false;
        } else {
            // 1. Buat database
            $query = "CREATE DATABASE IF NOT EXISTS `$database` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci";
            if (mysqli_query($conn, $query)) {
                $messages[] = "✓ Database '$database' berhasil dibuat";
            } else {
                $messages[] = "✗ Error membuat database: " . mysqli_error($conn);
                $success = false;
            }
            
            if ($success) {
                mysqli_select_db($conn, $database);
                mysqli_set_charset($conn, "utf8mb4");
                
                // 2. Buat tabel users
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
                    $messages[] = "✓ Tabel 'users' berhasil dibuat";
                } else {
                    $messages[] = "✗ Error membuat tabel users: " . mysqli_error($conn);
                    $success = false;
                }
                
                // 3. Buat tabel pengeluaran
                if ($success) {
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
                        $messages[] = "✓ Tabel 'pengeluaran' berhasil dibuat";
                    } else {
                        $messages[] = "✗ Error membuat tabel pengeluaran: " . mysqli_error($conn);
                        $success = false;
                    }
                    
                    // 4. Buat admin user
                    if ($success) {
                        $check_admin = mysqli_query($conn, "SELECT id FROM users WHERE email = 'admin@findtrack.com'");
                        if ($check_admin && mysqli_num_rows($check_admin) == 0) {
                            $nama = 'Administrator';
                            $email = 'admin@findtrack.com';
                            $password_hash = password_hash('admin123', PASSWORD_DEFAULT);
                            $role = 'admin';
                            
                            $query_admin = "INSERT INTO users (nama, email, password, role) VALUES ('$nama', '$email', '$password_hash', '$role')";
                            if (mysqli_query($conn, $query_admin)) {
                                $messages[] = "✓ User admin berhasil dibuat";
                                $messages[] = "  Email: admin@findtrack.com";
                                $messages[] = "  Password: admin123";
                            } else {
                                $messages[] = "✗ Error membuat user admin: " . mysqli_error($conn);
                                $success = false;
                            }
                        } else {
                            $messages[] = "ℹ User admin sudah ada";
                        }
                    }
                }
            }
            
            if ($conn) {
                mysqli_close($conn);
            }
        }
        
        // Tampilkan pesan
        foreach ($messages as $msg) {
            $class = 'info';
            if (strpos($msg, '✓') !== false) $class = 'success';
            if (strpos($msg, '✗') !== false) $class = 'error';
            echo '<div class="msg ' . $class . '">' . htmlspecialchars($msg) . '</div>';
        }
        
        if ($success) {
            echo '<div style="background: rgba(25,135,84,0.2); border-left: 4px solid #198754; padding: 15px; margin-top: 20px; border-radius: 5px;">';
            echo '<h3 style="color: #75b798; margin-bottom: 10px;">✓ Instalasi Berhasil!</h3>';
            echo '<p>Database dan tabel berhasil dibuat. Silakan lanjutkan ke halaman login.</p>';
            echo '</div>';
            echo '<a href="login.php" class="btn">Lanjut ke Login</a>';
        } else {
            echo '<div style="background: rgba(220,53,69,0.2); border-left: 4px solid #dc3545; padding: 15px; margin-top: 20px; border-radius: 5px;">';
            echo '<h3 style="color: #f1aeb5; margin-bottom: 10px;">✗ Instalasi Gagal!</h3>';
            echo '<p>Terjadi error. Pastikan MySQL sudah running di Laragon.</p>';
            echo '</div>';
        }
        ?>
        <div style="margin-top: 20px; text-align: center; color: #888; font-size: 12px;">
            <strong>Catatan:</strong> Setelah instalasi berhasil, hapus file install.php untuk keamanan.
        </div>
    </div>
</body>
</html>
