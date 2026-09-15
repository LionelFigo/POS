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
                <h3 class="card-title"><b>Edit Nota Konsinyasi</b></h3>
            </div>
            <div class="card-body">
                <?php
                $kode_konsinyasi = isset($_GET['kode_konsinyasi']) ? trim(mysqli_real_escape_string($koneksi, $_GET['kode_konsinyasi'])) : (isset($_GET['kode_nota']) ? trim(mysqli_real_escape_string($koneksi, $_GET['kode_nota'])) : '');
                $query_ambil = mysqli_query($koneksi, "SELECT * FROM nota_konsinyasi WHERE kode_konsinyasi = '$kode_konsinyasi'") or die(mysqli_error($koneksi));
                $data_nota = mysqli_fetch_assoc($query_ambil);
                
                if(!$data_nota){
                    echo '<div class="alert alert-danger">Data Nota Konsinyasi tidak ditemukan.</div>';
                    echo '<a href="index.php" class="btn btn-default">Kembali</a>';
                }else{
                    $kode_supplier = $data_nota['kode_supplier'];
                    $tgl_masuk = $data_nota['tgl_masuk'];
                    $tgl_jatuh_tempo = $data_nota['tgl_jatuh_tempo'];
                    $total_nilai = $data_nota['total_nilai'];
                    $status = $data_nota['status'];
                    $keterangan = $data_nota['keterangan'];
                ?>
                <form action="ubah.php" method="post">
                    <div class="form-group">
                        <label for="kode_konsinyasi">Kode Konsinyasi</label>
                        <input type="text" maxlength="10" name="kode_konsinyasi" class="form-control" id="kode_konsinyasi" value="<?= $kode_konsinyasi ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="kode_supplier">Supplier</label>
                        <select name="kode_supplier" class="form-control" id="kode_supplier" required>
                            <option value="">-- Pilih Supplier --</option>
                            <?php
                            $ambil_supplier = mysqli_query($koneksi, "SELECT * FROM supplier") or die(mysqli_error($koneksi));
                            while($s = mysqli_fetch_assoc($ambil_supplier)){
                                $selected = ($s['kode_supplier'] == $kode_supplier) ? 'selected' : '';
                                echo '<option value="'.$s['kode_supplier'].'" '.$selected.'>'.$s['kode_supplier'].' - '.$s['nama_supplier'].'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tgl_masuk">Tanggal Masuk</label>
                        <input type="date" name="tgl_masuk" class="form-control" id="tgl_masuk" value="<?= $tgl_masuk ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="tgl_jatuh_tempo">Tanggal Jatuh Tempo</label>
                        <input type="date" name="tgl_jatuh_tempo" class="form-control" id="tgl_jatuh_tempo" value="<?= $tgl_jatuh_tempo ?>">
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" class="form-control" id="status">
                            <option value="proses" <?= ($status == 'proses') ? 'selected' : '' ?>>Proses</option>
                            <option value="selesai" <?= ($status == 'selesai') ? 'selected' : '' ?>>Selesai</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="keterangan">Keterangan</label>
                        <input type="text" name="keterangan" class="form-control" value="<?= $keterangan ?>" id="keterangan" placeholder="Tambahkan Keterangan">
                    </div>
                    <a href="index.php" class="btn btn-default btn-md">Kembali</a>
                    <button type="submit" class="btn btn-primary btn-md" name="btn_edit">Simpan Perubahan</button>
                </form>
                <?php } ?>
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