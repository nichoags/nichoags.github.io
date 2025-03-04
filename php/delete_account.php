<?php
// Koneksi ke database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'koperasi';

$connection = new mysqli($host, $username, $password, $database);

// Periksa koneksi
if ($connection->connect_error) {
    die(json_encode(["success" => false, "message" => "Koneksi gagal: " . $connection->connect_error]));
}

// Ambil ID pengguna dari permintaan POST
$userId = isset($_POST['userId']) ? $_POST['userId'] : null;

// Pastikan ID pengguna tidak kosong
if (!$userId) {
    echo json_encode(["success" => false, "message" => "ID pengguna tidak valid."]);
    exit();
}

// Mulai transaksi
$connection->begin_transaction();

try {
    // Hapus data simpanan pengguna
    $sqlDeleteSimpanan = "DELETE FROM simpanan WHERE id_anggota = ?";
    $stmtSimpanan = $connection->prepare($sqlDeleteSimpanan);
    $stmtSimpanan->bind_param("s", $userId); // Ganti 'i' dengan 's' jika id_anggota adalah string
    $stmtSimpanan->execute();

    // Hapus data pinjaman pengguna
    $sqlDeletePinjaman = "DELETE FROM pinjaman WHERE id_anggota = ?";
    $stmtPinjaman = $connection->prepare($sqlDeletePinjaman);
    $stmtPinjaman->bind_param("s", $userId); // Ganti 'i' dengan 's' jika id_anggota adalah string
    $stmtPinjaman->execute();

    // Hapus akun pengguna dari tabel users
    $sqlDeleteUser = "DELETE FROM users WHERE id_anggota = ?";
    $stmtUser = $connection->prepare($sqlDeleteUser);
    $stmtUser->bind_param("s", $userId); // Ganti 'i' dengan 's' jika id_anggota adalah string
    $stmtUser->execute();

    // Commit transaksi
    $connection->commit();

    echo json_encode(["success" => true, "message" => "Akun dan data terkait berhasil dihapus."]);

} catch (Exception $e) {
    // Rollback transaksi jika ada kesalahan
    $connection->rollback();
    echo json_encode(["success" => false, "message" => "Gagal menghapus akun dan data terkait: " . $e->getMessage()]);
} finally {
    // Tutup koneksi
    if (isset($stmtSimpanan)) $stmtSimpanan->close();
    if (isset($stmtPinjaman)) $stmtPinjaman->close();
    if (isset($stmtUser)) $stmtUser->close();
    $connection->close();
}
?>
