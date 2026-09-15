<?php 
require_once "../database/koneksi.php";

$authority = @$_SESSION['peran'];
if($authority != 's'){
  echo '<script>alert("User melakukan Cross Authority")</script>';
  echo '<script>window.location.href="../logout.php"</script>';
  exit;
}

if(isset($_POST['btn_edit'])){
    $id_detail = trim(mysqli_real_escape_string($koneksi, $_POST['id_detail']));
    $harga_titip_baru = (int)trim(mysqli_real_escape_string($koneksi, $_POST['harga_titip']));
    $jumlah_titip_baru = (int)trim(mysqli_real_escape_string($koneksi, $_POST['jumlah_titip']));
    $jumlah_laku_baru = (int)trim(mysqli_real_escape_string($koneksi, $_POST['jumlah_laku']));
    $jumlah_retur_baru = (int)trim(mysqli_real_escape_string($koneksi, $_POST['jumlah_retur']));

    $query_old = mysqli_query($koneksi, "SELECT * FROM detail_nota_konsinyasi WHERE id_detail = '$id_detail'") or die(mysqli_error($koneksi));
    $old = mysqli_fetch_assoc($query_old);

    if($old){
        $kode_konsinyasi = $old['kode_konsinyasi'];
        $kode_barang = $old['kode_barang'];
        $old_total = (int)$old['harga_titip'] * (int)$old['jumlah_titip'];
        $old_stock = (int)$old['jumlah_titip'] - (int)$old['jumlah_retur'];

        $new_total = $harga_titip_baru * $jumlah_titip_baru;
        $new_stock = $jumlah_titip_baru - $jumlah_retur_baru;

        $delta_total = $new_total - $old_total;
        $delta_stock = $new_stock - $old_stock;

        mysqli_query($koneksi, "UPDATE detail_nota_konsinyasi SET 
            harga_titip = '$harga_titip_baru',
            jumlah_titip = '$jumlah_titip_baru',
            jumlah_laku = '$jumlah_laku_baru',
            jumlah_retur = '$jumlah_retur_baru'
            WHERE id_detail = '$id_detail'") or die(mysqli_error($koneksi));

        mysqli_query($koneksi, "UPDATE nota_konsinyasi SET total_nilai = total_nilai + '$delta_total' WHERE kode_konsinyasi = '$kode_konsinyasi'") or die(mysqli_error($koneksi));
        mysqli_query($koneksi, "UPDATE barang SET stok = stok + '$delta_stock', rata_harga_beli = '$harga_titip_baru' WHERE kode_brg = '$kode_barang'") or die(mysqli_error($koneksi));

        echo '<script>alert("Berhasil Mengubah Data");
        window.location.href="index.php?kode_konsinyasi='.$kode_konsinyasi.'"</script>';
        exit;
    }
}

$id_detail = isset($_GET['id_detail']) ? trim(mysqli_real_escape_string($koneksi, $_GET['id_detail'])) : (isset($_GET['urut']) ? trim(mysqli_real_escape_string($koneksi, $_GET['urut'])) : '');
$query_detail = mysqli_query($koneksi, "SELECT * FROM detail_nota_konsinyasi WHERE id_detail = '$id_detail'") or die(mysqli_error($koneksi));
$data_detail = mysqli_fetch_assoc($query_detail);

if(!$data_detail){
    echo '<script>alert("Data detail tidak ditemukan!"); window.history.back();</script>';
    exit;
}

$kode_konsinyasi = $data_detail['kode_konsinyasi'];
$kode_barang = $data_detail['kode_barang'];
$query_barang = mysqli_query($koneksi, "SELECT nama_brg FROM barang WHERE kode_brg = '$kode_barang'") or die(mysqli_error($koneksi));
$data_barang = mysqli_fetch_assoc($query_barang);
$nama_barang = $data_barang ? $data_barang['nama_brg'] : $kode_barang;
?>
<!DOCTYPE html>
<html lang="en">
<?php include '../css.php'; ?>
<?php $hal = 'nota_konsinyasi_superadmin'; ?>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
  <!-- Navbar -->
   <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
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
    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
          <a href="#" class="d-block">SISTEM POS</a>
        </div>
      </div>
      <?php include '../sidebar_superadmin.php'; ?>
    </div>
  </aside>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><b>Edit Detail Barang Konsinyasi</b></h3>
            </div>
            <div class="card-body">
                <form action="edit.php" method="post">
                    <input type="hidden" name="id_detail" value="<?= $id_detail ?>">
                    <div class="form-group">
                        <label for="kode_konsinyasi">Kode Konsinyasi</label>
                        <input type="text" name="kode_konsinyasi" class="form-control" id="kode_konsinyasi" value="<?= $kode_konsinyasi ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="barang">Barang</label>
                        <input type="text" class="form-control" id="barang" value="<?= $kode_barang ?> - <?= $nama_barang ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label for="harga_titip">Harga Titip</label>
                        <input type="number" name="harga_titip" class="form-control" id="harga_titip" value="<?= $data_detail['harga_titip'] ?>" required min="0">
                    </div>
                    <div class="form-group">
                        <label for="jumlah_titip">Jumlah Titip</label>
                        <input type="number" name="jumlah_titip" class="form-control" id="jumlah_titip" value="<?= $data_detail['jumlah_titip'] ?>" required min="0">
                    </div>
                    <div class="form-group">
                        <label for="jumlah_laku">Jumlah Laku</label>
                        <input type="number" name="jumlah_laku" class="form-control" id="jumlah_laku" value="<?= $data_detail['jumlah_laku'] ?>" min="0">
                    </div>
                    <div class="form-group">
                        <label for="jumlah_retur">Jumlah Retur</label>
                        <input type="number" name="jumlah_retur" class="form-control" id="jumlah_retur" value="<?= $data_detail['jumlah_retur'] ?>" min="0">
                    </div>
                    <a href="index.php?kode_konsinyasi=<?= $kode_konsinyasi ?>" class="btn btn-default btn-md">Kembali</a>
                    <button type="submit" class="btn btn-primary btn-md" name="btn_edit">Simpan Perubahan</button>
                </form>
            </div>
        </div>
      </div>
    </div>
  </div>

  <?php include '../footer.php'; ?>
</div>

<?php include '../script.php'; ?>
</body>
</html>
