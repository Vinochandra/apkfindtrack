<?php
require_once '../layout/user_layout.php';
requireUser();

$user_id = $_SESSION['user_id'];
$edit_id = isset($_GET['edit']) ? (int)$_GET['edit'] : 0;
$delete_id = isset($_GET['delete']) ? (int)$_GET['delete'] : 0;

// Handle delete
if ($delete_id > 0) {
    $check = mysqli_query($conn, "SELECT id FROM pengeluaran WHERE id = $delete_id AND user_id = $user_id");
    if (mysqli_num_rows($check) > 0) {
        mysqli_query($conn, "DELETE FROM pengeluaran WHERE id = $delete_id AND user_id = $user_id");
        $_SESSION['alert'] = ['type' => 'success', 'message' => 'Data berhasil dihapus!'];
        header('Location: index.php');
        exit();
    } else {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Data tidak ditemukan!'];
        header('Location: index.php');
        exit();
    }
}

// Get data for edit
$data = null;
if ($edit_id > 0) {
    $query = "SELECT * FROM pengeluaran WHERE id = $edit_id AND user_id = $user_id";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
    }
}

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
        if ($edit_id > 0) {
            // Update
            $query = "UPDATE pengeluaran SET tanggal = '$tanggal', nopol = '$nopol', km = $km, kegiatan = '$kegiatan', nilai = $nilai WHERE id = $edit_id AND user_id = $user_id";
            if (mysqli_query($conn, $query)) {
                $_SESSION['alert'] = ['type' => 'success', 'message' => 'Data berhasil diupdate!'];
                header('Location: index.php');
                exit();
            } else {
                $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Gagal mengupdate data!'];
            }
        } else {
            // Insert
            $query = "INSERT INTO pengeluaran (user_id, tanggal, nopol, km, kegiatan, nilai) VALUES ($user_id, '$tanggal', '$nopol', $km, '$kegiatan', $nilai)";
            if (mysqli_query($conn, $query)) {
                $_SESSION['alert'] = ['type' => 'success', 'message' => 'Data berhasil ditambahkan!'];
                header('Location: index.php');
                exit();
            } else {
                $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Gagal menambahkan data!'];
            }
        }
    }
}

$pageTitle = $edit_id > 0 ? 'Edit Data' : 'Tambah Data';
?>
<div class="card">
    <div class="card-header">
        <h4 class="section-title"><i class="fas fa-<?php echo $edit_id > 0 ? 'edit' : 'plus-circle'; ?>"></i> <?php echo $pageTitle; ?></h4>
    </div>
    <div class="card-body">
        <form method="POST" action="">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="tanggal" class="form-label">Tanggal</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?php echo $data ? $data['tanggal'] : date('Y-m-d'); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="nopol" class="form-label">Nopol</label>
                    <input type="text" class="form-control" id="nopol" name="nopol" value="<?php echo $data ? htmlspecialchars($data['nopol']) : ''; ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="km" class="form-label">KM</label>
                    <input type="number" class="form-control" id="km" name="km" value="<?php echo $data ? $data['km'] : ''; ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="nilai" class="form-label">Nilai Pengeluaran</label>
                    <input type="number" class="form-control" id="nilai" name="nilai" step="0.01" value="<?php echo $data ? $data['nilai'] : ''; ?>" required>
                </div>
            </div>
            <div class="mb-3">
                <label for="kegiatan" class="form-label">Kegiatan</label>
                <textarea class="form-control" id="kegiatan" name="kegiatan" rows="3" required><?php echo $data ? htmlspecialchars($data['kegiatan']) : ''; ?></textarea>
            </div>
            <div class="d-flex gap-2 mt-3">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="index.php" class="btn btn-secondary" style="text-decoration: none;">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
<?php require_once '../layout/footer.php'; ?>

