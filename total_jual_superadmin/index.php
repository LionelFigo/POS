<?php 
require_once "../database/koneksi.php";

$authority = @$_SESSION['peran'];
if($authority != 's'){
  echo '<script>alert("User melakukan Cross Authority")</script>';
  echo '<script>window.location.href="../logout.php"</script>';
} else {

$tgl_mulai   = isset($_GET['tgl_mulai']) ? $_GET['tgl_mulai'] : date('Y-m-01');
$tgl_selesai = isset($_GET['tgl_selesai']) ? $_GET['tgl_selesai'] : date('Y-m-d');

$ambil_nota_jual = mysqli_query($koneksi, "SELECT * FROM nota_penjualan WHERE tgl_penjualan BETWEEN '$tgl_mulai' AND '$tgl_selesai' ORDER BY tgl_penjualan DESC") or die(mysqli_error($koneksi));
$grand_total = 0;
while ($data = mysqli_fetch_assoc($ambil_nota_jual)){
  $st = $data['status_bayar'];
  $total_beli = $data['total_penjualan'];

  if($st == 'L'){
    $grand_total += $total_beli;
  }elseif($st == '2'){
    $grand_total += ($total_beli * 0.25);
  }elseif($st == '3'){
    $grand_total += ($total_beli * 0.50);
  }else{
    $grand_total += ($total_beli * 0.75);
  }
}
if(mysqli_num_rows($ambil_nota_jual) > 0){
    mysqli_data_seek($ambil_nota_jual, 0);
}
?>
<!DOCTYPE html>
<html lang="en">
<?php include '../css.php'; ?>
<?php $hal = 'total_jual_superadmin';  ?>

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
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><b>Laporan Total Penjualan</b></h3>
            </div>
            <div class="card-body">
                <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#modal-filter">
                    <i class="fas fa-calendar-alt"></i> Filter Tanggal
                </button>
                <a href="?" class="btn btn-secondary mb-3"><i class="fas fa-sync-alt"></i> Reset Filter</a>
                <a href="pdf.php?tgl_mulai=<?= $tgl_mulai ?>&tgl_selesai=<?= $tgl_selesai ?>" target="_blank" class="btn btn-danger mb-3"><i class="fas fa-file-pdf"></i> Cetak PDF</a>

                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Nota</th>
                            <th>Kode Supplier</th>
                            <th>Tanggal Jual</th>
                            <th>Total Jual (Per Nota)</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $rv = mysqli_num_rows($ambil_nota_jual);
                        if($rv > 0){
                            $no = 1;
                            while($data_nota_jual = mysqli_fetch_assoc($ambil_nota_jual)){
                                $kode_nota       = $data_nota_jual['kode_nota'];
                                $kode_supplier   = $data_nota_jual['kode_supplier'];
                                $tgl_penjualan   = $data_nota_jual['tgl_penjualan'];
                                $total_penjualan = $data_nota_jual['total_penjualan'];
                                $status          = $data_nota_jual['status_bayar'];
                                $keterangan      = $data_nota_jual['keterangan'];
                                ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $kode_nota ?></td>
                                    <td><?= $kode_supplier ?></td>
                                    <td><?= $tgl_penjualan ?></td>
                                    <td>Rp <?= number_format($total_penjualan, 0, ',', '.'); ?></td>
                                    <td>
                                        <?php
                                        if($status == 'L' || $status == '1'){
                                            echo 'Lunas';
                                        }elseif($status == '4'){
                                            echo 'Dibayar 75%';
                                        }elseif($status == '3'){
                                            echo 'Dibayar 50%';
                                        }else{
                                            echo 'Dibayar 25%';
                                        }
                                        ?>
                                    </td>
                                    <td><?= $keterangan ?></td>
                                </tr>
                                <?php
                            }
                            ?>
                            <tr>
                              <td colspan="4"><b>Total Penjualan</b></td>
                              <td colspan="3" align="center"><b>Rp <?= number_format($grand_total, 0, ',', '.'); ?></b></td>
                            </tr>
                            <?php
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
      </div>
  </section>
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

<div class="modal fade" id="modal-filter">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fas fa-filter"></i> Pilih Rentang Tanggal</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="GET">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tgl_mulai">Dari Tanggal (Mulai)</label>
                        <input type="date" name="tgl_mulai" class="form-control" id="tgl_mulai" value="<?= $tgl_mulai ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="tgl_selesai">Sampai Tanggal (Selesai)</label>
                        <input type="date" name="tgl_selesai" class="form-control" id="tgl_selesai" value="<?= $tgl_selesai ?>" required>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Tampilkan Data</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- REQUIRED SCRIPTS -->
<?php include '../script.php'; ?>
</body>
</html>
<?php 
}
?>