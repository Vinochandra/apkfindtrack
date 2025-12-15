-- Database Schema untuk FindTrack
-- Jalankan file ini di phpMyAdmin atau MySQL untuk membuat tabel

CREATE DATABASE IF NOT EXISTS `apkfindtrack` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `apkfindtrack`;

-- Tabel Users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Pengeluaran
CREATE TABLE IF NOT EXISTS `pengeluaran` (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert Admin Default (password: admin123)
-- Catatan: Jalankan setup_admin.php untuk membuat admin user dengan password hash yang benar
-- Atau gunakan query berikut setelah membuat user pertama kali:
-- INSERT INTO `users` (`nama`, `email`, `password`, `role`) VALUES
-- ('Administrator', 'admin@findtrack.com', '[GENERATE_PASSWORD_HASH]', 'admin');

