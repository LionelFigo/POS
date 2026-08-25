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
<?php $hal = 'supplier_superadmin'; ?>
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
                <h3 class="card-title"><b>Data Supplier<b></h3>
            </div>
            <div class="card-body">
                <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#modal-tambah"><i class="fas fa-plus"></i> Tambah Supplier</button>

                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <td width="5%">No</td>
                            <td>Kode Supplier</td>
                            <td>Nama Supplier</td>
                            <td>Nama Pic</td>
                            <td>Kontak Pic</td>
                            <td>Alamat Supplier</td>
                            <td>Website</td>
                            <td>Akun Instagram</td>
                            <td>Akun TikTok</td>
                            <td>Aksi</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $panggil_supplier = mysqli_query($koneksi, "SELECT * from supplier")or die(mysqli_error($koneksi));
                        $rv = mysqli_num_rows($panggil_supplier);
                        if($rv > 0){
                            $no = 1;
                            while ($data = mysqli_fetch_array($panggil_supplier)) {
                                $kode = $data['kode_supplier'];
                                $nama = $data['nama_supplier'];
                                $nama_pic = $data['nama_pic'];
                                $kontak_pic = $data['kontak_pic'];
                                $alamat = $data['alamat_supplier'];
                                $website = $data['website'];
                                $akun_ig = $data['akun_ig'];
                                $akun_tiktok = $data['akun_tiktok'];
                                ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $kode ?></td>
                                    <td><?= $nama ?></td>
                                    <td><?= $nama_pic ?></td>
                                    <td><?= $kontak_pic ?></td>
                                    <td><?= $alamat ?></td>
                                    <td><?= $website ?></td>
                                    <td><?= $akun_ig ?></td>
                                    <td><?= $akun_tiktok ?></td>
                                    <td>
                                        <a href="edit.php?kode=<?= $kode ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                        <a href="hapus.php?kode=<?= $kode ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin Hapus?')"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php
                            }
                        }else{
                            ?>
                            <tr>
                                <td colspan="10" align="center">Tidak Ada Data Supplier</td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    <!-- /.content -->
      </div>
    </div>
  </section>
  <!-- /.content-wrapper -->

  <!-- ControFl Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
    <div class="modal fade" id="modal-tambah">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Tambah Supplier </h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="tambah.php" method="post">
            <div class="modal-body">
                <div class="form-group">
                    <label for="kode_supplier">Kode Supplier</label>
                    <input type="text" maxlength="10" name="kode_supplier" class="form-control" id="kode_supplier" placeholder="Masukkan Kode Supplier" required>
                </div>
                <div class="form-group">
                    <label for="nama_supplier">Nama Supplier</label>
                    <input type="text" name="nama_supplier" class="form-control" id="nama_supplier" placeholder="Masukkan Nama Supplier" required>
                </div>
                <div class="form-group">
                    <label for="nama_pic">Nama Pic</label>
                    <input type="text" name="nama_pic" class="form-control" id="nama_pic" placeholder="" required>
                </div>
                <div class="form-group">
                    <label for="kontak_pic">Kontak Pic</label>
                    <input type="text" name="kontak_pic" class="form-control" id="kontak_pic" placeholder="Masukkan Kontak" required>
                </div>
                <div class="form-group">
                    <label for="alamat_supplier">Alamat Supplier</label>
                    <input type="text" name="alamat_supplier" class="form-control" id="alamat_supplier" placeholder="Masukkan Alamat Supplier">
                </div>
                <div class="form-group">
                    <label for="website">Website</label>
                    <input type="text" name="website" class="form-control" id="website" placeholder="Masukkan Nama Website">
                </div>
                <div class="form-group">
                    <label for="akun_ig">Akun Instagram</label>
                    <input type="text" name="akun_ig" class="form-control" id="akun_ig" placeholder="Masukkan Nama Akun Instagram">
                </div>
                <div class="form-group">
                    <label for="akun_tiktok">Akun TikTok</label>
                    <input type="text" name="akun_tiktok" class="form-control" id="akun_tiktok" placeholder="Masukkan Nama Supplier">
                </div>
            </div>
            <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
              <button type="submit" class="btn btn-primary" name="btn_tambah">Tambah</button>
            </div>
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>

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