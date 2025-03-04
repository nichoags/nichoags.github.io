<?php 
session_start();
include 'config.php'; // Sertakan file koneksi database

// Cek apakah user sudah login
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Ambil data user dari session
$user = $_SESSION['user'];
$nama = $user['nama'];
$role = $user['role'];
$id_anggota = $user['id_anggota'];

// Inisialisasi variabel pesan
$pembayaran_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jenis_simpanan = $_POST['jenis_simpanan'] ?? ''; // Ambil jenis simpanan
    $jumlah_simpanan = $_POST['jumlah_simpanan'] ?? 0; // Ambil jumlah simpanan
    $tanggal_setor = date('Y-m-d'); // Tanggal setor otomatis menggunakan tanggal hari ini
    $id_simpanan = 'sm' . str_pad(mt_rand(1, 99999999), 9, '0', STR_PAD_LEFT); // Menghasilkan ID simpanan dengan format sm000000001

    // Validasi berdasarkan jenis simpanan
    if ($jenis_simpanan === 'wajib' && $jumlah_simpanan < 25000) {
        $pembayaran_message .= "Simpanan Wajib harus minimal Rp. 25.000.<br>";
    }

    if ($jenis_simpanan === 'sukarela' && $jumlah_simpanan < 10000 && $jumlah_simpanan > 0) {
        $pembayaran_message .= "Simpanan Sukarela harus minimal Rp. 10.000.<br>";
    }

    // Simpan data jika valid
    if ($pembayaran_message === '') {
        // Logika untuk menyimpan pembayaran ke database
        $stmt = $config->prepare("INSERT INTO simpanan (id_simpanan, id_anggota, jenis_simpanan, nominal, tanggal_setor) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssis", $id_simpanan, $id_anggota, $jenis_simpanan, $jumlah_simpanan, $tanggal_setor); // Perbaiki argumen tipe menjadi "sssis"

        if ($stmt->execute()) {
            $pembayaran_message = "Pembayaran berhasil.";
        } else {
            $pembayaran_message = "Gagal menyimpan data: " . $stmt->error;
        }
        
        $stmt->close();
    }
}

$config->close(); // Tutup koneksi database
?>

<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Koperasi</title>
    <meta name="description" content="" />
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="../assets/vendor/libs/apex-charts/apex-charts.css" />
    <script src="../assets/vendor/js/helpers.js"></script>
    <script src="../assets/js/config.js"></script>

    <script>
    function validateForm() {
        const simpananWajib = document.getElementById('simpananWajib').value;
        const simpananSukarela = document.getElementById('simpananSukarela').value;

        if (simpanWajib === '' || parseInt(simpananWajib) < 25000) {
            alert('Simpanan Wajib harus minimal Rp. 25.000.');
            return false;
        }

        if (simpananSukarela !== '' && parseInt(simpananSukarela) < 10000) {
            alert('Simpanan Sukarela harus minimal Rp. 10.000 jika diisi.');
            return false;
        }

        return true; // Validasi berhasil
    }
    </script>

</head>

