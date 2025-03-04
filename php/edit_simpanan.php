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
$nama = $user['nama'];
$role = $user['role'];

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
// Ambil data simpanan berdasarkan ID anggota dari session
$id_anggota = $user['id_anggota']; // Gunakan ID anggota dari session
$sql = "SELECT * FROM simpanan WHERE id_anggota = ?"; // Mengambil data dari tabel simpanan
$stmt = $connection->prepare($sql);
$stmt->bind_param("s", $id_anggota); // Pastikan menggunakan 's' karena id_anggota adalah string
$stmt->execute();
$result = $stmt->get_result();

$simpananData = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $simpananData[] = $row; // Ambil semua data simpanan
    }
} else {
    echo "Data pinjsimpananaman tidak ditemukan.";
    exit; // Hentikan eksekusi jika tidak ada data simpanan
}

// Misalkan kita ambil nominal dari simpanan yang pertama
$nominal = isset($simpananData[0]) ? $simpananData[0]['nominal'] : 0; // Ambil nominal dari data simpanan

// Tutup koneksi statement
$stmt->close();

// Tutup koneksi database
$connection->close();
?>

<!DOCTYPE html>
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Koperasi</title>

    <meta name="description" content="" />
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="../assets/vendor/libs/apex-charts/apex-charts.css" />
    <script src="../assets/vendor/js/helpers.js"></script>
    <script src="../assets/js/config.js"></script>
  </head>

  <body>
    <!-- Layout wrapper -->
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
                        <a href="admin_dashboard.php" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-home-circle"></i>
                            <div data-i18n="Analytics">Dashboard</div>
                        </a>
                    </li>
                    <li class="menu-header small text-uppercase">
                        <span class="menu-header-text">Akun</span>
                    </li>
                    <li class="menu-item">
                    <a href="pages-account-settings-account.php" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-dock-top"></i>
                            <div data-i18n="Account Settings">Pengaturan Akun</div>
                        </a>
                    </li>
                    <li class="menu-header small text-uppercase"><span class="menu-header-text">Transaksi</span></li>
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-detail"></i>
                            <div data-i18n="Form Elements">Transaksi</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="forms-input-groups.php" class="menu-link">
                                    <div data-i18n="Input groups">Transaksi Simpanan</div>
                                </a>
                            </li>
                        </ul>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="forms-input-groups-pinjam.php" class="menu-link">
                                    <div data-i18n="Input groups">Transaksi Pinjaman</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-item">
                        <a href="tables-basic.php" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-table"></i>
                            <div data-i18n="Tables">Detail Transaksi</div>
                        </a>
                    </li>
                </ul>
            </aside>

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

          <nav
            class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
            id="layout-navbar"
          >
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
              <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                <i class="bx bx-menu bx-sm"></i>
              </a>
            </div>

            <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
    <!-- Display User Name and Role -->
    <div class="navbar-nav align-items-center">
    <div class="nav-item d-flex align-items-center">
        <!-- Avatar -->
        <div class="avatar avatar-online me-3">
            <img src="../assets/img/avatars/1.png" alt="User Avatar" class="w-px-40 h-auto rounded-circle" />
        </div>
        <!-- User Name and Role -->
        <div style="white-space: nowrap; width: auto;">
            <span class="fw-semibold d-block" style="font-size: 20px;"><?= ucwords($nama) ?></span>
            <small class="text-muted" style="font-size: 16px;"><?= ucwords($role) ?></small>
        </div>
    </div>
</div>



<div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
  <ul class="navbar-nav flex-row align-items-center ms-auto">
    <!-- Logout Button -->
    <li class="nav-item">
      <a class="nav-link" href="logout.php">
        <button class="btn btn-primary">
          <span class="align-middle">Log Out</span>
        </button>
      </a>
    </li>
    <!--/ Logout Button -->
  </ul>
</div>

          </nav>

          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"></span>Pengaturan Akun</h4>

              <div class="row">
                <div class="col-md-12">
                
<!-- Form untuk edit simpanan -->
<div class="card mb-4">
    <h5 class="card-header">Edit simpanan</h5>
    <hr class="my-0" />
    <div class="card-body">
        <form id="formAccountSettings" onsubmit="updateUser(event)">
            <input type="hidden" name="userId" value="<?php echo htmlspecialchars($user['id_anggota']); ?>">
            <div class="row">
                <div class="mb-3">
                    <label for="nominal" class="form-label">Jumlah simpanan</label>
                    <input
                        class="form-control"
                        type="text"
                        id="nominal"
                        name="nominal"
                        value="<?php echo htmlspecialchars($nominal); ?>" >
                </div>
            </div>
            <div class="mt-2">
                <button type="submit" class="btn btn-primary me-2">Save changes</button>
                <button type="reset" class="btn btn-outline-secondary">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    function updateUser(event) {
        event.preventDefault(); // Mencegah pengiriman formulir default
        
        const formData = new FormData(document.getElementById('formAccountSettings'));
        fetch('update_simpanan.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Data berhasil diperbarui');
                // Mungkin redirect atau lakukan tindakan lain, misalnya:
                // window.location.href = 'halaman_setelah_update.php';
            } else {
                alert('Gagal memperbarui data: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memperbarui data');
        });
    }
</script>


                </div>
              </div>
            </div>
            <!-- / Content -->
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
