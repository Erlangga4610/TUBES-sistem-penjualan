-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 22 Jan 2026 pada 14.20
-- Versi server: 8.0.44-0ubuntu0.24.04.1
-- Versi PHP: 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_penjualan`
--

DELIMITER $$

--
-- Prosedur
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_tambah_barang` (
    IN `p_kode` CHAR(2), 
    IN `p_nama` VARCHAR(100),
    IN `p_harga` DECIMAL(10,2)
)  
BEGIN
    INSERT INTO barang (kode_barang, nama_barang, harga) 
    VALUES (p_kode, p_nama, p_harga);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_tambah_customer` (
    IN `p_id` CHAR(2), 
    IN `p_nama` VARCHAR(20)
)  
BEGIN
    INSERT INTO customer (customer_id, nama_customer) 
    VALUES (p_id, p_nama);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_tambah_sales` (
    IN `p_id` CHAR(2), 
    IN `p_nama` VARCHAR(20)
)  
BEGIN
    INSERT INTO sales (Sales_Id, nama_sales) 
    VALUES (p_id, p_nama);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_tambah_transaksi` (
    IN `p_faktur` VARCHAR(20), 
    IN `p_tanggal` DATE, 
    IN `p_unit` INT, 
    IN `p_cust` CHAR(2), 
    IN `p_sales` CHAR(2), 
    IN `p_kode_barang` CHAR(2)
)   
BEGIN
    DECLARE v_harga DECIMAL(10,2);
    DECLARE v_total DECIMAL(10,2);
    
    -- Ambil harga dari tabel barang
    SELECT harga INTO v_harga 
    FROM barang 
    WHERE kode_barang = p_kode_barang;
    
    -- Hitung total
    SET v_total = v_harga * p_unit;
    
    -- Masukkan data ke tabel transaksi
    INSERT INTO transaksi (no_faktur, tanggal, jumlah_unit, total_jumlah, customer_id, sales_id)
    VALUES (p_faktur, p_tanggal, p_unit, v_total, p_cust, p_sales);
    
    -- Masukkan data ke tabel detail_transaksi
    INSERT INTO detail_transaksi (no_faktur, kode_barang, banyak, Jumlah)
    VALUES (p_faktur, p_kode_barang, p_unit, v_total);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_update_transaksi` (
    IN `p_faktur` VARCHAR(20), 
    IN `p_tanggal` DATE, 
    IN `p_unit` INT, 
    IN `p_cust` CHAR(2), 
    IN `p_sales` CHAR(2), 
    IN `p_kode_barang` CHAR(2)
)   
BEGIN
    DECLARE v_harga DECIMAL(10,2);
    DECLARE v_total DECIMAL(10,2);
    
    -- Ambil harga dari tabel barang
    SELECT harga INTO v_harga 
    FROM barang 
    WHERE kode_barang = p_kode_barang;
    
    -- Hitung total
    SET v_total = v_harga * p_unit;
    
    -- Update data di tabel transaksi
    UPDATE transaksi 
    SET tanggal = p_tanggal, 
        jumlah_unit = p_unit, 
        total_jumlah = v_total,
        customer_id = p_cust, 
        sales_id = p_sales
    WHERE no_faktur = p_faktur;
    
    -- Update data di tabel detail_transaksi
    UPDATE detail_transaksi 
    SET kode_barang = p_kode_barang, 
        banyak = p_unit, 
        Jumlah = v_total
    WHERE no_faktur = p_faktur;
END$$

--
-- Fungsi
--
CREATE DEFINER=`root`@`localhost` FUNCTION `f_hitung_poin` (
    `total` DECIMAL(50,0)
) 
RETURNS INT 
DETERMINISTIC 
BEGIN
    DECLARE poin INT;
    
    -- Hitung poin berdasarkan total pembelian
    IF total >= 10000000 THEN
        SET poin = 100;
    ELSEIF total >= 5000000 THEN
        SET poin = 50;
    ELSEIF total >= 1000000 THEN
        SET poin = 10;
    ELSE
        SET poin = 0;
    END IF;
    
    RETURN poin;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `barang`
--