<body>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <span class="app-brand-text demo menu-text fw-bolder ms-2">Koperasi</span>
                </div>
                <li class="menu-header small text-uppercase"><span class="menu-header-text">Dashboard</span></li>
                <div class="menu-inner-shadow"></div>
                <ul class="menu-inner py-1">
                    <li class="menu-item">
                        <a href="member_dashboard.php" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-home-circle"></i>
                            <div data-i18n="Analytics">Dashboard</div>
                        </a>
                    </li>
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">Akun</span>
                    </li>
                    <li class="menu-item">
                    <a href="pages-account-settings-account-member.php" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-dock-top"></i>
                            <div data-i18n="Account Settings">Pengaturan Akun</div>
                        </a>
                    </li>
                    <li class="menu-header small text-uppercase"><span class="menu-header-text">Transaksi</span></li>
                    <li class="menu-item active">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-detail"></i>
                            <div data-i18n="Form Elements">Transaksi</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="forms-input-groups-member.php" class="menu-link">
                                    <div data-i18n="Input groups">Transaksi Simpanan</div>
                                </a>
                            </li>
                        </ul>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="forms-input-groups-pinjam-member.php" class="menu-link">
                                    <div data-i18n="Input groups">Transaksi Pinjaman</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-item">
                        <a href="tables-member.php" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-table"></i>
                            <div data-i18n="Tables">Detail Transaksi</div>
                        </a>
                    </li>
                </ul>
            </aside>

            <div class="layout-page">
                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="bx bx-menu bx-sm"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <div class="navbar-nav align-items-center">
                            <div class="nav-item d-flex align-items-center">
                                <div class="avatar avatar-online me-3">
                                    <img src="../assets/img/avatars/1.png" alt="User Avatar" class="w-px-40 h-auto rounded-circle" />
                                </div>
                                <div style="white-space: nowrap; width: auto;">
                                    <span class="fw-semibold d-block" style="font-size: 20px;"><?= ucwords($nama) ?></span>
                                    <small class="text-muted" style="font-size: 16px;"><?= ucwords($role) ?></small>
                                </div>
                            </div>
                        </div>

                        <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                            <ul class="navbar-nav flex-row align-items-center ms-auto">
                                <li class="nav-item">
                                    <a class="btn btn-primary" href="logout.php">Logout</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>

                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <h4 class="fw-bold py-3 mb-4">Setor Simpanan</h4>
                        <div class="card mb-4">
                            <h5 class="card-header">Input Simpanan</h5>
                            <div class="card-body">
                            <form method="POST" onsubmit="return validateForm()">
    <div class="row mb-3">
        <label class="col-sm-2 col-form-label" for="jenis_simpanan">Jenis Simpanan</label>
        <div class="col-sm-10">
            <select class="form-select" id="jenis_simpanan" name="jenis_simpanan" required onchange="updateSimpanan()">
                <option value="" disabled selected>Pilih Jenis Simpanan</option>
                <option value="wajib">Simpanan Wajib</option>
                <option value="sukarela">Simpanan Sukarela</option>
            </select>
        </div>
    </div>
    <div class="row mb-3">
        <label class="col-sm-2 col-form-label" for="jumlah_simpanan">Jumlah Simpanan</label>
        <div class="col-sm-10">
            <input type="number" class="form-control" id="jumlah_simpanan" name="jumlah_simpanan" min="10000" required />
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-sm-10 offset-sm-2">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </div>
    <div class="alert alert-info">
        <?= $pembayaran_message ?>
    </div>
</form>

<script>
function updateSimpanan() {
    const jenisSimpanan = document.getElementById('jenis_simpanan').value;
    const jumlahSimpananInput = document.getElementById('jumlah_simpanan');

    if (jenisSimpanan === 'wajib') {
        jumlahSimpananInput.value = 25000; // Set nilai otomatis untuk simpanan wajib
        jumlahSimpananInput.disabled = false; // Nonaktifkan input
    } else if (jenisSimpanan === 'sukarela') {
        jumlahSimpananInput.value = 10000; // Set nilai otomatis untuk simpanan sukarela
        jumlahSimpananInput.disabled = false; // Aktifkan input
    }
}

// Menangani input untuk memastikan tidak kurang dari 10000
document.getElementById('jumlah_simpanan').addEventListener('input', function() {
    if (this.value < 10000 && document.getElementById('jenis_simpanan').value === 'sukarela') {
        this.value = 10000; // Reset ke 10000 jika kurang dari itu
    }
});
</script>

                            </div>
                        </div>
                    </div>
  
              <!-- Footer -->
              <footer class="content-footer footer bg-footer-theme">
                <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                  <div class="mb-2 mb-md-0">
                    ©
                    <script>
                      document.write(new Date().getFullYear());
                    </script>
                    , Developed by Nicholas Agustinus
                  </div>
                 
                </div>
              </footer>
              <!-- / Footer -->
  
              <div class="content-backdrop fade"></div>
            </div>
            <!-- Content wrapper -->
          </div>
          <!-- / Layout page -->
        </div>
  
        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
      </div>
      <!-- / Layout wrapper -->
  
      <script src="../assets/vendor/libs/jquery/jquery.js"></script>
      <script src="../assets/vendor/libs/popper/popper.js"></script>
      <script src="../assets/vendor/js/bootstrap.js"></script>
      <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
      <script src="../assets/vendor/js/menu.js"></script>
      <script src="../assets/vendor/libs/apex-charts/apexcharts.js"></script>
      <script src="../assets/js/main.js"></script>
      <script src="../assets/js/dashboards-analytics.js"></script>
      <script async defer src="https://buttons.github.io/buttons.js"></script>
    </body>
  </html>