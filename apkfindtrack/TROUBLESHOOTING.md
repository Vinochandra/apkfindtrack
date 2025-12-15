# Troubleshooting FindTrack

## Error: "localhost refused to connect" atau ERR_CONNECTION_REFUSED

### Solusi 1: Pastikan Laragon/Apache Running
1. Buka Laragon
2. Klik tombol "Start All"
3. Pastikan Apache dan MySQL berstatus "Running" (hijau)
4. Coba akses lagi: `http://localhost/apkfindtrack/`

### Solusi 2: Cek Port Apache
- Default Laragon menggunakan port 80
- Jika port 80 digunakan aplikasi lain, ubah port di Laragon Settings
- Atau akses dengan port yang berbeda: `http://localhost:8080/apkfindtrack/`

### Solusi 3: Test Koneksi
1. Akses file test: `http://localhost/apkfindtrack/test.php`
2. File ini akan menampilkan:
   - Status koneksi database
   - BASE_URL yang terdeteksi
   - Daftar tabel di database

## Error: "Koneksi gagal" atau Database Error

### Solusi 1: Pastikan Database Sudah Dibuat
1. Buka phpMyAdmin: `http://localhost/phpmyadmin`
2. Pastikan database `apkfindtrack` sudah ada
3. Jika belum, import file `config/schema.sql`

### Solusi 2: Cek Konfigurasi Database
Buka file `config/db.php` dan pastikan:
```php
$host = 'localhost';
$username = 'root';
$password = '';  // Sesuaikan jika ada password
$database = 'apkfindtrack';
```

### Solusi 3: Buat User Admin
1. Akses: `http://localhost/apkfindtrack/setup_admin.php`
2. Atau buat manual via phpMyAdmin

## Error: CSS/JS Tidak Load

### Solusi: Cek BASE_URL
1. Akses `test.php` untuk melihat BASE_URL yang terdeteksi
2. Jika BASE_URL salah, edit file `config/base_url.php`
3. Atau gunakan path absolut di browser untuk test

## Error: "Table doesn't exist"

### Solusi: Import Schema Database
1. Buka phpMyAdmin
2. Pilih database `apkfindtrack`
3. Klik tab "SQL"
4. Copy isi file `config/schema.sql`
5. Paste dan klik "Go"

## Error: "Access Denied" atau Redirect Loop

### Solusi: Cek Session
1. Hapus semua cookie browser untuk localhost
2. Atau gunakan mode incognito/private browsing
3. Pastikan `session_start()` hanya dipanggil sekali

## Tips

1. **Selalu cek error log:**
   - Laragon: Klik "Logs" > "Apache Error Log"
   - Atau cek file: `C:\laragon\bin\apache\logs\error.log`

2. **Test file per file:**
   - Mulai dari `test.php`
   - Lalu `login.php`
   - Baru ke halaman lain

3. **Cek PHP Version:**
   - Laragon biasanya menggunakan PHP 7.4 atau 8.x
   - Pastikan versi PHP mendukung semua fungsi yang digunakan

4. **Clear Cache Browser:**
   - Tekan Ctrl + Shift + Delete
   - Clear cache dan cookies
   - Refresh halaman dengan Ctrl + F5

## Kontak Support

Jika masih ada masalah, cek:
1. Error message di browser (F12 > Console)
2. Error log Apache
3. Error log PHP
4. Status database di phpMyAdmin

