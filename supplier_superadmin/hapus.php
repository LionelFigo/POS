<?php 
require_once '../database/koneksi.php';
$kode = @$_GET['kode'];

$query_hapus = mysqli_query($koneksi, "DELETE from supplier where kode_supplier = '$kode'")or die(mysqli_error($koneksi));

echo'<script>alert("Data Berhasil Dihapus");
window.location.href="../supplier_superadmin";</script>';
?>