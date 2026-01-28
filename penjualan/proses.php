<?php
// --- SETUP ERROR REPORTING (Agar error terlihat jelas) ---
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'koneksi.php';

// Cek apakah parameter 'act' ada
if (!isset($_GET['act'])) {
    die("Error: Parameter 'act' tidak ditemukan di URL.");
}

$act = $_GET['act'];

// ==========================================================
// 1. LOGIKA TAMBAH BARANG
// ==========================================================
if ($act == 'tambah_barang') {
    $kode   = $_POST['kode_barang'];
    $nama   = $_POST['nama_barang'];
    $harga  = $_POST['harga'];

    // Panggil Stored Procedure Barang
    $query = "CALL sp_tambah_barang('$kode', '$nama', '$harga')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Barang Baru Berhasil Ditambahkan!'); window.location='tambah_barang.php';</script>"; 
    } else {
        echo "<script>alert('Gagal! Kode barang mungkin sudah terpakai atau format tidak sesuai.'); history.back();</script>";
    }

// ==========================================================
// 2. LOGIKA TAMBAH SALES
// ==========================================================
} elseif ($act == 'tambah_sales') {
    $id     = $_POST['Sales_Id'];
    $nama   = $_POST['nama_sales'];

    // Panggil Stored Procedure Sales
    $query = "CALL sp_tambah_sales('$id', '$nama')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Sales Baru Berhasil Ditambahkan!'); window.location='tambah_sales.php';</script>"; 
    } else {
        echo "<script>alert('Gagal! ID sales mungkin sudah terpakai atau format tidak sesuai.'); history.back();</script>";
    }

// ==========================================================
// 3. LOGIKA TAMBAH CUSTOMER
// ==========================================================
} elseif ($act == 'tambah_customer') {
    $id   = $_POST['customer_id'];
    $nama = $_POST['nama_customer'];

    // Panggil Stored Procedure Customer
    $query = "CALL sp_tambah_customer('$id', '$nama')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Customer Baru Berhasil Ditambahkan!'); window.location='tambah.php';</script>"; 
    } else {
        echo "<script>alert('Gagal! ID mungkin sudah terpakai.'); history.back();</script>";
    }

// ==========================================================
// 4. LOGIKA TAMBAH TRANSAKSI
// ==========================================================
} elseif ($act == 'tambah') {
    $faktur   = $_POST['no_faktur'];
    $tanggal  = $_POST['tanggal'];
    $cust     = $_POST['customer_id'];
    $sales    = $_POST['sales_id'];
    $unit     = $_POST['jumlah_unit'];
    
    // [PENTING] Kita ambil kode barang, bukan total manual
    $barang   = $_POST['kode_barang']; 

    // Panggil Stored Procedure Baru 
    // Urutan parameter: Faktur, Tanggal, Unit, Cust, Sales, Kode Barang
    $query = "CALL sp_tambah_transaksi('$faktur', '$tanggal', '$unit', '$cust', '$sales', '$barang')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Data Berhasil Disimpan & Stok Terhubung!'); window.location='index.php';</script>";
    } else {
        echo "Error Database: " . mysqli_error($conn);
    }

// ==========================================================
// 5. LOGIKA UPDATE TRANSAKSI
// ==========================================================
} elseif ($act == 'update') {
    $faktur   = $_POST['no_faktur'];
    $tanggal  = $_POST['tanggal'];
    $cust     = $_POST['customer_id'];
    $sales    = $_POST['sales_id'];
    $unit     = $_POST['jumlah_unit'];
    $barang   = $_POST['kode_barang']; // Ambil kode barang baru

    // Panggil Stored Procedure Update yang Baru
    $query = "CALL sp_update_transaksi('$faktur', '$tanggal', '$unit', '$cust', '$sales', '$barang')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Data Berhasil Diupdate!'); window.location='index.php';</script>";
    } else {
        echo "Error Database: " . mysqli_error($conn);
    }

// ==========================================================
// 6. LOGIKA HAPUS DATA
// ==========================================================
} elseif ($act == 'hapus') {
    $id = $_GET['id'];
    
    // Hapus detail dulu (karena Foreign Key)
    mysqli_query($conn, "DELETE FROM detail_transaksi WHERE no_faktur='$id'");
    
    // Hapus transaksi utama
    $query = "DELETE FROM transaksi WHERE no_faktur='$id'";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Data Berhasil Dihapus!'); window.location='index.php';</script>";
    } else {
        echo "Error Database: " . mysqli_error($conn);
    }

} else {
    echo "Aksi tidak dikenali!";
}
?>
