<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user'])) {
    // Jika belum login, redirect ke halaman login
    header('Location: login.php');
    exit();
}

// Ambil data user dari session
$user = $_SESSION['user'];
$id_anggota = $user['id_anggota'];

// Koneksi ke database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'koperasi';

$connection = new mysqli($host, $username, $password, $database);

// Periksa koneksi
if ($connection->connect_error) {
    die("Koneksi gagal: " . $connection->connect_error);
}

// Ambil data dari form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nominal = isset($_POST['nominal']) ? $_POST['nominal'] : 0;

    // Update data simpanan
    $sql = "UPDATE simpanan SET nominal = ? WHERE id_anggota = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("is", $nominal, $id_anggota); // Pastikan 'i' untuk integer dan 's' untuk string
    $success = $stmt->execute();

    // Tutup koneksi statement
    $stmt->close();
    // Tutup koneksi database
    $connection->close();

    // Mengembalikan respon JSON
    if ($success) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal memperbarui data simpanan.']);
    }
    exit; // Hentikan eksekusi setelah mengembalikan respon
}
?>
