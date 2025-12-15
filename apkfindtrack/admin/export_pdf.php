<?php
require_once '../config/db.php';
require_once '../config/auth.php';
requireAdmin();

// Filter tanggal
$filter_tanggal_awal = isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : '';
$filter_tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : '';

$where = "1=1";
if (!empty($filter_tanggal_awal)) {
    $where .= " AND p.tanggal >= '$filter_tanggal_awal'";
}
if (!empty($filter_tanggal_akhir)) {
    $where .= " AND p.tanggal <= '$filter_tanggal_akhir'";
}

// Get data
$query = "SELECT p.*, u.nama as user_nama, u.email as user_email 
          FROM pengeluaran p 
          JOIN users u ON p.user_id = u.id 
          WHERE $where
          ORDER BY p.tanggal DESC, p.created_at DESC";
$result = mysqli_query($conn, $query);

// Get total
$query_total = "SELECT SUM(p.nilai) as total FROM pengeluaran p WHERE $where";
$result_total = mysqli_query($conn, $query_total);
$total = mysqli_fetch_assoc($result_total)['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pengeluaran - FindTrack</title>
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            color: #333;
            background-color: #fff;
        }
        .letterhead {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 10px;
        }
        .letterhead img {
            width: 110px;
            height: auto;
        }
        .company-info h2 {
            margin: 0;
            color: #b22222;
            font-size: 1.6rem;
            text-transform: uppercase;
        }
        .company-info p {
            margin: 2px 0;
            color: #1f1f1f;
            font-size: 0.9rem;
        }
        .address {
            font-weight: 600;
            color: #0b3d91;
        }
        .divider {
            border-top: 3px double #000;
            margin: 15px 0 10px;
        }
        h1 {
            text-align: center;
            color: #1a1a1a;
            margin-bottom: 10px;
        }
        .info {
            text-align: center;
            margin-bottom: 20px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #1a1a1a;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .total {
            margin-top: 20px;
            text-align: right;
            font-size: 18px;
            font-weight: bold;
        }
        .btn {
            padding: 10px 20px;
            background-color: #dc3545;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .btn:hover {
            background-color: #bb2d3b;
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" class="btn">Print / Save as PDF</button>
    </div>
    
    <div class="letterhead">
        <img src="../assets/img/logotelkom.png" alt="Company Logo">
        <div class="company-info">
            <h2>PT Telkom Akses Cibitung</h2>
            <p>General Supplier, Contractor, Technical Service, Fabrication & Machining</p>
            <p class="address">Located at Jl. Raya Setu No.27, Cibuntu, Cibitung, Bekasi, Jawa Barat 17520, Indonesia.</p>
        </div>
    </div>
    <div class="divider"></div>
    <h1>LAPORAN PENGELUARAN</h1>
    <div class="info">
        <p><strong>FindTrack System</strong></p>
        <?php if (!empty($filter_tanggal_awal) || !empty($filter_tanggal_akhir)): ?>
            <p>
                Periode: 
                <?php echo !empty($filter_tanggal_awal) ? date('d/m/Y', strtotime($filter_tanggal_awal)) : 'Semua'; ?> 
                - 
                <?php echo !empty($filter_tanggal_akhir) ? date('d/m/Y', strtotime($filter_tanggal_akhir)) : 'Semua'; ?>
            </p>
        <?php else: ?>
            <p>Semua Data</p>
        <?php endif; ?>
        <p>Tanggal Cetak: <?php echo date('d/m/Y H:i:s'); ?></p>
    </div>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>User</th>
                    <th>Nopol</th>
                    <th>KM</th>
                    <th>Kegiatan</th>
                    <th>Nilai</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                while ($row = mysqli_fetch_assoc($result)): 
                ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                        <td><?php echo htmlspecialchars($row['user_nama']); ?></td>
                        <td><?php echo htmlspecialchars($row['nopol']); ?></td>
                        <td><?php echo number_format($row['km'], 0, ',', '.'); ?></td>
                        <td><?php echo htmlspecialchars($row['kegiatan']); ?></td>
                        <td>Rp <?php echo number_format($row['nilai'], 0, ',', '.'); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        
        <div class="total">
            <p>Total Pengeluaran: Rp <?php echo number_format($total, 0, ',', '.'); ?></p>
            <p>Jumlah Data: <?php echo mysqli_num_rows($result); ?></p>
        </div>
        <div style="margin-top:60px; text-align:right;">
            <p>Bekasi, <?php echo date('d F Y'); ?></p>
            <p style="margin-top:60px;"><strong>Taufik Nurachmat</strong><br>Manager (Validasi)</p>
        </div>
    <?php else: ?>
        <p>Tidak ada data untuk ditampilkan.</p>
    <?php endif; ?>

    <script>
        // Auto print when page loads
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>

