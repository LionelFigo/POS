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
                <h3 class="card-title"><b>Data Barang<b></h3>
            </div>
            <div class="card-body">
                <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#modal-tambah"><i class="fas fa-plus"></i> Tambah Barang</button>

                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Kode Barang</th>
                            <th>Kode Supplier</th>
                            <th>Nama Barang</th>
                            <th>Merk</th>
                            <th>Stok</th>
                            <th>Rata Harga Beli</th>
                            <th>Harga Jual</th>
                            <th>Gambar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $panggil_supplier = mysqli_query($koneksi, "SELECT * from barang")or die(mysqli_error($koneksi));
                        $rv = mysqli_num_rows($panggil_supplier);
                        if($rv > 0){
                            $no = 1;
                            while ($data = mysqli_fetch_array($panggil_supplier)) {
                                $kode = $data['kode_brg'];
                                $kode_supplier = $data['kode_supplier'];
                                $nama_brg = $data['nama_brg'];
                                $merk = $data['merk'];
                                $stok = $data['stok'];
                                $harga_beli = $data['rata_harga_beli'];
                                $harga_jual = $data['harga_jual'];
                                $foto = $data['foto_brg'];
                                ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $kode ?></td>
                                    <td><?= $kode_supplier ?></td>
                                    <td><?= $nama_brg ?></td>
                                    <td><?= $merk ?></td>
                                    <td><?= $stok ?></td>
                                    <td><?= $harga_beli ?></td>
                                    <td><?= $harga_jual ?></td>
                                    <td>
                                      <button data-toggle="modal" data-target="#modal-foto" class="btn btn-default" data-kode="<?= $kode ?>">
                                        <img src="<?= (!empty($foto)) ? $foto : '../asset_web/img/barang_icon.jpg' ?>" width="50px" alt="gaada foto" >
                                      </button>
                                    </td>
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
                                <td colspan="10" align="center">Tidak Ada Data Barang</td>
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
              <h4 class="modal-title">Tambah Barang </h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="tambah.php" method="post">
            <div class="modal-body">
                <div class="form-group">
                    <label for="kode_barang">Kode Barang</label>
                    <input type="text" maxlength="10" name="kode_barang" class="form-control" id="kode_barang" placeholder="Masukkan Kode Barang" required>
                </div>
                <div class="form-group">
                    <label for="kode_supplier">Kode Supplier</label>
                    <input type="text" maxlength="10" name="kode_supplier" class="form-control" id="kode_supplier" placeholder="Masukkan Kode Supplier" required>
                </div>
                <div class="form-group">
                    <label for="nama_barang">Nama Barang</label>
                    <input type="text" name="nama_barang" class="form-control" id="nama_barang" placeholder="Masukkan Nama Supplier" required>
                </div>
                <div class="form-group">
                    <label for="merk">Merk</label>
                    <input type="text" name="merk" class="form-control" id="merk" placeholder="Masukkan Nama Merk" required>
                </div>
                <div class="form-group">
                    <label for="stok">Stok</label>
                    <input type="number" name="stok" class="form-control" id="stok" placeholder="Masukkan Jumlah Stok" required>
                </div>
                <div class="form-group">
                    <label for="rata_harga_beli">Harga Beli</label>
                    <input type="number" name="rata_harga_beli" class="form-control" id="rata_harga_beli" placeholder="Masukkan Harga Beli">
                </div>
                <div class="form-group">
                    <label for="harga_jual">Harga Jual</label>
                    <input type="number" name="harga_jual" class="form-control" id="harga_jual" placeholder="Masukkan Harga Jual">
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
      <div class="modal fade" id="modal-foto">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Edit Foto Barang </h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form action="foto.php" method="post" enctype="multipart/form-data">
              <div class="modal-body">
                <div class="form-group">
                  <input type="text" name="kode_barang" hidden>
                  <label for="file">Upload Foto</label>
                  <input type="file" class="form-control" name="file_foto" required >
                </div>
              </div>
              <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary" name="btn_foto">Upload</button>
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

<script>
  $('#modal-foto').on('show.bs.modal', function(e){
    var kode = $(e.relatedTarget).data('kode');

    $(e.currentTarget).find('input[name="kode_barang"]').val(kode);
  })
</script>
</body>
</html>
<?php 
}
?>