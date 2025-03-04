<?php
session_start();

// Sertakan file konfigurasi untuk koneksi database
include 'config.php';

// Menampilkan pesan kesalahan jika ada
if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
    unset($_SESSION['error']);
}

// Proses login
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Cek akun dari database
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($config, $query); // Menggunakan variabel $config

    // Periksa apakah ada hasil dari query
    if ($user = mysqli_fetch_assoc($result)) {
        // Verifikasi password jika disimpan menggunakan hash
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;

            // Cek role user
            if ($user['role'] == 'admin') {
                header('Location: admin_dashboard.php');
            } else if ($user['role'] == 'member') {
                header('Location: member_dashboard.php');
            }
            exit();
        } else {
            $_SESSION['error'] = 'Email atau kata sandi salah';
            header('Location: login.php');
            exit();
        }
    } else {
        $_SESSION['error'] = 'Email atau kata sandi salah';
        header('Location: login.php');
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Koperasi Simpan Pinjam</title>
    <meta name="description" content="Sistem Koperasi Simpan Pinjam" />
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon/favicon.ico" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="../assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/css/demo.css" />
    <link rel="stylesheet" href="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="../assets/vendor/css/pages/page-auth.css" />
    <script src="../assets/vendor/js/helpers.js"></script>
    <script src="../assets/js/config.js"></script>
  </head>
  <body>
    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Login Card -->
          <div class="card">
            <div class="card-body">
              <h4 class="mb-2">Selamat Datang di Koperasi Simpan Pinjam</h4>
              <p class="mb-4">Silakan masuk ke akun Anda untuk mengelola simpanan dan pinjaman Anda</p>

              <form id="formAuthentication" class="mb-3" action="login.php" method="POST">
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="text" class="form-control" id="email" name="email" placeholder="Masukkan Email Anda" autofocus />
                </div>
                <div class="mb-3 form-password-toggle">
                  <div class="d-flex justify-content-between">
                    <label class="form-label" for="password">Kata Sandi</label>
                    <a href="forgot-password.php">
                      <small>Lupa Kata Sandi?</small>
                    </a>
                  </div>
                  <div class="input-group input-group-merge">
                    <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  </div>
                </div>
                <div class="mb-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember-me" />
                    <label class="form-check-label" for="remember-me"> Ingat Saya </label>
                  </div>
                </div>
                <div class="mb-3">
                  <button class="btn btn-primary d-grid w-100" type="submit">Masuk</button>
                </div>
              </form>

              <p class="text-center">
                <span>Baru di platform kami?</span>
                <a href="register.php">
                  <span>Buat akun baru</span>
                </a>
              </p>
            </div>
          </div>
          <!-- /Login Card -->
        </div>
      </div>
    </div>

    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../assets/vendor/js/menu.js"></script>
    <script src="../assets/js/main.js"></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
  </body>
</html>
