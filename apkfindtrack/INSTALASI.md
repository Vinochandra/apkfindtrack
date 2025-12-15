# Panduan Instalasi FindTrack

## Langkah 1: Setup Database

1. Buka phpMyAdmin di browser: `http://localhost/phpmyadmin`
2. Klik tab "SQL"
3. Copy dan paste isi file `config/schema.sql`
4. Klik "Go" untuk menjalankan query
5. Database `apkfindtrack` akan dibuat beserta tabel-tabelnya

## Langkah 2: Buat User Admin

**Opsi A: Menggunakan Setup Script (Recommended)**
1. Buka browser dan akses: `http://localhost/findtrack/setup_admin.php`
2. User admin akan dibuat otomatis
3. **PENTING:** Hapus file `setup_admin.php` setelah selesai untuk keamanan

**Opsi B: Manual via phpMyAdmin**
1. Buka phpMyAdmin
2. Pilih database `apkfindtrack`
3. Klik tabel `users` > Insert
4. Isi data:
   - nama: Administrator
   - email: admin@findtrack.com
   - password: (gunakan password_hash PHP untuk 'admin123')
   - role: admin
5. Atau jalankan query:
```sql
INSERT INTO users (nama, email, password, role) 
VALUES ('Administrator', 'admin@findtrack.com', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcfl7p92ldGxad68LJZdL17lhWy', 'admin');
```

## Langkah 3: Akses Aplikasi

1. Buka browser: `http://localhost/findtrack/`
2. Login dengan:
   - **Email:** admin@findtrack.com
   - **Password:** admin123

## Troubleshooting

### Error: "Koneksi gagal"
- Pastikan MySQL/MariaDB sudah running
- Cek konfigurasi di `config/db.php`
- Pastikan database `apkfindtrack` sudah dibuat

### Error: "Table doesn't exist"
- Pastikan sudah menjalankan `schema.sql`
- Cek apakah tabel `users` dan `pengeluaran` sudah ada

### Password admin tidak bekerja
- Gunakan `setup_admin.php` untuk membuat ulang
- Atau reset password via phpMyAdmin

## Catatan Keamanan

- **Hapus** file `setup_admin.php` setelah setup selesai
- Ganti password admin default setelah login pertama kali
- Jangan commit file `config/db.php` ke repository publik jika berisi kredensial sensitif

