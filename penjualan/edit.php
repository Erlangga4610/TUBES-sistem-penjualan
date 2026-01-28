<?php 
include 'koneksi.php'; 

$id = $_GET['id'];

// [UPDATE QUERY] Kita join ke detail_transaksi untuk ambil kode_barang lama
$query = mysqli_query($conn, "SELECT t.*, dt.kode_barang 
                              FROM transaksi t 
                              LEFT JOIN detail_transaksi dt ON t.no_faktur = dt.no_faktur 
                              WHERE t.no_faktur = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='index.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Transaksi - Sales App</title>
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
                <h1 class="page-title">Edit Transaksi</h1>
                <p class="page-subtitle">Perbarui data transaksi penjualan</p>
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
                        <i class="bi bi-pencil-square"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">Formulir Edit Data</h5>
                        <p class="text-muted mb-0 small">Perbarui informasi transaksi di bawah ini</p>
                    </div>
                </div>
            </div>
            <div class="card-body-form">
                
                <form action="proses.php?act=update" method="POST">
                    
                    <!-- Section 1: Informasi Transaksi -->
                    <div class="form-section">
                        <div class="section-header">
                            <span class="section-badge">1</span>
                            <h6 class="section-title">Informasi Transaksi</h6>
                        </div>
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="no_faktur" id="no_faktur" class="form-control bg-light" 
                                           value="<?= $data['no_faktur'] ?>" readonly>
                                    <label for="no_faktur">Nomor Faktur</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="date" name="tanggal" id="tanggal" class="form-control" 
                                           value="<?= $data['tanggal'] ?>" required>
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
                                            $selected = ($data['customer_id'] == $c['customer_id']) ? 'selected' : '';
                                            echo "<option value='".$c['customer_id']."' $selected>".$c['nama_customer']."</option>";
                                        }
                                        ?>
                                    </select>
                                    <label for="customer_id">Pelanggan</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <select name="sales_id" id="sales_id" class="form-select" required>
                                        <option value="">-- Pilih Sales --</option>
                                        <?php
                                        $sales = mysqli_query($conn, "SELECT * FROM sales ORDER BY nama_sales ASC");
                                        while($s = mysqli_fetch_assoc($sales)){
                                            $selected = ($data['sales_id'] == $s['Sales_Id']) ? 'selected' : '';
                                            echo "<option value='".$s['Sales_Id']."' $selected>".$s['nama_sales']."</option>";
                                        }
                                        ?>
                                    </select>
                                    <label for="sales_id">Sales / Marketing</label>
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
                                                $selected = ($data['kode_barang'] == $b['kode_barang']) ? 'selected' : '';
                                                echo "<option value='".$b['kode_barang']."' data-harga='".$b['harga']."' $selected>".$b['nama_barang']." (Rp ".number_format($b['harga']).")</option>";
                                            }
                                            ?>
                                        </select>
                                        <label for="pilihBarang">Pilih Barang</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-floating">
                                        <input type="number" name="jumlah_unit" id="jumlahUnit" class="form-control" 
                                               value="<?= $data['jumlah_unit'] ?>" required min="1" oninput="hitungTotal()">
                                        <label for="jumlahUnit">Jumlah Unit</label>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="total-display">
                                        <label class="text-muted small mb-1">Total Baru</label>
                                        <div class="total-amount" id="tampilTotal">Rp 0</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="form-actions">
                        <button type="submit" class="btn btn-warning-custom">
                            <i class="bi bi-pencil-square me-2"></i>Update Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>

    </div>
</div>

<script>
// Fungsi Hitung Otomatis
function hitungTotal() {
    var select = document.getElementById('pilihBarang');
    var harga = select.options[select.selectedIndex].getAttribute('data-harga') || 0;
    var unit = document.getElementById('jumlahUnit').value || 0;
    
    var total = harga * unit;
    
    document.getElementById('tampilTotal').innerHTML = "Rp " + new Intl.NumberFormat('id-ID').format(total);
}

// Jalankan hitungTotal saat halaman pertama kali dibuka (agar total terisi)
window.onload = hitungTotal;
</script>

</body>
</html>