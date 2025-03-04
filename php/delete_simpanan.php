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
    $id_simpanan = isset($_POST['id_simpanan']) ? $_POST['id_simpanan'] : '';

    // Validasi ID simpanan
    if (empty($id_simpanan)) {
        echo json_encode(['success' => false, 'message' => 'ID simpanan tidak valid.']);
        exit;
    }

    // Query untuk menghapus simpanan
    $sql = "DELETE FROM simpanan WHERE id_simpanan = ? AND id_anggota = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ss", $id_simpanan, $id_anggota); // 's' untuk string
    $success = $stmt->execute();

    // Tutup koneksi statement
    $stmt->close();
    // Tutup koneksi database
    $connection->close();

    // Mengembalikan respon JSON
    if ($success) {
        echo json_encode(['success' => true, 'message' => 'Simpanan berhasil dihapus.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Gagal menghapus simpanan.']);
    }
    exit; // Hentikan eksekusi setelah mengembalikan respon
}
?>
