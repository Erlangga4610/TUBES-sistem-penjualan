<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang Baru - Sales App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Page Wrapper with Sidebar -->
<div class="page-wrapper">
    <!-- Include Sidebar -->
    <?php include 'sidebar.php'; ?>
    
    <!-- Main Content -->
    <div class="main-content">
        
        <!-- Page Header -->
        <div class="header">
            <div>
                <h1 class="page-title">Tambah Barang Baru</h1>
                <p class="page-subtitle">Tambahkan produk baru ke dalam sistem</p>
            </div>
            <div class="header-actions">
                <a href="index.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>

        <!-- Form Card -->
        <div class="card card-form">
            <div class="card-header-custom">
                <div class="d-flex align-items-center">
                    <div class="icon-wrapper">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">Formulir Barang Baru</h5>
                        <p class="text-muted mb-0 small">Lengkapi data produk di bawah ini</p>
                    </div>
                </div>
            </div>
            <div class="card-body-form">
                
                <form action="proses.php?act=tambah_barang" method="POST">
                    
                    <!-- Section 1: Kode Barang -->
                    <div class="form-section">
                        <div class="section-header">
                            <span class="section-badge">1</span>
                            <h6 class="section-title">Kode & Identifikasi</h6>
                        </div>
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="kode_barang" id="kode_barang" class="form-control" maxlength="2" required placeholder="Kode Barang">
                                    <label for="kode_barang">Kode Barang (Max 2 Karakter)</label>
                                </div>
                                <div class="info-badge mt-2">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Kode yang sudah ada: 
                                    <?php 
                                    $q = mysqli_query($conn, "SELECT kode_barang FROM barang");
                                    $ids = [];
                                    while($row = mysqli_fetch_assoc($q)) { $ids[] = $row['kode_barang']; }
                                    echo implode(", ", $ids);
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="nama_barang" id="nama_barang" class="form-control" required placeholder="Nama Barang">
                                    <label for="nama_barang">Nama Barang</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Harga -->
                    <div class="form-section">
                        <div class="section-header">
                            <span class="section-badge">2</span>
                            <h6 class="section-title">Informasi Harga</h6>
                        </div>
                        
                        <div class="row g-4">
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="number" name="harga" id="harga" class="form-control" required min="0" step="0.01" placeholder="Harga">
                                    <label for="harga">Harga (Rp)</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary-custom">
                            <i class="bi bi-check-circle me-2"></i>Simpan Barang
                        </button>
                        <button type="reset" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-counterclockwise me-2"></i>Reset
                        </button>
                    </div>

                </form>
            </div>
        </div>

        <!-- Info Card -->
        <div class="info-card">
            <div class="info-icon">
                <i class="bi bi-lightbulb"></i>
            </div>
            <div class="info-content">
                <h6>Informasi Sistem</h6>
                <p class="mb-0">Barang yang ditambahkan akan langsung tersedia di dropdown saat input transaksi. Sistem ini tidak mengelola stok barang.</p>
            </div>
        </div>

    </div>
</div>

</body>
</html>