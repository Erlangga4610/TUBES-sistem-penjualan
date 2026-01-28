<?php
include 'koneksi.php';

// [FUNGSI AGREGAT] Menghitung total statistik
$query_stat = mysqli_query($conn, "SELECT 
    COUNT(*) as total_transaksi, 
    SUM(total_jumlah) as omset_total, 
    AVG(total_jumlah) as rata_rata 
    FROM transaksi");
$stat = mysqli_fetch_assoc($query_stat);

// Get recent activities
$log_q = mysqli_query($conn, "SELECT * FROM log_aktivitas ORDER BY id DESC LIMIT 8");

// Get customer count
$cust_count = mysqli_query($conn, "SELECT COUNT(*) as total FROM customer");
$cust_total = mysqli_fetch_assoc($cust_count)['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - App Penjualan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<!-- Layout with Sidebar -->
<div class="app-wrapper">
    <!-- Include Sidebar -->
    <?php include 'sidebar.php'; ?>
    
    <!-- Main Content -->
    <main class="main-content">
        
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Dashboard</h1>
            <p class="page-subtitle">Ringkasan aktivitas penjualan</p>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stats-card">
                <div class="stats-card-content">
                    <div class="stats-card-label">Total Transaksi</div>
                    <div class="stats-card-value"><?= number_format($stat['total_transaksi'] ?? 0) ?></div>
                </div>
                <div class="stats-card-icon primary">
                    <i class="bi bi-cart-check-fill"></i>
                </div>
            </div>
            <div class="stats-card">
                <div class="stats-card-content">
                    <div class="stats-card-label">Total Omset</div>
                    <div class="stats-card-value">Rp <?= number_format($stat['omset_total'] ?? 0, 0, ',', '.') ?></div>
                </div>
                <div class="stats-card-icon success">
                    <i class="bi bi-wallet2"></i>
                </div>
            </div>
            <div class="stats-card">
                <div class="stats-card-content">
                    <div class="stats-card-label">Rata-rata Transaksi</div>
                    <div class="stats-card-value">Rp <?= number_format($stat['rata_rata'] ?? 0, 0, ',', '.') ?></div>
                </div>
                <div class="stats-card-icon info">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
            </div>
            <div class="stats-card">
                <div class="stats-card-content">
                    <div class="stats-card-label">Total Customer</div>
                    <div class="stats-card-value"><?= number_format($cust_total) ?></div>
                </div>
                <div class="stats-card-icon warning">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
        </div>

        <div class="content-grid">
            <!-- Data Transaksi Terkini -->
            <div class="card main-card">
                <div class="card-header">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-table me-2"></i>Transaksi Terkini
                        </h5>
                        <div class="d-flex gap-2">
                            <a href="tambah.php" class="btn btn-primary btn-sm">
                                <i class="bi bi-plus-circle me-1"></i>Transaksi
                            </a>
                            <a href="laporan.php" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-file-earmark-text me-1"></i>Laporan
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body-table">
                    <div class="table-responsive-custom">
                        <table class="table-modern">
                            <thead>
                                <tr>
                                    <th class="text-nowrap">Faktur</th>
                                    <th class="text-nowrap">Tanggal</th>
                                    <th class="text-nowrap">Barang</th>
                                    <th class="text-nowrap">Customer</th>
                                    <th class="text-nowrap">Sales</th>
                                    <th class="text-end text-nowrap">Total</th>
                                    <th class="text-center text-nowrap">Poin</th>
                                    <th class="text-center text-nowrap">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT *, f_hitung_poin(total_jumlah) as poin FROM v_laporan_lengkap ORDER BY tanggal DESC LIMIT 8";
                                $result = mysqli_query($conn, $query);

                                if (!$result):
                                    echo "<tr><td colspan='8' class='text-center py-4 text-muted'>Error: ".mysqli_error($conn)."</td></tr>";
                                elseif (mysqli_num_rows($result) > 0):
                                    while($row = mysqli_fetch_assoc($result)): 
                                ?>
                                <tr>
                                    <td class="fw-semibold text-primary"><?= $row['no_faktur'] ?></td>
                                    <td><small><?= date('d M Y', strtotime($row['tanggal'])) ?></small></td>
                                    <td>
                                        <span class="badge bg-info">
                                            <i class="bi bi-box-seam me-1"></i><?= $row['nama_barang'] ?>
                                        </span>
                                        <div class="small text-muted"><?= $row['jumlah_unit'] ?> Unit</div>
                                    </td>
                                    <td class="fw-medium"><?= strtoupper($row['nama_customer']) ?></td>
                                    <td><span class="text-secondary small"><?= $row['nama_sales'] ?></span></td>
                                    <td class="text-end fw-semibold">Rp <?= number_format($row['total_jumlah'], 0, ',', '.') ?></td>
                                    <td class="text-center">
                                        <?php if($row['poin'] > 20): ?>
                                            <span class="badge bg-warning text-dark">
                                                <i class="bi bi-star-fill me-1"></i><?= $row['poin'] ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><?= $row['poin'] ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="edit.php?id=<?= $row['no_faktur'] ?>" class="btn btn-icon btn-outline-primary btn-sm" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <a href="proses.php?act=hapus&id=<?= $row['no_faktur'] ?>" class="btn btn-icon btn-outline-danger btn-sm" title="Hapus" onclick="return confirm('Yakin hapus?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php 
                                    endwhile; 
                                else:
                                ?>
                                    <tr>
                                        <td colspan="8">
                                            <div class="empty-state">
                                                <i class="bi bi-inbox"></i>
                                                <p class="mt-3 mb-0">Belum ada transaksi</p>
                                                <p class="small text-muted">Mulai dengan menambahkan transaksi baru</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sidebar Content -->
            <div class="sidebar-content">
                <!-- Log Aktivitas -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-activity me-2"></i>Aktivitas Terbaru
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="activity-list px-3 py-2">
                            <?php
                            if(mysqli_num_rows($log_q) > 0):
                                while($log = mysqli_fetch_assoc($log_q)):
                                    
                                    // Determine icon based on message
                                    $iconClass = 'primary';
                                    $icon = 'bi-circle-fill';
                                    if (strpos($log['pesan'], 'Transaksi') !== false) {
                                        $iconClass = 'success';
                                        $icon = 'bi-cart-check';
                                    } elseif (strpos($log['pesan'], 'Customer') !== false) {
                                        $iconClass = 'primary';
                                        $icon = 'bi-person-plus';
                                    } elseif (strpos($log['pesan'], 'Barang') !== false) {
                                        $iconClass = 'warning';
                                        $icon = 'bi-box-seam';
                                    } elseif (strpos($log['pesan'], 'Sales') !== false) {
                                        $iconClass = 'info';
                                        $icon = 'bi-person-badge';
                                    }
                            ?>
                                <li class="activity-item">
                                    <div class="activity-icon <?= $iconClass ?>">
                                        <i class="bi <?= $icon ?>"></i>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-message"><?= $log['pesan'] ?></div>
                                        <div class="activity-time"><?= date('H:i', strtotime($log['waktu'])) ?></div>
                                    </div>
                                </li>
                            <?php 
                                endwhile;
                            else:
                            ?>
                                <li class="activity-item">
                                    <div class="activity-content text-center w-100 py-3">
                                        <span class="text-muted">Belum ada aktivitas</span>
                                    </div>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
                
                <!-- Info Card -->
                <div class="info-card">
                    <div class="info-icon">
                        <i class="bi bi-info-circle"></i>
                    </div>
                    <div class="info-content">
                        <h6>Tentang Sistem</h6>
                        <p>Aplikasi ini menggunakan <strong>SQL View</strong> untuk laporan, <strong>Stored Function</strong> untuk perhitungan poin otomatis, dan <strong>Trigger</strong> untuk pencatatan aktivitas.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Mobile sidebar toggle functionality
document.addEventListener('DOMContentLoaded', function() {
    const mobileToggle = document.querySelector('.mobile-toggle');
    const mobileSidebar = document.getElementById('mobileSidebar');
    const mobileOverlay = document.getElementById('mobileOverlay');
    
    if (mobileToggle && mobileSidebar) {
        // Toggle sidebar when button is clicked
        mobileToggle.addEventListener('click', function() {
            if (mobileSidebar.classList.contains('show')) {
                // Close
                mobileSidebar.classList.remove('show');
                mobileSidebar.style.visibility = 'hidden';
                if (mobileOverlay) mobileOverlay.classList.remove('show');
                document.body.classList.remove('sidebar-open');
            } else {
                // Open
                mobileSidebar.classList.add('show');
                mobileSidebar.style.visibility = 'visible';
                if (mobileOverlay) mobileOverlay.classList.add('show');
                document.body.classList.add('sidebar-open');
            }
        });
    }
    
    // Close sidebar when clicking overlay
    if (mobileOverlay) {
        mobileOverlay.addEventListener('click', function() {
            mobileSidebar.classList.remove('show');
            mobileSidebar.style.visibility = 'hidden';
            mobileOverlay.classList.remove('show');
            document.body.classList.remove('sidebar-open');
        });
    }
    
    // Close sidebar when clicking close button
    const closeBtn = mobileSidebar ? mobileSidebar.querySelector('.btn-close-white') : null;
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            mobileSidebar.classList.remove('show');
            mobileSidebar.style.visibility = 'hidden';
            if (mobileOverlay) mobileOverlay.classList.remove('show');
            document.body.classList.remove('sidebar-open');
        });
    }
    
    // Close sidebar when clicking a link
    const sidebarLinks = mobileSidebar ? mobileSidebar.querySelectorAll('.sidebar-link') : [];
    sidebarLinks.forEach(function(link) {
        link.addEventListener('click', function() {
            mobileSidebar.classList.remove('show');
            mobileSidebar.style.visibility = 'hidden';
            if (mobileOverlay) mobileOverlay.classList.remove('show');
            document.body.classList.remove('sidebar-open');
        });
    });
});
</script>
</body>
</html>
