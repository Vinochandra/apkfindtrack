<?php
require_once '../layout/user_layout.php';
requireUser();

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$current_password = $_POST['current_password'] ?? '';
	$new_password = $_POST['new_password'] ?? '';
	$confirm_password = $_POST['confirm_password'] ?? '';

	// Ambil password hash sekarang
	$res = mysqli_query($conn, "SELECT password FROM users WHERE id = $user_id");
	$row = mysqli_fetch_assoc($res);
	$hash = $row ? $row['password'] : '';

	if (!$row || !password_verify($current_password, $hash)) {
		$_SESSION['alert'] = ['type' => 'danger', 'message' => 'Password saat ini salah!'];
	} elseif (strlen($new_password) < 6) {
		$_SESSION['alert'] = ['type' => 'danger', 'message' => 'Password baru minimal 6 karakter!'];
	} elseif ($new_password !== $confirm_password) {
		$_SESSION['alert'] = ['type' => 'danger', 'message' => 'Konfirmasi password tidak cocok!'];
	} else {
		$new_hash = password_hash($new_password, PASSWORD_DEFAULT);
		if (mysqli_query($conn, "UPDATE users SET password = '$new_hash' WHERE id = $user_id")) {
			$_SESSION['alert'] = ['type' => 'success', 'message' => 'Password berhasil diubah.'];
			header('Location: profil.php');
			exit();
		} else {
			$_SESSION['alert'] = ['type' => 'danger', 'message' => 'Gagal mengubah password.'];
		}
	}
}

$pageTitle = 'Ubah Password';
?>
<div class="card">
	<div class="card-header">
		<h4 class="section-title"><i class="fas fa-key"></i> Ubah Password</h4>
	</div>
	<div class="card-body">
		<form method="POST" action="">
			<div class="mb-3">
				<label for="current_password" class="form-label">Password Saat Ini</label>
				<input type="password" class="form-control" id="current_password" name="current_password" required>
			</div>
			<div class="mb-3">
				<label for="new_password" class="form-label">Password Baru</label>
				<input type="password" class="form-control" id="new_password" name="new_password" required>
			</div>
			<div class="mb-3">
				<label for="confirm_password" class="form-label">Konfirmasi Password Baru</label>
				<input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
			</div>
			<button type="submit" class="btn btn-primary">
				<i class="fas fa-save"></i> Simpan Perubahan
			</button>
			<a href="profil.php" class="btn btn-secondary ms-2"><i class="fas fa-arrow-left"></i> Kembali</a>
		</form>
	</div>
</div>
<?php require_once '../layout/footer.php'; ?>
