<?php
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

// Proses pembaruan data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pastikan semua variabel ada dalam POST
    if (isset($_POST['nama'], $_POST['email'], $_POST['no_hp'], $_POST['role'], $_POST['userId'])) {
        $nama = $_POST['nama'];
        $email = $_POST['email'];
        $no_hp = $_POST['no_hp'];
        $role = $_POST['role'];
        $userId = $_POST['userId'];

        // Query untuk memperbarui data
        $updateQuery = "UPDATE users SET nama = ?, email = ?, no_hp = ?, role = ? WHERE id_anggota = ?";
        $stmt = $connection->prepare($updateQuery);
        
        // Pastikan untuk menggunakan parameter yang sesuai
        $stmt->bind_param("sssss", $nama, $email, $no_hp, $role, $userId); // 'sssss' karena semua parameter adalah string

        if ($stmt->execute()) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode(["success" => false, "message" => "Error saat memperbarui data: " . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Data tidak lengkap."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Metode permintaan tidak valid."]);
}

// Tutup koneksi database
$connection->close();
?>
