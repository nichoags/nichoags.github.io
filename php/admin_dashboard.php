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
        <!-- Menu -->

        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <span class="app-brand-text demo menu-text fw-bolder ms-2">Koperasi</span>
                </div>
                <li class="menu-header small text-uppercase"><span class="menu-header-text">Dashboard</span></li>
                <div class="menu-inner-shadow"></div>
                <ul class="menu-inner py-1">
                    <li class="menu-item active">
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
              <div class="row">
              <div class="col-12 mb-4">
                  <div class="card">
                    <div class="d-flex align-items-end row">
                      <div class="col-sm-7">
                        <div class="card-body">
                          <h5 class="card-title text-primary">Selamat Datang <?= ucwords($nama) ?> 🎉</h5>
                          <p class="mb-4">
                          Selamat menikmati kemudahan dalam melakukan simpan dan pinjam di Koperasi Nicholas
                          </p>

                          <a href="tables-basic.php" class="btn btn-sm btn-outline-primary">Lihat Transaksi</a>
                        </div>
                      </div>
                      <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                          <img
                            src="../assets/img/illustrations/man-with-laptop-light.png"
                            height="140"
                            alt="View Badge User"
                            data-app-dark-img="illustrations/man-with-laptop-dark.png"
                            data-app-light-img="illustrations/man-with-laptop-light.png"
                          />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <?php
// Koneksi ke database
$host = 'localhost';
$username = 'root'; // Sesuaikan dengan username MySQL kamu
$password = ''; // Sesuaikan dengan password MySQL kamu
$database = 'koperasi'; // Nama database

$connection = new mysqli($host, $username, $password, $database);

// Cek koneksi
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Query untuk menghitung total transaksi penyimpanan
$query_simpanan = "SELECT SUM(nominal) AS total_simpanan FROM simpanan;";
$result_simpanan = $connection->query($query_simpanan);

// Ambil hasil query simpanan
$total_simpanan = 0;
if ($result_simpanan->num_rows > 0) {
    $row_simpanan = $result_simpanan->fetch_assoc();
    $total_simpanan = $row_simpanan['total_simpanan'];
}

// Query untuk menghitung total transaksi pinjaman
$query_pinjaman = "SELECT SUM(nominal) AS total_pinjaman FROM pinjaman;";
$result_pinjaman = $connection->query($query_pinjaman);

// Ambil hasil query pinjaman
$total_pinjaman = 0;
if ($result_pinjaman->num_rows > 0) {
    $row_pinjaman = $result_pinjaman->fetch_assoc();
    $total_pinjaman = $row_pinjaman['total_pinjaman'];
}

// Format total transaksi menjadi bentuk uang
$total_simpanan_formatted = number_format($total_simpanan, 2, ',', '.');
$total_pinjaman_formatted = number_format($total_pinjaman, 2, ',', '.');

// Tutup koneksi database
$connection->close();
?>

<div class="col-12 mb-4">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between flex-sm-row flex-column gap-3">
                <div class="d-flex flex-sm-column flex-row align-items-start justify-content-between">
                    <div class="card-title">
                        <h5 class="card-title text-primary">Total Simpanan</h5>
                        <a href="tables-basic.php"><span class="btn btn-sm btn-outline-primary">Lihat Detail</span></a>
                    </div>
                    <div class="mt-sm-auto">
                        <small class="text-nowrap fw-semibold">Jumlah Total:</small>
                        <h3 class="mb-0">Rp <?= $total_simpanan_formatted; ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-12 mb-4">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between flex-sm-row flex-column gap-3">
                <div class="d-flex flex-sm-column flex-row align-items-start justify-content-between">
                    <div class="card-title">
                        <h5 class="card-title text-primary">Total Pinjaman</h5>
                        <a href="tables-basic.php"><span class="btn btn-sm btn-outline-primary">Lihat Detail</span></a>
                    </div>
                    <div class="mt-sm-auto">
                        <small class="text-nowrap fw-semibold">Jumlah Total:</small>
                        <h3 class="mb-0">Rp <?= $total_pinjaman_formatted; ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


              </div>
              <div class="row">
                
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
