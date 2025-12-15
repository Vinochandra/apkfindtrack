<?php
require_once '../layout/user_layout.php';
requireAdmin();

// Handle delete
if (isset($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    if ($delete_id > 0 && $delete_id != $_SESSION['user_id']) {
        mysqli_query($conn, "DELETE FROM users WHERE id = $delete_id");
        $_SESSION['alert'] = ['type' => 'success', 'message' => 'User berhasil dihapus!'];
        header('Location: data_user.php');
        exit();
    }
}

// Get all users
$query = "SELECT u.*, COALESCE(SUM(p.nilai), 0) as total_pengeluaran, COUNT(p.id) as jumlah_data 
          FROM users u 
          LEFT JOIN pengeluaran p ON u.id = p.user_id 
          GROUP BY u.id 
          ORDER BY u.created_at DESC";
$result = mysqli_query($conn, $query);

$pageTitle = 'Data User';
?>
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4><i class="fas fa-users"></i> Data User</h4>
        <a href="tambah_user.php" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah User
        </a>
    </div>
    <div class="card-body">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Total Pengeluaran</th>
                            <th>Jumlah Data</th>
                            <th>Tanggal Daftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $row['role'] == 'admin' ? 'danger' : 'primary'; ?>">
                                        <?php echo strtoupper($row['role']); ?>
                                    </span>
                                </td>
                                <td>Rp <?php echo number_format($row['total_pengeluaran'], 0, ',', '.'); ?></td>
                                <td><?php echo $row['jumlah_data']; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($row['created_at'])); ?></td>
                                <td>
                                    <a href="tambah_user.php?edit=<?php echo $row['id']; ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if ($row['id'] != $_SESSION['user_id']): ?>
                                        <a href="data_user.php?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus user ini?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-muted">Belum ada user.</p>
        <?php endif; ?>
    </div>
</div>
<?php require_once '../layout/footer.php'; ?>

