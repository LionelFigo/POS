<?php 
require_once '../database/koneksi.php';
$kode = @$_GET['kode'];

$query_hapus = mysqli_query($koneksi, "DELETE from barang where kode_brg = '$kode'")or die(mysqli_error($koneksi));
echo '<script>alert("Data Berhasil Dihapus");
window.location.href="../d_barang_superadmin";</script>'
?>