<?php
require_once '../layout/user_layout.php';
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

$pageTitle = 'Laporan';
?>
<div class="card mb-4 filter-card">
    <div class="card-header">
        <h4 class="section-title"><i class="fas fa-filter"></i> Filter Laporan</h4>
    </div>
    <div class="card-body">
        <form method="GET" action="" class="row g-3">
            <div class="col-md-4">
                <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal" value="<?php echo htmlspecialchars($filter_tanggal_awal); ?>">
            </div>
            <div class="col-md-4">
                <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" value="<?php echo htmlspecialchars($filter_tanggal_akhir); ?>">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-search"></i> Filter
                </button>
                <a href="laporan.php" class="btn btn-secondary me-2">
                    <i class="fas fa-redo"></i> Reset
                </a>
                <a href="export_pdf.php?tanggal_awal=<?php echo urlencode($filter_tanggal_awal); ?>&tanggal_akhir=<?php echo urlencode($filter_tanggal_akhir); ?>" class="btn btn-success" target="_blank">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <h5>Total Pengeluaran: <span class="text-success">Rp <?php echo number_format($total, 0, ',', '.'); ?></span></h5>
        <p class="text-muted mb-0">Jumlah Data: <?php echo mysqli_num_rows($result); ?></p>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h4><i class="fas fa-file-alt"></i> Data Laporan</h4>
    </div>
    <div class="card-body">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>User</th>
                            <th>Nopol</th>
                            <th>KM</th>
                            <th>Kegiatan</th>
                            <th>Nilai</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td data-label="Tanggal"><?php echo date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                                <td data-label="User">
                                    <strong><?php echo htmlspecialchars($row['user_nama']); ?></strong><br>
                                    <small class="text-muted"><?php echo htmlspecialchars($row['user_email']); ?></small>
                                </td>
                                <td data-label="Nopol"><?php echo htmlspecialchars($row['nopol']); ?></td>
                                <td data-label="KM"><?php echo number_format($row['km'], 0, ',', '.'); ?></td>
                                <td data-label="Kegiatan"><?php echo htmlspecialchars($row['kegiatan']); ?></td>
                                <td data-label="Nilai">Rp <?php echo number_format($row['nilai'], 0, ',', '.'); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-muted">Tidak ada data untuk ditampilkan.</p>
        <?php endif; ?>
    </div>
</div>
<?php require_once '../layout/footer.php'; ?>

