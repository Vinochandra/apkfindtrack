<?php
require_once '../layout/user_layout.php';
requireAdmin();

$edit_id = isset($_GET['edit']) ? (int)$_GET['edit'] : 0;

if ($edit_id == 0) {
    header('Location: pengeluaran.php');
    exit();
}

// Get data
$query = "SELECT * FROM pengeluaran WHERE id = $edit_id";
$result = mysqli_query($conn, $query);
if (!$result || mysqli_num_rows($result) == 0) {
    $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Data tidak ditemukan!'];
    header('Location: pengeluaran.php');
    exit();
}
$data = mysqli_fetch_assoc($result);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tanggal = mysqli_real_escape_string($conn, $_POST['tanggal']);
    $nopol = mysqli_real_escape_string($conn, $_POST['nopol']);
    $km = (int)$_POST['km'];
    $kegiatan = mysqli_real_escape_string($conn, $_POST['kegiatan']);
    $nilai = (float)$_POST['nilai'];

    if (empty($tanggal) || empty($nopol) || empty($kegiatan) || $nilai <= 0) {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Semua field harus diisi dengan benar!'];
    } else {
        $query = "UPDATE pengeluaran SET tanggal = '$tanggal', nopol = '$nopol', km = $km, kegiatan = '$kegiatan', nilai = $nilai WHERE id = $edit_id";
        if (mysqli_query($conn, $query)) {
            $_SESSION['alert'] = ['type' => 'success', 'message' => 'Data berhasil diupdate!'];
            header('Location: pengeluaran.php');
            exit();
        } else {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Gagal mengupdate data!'];
        }
    }
}

$pageTitle = 'Edit Pengeluaran';
?>
<div class="card">
    <div class="card-header">
        <h4><i class="fas fa-edit"></i> Edit Pengeluaran</h4>
    </div>
    <div class="card-body">
        <form method="POST" action="">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="tanggal" class="form-label">Tanggal</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?php echo $data['tanggal']; ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="nopol" class="form-label">Nopol</label>
                    <input type="text" class="form-control" id="nopol" name="nopol" value="<?php echo htmlspecialchars($data['nopol']); ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="km" class="form-label">KM</label>
                    <input type="number" class="form-control" id="km" name="km" value="<?php echo $data['km']; ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="nilai" class="form-label">Nilai (tanpa PPN dan PPh)</label>
                    <input type="number" class="form-control" id="nilai" name="nilai" step="0.01" value="<?php echo $data['nilai']; ?>" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="kegiatan" class="form-label">Kegiatan</label>
                <textarea class="form-control" id="kegiatan" name="kegiatan" rows="3" required><?php echo htmlspecialchars($data['kegiatan']); ?></textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="pengeluaran.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
<?php require_once '../layout/footer.php'; ?>

