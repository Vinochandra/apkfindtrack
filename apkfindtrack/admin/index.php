<?php
require_once '../layout/user_layout.php';
requireAdmin();

// Get statistics
$query_total_pengeluaran = "SELECT SUM(nilai) as total FROM pengeluaran";
$result_total = mysqli_query($conn, $query_total_pengeluaran);
$total_pengeluaran = mysqli_fetch_assoc($result_total)['total'] ?? 0;

$query_total_user = "SELECT COUNT(*) as jumlah FROM users WHERE role = 'user'";
$result_user = mysqli_query($conn, $query_total_user);
$total_user = mysqli_fetch_assoc($result_user)['jumlah'] ?? 0;

$query_total_data = "SELECT COUNT(*) as jumlah FROM pengeluaran";
$result_data = mysqli_query($conn, $query_total_data);
$total_data = mysqli_fetch_assoc($result_data)['jumlah'] ?? 0;

// Get monthly data for chart
$query_monthly = "SELECT 
    DATE_FORMAT(tanggal, '%Y-%m') as bulan,
    SUM(nilai) as total
    FROM pengeluaran 
    WHERE tanggal >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
    GROUP BY DATE_FORMAT(tanggal, '%Y-%m')
    ORDER BY bulan ASC";
$result_monthly = mysqli_query($conn, $query_monthly);
$monthly_data = [];
$monthly_labels = [];
while ($row = mysqli_fetch_assoc($result_monthly)) {
    $monthly_labels[] = date('M Y', strtotime($row['bulan'] . '-01'));
    $monthly_data[] = (float)$row['total'];
}

// Get pengeluaran per user
$query_per_user = "SELECT 
    u.nama,
    u.email,
    COALESCE(SUM(p.nilai), 0) as total
    FROM users u
    LEFT JOIN pengeluaran p ON u.id = p.user_id
    WHERE u.role = 'user'
    GROUP BY u.id, u.nama, u.email
    ORDER BY total DESC
    LIMIT 5";
$result_per_user = mysqli_query($conn, $query_per_user);

$pageTitle = 'Dashboard Admin';
$additionalJS = '<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>';
?>
<!-- Shortcuts Section -->
<div class="card mb-4">
    <div class="card-header">
        <h4 class="section-title"><i class="fas fa-bolt"></i> Shortcuts</h4>
    </div>
    <div class="card-body">
        <div class="shortcuts-grid">
            <div class="shortcut-card">
                <div class="shortcut-card-icon"><i class="fas fa-clock"></i></div>
                <div class="shortcut-card-title">Total Pengeluaran</div>
                <div class="shortcut-card-value">Rp <?php echo number_format($total_pengeluaran, 0, ',', '.'); ?></div>
            </div>
            <div class="shortcut-card">
                <div class="shortcut-card-icon"><i class="fas fa-users"></i></div>
                <div class="shortcut-card-title">Total User</div>
                <div class="shortcut-card-value"><?php echo $total_user; ?></div>
            </div>
            <div class="shortcut-card">
                <div class="shortcut-card-icon"><i class="fas fa-list"></i></div>
                <div class="shortcut-card-title">Total Data</div>
                <div class="shortcut-card-value"><?php echo $total_data; ?></div>
            </div>
            <div class="shortcut-card brown">
                <div class="shortcut-card-icon"><i class="fas fa-file-alt"></i></div>
                <div class="shortcut-card-title">Laporan</div>
                <div class="shortcut-card-value"><?php echo $total_data; ?> Data</div>
            </div>
            <div class="shortcut-card blue">
                <div class="shortcut-card-icon"><i class="fas fa-chart-line"></i></div>
                <div class="shortcut-card-title">Grafik</div>
                <div class="shortcut-card-value">Aktif</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4 mb-4">
        <div class="card stats-card success">
            <div class="card-body">
                <p><i class="fas fa-money-bill-wave"></i> Total Pengeluaran</p>
                <h3>Rp <?php echo number_format($total_pengeluaran, 0, ',', '.'); ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card stats-card info">
            <div class="card-body">
                <p><i class="fas fa-users"></i> Total User</p>
                <h3><?php echo $total_user; ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card stats-card">
            <div class="card-body">
                <p><i class="fas fa-list"></i> Total Data</p>
                <h3><?php echo $total_data; ?></h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-chart-line"></i> Grafik Pengeluaran Per Bulan</h4>
            </div>
            <div class="card-body">
                <canvas id="monthlyChart" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-user-chart"></i> Top 5 Pengeluaran Per User</h4>
            </div>
            <div class="card-body">
                <?php if (mysqli_num_rows($result_per_user) > 0): ?>
                    <div class="list-group">
                        <?php while ($row = mysqli_fetch_assoc($result_per_user)): ?>
                            <div class="list-group-item" style="background-color: var(--sidebar-hover); border-color: var(--card-border);">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong style="color: var(--text-primary);"><?php echo htmlspecialchars($row['nama']); ?></strong><br>
                                        <small class="text-muted"><?php echo htmlspecialchars($row['email']); ?></small>
                                    </div>
                                    <span class="badge bg-success">Rp <?php echo number_format($row['total'], 0, ',', '.'); ?></span>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted">Belum ada data.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
const ctx = document.getElementById('monthlyChart').getContext('2d');
const monthlyChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($monthly_labels); ?>,
        datasets: [{
            label: 'Total Pengeluaran',
            data: <?php echo json_encode($monthly_data); ?>,
            borderColor: 'rgb(13, 110, 253)',
            backgroundColor: 'rgba(13, 110, 253, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                labels: {
                    color: '#b0b0b0'
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    color: '#b0b0b0',
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString('id-ID');
                    }
                },
                grid: {
                    color: '#3a3a3a'
                }
            },
            x: {
                ticks: {
                    color: '#b0b0b0'
                },
                grid: {
                    color: '#3a3a3a'
                }
            }
        }
    }
});
</script>
<?php require_once '../layout/footer.php'; ?>

