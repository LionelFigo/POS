<?php
require_once '../database/koneksi.php';

if(isset($_POST['btn_edit'])){
    $kode_nota = trim(mysqli_real_escape_string($koneksi, $_POST['kode_nota']));
    $kode_supplier = trim(mysqli_real_escape_string($koneksi, $_POST['kode_supplier']));
    $tgl_penjualan = trim(mysqli_real_escape_string($koneksi, $_POST['tgl_penjualan']));
    $status = trim(mysqli_real_escape_string($koneksi, $_POST['status']));
    $keterangan = trim(mysqli_real_escape_string($koneksi, $_POST['keterangan']));

    $query_update = mysqli_query($koneksi, "UPDATE nota_penjualan set 
    kode_supplier = '$kode_supplier',
    tgl_penjualan = '$tgl_penjualan',
    status_bayar = '$status', 
    keterangan = '$keterangan'
    where kode_nota = '$kode_nota'")or die(mysqli_error($koneksi));

    echo '<script>alert("Berhasil Edit Data");
    window.location.href="../nota_jual_superadmin";</script>';
}
?>