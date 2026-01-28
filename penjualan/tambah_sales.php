<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Sales Baru - Sales App</title>
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
                <h1 class="page-title">Tambah Sales Baru</h1>
                <p class="page-subtitle">Daftarkan sales baru ke dalam sistem</p>
            </div>
            <div class="header-actions">
                <a href="index.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>

        <!-- Form Card -->
        <div class="card card-form">
            <div class="card-header-custom bg-success-custom">
                <div class="d-flex align-items-center">
                    <div class="icon-wrapper bg-success">
                        <i class="bi bi-person-badge"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">Formulir Sales Baru</h5>
                        <p class="text-muted mb-0 small">Lengkapi data sales di bawah ini</p>
                    </div>
                </div>
            </div>
            <div class="card-body-form">
                
                <form action="proses.php?act=tambah_sales" method="POST">
                    
                    <!-- Section 1: ID Sales -->
                    <div class="form-section">
                        <div class="section-header">
                            <span class="section-badge">1</span>
                            <h6 class="section-title">Identifikasi Sales</h6>
                        </div>
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="Sales_Id" id="Sales_Id" class="form-control" maxlength="2" required placeholder="ID Sales">
                                    <label for="Sales_Id">Sales ID (Max 2 Karakter)</label>
                                </div>
                                <div class="info-badge mt-2">
                                    <i class="bi bi-info-circle me-1"></i>
                                    ID yang sudah ada: 
                                    <?php 
                                    $q = mysqli_query($conn, "SELECT Sales_Id FROM sales");
                                    $ids = [];
                                    while($row = mysqli_fetch_assoc($q)) { $ids[] = $row['Sales_Id']; }
                                    echo implode(", ", $ids);
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="nama_sales" id="nama_sales" class="form-control" required placeholder="Nama Sales">
                                    <label for="nama_sales">Nama Sales</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="form-actions">
                        <button type="submit" class="btn btn-success-custom">
                            <i class="bi bi-check-circle me-2"></i>Simpan Sales
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
            <div class="info-icon bg-success">
                <i class="bi bi-lightbulb"></i>
            </div>
            <div class="info-content">
                <h6>Informasi Sistem</h6>
                <p class="mb-0">Sales yang ditambahkan akan langsung tersedia di dropdown saat input transaksi.</p>
            </div>
        </div>

    </div>
</div>

</body>
</html>