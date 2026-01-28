<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan - Sales App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            @page {
                size: A4;
                margin: 15mm;
            }
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #1e293b;
            background: #fff;
        }
        
        .print-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #1e293b;
        }
        
        .print-header h1 {
            font-size: 24px;
            font-weight: 700;
            margin: 0 0 8px 0;
            color: #1e293b;
        }
        
        .print-header p {
            font-size: 14px;
            margin: 0;
            color: #64748b;
        }
        
        .print-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .print-table thead th {
            background: #f1f5f9;
            padding: 12px 16px;
            text-align: left;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            color: #64748b;
            border-bottom: 2px solid #e2e8f0;
        }
        
        .print-table thead th.text-end {
            text-align: right;
        }
        
        .print-table tbody td {
            padding: 12px 16px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .print-table tbody td.text-end {
            text-align: right;
        }
        
        .print-table tfoot td {
            padding: 16px;
            font-weight: 600;
            background: #f1f5f9;
            border-top: 2px solid #1e293b;
        }
        
        .print-table tfoot td.text-end {
            text-align: right;
            font-size: 14px;
            color: #10b981;
        }
        
        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: flex-end;
        }
        
        .signature-box {
            width: 200px;
            text-align: center;
        }
        
        .signature-box p {
            margin: 0 0 40px 0;
            font-size: 12px;
            color: #64748b;
        }
        
        .signature-box .signature-line {
            border-bottom: 1px solid #1e293b;
            height: 40px;
            margin-bottom: 8px;
        }
        
        .signature-box .signature-label {
            font-size: 11px;
            color: #64748b;
            text-transform: uppercase;
        }
        
        .no-print {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }
    </style>
</head>
<body>

<div class="no-print">
    <button onclick="window.print()" class="btn btn-primary">
        <i class="bi bi-printer me-2"></i>Cetak
    </button>
    <a href="laporan.php" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-2"></i>Kembali
    </a>
</div>

<div class="print-container" style="max-width: 800px; margin: 0 auto;">
    <!-- Header -->
    <div class="print-header">
        <h1>LAPORAN PENJUALAN</h1>
        <p>Periode: <?= date('d/m/Y', strtotime($_GET['tgl_awal'])) ?> s/d <?= date('d/m/Y', strtotime($_GET['tgl_akhir'])) ?></p>
    </div>

    <!-- Table -->
    <table class="print-table">
        <thead>
            <tr>
                <th style="width: 50px;">No</th>
                <th>Faktur</th>
                <th>Tanggal</th>
                <th>Barang</th>
                <th>Qty</th>
                <th>Customer</th>
                <th class="text-end">Total (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $q = mysqli_query($conn, "SELECT * FROM v_laporan_lengkap WHERE tanggal BETWEEN '".$_GET['tgl_awal']."' AND '".$_GET['tgl_akhir']."'");
            $total = 0; $no = 1;
            while($r = mysqli_fetch_assoc($q)):
                $total += $r['total_jumlah'];
            ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= $r['no_faktur'] ?></td>
                <td><?= date('d/m/y', strtotime($r['tanggal'])) ?></td>
                <td><?= $r['nama_barang'] ?></td>
                <td><?= $r['jumlah_unit'] ?></td>
                <td><?= $r['nama_customer'] ?></td>
                <td class="text-end"><?= number_format($r['total_jumlah'], 0, ',', '.') ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" class="text-end" style="font-size: 14px; font-weight: 700;">GRAND TOTAL</td>
                <td class="text-end" style="font-size: 16px; font-weight: 700;">Rp <?= number_format($total, 0, ',', '.') ?></td>
            </tr>
        </tfoot>
    </table>
    
    <!-- Signature -->
    <div class="signature-section">
        <div class="signature-box">
            <p>Tanggal: <?= date('d/m/Y') ?></p>
            <div class="signature-line"></div>
            <p class="signature-label">Manager</p>
        </div>
    </div>
</div>

</body>
</html>
