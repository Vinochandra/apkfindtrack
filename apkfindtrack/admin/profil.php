<?php
require_once '../layout/user_layout.php';
requireAdmin();

$user_id = $_SESSION['user_id'];

// Get user data
$query = "SELECT * FROM users WHERE id = $user_id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Handle update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    if (empty($nama) || empty($email)) {
        $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Nama dan email harus diisi!'];
    } else {
        // Check if email is taken by another user
        $check = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email' AND id != $user_id");
        if (mysqli_num_rows($check) > 0) {
            $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Email sudah digunakan!'];
        } else {
            $query = "UPDATE users SET nama = '$nama', email = '$email' WHERE id = $user_id";
            if (mysqli_query($conn, $query)) {
                $_SESSION['nama'] = $nama;
                $_SESSION['email'] = $email;
                $_SESSION['alert'] = ['type' => 'success', 'message' => 'Profil berhasil diupdate!'];
                header('Location: profil.php');
                exit();
            }
        }
    }
}

$pageTitle = 'Profil';
?>
<div class="card">
    <div class="card-header">
        <h4 class="section-title"><i class="fas fa-user"></i> Profil Saya</h4>
    </div>
    <div class="card-body">
        <form method="POST" action="">
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" id="nama" name="nama" value="<?php echo htmlspecialchars($user['nama']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Role</label>
                <input type="text" class="form-control" value="<?php echo strtoupper($user['role']); ?>" readonly style="background-color: var(--sidebar-hover);">
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Profil
            </button>
            <a href="ubah_password.php" class="btn btn-info ms-2">
                <i class="fas fa-key"></i> Ubah Password
            </a>
        </form>
    </div>
</div>
<?php require_once '../layout/footer.php'; ?>

