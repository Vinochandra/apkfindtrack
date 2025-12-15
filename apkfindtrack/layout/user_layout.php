<?php
require_once __DIR__ . '/../config/base_url.php';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/auth.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?>FindTrack</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/apkfindtrack/assets/css/style.css">
    <?php if (isset($additionalCSS)) echo $additionalCSS; ?>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-section">
            <div class="sidebar-section-title">
                <i class="fas fa-bars" style="cursor: pointer;" id="sidebarToggle"></i>
                <span>Workdesk</span>
            </div>
            <ul class="sidebar-menu">
                <?php if (isAdmin()): ?>
                    <li><a href="/apkfindtrack/admin/index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li><a href="/apkfindtrack/admin/data_user.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'data_user.php' ? 'active' : ''; ?>"><i class="fas fa-users"></i> Data User</a></li>
                    <li><a href="/apkfindtrack/admin/pengeluaran.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'pengeluaran.php' ? 'active' : ''; ?>"><i class="fas fa-money-bill-wave"></i> Pengeluaran</a></li>
                    <li><a href="/apkfindtrack/admin/laporan.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'laporan.php' ? 'active' : ''; ?>"><i class="fas fa-file-alt"></i> Laporan</a></li>
                    <li><a href="/apkfindtrack/admin/profil.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'profil.php' ? 'active' : ''; ?>"><i class="fas fa-user"></i> Profil</a></li>
                <?php else: ?>
                    <li><a href="/apkfindtrack/user/index.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li><a href="/apkfindtrack/user/tambah.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'tambah.php' ? 'active' : ''; ?>"><i class="fas fa-plus-circle"></i> Tambah Data</a></li>
                    <li><a href="/apkfindtrack/user/riwayat.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'riwayat.php' ? 'active' : ''; ?>"><i class="fas fa-history"></i> Riwayat</a></li>
                    <li><a href="/apkfindtrack/user/profil.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'profil.php' ? 'active' : ''; ?>"><i class="fas fa-user"></i> Profil</a></li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="sidebar-section" style="border-bottom: none;">
            <div style="padding: 15px; text-align: center; border-top: 1px solid var(--card-border); margin-top: 10px;">
                <a href="/apkfindtrack/logout.php" style="color: var(--text-secondary); text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
                </a>
            </div>
        </div>
    </aside>

    <!-- Top Header -->
    <header class="top-header">
        <div class="header-left">
            <div class="logo">
                <div class="logo-main">
                    FIND<span class="accent">TRACK</span>
                </div>
                <div class="logo-sub">Track Your Expenses</div>
            </div>
        </div>
        <div class="header-right">
            <?php if (isAdmin()): 
                // Get count of unapproved expenses
                $query_notif = "SELECT COUNT(*) as count FROM pengeluaran WHERE approved = 0";
                $result_notif = mysqli_query($conn, $query_notif);
                $notif_count = mysqli_fetch_assoc($result_notif)['count'] ?? 0;
            ?>
                <div class="header-icon bell-container" style="position: relative; cursor: pointer;" onclick="window.location.href='/apkfindtrack/admin/pengeluaran.php'">
                    <i class="fas fa-bell"></i>
                    <?php if ($notif_count > 0): ?>
                        <span class="bell-badge"><?php echo $notif_count > 9 ? '9+' : $notif_count; ?></span>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <i class="fas fa-bell header-icon" style="opacity: 0.3; cursor: not-allowed;"></i>
            <?php endif; ?>
            <div class="header-icon" style="display: flex; align-items: center; gap: 5px;">
                Help <i class="fas fa-chevron-down" style="font-size: 0.8rem;"></i>
            </div>
            <div class="user-avatar" title="<?php echo htmlspecialchars($_SESSION['nama']); ?>">
                <?php echo strtoupper(substr($_SESSION['nama'], 0, 2)); ?>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Content Area -->
        <div class="content">
            <?php
            // Display alerts
            if (isset($_SESSION['alert'])) {
                $alert = $_SESSION['alert'];
                echo '<div class="alert alert-' . $alert['type'] . ' alert-dismissible fade show" role="alert">';
                echo '<i class="fas fa-' . ($alert['type'] == 'success' ? 'check-circle' : ($alert['type'] == 'danger' ? 'exclamation-circle' : 'info-circle')) . '"></i> ';
                echo htmlspecialchars($alert['message']);
                echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                echo '</div>';
                unset($_SESSION['alert']);
            }
            ?>

