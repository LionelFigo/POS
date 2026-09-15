<?php 
require_once "../database/koneksi.php";

$authority = @$_SESSION['peran'];
if($authority != 's'){
  echo '<script>alert("User melakukan Cross Authority")</script>';
  echo '<script>window.location.href="../logout.php"</script>';
}else {
?>
<!DOCTYPE html>
<html lang="en">
<?php include '../css.php'; ?>
<?php $hal = 'barang_superadmin'; ?>
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
          <a href="#" class="d-block">SISTEM POS</a>
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
  <section class="content">
      <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Data Barang</h3>
            </div>
            <div class="card-body">
                <?php
                    $kode = @$_GET['kode'];
                    $query_ambil = mysqli_query($koneksi, "SELECT * from barang where kode_brg = '$kode'")or die(mysqli_error($koneksi));
                    $data = mysqli_fetch_assoc($query_ambil);

                ?>
                <form action="ubah.php" method="POST">
                    <div class="form-group">
                    <label for="kode_barang">Kode Barang</label>
                    <input type="text" maxlength="10" value="<?= $kode ?>" name="kode_barang" class="form-control" id="kode_barang" placeholder="Masukkan Kode Barang" readonly>
                    </div>
                    <div class="form-group">
                        <label for="kode_supplier">Kode Supplier</label>
                        <input type="text" maxlength="10" value="<?= $data['kode_supplier'] ?>" name="kode_supplier" class="form-control" id="kode_supplier" placeholder="Masukkan Kode Supplier" readonly>
                    </div>
                    <div class="form-group">
                        <label for="nama_barang">Nama Barang</label>
                        <input type="text" value="<?= $data['nama_brg'] ?>" name="nama_barang" class="form-control" id="nama_barang" placeholder="Masukkan Nama Supplier" required>
                    </div>
                    <div class="form-group">
                        <label for="merk">Merk</label>
                        <input type="text" name="merk" value="<?= $data['merk'] ?>" class="form-control" id="merk" placeholder="Masukkan Nama Merk" required>
                    </div>
                    <div class="form-group">
                        <label for="stok">Stok</label>
                        <input type="number" name="stok" value="<?= $data['stok'] ?>" class="form-control" id="stok" placeholder="Masukkan Jumlah Stok" required>
                    </div>
                    <div class="form-group">
                        <label for="harga_jual">Harga Jual</label>
                        <input type="number" value="<?= $data['harga_jual'] ?>" name="harga_jual" class="form-control" id="harga_jual" placeholder="Masukkan Harga Jual">
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="submit" name="btn_edit" class="btn btn-primary btn-block">Edit</button>
                    </div>
                </form>
            </div>
        </div>
      </div>
  </section>
  </div>
  <!-- /.content-wrapper -->

  <!-- ControFl Sidebar -->
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