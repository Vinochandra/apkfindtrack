<?php
require_once '../layout/user_layout.php';
requireAdmin();

// Handle delete
if (isset($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    if ($delete_id > 0) {
        mysqli_query($conn, "DELETE FROM pengeluaran WHERE id = $delete_id");
        $_SESSION['alert'] = ['type' => 'success', 'message' => 'Data berhasil dihapus!'];
        header('Location: pengeluaran.php');
        exit();
    }
}

// Get all pengeluaran
$query = "SELECT p.*, u.nama as user_nama, u.email as user_email 
          FROM pengeluaran p 
          JOIN users u ON p.user_id = u.id 
          ORDER BY p.approved ASC, p.tanggal DESC, p.created_at DESC";
$result = mysqli_query($conn, $query);

// Get total
$query_total = "SELECT SUM(nilai) as total FROM pengeluaran";
$result_total = mysqli_query($conn, $query_total);
$total = mysqli_fetch_assoc($result_total)['total'] ?? 0;

$pageTitle = 'Data Pengeluaran';
?>
<div class="card mb-4">
    <div class="card-body">
        <h5>Total Semua Pengeluaran: <span class="text-success">Rp <?php echo number_format($total, 0, ',', '.'); ?></span></h5>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h4><i class="fas fa-money-bill-wave"></i> Data Pengeluaran Semua User</h4>
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
                            <th>Status</th>
                            <th>Aksi</th>
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
                                <td data-label="Status">
                                    <?php if ((int)$row['approved'] === 1): ?>
                                        <span class="badge bg-success">Approved</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td data-label="Aksi" class="d-flex gap-1">
                                    <?php if ((int)$row['approved'] === 0): ?>
                                        <a href="approve_pengeluaran.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-success" onclick="return confirm('Approve data ini?')">
                                            <i class="fas fa-check"></i>
                                        </a>
                                    <?php endif; ?>
                                    <a href="edit_pengeluaran.php?edit=<?php echo $row['id']; ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="pengeluaran.php?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-muted">Belum ada data pengeluaran.</p>
        <?php endif; ?>
    </div>
</div>
<?php require_once '../layout/footer.php'; ?>

