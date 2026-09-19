<?php 
require_once "../database/koneksi.php";

$authority = @$_SESSION['peran'];
if($authority != 's'){
  echo '<script>alert("User melakukan Cross Authority")</script>';
  echo '<script>window.location.href="../logout.php"</script>';
}else {
  $kode_konsinyasi = isset($_GET['kode_konsinyasi']) ? trim(mysqli_real_escape_string($koneksi, $_GET['kode_konsinyasi'])) : (isset($_GET['kode_nota']) ? trim(mysqli_real_escape_string($koneksi, $_GET['kode_nota'])) : '');
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
                <h3 class="card-title"><b>Detail Nota Konsinyasi - <?= $kode_konsinyasi ?></b></h3>
            </div>
            <div class="card-body">
                <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#modal-tambah"><i class="fas fa-plus"></i> Tambah Barang</button>
                <a href="pdf.php?kode_konsinyasi=<?= $kode_konsinyasi ?>" target="_blank" class="btn btn-danger mb-2" type="button"><i class="fas fa-file-pdf"></i> Export Nota</a>
                <a href="../nota_konsinyasi_superadmin/" class="btn btn-secondary mb-2"><i class="fas fa-arrow-left"></i> Kembali</a>

                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Harga Titip</th>
                            <th>Jumlah Titip</th>
                            <th>Jumlah Laku</th>
                            <th>Jumlah Retur</th>
                            <th>Total Nilai</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $ambil_detail = mysqli_query($koneksi, "SELECT * FROM detail_nota_konsinyasi WHERE kode_konsinyasi = '$kode_konsinyasi' ORDER BY id_detail ASC") or die(mysqli_error($koneksi));
                        $rv = mysqli_num_rows($ambil_detail);
                        if($rv > 0){
                            $no = 1;
                            while($data_detail = mysqli_fetch_assoc($ambil_detail)){
                                $id_detail = $data_detail['id_detail'];
                                $kode_barang = $data_detail['kode_barang'];
                                $harga_titip = $data_detail['harga_titip'];
                                $jumlah_titip = $data_detail['jumlah_titip'];
                                $jumlah_laku = $data_detail['jumlah_laku'];
                                $jumlah_retur = $data_detail['jumlah_retur'];
                                $subtotal = $harga_titip * $jumlah_titip;

                                $ambil_barang = mysqli_query($koneksi, "SELECT nama_brg FROM barang WHERE kode_brg = '$kode_barang'") or die(mysqli_error($koneksi));
                                $data_brg = mysqli_fetch_assoc($ambil_barang);
                                $nama_brg = $data_brg ? $data_brg['nama_brg'] : $kode_barang;
                                ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $kode_barang ?></td>
                                    <td><?= $nama_brg ?></td>
                                    <td>Rp <?= number_format($harga_titip, 0, ',', '.') ?></td>
                                    <td><?= $jumlah_titip ?></td>
                                    <td><?= $jumlah_laku ?></td>
                                    <td><?= $jumlah_retur ?></td>
                                    <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                                    <td>
                                        <a href="edit.php?id_detail=<?= $id_detail ?>" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                                        <a href="hapus.php?id_detail=<?= $id_detail ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?')" class="btn btn-danger btn-sm" title="Hapus"><i class="fas fa-trash"></i></a>
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
            <h4 class="modal-title">Tambah Barang Titip</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="tambah.php" method="post">
        <div class="modal-body">
            <div class="form-group">
                <label for="kode_konsinyasi">Kode Konsinyasi</label>
                <input type="text" maxlength="10" name="kode_konsinyasi" class="form-control" value="<?= $kode_konsinyasi ?>" id="kode_konsinyasi" readonly>
            </div>
            <div class="form-group">
                <label for="kode_barang">Pilih Barang</label>
                <select name="kode_barang" class="form-control" id="kode_barang" required>
                    <option value="">-- Pilih Barang --</option>
                    <?php
                    $ambil_brg = mysqli_query($koneksi, "SELECT kode_brg, nama_brg, jenis_brg, rata_harga_beli FROM barang where jenis_brg = 'konsinyasi' ORDER BY nama_brg ASC") or die(mysqli_error($koneksi));
                    while($b = mysqli_fetch_assoc($ambil_brg)){
                        echo '<option value="'.$b['kode_brg'].'" data-harga="'.$b['rata_harga_beli'].'">'.$b['kode_brg'].' - '.$b['nama_brg'].'</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="harga_titip">Harga Titip (Satuan)</label>
                <input type="number" name="harga_titip" class="form-control" id="harga_titip" placeholder="Masukkan Harga Titip" required min="0">
            </div>
            <div class="form-group">
                <label for="jumlah_titip">Jumlah Titip</label>
                <input type="number" name="jumlah_titip" class="form-control" id="jumlah_titip" placeholder="Masukkan Jumlah Titip" required min="1">
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