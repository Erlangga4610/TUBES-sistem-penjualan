<?php
$host = "localhost";
$user = "root"; // Sesuaikan user database
$pass = "password";     // Sesuaikan password database
$db   = "db_penjualan";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>