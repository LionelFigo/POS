<?php require_once '../database/koneksi.php'; ?>
<!DOCTYPE html>
<html lang="en">
<?php include '../css.php'; 
$authority = @$_SESSION['peran'];
if($authority != 'k'){
  echo '<script>alert("User melakukan Cross Authority")</script>';
  echo '<script>window.location.href="../logout.php"</script>';
}else {
?>
<?php $hal = 'sandi_kasir'; ?>
<!--
`body` tag options:

  Apply one or more of the following classes to to the body tag
  to get the desired effect

  * sidebar-collapse
  * sidebar-mini
-->
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
   <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->

      <!-- Messages Dropdown Menu -->
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-user"></i>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-user mr-2"></i> Profil
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-sign-out-alt mr-2"></i> Keluar
          </a>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        
        <div class="info">
          <a href="#" class="d-block">SISTEM MANAJEMEN</a>
        </div>
      </div>

      <!-- Sidebar Menu --> 
      <?php include '../sidebar_superadmin.php'; ?>
      <!-- /.sidebar-menu -->
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
    
    <!-- /.content-header -->
      </div> 
    </div>

  <!-- Main content -->
  <div class="content">
      <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4">
                <div class="card-primary card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-lock"></i> Ganti Password</h3>
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
                        <div class="form-group">
                            <label for="sandi_lama">Password Lama</label>
                            <?php
                            $pengguna = @$_SESSION['username'];
                            ?>
                            <input type="text" value="<?= $pengguna ?>" name="pengguna" class="form-control" hidden>
                            <input type="password" name="sandi_lama" class="form-control"
                            placeholder="Input Password lama" required >
                        </div>
                        <div class="form-group">
                            <label for="sandi_baru">Password Baru</label>
                            <input type="password" name="sandi_baru" class="form-control"
                            placeholder="Input Password baru max 10 char" maxlength="10" required >
                        </div>
                        <div class="form-group">
                            <label for="pin">PIN</label>
                            <input type="number" name="pin" class="form-control"
                            placeholder="Input PIN" maxlength="6" required >
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block" name="btn_edit">
                                <i class="fas fa-edit"> Edit</i>
                            </button>
                        </form>
                        <?php
                        if (isset($_POST['btn_edit'])) { //trigger button ketika ditekan
                            $pengguna = trim(mysqli_real_escape_string($koneksi, $_POST['pengguna'])); //menyimpan value pada var lokal
                            
                            $query_pengguna = mysqli_query($koneksi, "SELECT sandi,pin FROM user 
                            WHERE username = '$pengguna'") or die(mysqli_error($koneksi));
                            $data = mysqli_fetch_assoc($query_pengguna); //mendefinisikan variabel data dari query
                            $password = $data['sandi'];//mennampung value sandi pada array(data) kedalam varabel sandi / $sandi
                            $pin = $data['pin'];//mennampung value pin pada array(data) kedalam varabel pin / $pin

                            $input_password = sha1(trim(mysqli_real_escape_string($koneksi, $_POST['password_lama']))); //simpan value inputan user
                            $input_password_baru = sha1(trim(mysqli_real_escape_string($koneksi, $_POST['password_baru'])));//simpan value inputan user
                            $input_pin = sha1(trim(mysqli_real_escape_string($koneksi, $_POST['pin'])));//simpan value inputan user
                            
                            if ($input_password == $password && $input_pin == $pin){
                                $query_update= mysqli_query($koneksi,"UPDATE user SET sandi ='$input_password_baru'
                                WHERE username = '$pengguna'") or die(mysqli_error($koneksi));
                                echo '<script> alert("Password berhasil di update!") </script>';
                                echo '<script> window.location.href="index.php" </script>';
                            } else {
                                echo '<script> alert("Password/pin salah!") </script>';
                                echo '<script> window.location.href="index.php" </script>';
                            }
                        }
                        ?>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    <!-- /.content -->
      </div>
      </div>
</div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
  <?php include '../footer.php'; ?>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<?php include '../script.php'; ?>
</body>
</html>
<?php 
}
?>