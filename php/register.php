<?php
// Memulai sesi
session_start();

// Menghubungkan dengan file konfigurasi database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "koperasi"; // Ubah sesuai nama database koperasi Anda

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Variabel untuk status pendaftaran
$registration_success = false;

// Mendapatkan ID anggota berikutnya
$result = $conn->query("SELECT COUNT(*) AS total FROM users");
if ($result && $row = $result->fetch_assoc()) {
    $total_users = $row['total'] + 1; // Menambahkan 1 untuk ID baru
    $id_anggota = 'id' . str_pad($total_users, 9, '0', STR_PAD_LEFT); // Format id000000001
} else {
    die("Gagal mendapatkan ID anggota: " . $conn->error);
}

// Memeriksa apakah form sudah disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data dari form
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $no_hp = preg_replace('/[^0-9]/', '', $_POST['no_hp']); // Menyaring hanya angka
    $role = $_POST['role'];
    $tanggal_bergabung = $_POST['tanggal_bergabung'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Meng-hash password
    $status_pembayaran = 'sudah'; // Status pembayaran simpanan pokok
    $nominal_pembayaran = 50000; // Nominal simpanan pokok

    // Menyiapkan pernyataan SQL dengan prepared statement untuk menambahkan anggota
    $stmt = $conn->prepare("INSERT INTO users (id_anggota, nama, email, no_hp, role, tanggal_bergabung, password, status_pembayaran) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $id_anggota, $nama, $email, $no_hp, $role, $tanggal_bergabung, $password, $status_pembayaran);

    if ($stmt->execute()) {
        // Menambahkan simpanan pokok setelah anggota berhasil ditambahkan
        $jenis_simpanan = 'Pokok';
        $tanggal_setor = $tanggal_bergabung;
        $id_simpanan = 'sm' . str_pad(mt_rand(1, 999999999), 9, '0', STR_PAD_LEFT); // Menghasilkan ID simpanan dengan format sm000000001

        // Menyiapkan pernyataan SQL untuk menambahkan simpanan
        $stmt_simpanan = $conn->prepare("INSERT INTO simpanan (id_simpanan, id_anggota, jenis_simpanan, tanggal_setor, nominal) VALUES (?, ?, ?, ?, ?)");
        $stmt_simpanan->bind_param("ssssi", $id_simpanan, $id_anggota, $jenis_simpanan, $tanggal_setor, $nominal_pembayaran);

        // Eksekusi dan periksa hasil
        if ($stmt_simpanan->execute()) {
            $registration_success = true; // Set status pendaftaran berhasil
        } else {
            echo "Kesalahan saat menyimpan simpanan: " . $stmt_simpanan->error;
        }

        // Menutup pernyataan simpanan
        $stmt_simpanan->close();
    } else {
        echo "Kesalahan: " . $stmt->error;
    }

    // Menutup pernyataan dan koneksi
    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="id" class="light-style customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Pendaftaran Dasar - Halaman | Koperasi Simpan Pinjam</title>
    <meta name="description" content="" />
    <link rel="stylesheet" href="../assets/vendor/css/core.css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />
</head>

<body>
    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-2">Daftar sebagai Anggota 🚀</h4>
                        <p class="mb-4">Daftarkan diri Anda untuk menjadi anggota koperasi!</p>

                        <form id="formAuthentication" class="mb-3" action="" method="POST" onsubmit="return confirmRegistration();">
                            <input type="hidden" id="id_anggota" name="id_anggota" value="<?php echo $id_anggota; ?>" />
                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama Lengkap</label>
                                <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama lengkap Anda" required autofocus />
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email Anda" required />
                            </div>
                            <div class="mb-3">
                                <label for="no_hp" class="form-label">Nomor HP</label>
                                <input type="text" class="form-control" id="no_hp" name="no_hp" placeholder="Masukkan nomor HP Anda" required />
                            </div>
                            <div class="mb-3">
                                <label for="role" class="form-label">Peran</label>
                                <select class="form-select" id="role" name="role" required>
                                    <option value="" disabled selected>Pilih peran Anda</option>
                                    <option value="member">Member</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="tanggal_bergabung" class="form-label">Tanggal Bergabung</label>
                                <input type="date" class="form-control" id="tanggal_bergabung" name="tanggal_bergabung" readonly required />
                            </div>
                            <div class="mb-3 form-password-toggle">
                                <label class="form-label" for="password">Kata Sandi</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password" placeholder="********" required />
                                </div>
                            </div>

                            <!-- Form untuk Pembayaran Simpanan Pokok -->
                            <div class="mb-3">
                                <label for="nominal_pembayaran" class="form-label">Nominal Pembayaran Simpanan Pokok</label>
                                <input type="number" class="form-control" id="nominal_pembayaran" name="nominal_pembayaran" value="50000" readonly required />
                                <small class="form-text text-muted">Anda diwajibkan membayar Simpanan Pokok sebesar Rp. 50.000</small>
                            </div>

                            <button class="btn btn-primary d-grid w-100">Daftar dan Bayar Simpanan Pokok</button>
                        </form>

                        <p class="text-center">
                            <span>Sudah memiliki akun?</span>
                            <a href="login.php">
                                <span>Masuk</span>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Mengisi otomatis tanggal hari ini di field "Tanggal Bergabung"
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date().toISOString().split('T')[0]; // Mendapatkan tanggal hari ini dalam format YYYY-MM-DD
            document.getElementById('tanggal_bergabung').value = today; // Mengisi value dengan tanggal hari ini
        });

        // Menampilkan alert jika pendaftaran berhasil
        <?php if ($registration_success): ?>
            alert("Pendaftaran berhasil! Simpanan Pokok sebesar Rp. 50.000 telah dibayarkan.");
            window.location.href = "login.php"; // Redirect ke halaman login setelah pendaftaran berhasil
        <?php endif; ?>
    </script>
</body>
</html>
