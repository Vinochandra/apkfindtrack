# FindTrack - Sistem Manajemen Pengeluaran

Aplikasi web PHP untuk manajemen pengeluaran dengan tema gelap modern seperti dashboard OneFlux.

## Fitur

### User
- Login dan Register
- Dashboard dengan ringkasan pengeluaran
- Input data pengeluaran (Tanggal, Nopol, KM, Kegiatan, Nilai)
- Lihat, edit, dan hapus data sendiri
- Filter data berdasarkan tanggal
- Profil user

### Admin
- Dashboard dengan grafik pengeluaran per bulan (Chart.js)
- Kelola data user (CRUD)
- Lihat semua data pengeluaran
- Laporan dengan filter tanggal
- Export PDF laporan
- Statistik pengeluaran per user

## Instalasi

### Langkah 1: Pastikan Laragon Running
1. Buka aplikasi **Laragon**
2. Klik tombol **"Start All"** (atau pastikan Apache dan MySQL sudah running)
3. Status harus hijau (Running)

### Langkah 2: Setup Database
1. Buka phpMyAdmin: `http://localhost/phpmyadmin`
2. Klik tab **"SQL"**
3. Copy semua isi file `config/schema.sql`
4. Paste di textarea SQL
5. Klik **"Go"** untuk menjalankan
6. Database `apkfindtrack` akan dibuat beserta tabel-tabelnya

### Langkah 3: Buat User Admin
**Opsi A (Recommended):**
1. Akses: `http://localhost/apkfindtrack/setup_admin.php`
2. User admin akan dibuat otomatis
3. **PENTING:** Hapus file `setup_admin.php` setelah selesai

**Opsi B (Manual):**
1. Buka phpMyAdmin
2. Pilih database `apkfindtrack` > tabel `users` > Insert
3. Isi data sesuai kebutuhan

### Langkah 4: Test Koneksi
1. Akses: `http://localhost/apkfindtrack/test.php`
2. Pastikan menampilkan "✓ Database Connected!"
3. Jika error, cek konfigurasi di `config/db.php`

### Langkah 5: Akses Aplikasi
1. Buka browser: `http://localhost/apkfindtrack/`
2. Akan redirect ke halaman login
3. Login dengan:
   - **Email:** admin@findtrack.com
   - **Password:** admin123

### Konfigurasi Database (jika perlu)
Edit file `config/db.php` jika konfigurasi berbeda:
```php
$host = 'localhost';      // Sesuaikan jika perlu
$username = 'root';       // Sesuaikan jika perlu
$password = '';           // Isi jika ada password
$database = 'apkfindtrack'; // Nama database
```

## Struktur Folder

```
findtrack/
├── config/
│   ├── db.php          # Koneksi database
│   ├── auth.php        # Helper authentication
│   └── schema.sql     # Schema database
├── layout/
│   ├── user_layout.php # Layout utama (sidebar + navbar)
│   └── footer.php      # Footer layout
├── user/
│   ├── index.php       # Dashboard user
│   ├── tambah.php      # Tambah/Edit data
│   ├── riwayat.php     # Riwayat dengan filter
│   └── profil.php      # Profil user
├── admin/
│   ├── index.php       # Dashboard admin (dengan chart)
│   ├── data_user.php   # Kelola user
│   ├── tambah_user.php # Tambah/Edit user
│   ├── pengeluaran.php # Data pengeluaran semua user
│   ├── edit_pengeluaran.php # Edit pengeluaran
│   ├── laporan.php     # Laporan dengan filter
│   └── export_pdf.php  # Export PDF
├── assets/
│   ├── css/
│   │   └── style.css   # Custom dark theme
│   └── js/
│       └── main.js     # JavaScript untuk sidebar toggle
├── login.php
├── register.php
├── logout.php
└── index.php
```

## Teknologi

- PHP Native
- MySQL
- Bootstrap 5
- FontAwesome 6.4.0
- Chart.js (untuk grafik)
- Custom Dark Theme CSS

## Catatan

- Password default admin: `admin123`
- Semua password di-hash menggunakan `password_hash()` PHP
- Sidebar dapat di-collapse (responsive)
- Export PDF menggunakan browser print to PDF
- Tema gelap dengan sidebar hitam dan card berwarna

## Lisensi

Proyek ini dibuat untuk keperluan pembelajaran dan penggunaan internal.

