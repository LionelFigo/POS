<?php 
require_once "../database/koneksi.php";

$authority = @$_SESSION['peran'];
if($authority != 'k'){
  echo '<script>alert("User melakukan Cross Authority")</script>';
  echo '<script>window.location.href="../logout.php"</script>';
}else {
?>
<!DOCTYPE html>
<html lang="en">
<?php include '../css.php'; ?>
<?php $hal = 'nota_jual_kasir'; ?>
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
      <?php include '../sidebar_kasir.php'; ?>
      <!-- /.sidebar-menu -->
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
 <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
         <?php
            $kode_nota = @$_GET['kode_nota'];
            $kode_supplier = @$_GET['kode_supplier'];
            $ambil_supplier = mysqli_query($koneksi, "SELECT * from supplier where kode_supplier = '$kode_supplier'")or die(mysqli_error($koneksi));
            $data_supplier = mysqli_fetch_assoc($ambil_supplier);
            $nama_supplier = $data_supplier['nama_supplier'];
            ?>
        <h3 class="card-title"><b>Detail Nota Jual - <?= $kode_nota ?> - <?= $nama_supplier ?></b></h3><br>
        <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="custom-tab1" data-toggle="pill" href="#tab-reguler" role="tab" aria-controls="custom-tab1" aria-selected="true">Reguler</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="custom-tab2" data-toggle="pill" href="#tab-konsinyasi" role="tab" aria-controls="custom-tab2" aria-selected="false">Konsinyasi</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <button type="button" class="btn btn-primary mb-2" data-toggle="modal" data-target="#modal-tambah"><i class="fas fa-plus"></i> Tambah Nota</button>
                <a href="pdf.php?kode_nota=<?= $kode_nota ?>" target="_blank" class="btn btn-danger mb-2" type="button"><i class="fas fa-file-pdf"></i> Export Nota</a>

                <div class="tab-content" id="custom-tabs-one-tabContent">
                    <?php
                    $kategori_tab = [
                        ['id' => 'tab-reguler', 'jenis_brg' => 'reguler', 'active' => 'show active'],
                        ['id' => 'tab-konsinyasi', 'jenis_brg' => 'konsinyasi', 'active' => ''],
                    ];

                    foreach ($kategori_tab as $tab){
                    ?>
                    <div class="tab-pane fade <?= $tab['active'] ?>" id="<?= $tab['id'] ?>" role="tabpanel">
                        <table id="tabel-<?= $tab['id'] ?>" class="table table-bordered table-striped tabel-data" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Harga Jual</th>
                                    <th>Total Harga</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $jenis_brg = $tab['jenis_brg'];
                                $ambil_detail = mysqli_query($koneksi, "SELECT * from detail_notajual where kode_nota = '$kode_nota'")or die(mysqli_error($koneksi));
                                $rv = mysqli_num_rows($ambil_detail);
                                if($rv > 0){
                                    $no = 1;
                                    while($data_detail = mysqli_fetch_assoc($ambil_detail)){
                                        $urut = $data_detail['urut'];
                                        $kode_brg = $data_detail['kode_brg'];
                                        $jumlah = $data_detail['jumlah'];
                                        $total_harga = $data_detail['total_harga_jual'];
                                        $ambil_barang = mysqli_query($koneksi, "SELECT * from barang where kode_brg = '$kode_brg' and jenis_brg = '$jenis_brg'")or die(mysqli_error($koneksi));
                                        $rv = mysqli_num_rows($ambil_barang);
                                        if($rv > 0){
                                            while($data_brg = mysqli_fetch_assoc($ambil_barang)){
                                                $nama_brg = $data_brg['nama_brg'];
                                                $harga_brg = $data_brg['harga_jual'];
                                                ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= $nama_brg ?></td>
                                                    <td><?= $jumlah ?></td>
                                                    <td><?= $harga_brg ?></td>
                                                    <td><?= $total_harga ?></td>
                                                    <td>
                                                        <a href="edit.php?urut=<?= $urut ?>" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                                        <a href="hapus.php?urut=<?= $urut ?>" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <?php } ?>
                </div>
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
                <input type="text" maxlength="10" name="kode_nota" class="form-control" value="<?= $kode_nota ?>" id="kode_nota" readonly>
            </div>
            <div class="form-group">
                <label for="kode_brg">Pilih Barang</label>
                <select name="kode_brg" class="form-control">
                    <?php
                    $ambil_brg = mysqli_query($koneksi, "SELECT * FROM barang where kode_supplier = '$kode_supplier'")or die(mysqli_error($koneksi));
                    $rv_b = mysqli_num_rows($ambil_brg);
                    if($rv_b > 0){
                        while($data_barang = mysqli_fetch_assoc($ambil_brg)){
                            $kode_barang = $data_barang['kode_brg'];
                            $nama_barang = $data_barang['nama_brg'];
                            ?>
                            <option value="<?= $kode_barang ?>"><?= $nama_barang ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="jumlah">Jumlah</label>
                <input type="number" name="jumlah" class="form-control" id="jumlah" placeholder="Masukkan Jumlah">
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
<script>
  $(function(){
    $('.tabel-data').DataTable();

    $('a[data-toggle="pill"]').on('shown.bs.tab', function (e) {
          $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
      });
  });
</script>
</html>
<?php 
}
?>