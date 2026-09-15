<?php
require_once '../database/koneksi.php';

if(isset($_POST['btn_edit'])){
    $kode_nota = trim(mysqli_real_escape_string($koneksi, $_POST['kode_nota']));
    $kode_supplier = trim(mysqli_real_escape_string($koneksi, $_POST['kode_supplier']));
    $tgl_pembelian = trim(mysqli_real_escape_string($koneksi, $_POST['tgl_pembelian']));
    $status = trim(mysqli_real_escape_string($koneksi, $_POST['status']));
    $keterangan = trim(mysqli_real_escape_string($koneksi, $_POST['keterangan']));

    $query_update = mysqli_query($koneksi, "UPDATE nota_beli set 
    kode_supplier = '$kode_supplier',
    tgl_pembelian = '$tgl_pembelian',
    status_bayar = '$status', 
    keterangan = '$keterangan'
    where kode_nota = '$kode_nota'")or die(mysqli_error($koneksi));

    echo '<script>alert("Berhasil Edit Data");
    window.location.href="../nota_beli_superadmin";</script>';
}
?>