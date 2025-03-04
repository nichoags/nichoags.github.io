<?php
session_start();

// Menghubungkan dengan database
$servername = "localhost"; // Ganti dengan server database Anda
$username = "root"; // Ganti dengan username database Anda
$password = ""; // Ganti dengan password database Anda
$dbname = "koperasi_db"; // Ganti dengan nama database koperasi Anda

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Memeriksa apakah form sudah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nim = $_POST['nim'];
    $password_input = $_POST['password'];

    // Menyiapkan pernyataan SQL untuk mendapatkan hash password dan role
    $stmt = $conn->prepare("SELECT password, role FROM users WHERE nim = ?");
    $stmt->bind_param("s", $nim);
    $stmt->execute();
    $stmt->bind_result($hashed_password, $role);
    $stmt->fetch();
    
    // Memverifikasi password
    if (password_verify($password_input, $hashed_password)) {
        // Password cocok, lakukan login
        $_SESSION['nim'] = $nim; // Atur session nim
        $_SESSION['role'] = $role; // Atur session role

        // Cek role dan arahkan ke halaman yang sesuai
        if ($role === 'admin') {
            header("Location: admin_dashboard.php"); // Redirect ke dashboard admin
        } else if ($role === 'member') {
            header("Location: member_dashboard.php"); // Redirect ke dashboard member
        }
    } else {
        // Password tidak cocok
        $_SESSION['error'] = "NIM atau kata sandi salah.";
        header("Location: login.php"); // Kembali ke halaman login
    }

    // Menutup pernyataan dan koneksi
    $stmt->close();
    $conn->close();
}
?>
