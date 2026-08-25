<?php 
require_once 'database/koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sistem POS | Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="asset_web/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="asset_web/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="asset_web/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="asset_web/plugins/toastr/toastr.min.css">
  <link rel="stylesheet" href="asset_web/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#">Login Sistem POS</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to start your session</p>

      <form action="" method="post">
        <div class="input-group mb-3">
          <input type="text" name="username" class="form-control" placeholder="Username" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="sandi" class="form-control" placeholder="Password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
        </div>
        
        <div class="social-auth-links text-center mb-3">
          <button type="submit" name="login" class="btn btn-block btn-primary">
            <i class="fas fa-sign-in-alt mr-2"></i> Log In
          </button>
        </div>
      </form>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<script src="asset_web/plugins/jquery/jquery.min.js"></script>
<script src="asset_web/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="asset_web/dist/js/adminlte.min.js"></script>
<script src="asset_web/plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="asset_web/plugins/toastr/toastr.min.js"></script>

<?php
  // Blok 1: Logika saat tombol "Log In" ditekan
  if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $sandi = trim($_POST['sandi']);

    $stmt = mysqli_prepare($koneksi, "SELECT * FROM user WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);

    if ($data && $data['sandi'] == sha1($sandi)) {
      $_SESSION['user_data_for_pin_verification'] = [
        'pin'          => $data['pin'],
        'peran'        => $data['peran'],
        'username'     => $username,
        'pin_attempts' => 0 
      ];
      
      echo "<script>
        $(document).ready(function() {
          $('#modal-primary').modal('show');
        });
      </script>";

    } else {
      echo "<script>
        $(function() {
          var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000
          });
          
          Toast.fire({
            icon: 'error',
            title: 'Username atau Password salah.'
          });
        });
      </script>";
    }
  }

  // Blok 2: Logika Verifikasi PIN (Tanpa sha1)
  if (isset($_POST['cek_pin'])) {
    if (isset($_SESSION['user_data_for_pin_verification'])) {
      $pin_input = trim($_POST['pin']);

      if (!is_numeric($pin_input)) {
          $error_message = "PIN harus berupa angka.";
      } else {
          $pin_database = $_SESSION['user_data_for_pin_verification']['pin'];
          $peran = $_SESSION['user_data_for_pin_verification']['peran'];
          $username = $_SESSION['user_data_for_pin_verification']['username'];

          // Perbandingan PIN langsung tanpa sha1()
          if ($pin_input == $pin_database) {
            
            $_SESSION['username'] = $username;
            $_SESSION['peran'] = $peran;
            unset($_SESSION['user_data_for_pin_verification']);

            if ($peran == 's') {
              echo '<script>window.location = "home_superadmin/";</script>';
            } else if ($peran == 'k') {
              echo '<script>window.location = "home_kasir/";</script>';
            } else {
              echo "<script>alert('Peran tidak dikenali'); window.location = 'index.php';</script>";
            }
            exit();

          } else {
            $_SESSION['user_data_for_pin_verification']['pin_attempts']++;
            $attempts_left = 3 - $_SESSION['user_data_for_pin_verification']['pin_attempts'];

            if ($attempts_left > 0) {
              $error_message = "PIN salah. Sisa percobaan: " . $attempts_left;
            } else {
              unset($_SESSION['user_data_for_pin_verification']);
              $_SESSION['flash_message'] = "Anda telah gagal 3 kali. Silakan login kembali.";
              echo '<script>window.location = "index.php";</script>';
              exit();
            }
          }
      }

      if (isset($error_message)) {
        echo "<script>
          $(document).ready(function() {
            $('#modal-primary').modal('show');
            $('.pin-error-msg').remove();
            $('[name=\"pin\"]').after('<small class=\"text-danger pin-error-msg d-block mt-1\">" . $error_message . "</small>');
          });
        </script>";
      }
    }
  }

  // Blok 3: Update PIN (Tanpa sha1)
  if (isset($_POST['insert_pin'])) {
    if (isset($_SESSION['user_data_for_pin_verification'])) {
      $new_pin = trim($_POST['pin']);
      $username = $_SESSION['user_data_for_pin_verification']['username'];
      $peran = $_SESSION['user_data_for_pin_verification']['peran'];

      if (!is_numeric($new_pin)) {
        echo "<script>
          $(document).ready(function() {
            $('#modal-secondary').modal('show');
            $('.pin-update-error').remove();
            $('#modal-secondary [name=\"pin\"]').after('<small class=\"text-danger pin-update-error d-block mt-1\">PIN baru harus berupa angka.</small>');
          });
        </script>";
      } else {
        // Menyimpan PIN baru langsung sebagai plain text
        $stmt_update = mysqli_prepare($koneksi, "UPDATE user SET pin = ? WHERE username = ?");
        mysqli_stmt_bind_param($stmt_update, "ss", $new_pin, $username);
        
        if (mysqli_stmt_execute($stmt_update)) {
          $_SESSION['username'] = $username;
          $_SESSION['peran'] = $peran;
          unset($_SESSION['user_data_for_pin_verification']);

          if ($peran == 's') {
            echo '<script>window.location = "home_superadmin/";</script>';
          } else if ($peran == 'k') {
            echo '<script>window.location = "home_kasir/";</script>';
          }
          exit();
        } else {
          echo "<script>alert('Gagal mengupdate PIN ke database.');</script>";
        }
      }
    }
  }

  // Modal Verifikasi PIN
  echo '<div class="modal fade" id="modal-primary" data-backdrop="static">
        <div class="modal-dialog">
          <div class="modal-content">
            <form action="" method="post">
              <div class="modal-header bg-primary">
                <h4 class="modal-title">Verifikasi PIN</h4>
              </div>
              <div class="modal-body">
                <input type="password" name="pin" class="form-control" placeholder="Masukkan PIN" required inputmode="numeric" pattern="[0-9]*" autocomplete="one-time-code">
              </div>
              <div class="modal-footer">
                <button type="submit" name="cek_pin" class="btn btn-primary">Verifikasi</button>
              </div>
            </form>
          </div>
        </div>
      </div>';

  // Modal Update PIN
  echo '<div class="modal fade" id="modal-secondary" data-backdrop="static">
        <div class="modal-dialog">
          <div class="modal-content">
            <form action="" method="post">
              <div class="modal-header bg-primary">
                <h4 class="modal-title">Update PIN</h4>
              </div>
              <div class="modal-body">
                <input type="password" name="pin" class="form-control" placeholder="Masukkan PIN Baru" required inputmode="numeric" pattern="[0-9]*" autocomplete="one-time-code">
              </div>
              <div class="modal-footer">
                <button type="submit" name="insert_pin" class="btn btn-primary">Update</button>
              </div>
            </form>
          </div>
        </div>
      </div>';

  // Pesan Flash Error
  if (isset($_SESSION['flash_message'])) {
    echo "<script>
      $(function() {
        toastr.error('" . $_SESSION['flash_message'] . "');
      });
    </script>";
    unset($_SESSION['flash_message']);
  }
?>
</body>
</html>