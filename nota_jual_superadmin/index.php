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
<?php $hal = 'nota_jual_superadmin'; ?>
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
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><b>Data Nota</b></h3>
            </div>
            <div class="card-body">
                <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#modal-tambah"><i class="fas fa-plus"></i> Tambah Nota</button>

                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Nota</th>
                            <th>kode Supplier</th>
                            <th>Tanggal Beli</th>
                            <th>Total Beli</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $ambil_nota_beli = mysqli_query($koneksi, "SELECT * from nota_penjualan")or die(mysqli_error($koneksi));
                        $rv = mysqli_num_rows($ambil_nota_beli);
                        if($rv > 0){
                            $no = 1;
                            while($data_nota_beli = mysqli_fetch_assoc($ambil_nota_beli)){
                                $kode_nota = $data_nota_beli['kode_nota'];
                                $kode_supplier = $data_nota_beli['kode_supplier'];
                                $tgl_penjualan = $data_nota_beli['tgl_penjualan'];
                                $total_penjualan = $data_nota_beli['total_penjualan'];
                                $status = $data_nota_beli['status_bayar'];
                                $keterangan = $data_nota_beli['keterangan'];
                                ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $kode_nota ?></td>
                                    <td><?= $kode_supplier ?></td>
                                    <td><?= $tgl_penjualan ?></td>
                                    <td><?= $total_penjualan ?></td>
                                    <td>
                                        <?php
                                        if($status == 'L'){
                                            echo 'Lunas';
                                        }elseif($status == '2'){
                                            echo 'Dibayar 75%';
                                        }elseif($status == '3'){
                                            echo 'Dibayar 50%';
                                        }else{
                                            echo 'Dibayar 25%';
                                        }
                                        ?>
                                    </td>
                                    <td><?= $keterangan ?></td>
                                    <td>
                                        <a href="../detail_notajual_superadmin?kode_nota=<?= $kode_nota ?>" type="button" class="btn btn-success btn-sm"><i class="fas fa-list"></i></a>
                                        <a href="edit.php?kode_nota=<?= $kode_nota ?>" type="button" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                        <a href="hapus.php?kode_nota=<?= $kode_nota ?>" type="button" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
      </div> 
    </div>
  
  <!-- Main content -->
  <section class="content">
      <div class="container-fluid">
    <!-- /.content -->
      </div>
  </section>
  </div>
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
            <h4 class="modal-title">Tambah Data </h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="tambah.php" method="post">
        <div class="modal-body">
            <div class="form-group">
                <label for="kode_nota">Kode Nota</label>
                <input type="text" maxlength="10" name="kode_nota" class="form-control" id="kode_nota" placeholder="Masukkan Kode Nota" required>
            </div>
            <div class="form-group">
                <label for="kode_supplier">Kode Supplier</label>
                <input type="text" maxlength="10" name="kode_supplier" class="form-control" id="kode_supplier" placeholder="Masukkan Kode Supplier" required>
            </div>
            <div class="form-group">
                <label for="tgl">Tanggal Beli</label>
                <input type="date" name="tgl_penjualan" class="form-control" id="tgl_penjualan" required>
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" class="form-control">
                    <option value="L">Lunas</option>
                    <option value="2">75%</option>
                    <option value="3">50%</option>
                    <option value="4">25%</option>
                </select>
            </div>
            <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <input type="textarea" name="keterangan" class="form-control" id="keterangan" placeholder="Tambahkan Keterangan">
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