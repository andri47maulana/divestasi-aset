<?php
$servername = "10.10.3.17";
$database = "aset_suppco";
$username = "dbadmin";
$password = "UrEtr3CzpUxxuweYhW";

// untuk tulisan bercetak tebal silakan sesuaikan dengan detail database Anda
// membuat koneksi
$conn = mysqli_connect($servername, $username, $password, $database);
// mengecek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
echo "Koneksi berhasil";
mysqli_close($conn);
