<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "koperasi"; // Ubah sesuai nama database

// Membuat koneksi
$config = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($config->connect_error) {
    die("Koneksi gagal: " . $config->connect_error);
}
?>
