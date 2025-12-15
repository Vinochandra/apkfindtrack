<?php
require_once '../layout/user_layout.php';
requireUser();

$user_id = $_SESSION['user_id'];

// Filter tanggal
$filter_tanggal_awal = isset($_GET['tanggal_awal']) ? $_GET['tanggal_awal'] : '';
$filter_tanggal_akhir = isset($_GET['tanggal_akhir']) ? $_GET['tanggal_akhir'] : '';

$where = "user_id = $user_id";
if (!empty($filter_tanggal_awal)) {
    $where .= " AND tanggal >= '$filter_tanggal_awal'";
}
if (!empty($filter_tanggal_akhir)) {
    $where .= " AND tanggal <= '$filter_tanggal_akhir'";
}

// Get total
$query_total = "SELECT SUM(nilai) as total FROM pengeluaran WHERE $where";
$result_total = mysqli_query($conn, $query_total);
$total = mysqli_fetch_assoc($result_total)['total'] ?? 0;

// Get data
$query = "SELECT * FROM pengeluaran WHERE $where ORDER BY tanggal DESC, created_at DESC";
$result = mysqli_query($conn, $query);

$pageTitle = 'Riwayat Pengeluaran';
?>
<div class="card mb-4 filter-card">
    <div class="card-header">
        <h4 class="section-title"><i class="fas fa-filter"></i> Filter Data</h4>
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
                <a href="riwayat.php" class="btn btn-secondary">
                    <i class="fas fa-redo"></i> Reset
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <h5>Total Pengeluaran: <span class="text-success">Rp <?php echo number_format($total, 0, ',', '.'); ?></span></h5>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h4 class="section-title"><i class="fas fa-history"></i> Riwayat Pengeluaran</h4>
    </div>
    <div class="card-body">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Nopol</th>
                            <th>KM</th>
                            <th>Kegiatan</th>
                            <th>Nilai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td data-label="Tanggal"><?php echo date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                                <td data-label="Nopol"><?php echo htmlspecialchars($row['nopol']); ?></td>
                                <td data-label="KM"><?php echo number_format($row['km'], 0, ',', '.'); ?></td>
                                <td data-label="Kegiatan"><?php echo htmlspecialchars($row['kegiatan']); ?></td>
                                <td data-label="Nilai">Rp <?php echo number_format($row['nilai'], 0, ',', '.'); ?></td>
                                <td data-label="Aksi">
                                    <a href="tambah.php?edit=<?php echo $row['id']; ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="tambah.php?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-muted">Tidak ada data pengeluaran.</p>
        <?php endif; ?>
    </div>
</div>
<?php require_once '../layout/footer.php'; ?>

