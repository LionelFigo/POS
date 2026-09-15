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
<?php $hal = 'nota_konsinyasi_superadmin'; ?>
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
                <h3 class="card-title"><b>Data Nota Konsinyasi</b></h3>
            </div>
            <div class="card-body">
                <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#modal-tambah"><i class="fas fa-plus"></i> Tambah Nota</button>

                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Konsinyasi</th>
                            <th>Kode Supplier</th>
                            <th>Tanggal Masuk</th>
                            <th>Tanggal Jatuh Tempo</th>
                            <th>Total Nilai</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $ambil_nota = mysqli_query($koneksi, "SELECT * FROM nota_konsinyasi ORDER BY tgl_masuk DESC") or die(mysqli_error($koneksi));
                        $rv = mysqli_num_rows($ambil_nota);
                        if($rv > 0){
                            $no = 1;
                            while($data_nota = mysqli_fetch_assoc($ambil_nota)){
                                $kode_konsinyasi = $data_nota['kode_konsinyasi'];
                                $kode_supplier = $data_nota['kode_supplier'];
                                
                                $ambil_sup = mysqli_query($koneksi, "SELECT nama_supplier FROM supplier WHERE kode_supplier = '$kode_supplier'") or die(mysqli_error($koneksi));
                                $data_sup = mysqli_fetch_assoc($ambil_sup);
                                $nama_supplier = $data_sup ? $data_sup['nama_supplier'] : $kode_supplier;

                                $tgl_masuk = $data_nota['tgl_masuk'];
                                $tgl_jatuh_tempo = !empty($data_nota['tgl_jatuh_tempo']) ? $data_nota['tgl_jatuh_tempo'] : '-';
                                $total_nilai = number_format($data_nota['total_nilai'], 0, ',', '.');
                                $status = $data_nota['status'];
                                $keterangan = $data_nota['keterangan'];
                                ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $kode_konsinyasi ?></td>
                                    <td><?= $kode_supplier ?> - <?= $nama_supplier ?></td>
                                    <td><?= $tgl_masuk ?></td>
                                    <td><?= $tgl_jatuh_tempo ?></td>
                                    <td>Rp <?= $total_nilai ?></td>
                                    <td>
                                        <?php
                                        if($status == 'selesai'){
                                            echo '<span class="badge badge-success">Selesai</span>';
                                        }else{
                                            echo '<span class="badge badge-warning">Proses</span>';
                                        }
                                        ?>
                                    </td>
                                    <td><?= $keterangan ?></td>
                                    <td>
                                        <a href="../detail_nota_konsinyasi?kode_konsinyasi=<?= $kode_konsinyasi ?>" type="button" class="btn btn-success btn-sm" title="Detail"><i class="fas fa-list"></i></a>
                                        <a href="edit.php?kode_konsinyasi=<?= $kode_konsinyasi ?>" type="button" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                        <a href="hapus.php?kode_konsinyasi=<?= $kode_konsinyasi ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus nota ini?')" type="button" class="btn btn-danger btn-sm" title="Hapus"><i class="fas fa-trash"></i></a>
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
      </div>
  </section>
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
  </aside>
  <!-- /.control-sidebar -->

   <div class="modal fade" id="modal-tambah">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Tambah Nota Konsinyasi</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="tambah.php" method="post">
        <div class="modal-body">
            <div class="form-group">
                <label for="kode_konsinyasi">Kode Konsinyasi</label>
                <input type="text" maxlength="10" name="kode_konsinyasi" class="form-control" id="kode_konsinyasi" placeholder="Masukkan Kode Konsinyasi" required>
            </div>
            <div class="form-group">
                <label for="kode_supplier">Supplier</label>
                <select name="kode_supplier" class="form-control" id="kode_supplier" required>
                    <option value="">-- Pilih Supplier --</option>
                    <?php
                    $ambil_supplier = mysqli_query($koneksi, "SELECT * FROM supplier") or die(mysqli_error($koneksi));
                    while($s = mysqli_fetch_assoc($ambil_supplier)){
                        echo '<option value="'.$s['kode_supplier'].'">'.$s['kode_supplier'].' - '.$s['nama_supplier'].'</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="tgl_masuk">Tanggal Masuk</label>
                <input type="date" name="tgl_masuk" class="form-control" id="tgl_masuk" value="<?= date('Y-m-d') ?>" required>
            </div>
            <div class="form-group">
                <label for="tgl_jatuh_tempo">Tanggal Jatuh Tempo</label>
                <input type="date" name="tgl_jatuh_tempo" class="form-control" id="tgl_jatuh_tempo">
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select name="status" class="form-control" id="status">
                    <option value="proses">Proses</option>
                    <option value="selesai">Selesai</option>
                </select>
            </div>
            <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <input type="text" name="keterangan" class="form-control" id="keterangan" placeholder="Tambahkan Keterangan">
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