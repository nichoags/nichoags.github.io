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
$penarikan_message = '';
$saldo = 0;

// Ambil saldo dari tabel simpanan berdasarkan jenis
$stmt = $config->prepare("SELECT jenis_simpanan, nominal FROM simpanan WHERE id_anggota = ?");
$stmt->bind_param("s", $id_anggota);
$stmt->execute();
$result = $stmt->get_result();
$simpanan = [];
while ($row = $result->fetch_assoc()) {
    $simpanan[$row['jenis_simpanan']] = $row['nominal']; // Simpan saldo per jenis
}
$stmt->close();

// Hitung total saldo
$saldo = array_sum($simpanan);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nominal = $_POST['nominal'] ?? 0; // Ambil jumlah penarikan
    $jenis_simpanan = $_POST['jenis_simpanan'] ?? ''; // Ambil jenis simpanan
    $tanggal_pinjam = date('Y-m-d'); // Tanggal pinjaman otomatis menggunakan tanggal hari ini
    $id_pinjaman = 'pj' . str_pad(mt_rand(1, 99999999), 9, '0', STR_PAD_LEFT); // Menghasilkan ID pinjaman

    // Validasi jumlah penarikan
    if ($nominal <= 0) {
        $penarikan_message .= "Jumlah penarikan tidak valid. Harus lebih dari Rp. 0.<br>";
    } elseif (!isset($simpanan[$jenis_simpanan]) || $nominal > $simpanan[$jenis_simpanan]) {
        $penarikan_message .= "Saldo Anda untuk jenis simpanan '{$jenis_simpanan}' tidak cukup. Saldo Anda adalah Rp. " . number_format($simpanan[$jenis_simpanan], 0, ',', '.') . ".<br>";
    }

    // Simpan data jika valid
    if (empty($penarikan_message)) {
        // Logika untuk menyimpan penarikan ke database
        $stmt = $config->prepare("INSERT INTO pinjaman (id_pinjaman, nominal, tanggal_pinjam, id_anggota) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $id_pinjaman, $nominal, $tanggal_pinjam, $id_anggota);

        if ($stmt->execute()) {
            // Update saldo di tabel simpanan hanya untuk jenis simpanan yang dipilih
            $stmt = $config->prepare("UPDATE simpanan SET nominal = nominal - ? WHERE id_anggota = ? AND jenis_simpanan = ?");
            $stmt->bind_param("iss", $nominal, $id_anggota, $jenis_simpanan);

            if ($stmt->execute()) {
                $penarikan_message = "Penarikan berhasil dari Jenis Simpanan '{$jenis_simpanan}', Sejumlah '{$nominal}'. Saldo Anda telah diperbarui.";
            } else {
                $penarikan_message = "Gagal memperbarui saldo: " . $stmt->error;
            }
        } else {
            $penarikan_message = "Gagal menyimpan data: " . $stmt->error;
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
                        <h4 class="fw-bold py-3 mb-4">Penarikan Simpanan</h4>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <?php if ($penarikan_message): ?>
                                            <div class="alert alert-info" role="alert">
                                                <?php echo $penarikan_message; ?>
                                            </div>
                                        <?php endif; ?>
                                        <form method="POST">
                                            <div class="mb-3">
                                                <label for="jenis_simpanan" class="form-label">Pilih Jenis Simpanan</label>
                                                <select class="form-select" id="jenis_simpanan" name="jenis_simpanan" required>
                                                    <option value="" disabled selected>Pilih jenis simpanan</option>
                                                    <?php foreach ($simpanan as $jenis_simpanan => $nominal): ?>
                                                        <option value="<?php echo $jenis_simpanan; ?>"><?php echo $jenis_simpanan . ' - Saldo: Rp. ' . number_format($nominal, 0, ',', '.'); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="nominal" class="form-label">Nominal Penarikan</label>
                                                <input type="number" class="form-control" id="nominal" name="nominal" min="1" required />
                                            </div>
                                            <button type="submit" class="btn btn-primary">Tarik Simpanan</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <footer class="content-footer footer bg-footer-theme">
                    <div class="footer-container">
                        <div class="footer-content">
                            <span class="footer-text">© 2024 Koperasi. All rights reserved.</span>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
    </div>

    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../assets/vendor/js/menu.js"></script>
    <script src="../assets/vendor/libs/apex-charts/apex-charts.js"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>