CREATE TABLE IF NOT EXISTS `barang` (
  `kode_barang` char(2) NOT NULL,
  `nama_barang` varchar(100) NOT NULL,
  `harga` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`kode_barang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `barang`
--

INSERT INTO `barang` (`kode_barang`, `nama_barang`, `harga`) VALUES
('B1', 'Laptop Asus A416', 7500000.00),
('B2', 'Laptop Lenovo V14', 6800000.00),
('B3', 'PC Rakitan i5 Gen10', 8200000.00),
('B4', 'Monitor Samsung 24"', 1850000.00),
('B5', 'Keyboard Mechanical RGB', 450000.00);

-- --------------------------------------------------------

--
-- Struktur dari tabel `customer`
--

CREATE TABLE IF NOT EXISTS `customer` (
  `customer_id` char(2) NOT NULL,
  `nama_customer` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `customer`
--

INSERT INTO `customer` (`customer_id`, `nama_customer`) VALUES
('01', 'Abdul'),
('02', 'Gilang Erlangga'),
('3', 'Maulana'),
('4', 'Adira'),
('6', 'Diza');

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_transaksi`
--

CREATE TABLE IF NOT EXISTS `detail_transaksi` (
  `no_faktur` varchar(20) DEFAULT NULL,
  `kode_barang` char(2) DEFAULT NULL,
  `banyak` int DEFAULT NULL,
  `Jumlah` decimal(10,2) DEFAULT NULL,
  `diskon` int DEFAULT NULL,
  KEY `no_faktur` (`no_faktur`),
  KEY `kode_barang` (`kode_barang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `detail_transaksi`
--

INSERT INTO `detail_transaksi` (`no_faktur`, `kode_barang`, `banyak`, `Jumlah`, `diskon`) VALUES
('F007', 'B1', 1, 7500000.00, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `log_aktivitas`
--

CREATE TABLE IF NOT EXISTS `log_aktivitas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pesan` varchar(255) DEFAULT NULL,
  `waktu` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `log_aktivitas`
--

INSERT INTO `log_aktivitas` (`id`, `pesan`, `waktu`) VALUES
(1, 'Transaksi baru: F007', '2026-01-22 10:52:22'),
(2, 'Customer baru: Gilang (06)', '2026-01-22 10:55:55'),
(3, 'Customer baru: dddd (07)', '2026-01-22 10:56:59'),
(4, 'Transaksi baru: F006', '2026-01-22 10:57:28'),
(5, 'Customer baru: Abdul (01)', '2026-01-22 20:46:14'),
(6, 'Transaksi baru: F001', '2026-01-22 20:46:33'),
(7, 'Transaksi baru: F007', '2026-01-22 20:49:53'),
(8, 'Customer baru: Gilang Erlangga (02)', '2026-01-22 20:54:46'),
(9, 'Customer baru: Maulana (3)', '2026-01-22 20:54:56'),
(10, 'Customer baru: Adira (4)', '2026-01-22 20:55:08'),
(11, 'Customer baru: Diza (6)', '2026-01-22 20:55:17'),
(12, 'Transaksi baru: F001', '2026-01-22 20:55:39'),
(13, 'Transaksi baru: f002', '2026-01-22 20:55:59'),
(14, 'Transaksi baru: F007', '2026-01-22 20:59:27');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sales`
--

CREATE TABLE IF NOT EXISTS `sales` (
  `Sales_Id` char(2) NOT NULL,
  `nama_sales` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`Sales_Id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `sales`
--

INSERT INTO `sales` (`Sales_Id`, `nama_sales`) VALUES
('01', 'Andi Saputra'),
('02', 'Dewi Lestari'),
('03', 'Rama Pratama'),
('04', 'Citra Angraini'),
('05', 'Fauzan Rahman');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE IF NOT EXISTS `transaksi` (
  `no_faktur` varchar(20) NOT NULL,
  `tanggal` date DEFAULT NULL,
  `total_jumlah` decimal(50,0) DEFAULT NULL,
  `jumlah_unit` int DEFAULT NULL,
  `debit_mandiri` decimal(10,2) DEFAULT NULL,
  `customer_id` char(2) DEFAULT NULL,
  `sales_id` char(2) DEFAULT NULL,
  PRIMARY KEY (`no_faktur`),
  KEY `customer_id` (`customer_id`),
  KEY `sales_id` (`sales_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`no_faktur`, `tanggal`, `total_jumlah`, `jumlah_unit`, `debit_mandiri`, `customer_id`, `sales_id`) VALUES
('F007', '2026-01-22', 7500000, 1, 1350000.00, '6', '01');

-- --------------------------------------------------------

--
-- Trigger untuk tabel `barang`
--

DELIMITER $$
CREATE TRIGGER `tr_after_insert_barang` 
AFTER INSERT ON `barang` 
FOR EACH ROW 
BEGIN
    INSERT INTO log_aktivitas (pesan, waktu) 
    VALUES (CONCAT('Barang baru: ', NEW.nama_barang, ' (', NEW.kode_barang, ')'), NOW());
END$$

--
-- Trigger untuk tabel `customer`
--

CREATE TRIGGER `tr_after_insert_customer` 
AFTER INSERT ON `customer` 
FOR EACH ROW 
BEGIN
    INSERT INTO log_aktivitas (pesan, waktu) 
    VALUES (CONCAT('Customer baru: ', NEW.nama_customer, ' (', NEW.customer_id, ')'), NOW());
END$$

--
-- Trigger untuk tabel `sales`
--

CREATE TRIGGER `tr_after_insert_sales` 
AFTER INSERT ON `sales` 
FOR EACH ROW 
BEGIN
    INSERT INTO log_aktivitas (pesan, waktu) 
    VALUES (CONCAT('Sales baru: ', NEW.nama_sales, ' (', NEW.Sales_Id, ')'), NOW());
END$$

--
-- Trigger untuk tabel `transaksi`
--

CREATE TRIGGER `tr_after_insert_transaksi` 
AFTER INSERT ON `transaksi` 
FOR EACH ROW 
BEGIN
    INSERT INTO log_aktivitas (pesan, waktu) 
    VALUES (CONCAT('Transaksi baru: ', NEW.no_faktur), NOW());
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in struktur untuk tampilan `v_laporan_lengkap`
--
CREATE TABLE IF NOT EXISTS `v_laporan_lengkap` (
`jumlah_unit` int
,`nama_barang` varchar(100)
,`nama_customer` varchar(20)
,`nama_sales` varchar(20)
,`no_faktur` varchar(20)
,`tanggal` date
,`total_jumlah` decimal(50,0)
);

-- --------------------------------------------------------

--
-- Struktur untuk view `v_laporan_lengkap`
--
DROP TABLE IF EXISTS `v_laporan_lengkap`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_laporan_lengkap`  AS SELECT `t`.`no_faktur` AS `no_faktur`, `t`.`tanggal` AS `tanggal`, `t`.`total_jumlah` AS `total_jumlah`, `t`.`jumlah_unit` AS `jumlah_unit`, coalesce(`c`.`nama_customer`,'Tanpa Nama') AS `nama_customer`, coalesce(`s`.`nama_sales`,'-') AS `nama_sales`, coalesce(`b`.`nama_barang`,'Item Terhapus') AS `nama_barang` FROM ((((`transaksi` `t` left join `customer` `c` on((`t`.`customer_id` = `c`.`customer_id`))) left join `sales` `s` on((`t`.`sales_id` = `s`.`Sales_Id`))) left join `detail_transaksi` `dt` on((`t`.`no_faktur` = `dt`.`no_faktur`))) left join `barang` `b` on((`dt`.`kode_barang` = `b`.`kode_barang`))) ;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `detail_transaksi`
--
ALTER TABLE `detail_transaksi`
  ADD CONSTRAINT `detail_transaksi_ibfk_1` FOREIGN KEY (`no_faktur`) REFERENCES `transaksi` (`no_faktur`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `detail_transaksi_ibfk_2` FOREIGN KEY (`kode_barang`) REFERENCES `barang` (`kode_barang`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`customer_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`sales_id`) REFERENCES `sales` (`Sales_Id`) ON DELETE SET NULL ON UPDATE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
