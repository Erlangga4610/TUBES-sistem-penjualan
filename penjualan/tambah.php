<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Transaksi Baru - Sales App</title>
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
                <h1 class="page-title">Transaksi Baru</h1>
                <p class="page-subtitle">Input data transaksi penjualan</p>
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
                        <i class="bi bi-cart-plus"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">Formulir Transaksi</h5>
                        <p class="text-muted mb-0 small">Lengkapi data transaksi di bawah ini</p>
                    </div>
                </div>
            </div>
            <div class="card-body-form">
                
                <form action="proses.php?act=tambah" method="POST">
                    
                    <!-- Section 1: Informasi Transaksi -->
                    <div class="form-section">
                        <div class="section-header">
                            <span class="section-badge">1</span>
                            <h6 class="section-title">Informasi Transaksi</h6>
                        </div>
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="no_faktur" id="no_faktur" class="form-control" required placeholder="Nomor Faktur">
                                    <label for="no_faktur">Nomor Faktur</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="date" name="tanggal" id="tanggal" class="form-control" required value="<?= date('Y-m-d') ?>">
                                    <label for="tanggal">Tanggal Transaksi</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Pelanggan & Sales -->
                    <div class="form-section">
                        <div class="section-header">
                            <span class="section-badge">2</span>
                            <h6 class="section-title">Pelanggan & Sales</h6>
                        </div>
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select name="customer_id" id="customer_id" class="form-select" required>
                                        <option value="">-- Pilih Customer --</option>
                                        <?php
                                        $cust = mysqli_query($conn, "SELECT * FROM customer ORDER BY nama_customer ASC");
                                        while($c = mysqli_fetch_assoc($cust)){
                                            echo "<option value='".$c['customer_id']."'>".$c['nama_customer']."</option>";
                                        }
                                        ?>
                                    </select>
                                    <label for="customer_id">Pelanggan</label>
                                </div>
                                <div class="small text-muted mt-1">
                                    <a href="tambah_customer.php" class="text-decoration-none link-secondary">+ Tambah Customer Baru</a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select name="sales_id" id="sales_id" class="form-select" required>
                                        <option value="">-- Pilih Sales --</option>
                                        <?php
                                        $sales = mysqli_query($conn, "SELECT * FROM sales ORDER BY nama_sales ASC");
                                        while($s = mysqli_fetch_assoc($sales)){
                                            echo "<option value='".$s['Sales_Id']."'>".$s['nama_sales']."</option>";
                                        }
                                        ?>
                                    </select>
                                    <label for="sales_id">Sales</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Detail Barang -->
                    <div class="form-section">
                        <div class="section-header">
                            <span class="section-badge">3</span>
                            <h6 class="section-title">Detail Barang</h6>
                        </div>
                        
                        <div class="detail-card">
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="form-floating">
                                        <select name="kode_barang" id="pilihBarang" class="form-select" required onchange="hitungTotal()">
                                            <option value="" data-harga="0">-- Pilih Produk --</option>
                                            <?php
                                            $brg = mysqli_query($conn, "SELECT * FROM barang ORDER BY nama_barang ASC");
                                            while($b = mysqli_fetch_assoc($brg)){
                                                echo "<option value='".$b['kode_barang']."' data-harga='".$b['harga']."'>".$b['nama_barang']." (Rp ".number_format($b['harga']).")</option>";
                                            }
                                            ?>
                                        </select>
                                        <label for="pilihBarang">Pilih Barang</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating">
                                        <input type="number" name="jumlah_unit" id="jumlahUnit" class="form-control" required min="1" value="1" oninput="hitungTotal()">
                                        <label for="jumlahUnit">Jumlah Unit</label>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="total-display">
                                        <label class="text-muted small mb-1">Estimasi Total</label>
                                        <div class="total-amount" id="tampilTotal">Rp 0</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary-custom">
                            <i class="bi bi-check-circle me-2"></i>Simpan Transaksi
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>

<script>
function hitungTotal() {
    var select = document.getElementById('pilihBarang');
    var harga = select.options[select.selectedIndex].getAttribute('data-harga');
    var unit = document.getElementById('jumlahUnit').value;
    
    var total = harga * unit;
    
    // Format Rupiah sederhana
    document.getElementById('tampilTotal').innerHTML = "Rp " + new Intl.NumberFormat('id-ID').format(total);
}
</script>

</body>
</html>