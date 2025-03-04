<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Ambil data user dari session
$user = $_SESSION['user'];
$nama = $user['nama'];
$role = $user['role'];

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "koperasi");

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mendapatkan data simpanan anggota
$sql_simpanan = "SELECT s.id_simpanan, s.id_anggota, u.nama AS nama_anggota, s.jenis_simpanan, s.nominal, s.tanggal_setor
FROM simpanan s
JOIN users u ON s.id_anggota = u.id_anggota"; // Menggunakan id_anggota untuk join

$result_simpanan = $conn->query($sql_simpanan);

// Query untuk mendapatkan data pinjaman anggota
$sql_pinjaman = "SELECT p.id_pinjaman, p.id_anggota, u.nama AS nama_anggota, p.nominal, p.tanggal_pinjam
FROM pinjaman p
JOIN users u ON p.id_anggota = u.id_anggota"; // Menggunakan id_anggota untuk join

$result_pinjaman = $conn->query($sql_pinjaman);
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
                    <li class="menu-item active">
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
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light"></span>Detail Transaksi</h4>
                        <!-- Bootstrap Table with Header - Footer -->
                        <div class="card">
    <h5 class="card-header">Total Simpanan Anggota</h5>
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr>
                    <th>ID Simpanan</th>
                    <th>ID Anggota</th>
                    <th>Nama Anggota</th>
                    <th>Jenis Simpanan</th>
                    <th>Nominal</th>
                    <th>Tanggal Setor</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Cek apakah ada hasil
                if ($result_simpanan->num_rows > 0) {
                    // Output data setiap baris
                    while ($row = $result_simpanan->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . ucwords($row['id_simpanan']) . "</td>";
                        echo "<td>" . ucwords($row['id_anggota']) . "</td>";
                        echo "<td>" . ucwords($row['nama_anggota']) . "</td>";
                        echo "<td>" . ucwords($row['jenis_simpanan']) . "</td>";
                        echo "<td>" . number_format($row['nominal'], 2, ',', '.') . "</td>"; // Format nominal
                        echo "<td>" . date('d-m-Y', strtotime($row['tanggal_setor'])) . "</td>"; // Format tanggal
                        echo '<td><a class="dropdown-item" href="javascript:void(0);" onclick="editSimpanan(\'' . $row['id_simpanan'] . '\')"><i class="bx bx-edit-alt me-1"></i> Edit</a></td>';
                        echo '<td><a class="dropdown-item" href="javascript:void(0);" onclick="deleteSimpanan(\'' . $row['id_simpanan'] . '\')"><i class="bx bx-trash me-1"></i> Delete</a></td>'; // Menggunakan ID simpanan
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>Tidak ada data</td></tr>"; // Ubah jumlah kolom di sini
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Tabel Pinjaman -->
<div class="card mt-4">
    <h5 class="card-header">Total Pinjaman Anggota</h5>
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr>
                    <th>ID Pinjaman</th>
                    <th>ID Anggota</th>
                    <th>Nama Anggota</th>
                    <th>Jumlah Pinjaman</th>
                    <th>Tanggal Pinjam</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_pinjaman->num_rows > 0) {
                    while ($row = $result_pinjaman->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . ucwords($row['id_pinjaman']) . "</td>";
                        echo "<td>" . ucwords($row['id_anggota']) . "</td>";
                        echo "<td>" . ucwords($row['nama_anggota']) . "</td>";
                        echo "<td>" . number_format($row['nominal'], 2, ',', '.') . "</td>"; // Ubah nominal menjadi jumlah_pinjaman
                        echo "<td>" . date('d-m-Y', strtotime($row['tanggal_pinjam'])) . "</td>";
                        echo '<td><a class="dropdown-item" href="javascript:void(0);" onclick="editPinjaman(\'' . $row['id_pinjaman'] . '\')"><i class="bx bx-edit-alt me-1"></i> Edit</a></td>';
                        echo '<td><a class="dropdown-item" href="javascript:void(0);" onclick="deletePinjaman(\'' . $row['id_pinjaman'] . '\')"><i class="bx bx-trash me-1"></i> Delete</a></td>'; // Menggunakan ID pinjaman
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>Tidak ada data</td></tr>"; // Ubah jumlah kolom di sini
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function editPinjaman(id_pinjaman) {
    // Arahkan pengguna ke halaman edit dengan ID pinjaman
    window.location.href = 'edit_pinjaman.php?id=' + encodeURIComponent(id_pinjaman);
}

function editSimpanan(id_simpanan) {
    // Arahkan pengguna ke halaman edit dengan ID simpanan
    window.location.href = 'edit_simpanan.php?id=' + encodeURIComponent(id_simpanan);
}

function deleteSimpanan(id_simpanan) {
    console.log("ID Simpanan yang akan dihapus:", id_simpanan);
    if (confirm("Apakah Anda yakin ingin menghapus transaksi simpanan ini?")) {
        $.ajax({
            type: "POST",
            url: "delete_simpanan.php",
            data: { id_simpanan: id_simpanan },
            dataType: "json",
            success: function(response) {
                console.log("Response dari server:", response);
                if (response.success) {
                    alert(response.message);
                    location.reload();
                } else {
                    alert("Gagal menghapus simpanan: " + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("XHR:", xhr);
                console.error("Status:", status);
                console.error("Error:", error);
                alert("Terjadi kesalahan saat menghapus simpanan.");
            }
        });
    }
}

function deletePinjaman(id_pinjaman) {
    console.log("ID Pinjaman yang akan dihapus:", id_pinjaman);
    if (confirm("Apakah Anda yakin ingin menghapus pinjaman ini?")) {
        $.ajax({
            type: "POST",
            url: "delete_pinjaman.php",
            data: { id_pinjaman: id_pinjaman },
            dataType: "json",
            success: function(response) {
                console.log("Response dari server:", response);
                if (response.success) {
                    alert(response.message);
                    location.reload();
                } else {
                    alert("Gagal menghapus pinjaman: " + response.message);
                }
            },
            error: function(xhr, status, error) {
                console.error("XHR:", xhr);
                console.error("Status:", status);
                console.error("Error:", error);
                alert("Terjadi kesalahan saat menghapus pinjaman.");
            }
        });
    }
}
</script>





                        <!-- Bootstrap Table with Header - Footer -->
                    </div>
                    <!-- / Content -->
                </div>
                <!-- / Content wrapper -->

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