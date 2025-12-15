<?php
require_once '../layout/user_layout.php';
requireAdmin();

$edit_id = isset($_GET['edit']) ? (int)$_GET['edit'] : 0;

// Get data for edit
$data = null;
if ($edit_id > 0) {
    $query = "SELECT * FROM users WHERE id = $edit_id";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
    } else {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'User tidak ditemukan!'];
        header('Location: data_user.php');
        exit();
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($nama) || empty($email) || empty($role)) {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Semua field harus diisi!'];
    } else {
        // Check if email is taken
        $check_query = "SELECT id FROM users WHERE email = '$email'";
        if ($edit_id > 0) {
            $check_query .= " AND id != $edit_id";
        }
        $check = mysqli_query($conn, $check_query);
        
        if (mysqli_num_rows($check) > 0) {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Email sudah terdaftar!'];
        } else {
            if ($edit_id > 0) {
                // Update
                if (!empty($password)) {
                    if ($password !== $confirm_password) {
                        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Password tidak cocok!'];
                    } elseif (strlen($password) < 6) {
                        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Password minimal 6 karakter!'];
                    } else {
                        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                        $query = "UPDATE users SET nama = '$nama', email = '$email', role = '$role', password = '$hashed_password' WHERE id = $edit_id";
                        if (mysqli_query($conn, $query)) {
                            $_SESSION['alert'] = ['type' => 'success', 'message' => 'User berhasil diupdate!'];
                            header('Location: data_user.php');
                            exit();
                        }
                    }
                } else {
                    $query = "UPDATE users SET nama = '$nama', email = '$email', role = '$role' WHERE id = $edit_id";
                    if (mysqli_query($conn, $query)) {
                        $_SESSION['alert'] = ['type' => 'success', 'message' => 'User berhasil diupdate!'];
                        header('Location: data_user.php');
                        exit();
                    }
                }
            } else {
                // Insert
                if (empty($password)) {
                    $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Password harus diisi!'];
                } elseif ($password !== $confirm_password) {
                    $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Password tidak cocok!'];
                } elseif (strlen($password) < 6) {
                    $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Password minimal 6 karakter!'];
                } else {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $query = "INSERT INTO users (nama, email, password, role) VALUES ('$nama', '$email', '$hashed_password', '$role')";
                    if (mysqli_query($conn, $query)) {
                        $_SESSION['alert'] = ['type' => 'success', 'message' => 'User berhasil ditambahkan!'];
                        header('Location: data_user.php');
                        exit();
                    }
                }
            }
        }
    }
}

$pageTitle = $edit_id > 0 ? 'Edit User' : 'Tambah User';
?>
<div class="card">
    <div class="card-header">
        <h4><i class="fas fa-<?php echo $edit_id > 0 ? 'edit' : 'plus-circle'; ?>"></i> <?php echo $pageTitle; ?></h4>
    </div>
    <div class="card-body">
        <form method="POST" action="">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $data ? htmlspecialchars($data['nama']) : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $data ? htmlspecialchars($data['email']) : ''; ?>" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="user" <?php echo ($data && $data['role'] == 'user') ? 'selected' : ''; ?>>User</option>
                    <option value="admin" <?php echo ($data && $data['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password <?php echo $edit_id > 0 ? '(kosongkan jika tidak ingin mengubah)' : ''; ?></label>
                <input type="password" class="form-control" id="password" name="password" <?php echo $edit_id > 0 ? '' : 'required'; ?>>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Konfirmasi Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" <?php echo $edit_id > 0 ? '' : 'required'; ?>>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="data_user.php" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
<?php require_once '../layout/footer.php'; ?>

