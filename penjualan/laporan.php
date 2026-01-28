<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan - Sales App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Page Wrapper -->
<div class="page-wrapper">
    <!-- Include Sidebar -->
    <?php include 'sidebar.php'; ?>
    
    <!-- Main Content -->
    <div class="main-content">
        
        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Laporan Penjualan</h1>
            <p class="page-subtitle">Monitor performa penjualan per periode</p>
        </div>

        <!-- Content Grid -->
        <div class="content-grid">
            <!-- Left Column - Main Content -->
            <div class="main-card">
                
                <!-- Filter Card -->
                <div class="card card-form">
                    <div class="card-header-custom">
                        <div class="d-flex flex-wrap align-items-center gap-3">
                            <div class="icon-wrapper">
                                <i class="bi bi-funnel"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">Filter Periode</h5>
                                <p class="text-muted mb-0 small">Pilih rentang waktu untuk laporan</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body-form">
                        <form method="GET" action="laporan.php" class="filter-form">
                            <div class="filter-row">
                                <div class="filter-col">
                                    <div class="form-floating">
                                        <input type="date" name="tgl_awal" id="tgl_awal" class="form-control" 
                                               value="<?= isset($_GET['tgl_awal']) ? $_GET['tgl_awal'] : date('Y-m-01') ?>" required>
                                        <label for="tgl_awal">Tanggal Awal</label>
                                    </div>
                                </div>
                                <div class="filter-col">
                                    <div class="form-floating">
                                        <input type="date" name="tgl_akhir" id="tgl_akhir" class="form-control" 
                                               value="<?= isset($_GET['tgl_akhir']) ? $_GET['tgl_akhir'] : date('Y-m-d') ?>" required>
                                        <label for="tgl_akhir">Tanggal Akhir</label>
                                    </div>
                                </div>
                                <div class="filter-col filter-col-btn">
                                    <button type="submit" class="btn btn-primary-custom w-100">
                                        <i class="bi bi-search me-2"></i>Tampilkan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Report Table Card -->
                <div class="card card-table">
                    <div class="card-header-table">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper-table">
                                    <i class="bi bi-table"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">Data Transaksi</h5>
                                    <p class="text-muted mb-0 small">Hasil laporan berdasarkan periode</p>
                                </div>
                            </div>
                            <div class="d-flex gap-2 flex-wrap">
                                <?php
                                $tgl_awal  = isset($_GET['tgl_awal']) ? $_GET['tgl_awal'] : date('Y-m-01');
                                $tgl_akhir = isset($_GET['tgl_akhir']) ? $_GET['tgl_akhir'] : date('Y-m-d');
                                
                                $query_count = mysqli_query($conn, "SELECT COUNT(*) as total FROM v_laporan_lengkap WHERE tanggal BETWEEN '$tgl_awal' AND '$tgl_akhir'");
                                $count = mysqli_fetch_assoc($query_count);
                                ?>
                                <span class="badge bg-primary">
                                    <i class="bi bi-clipboard-data me-1"></i><?= $count['total'] ?> Transaksi
                                </span>
                                <?php if(isset($_GET['tgl_awal'])): ?>
                                    <a href="cetak_laporan.php?tgl_awal=<?= $_GET['tgl_awal'] ?>&tgl_akhir=<?= $_GET['tgl_akhir'] ?>" target="_blank" class="btn btn-warning-custom btn-sm">
                                        <i class="bi bi-printer me-1"></i>Cetak
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-body-table">
                        <div class="table-responsive-custom">
                            <table class="table-modern">
                                <thead>
                                    <tr>
                                        <th class="text-nowrap text-center">No</th>
                                        <th class="text-nowrap">Faktur</th>
                                        <th class="text-nowrap">Tanggal</th>
                                        <th class="text-nowrap">Barang</th>
                                        <th class="text-nowrap">Customer</th>
                                        <th class="text-nowrap">Sales</th>
                                        <th class="text-end text-nowrap">Jumlah (Rp)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $tgl_awal  = isset($_GET['tgl_awal']) ? $_GET['tgl_awal'] : date('Y-m-01');
                                    $tgl_akhir = isset($_GET['tgl_akhir']) ? $_GET['tgl_akhir'] : date('Y-m-d');
                                    
                                    $query = "SELECT * FROM v_laporan_lengkap 
                                              WHERE tanggal BETWEEN '$tgl_awal' AND '$tgl_akhir' 
                                              ORDER BY tanggal ASC";
                                    $result = mysqli_query($conn, $query);
                                    $no = 1;
                                    $total_omset = 0;

                                    if(mysqli_num_rows($result) > 0):
                                        while($row = mysqli_fetch_assoc($result)):
                                            $total_omset += $row['total_jumlah'];
                                    ?>
                                        <tr>
                                            <td class="text-center text-nowrap"><?= $no++ ?></td>
                                            <td class="fw-semibold text-primary text-nowrap"><?= $row['no_faktur'] ?></td>
                                            <td class="text-nowrap"><?= date('d/m/Y', strtotime($row['tanggal'])) ?></td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span class="badge bg-info">
                                                        <i class="bi bi-box-seam me-1"></i><?= $row['nama_barang'] ?>
                                                    </span>
                                                    <small class="text-muted mt-1"><?= $row['jumlah_unit'] ?> Unit</small>
                                                </div>
                                            </td>
                                            <td class="fw-medium text-nowrap"><?= strtoupper($row['nama_customer']) ?></td>
                                            <td><span class="text-secondary small text-nowrap"><?= $row['nama_sales'] ?></span></td>
                                            <td class="text-end fw-semibold text-nowrap">Rp <?= number_format($row['total_jumlah'], 0, ',', '.') ?></td>
                                        </tr>
                                    <?php 
                                        endwhile; 
                                    else:
                                    ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <div class="empty-state">
                                                    <i class="bi bi-inbox"></i>
                                                    <p class="mt-3 mb-0 text-muted">Tidak ada data transaksi</p>
                                                    <p class="small text-muted">Coba pilih periode yang berbeda</p>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                                <?php if(mysqli_num_rows($result) > 0): ?>
                                <tfoot>
                                    <tr>
                                        <td colspan="6" class="text-end fw-bold">TOTAL OMSET</td>
                                        <td class="text-end fw-bold total-row text-nowrap">Rp <?= number_format($total_omset, 0, ',', '.') ?></td>
                                    </tr>
                                </tfoot>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Sidebar Content -->
            <div class="sidebar-content">
                
                <!-- Summary Stats Card -->
                <div class="stats-card" style="flex-direction: column; gap: 12px;">
                    <div class="stats-card-content">
                        <div class="stats-card-label">Total Omset Periode</div>
                        <div class="stats-card-value" style="font-size: 24px;">Rp <?= number_format($total_omset, 0, ',', '.') ?></div>
                    </div>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="index.php" class="btn btn-outline-secondary flex-fill">
                            <i class="bi bi-house me-2"></i>Dashboard
                        </a>
                    </div>
                </div>

                <!-- Quick Actions Card -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-lightning me-2"></i>Aksi Cepat
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="tambah.php" class="btn btn-primary-custom">
                                <i class="bi bi-plus-circle me-2"></i>Transaksi Baru
                            </a>
                            <a href="tambah_customer.php" class="btn btn-outline-secondary">
                                <i class="bi bi-person-plus me-2"></i>Tambah Customer
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="info-card">
                    <div class="info-icon">
                        <i class="bi bi-info-circle"></i>
                    </div>
                    <div class="info-content">
                        <h6>Tips Penggunaan</h6>
                        <p class="mb-0">Gunakan filter tanggal untuk menampilkan laporan sesuai periode yang diinginkan. Klik tombol Cetak untuk mengunduh atau mencetaknya.</p>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

</body>
</html>