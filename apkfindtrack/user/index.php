<?php
require_once '../layout/user_layout.php';
requireUser();

// Get user statistics
$user_id = $_SESSION['user_id'];
$query_total = "SELECT SUM(nilai) as total FROM pengeluaran WHERE user_id = $user_id";
$result_total = mysqli_query($conn, $query_total);
$total = mysqli_fetch_assoc($result_total)['total'] ?? 0;

$query_count = "SELECT COUNT(*) as jumlah FROM pengeluaran WHERE user_id = $user_id";
$result_count = mysqli_query($conn, $query_count);
$count = mysqli_fetch_assoc($result_count)['jumlah'] ?? 0;

// Get recent pengeluaran
$query_recent = "SELECT * FROM pengeluaran WHERE user_id = $user_id ORDER BY tanggal DESC, created_at DESC LIMIT 5";
$result_recent = mysqli_query($conn, $query_recent);

$pageTitle = 'Dashboard';
?>
<!-- Shortcuts Section -->
<div class="card mb-4">
    <div class="card-header">
        <h4 class="section-title"><i class="fas fa-bolt"></i> Shortcuts</h4>
    </div>
    <div class="card-body">
        <div class="shortcuts-grid">
            <div class="shortcut-card">
                <div class="shortcut-card-icon"><i class="fas fa-money-bill-wave"></i></div>
                <div class="shortcut-card-title">Total Pengeluaran</div>
                <div class="shortcut-card-value">Rp <?php echo number_format($total, 0, ',', '.'); ?></div>
            </div>
            <div class="shortcut-card">
                <div class="shortcut-card-icon"><i class="fas fa-list"></i></div>
                <div class="shortcut-card-title">Jumlah Data</div>
                <div class="shortcut-card-value"><?php echo $count; ?></div>
            </div>
            <a href="tambah.php" class="shortcut-card-link">
                <div class="shortcut-card blue">
                    <div class="shortcut-card-icon"><i class="fas fa-plus-circle"></i></div>
                    <div class="shortcut-card-title">Tambah Data</div>
                    <div class="shortcut-card-value">Baru</div>
                </div>
            </a>
            <a href="riwayat.php" class="shortcut-card-link">
                <div class="shortcut-card brown">
                    <div class="shortcut-card-icon"><i class="fas fa-history"></i></div>
                    <div class="shortcut-card-title">Riwayat</div>
                    <div class="shortcut-card-value"><?php echo $count; ?> Data</div>
                </div>
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card stats-card success">
            <div class="card-body">
                <p><i class="fas fa-money-bill-wave"></i> Total Pengeluaran</p>
                <h3>Rp <?php echo number_format($total, 0, ',', '.'); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card stats-card info">
            <div class="card-body">
                <p><i class="fas fa-list"></i> Jumlah Data</p>
                <h3><?php echo $count; ?></h3>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h4 class="section-title"><i class="fas fa-clock"></i> Data Terbaru</h4>
    </div>
    <div class="card-body">
        <?php if (mysqli_num_rows($result_recent) > 0): ?>
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
                        <?php while ($row = mysqli_fetch_assoc($result_recent)): ?>
                            <tr>
                                <td><?php echo date('d/m/Y', strtotime($row['tanggal'])); ?></td>
                                <td><?php echo htmlspecialchars($row['nopol']); ?></td>
                                <td><?php echo number_format($row['km'], 0, ',', '.'); ?></td>
                                <td><?php echo htmlspecialchars($row['kegiatan']); ?></td>
                                <td>Rp <?php echo number_format($row['nilai'], 0, ',', '.'); ?></td>
                                <td>
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
            <p class="text-muted">Belum ada data pengeluaran. <a href="tambah.php">Tambah data pertama</a></p>
        <?php endif; ?>
    </div>
</div>
<?php require_once '../layout/footer.php'; ?>

