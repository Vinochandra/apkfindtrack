<?php
require_once '../layout/user_layout.php';
requireAdmin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id > 0) {
	$adminId = $_SESSION['user_id'];
	// Pastikan data ada
	$cek = mysqli_query($conn, "SELECT id, approved FROM pengeluaran WHERE id = $id");
	if ($cek && mysqli_num_rows($cek) > 0) {
		$row = mysqli_fetch_assoc($cek);
		if ((int)$row['approved'] === 1) {
			$_SESSION['alert'] = ['type' => 'info', 'message' => 'Data sudah di-approve sebelumnya.'];
		} else {
			$q = "UPDATE pengeluaran SET approved = 1, approved_at = NOW(), approved_by = $adminId WHERE id = $id";
			if (mysqli_query($conn, $q)) {
				$_SESSION['alert'] = ['type' => 'success', 'message' => 'Data berhasil di-approve.'];
			} else {
				$_SESSION['alert'] = ['type' => 'danger', 'message' => 'Gagal approve data.'];
			}
		}
	} else {
		$_SESSION['alert'] = ['type' => 'danger', 'message' => 'Data tidak ditemukan.'];
	}
}
header('Location: pengeluaran.php');
exit();
