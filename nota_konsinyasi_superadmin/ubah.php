<?php
require_once '../database/koneksi.php';

if(isset($_POST['btn_edit'])){
    $kode_konsinyasi = trim(mysqli_real_escape_string($koneksi, $_POST['kode_konsinyasi']));
    $kode_supplier = trim(mysqli_real_escape_string($koneksi, $_POST['kode_supplier']));
    $tgl_masuk = trim(mysqli_real_escape_string($koneksi, $_POST['tgl_masuk']));
    $tgl_jatuh_tempo = !empty($_POST['tgl_jatuh_tempo']) ? "'".trim(mysqli_real_escape_string($koneksi, $_POST['tgl_jatuh_tempo']))."'" : "NULL";
    $status = trim(mysqli_real_escape_string($koneksi, $_POST['status']));
    $keterangan = trim(mysqli_real_escape_string($koneksi, $_POST['keterangan']));

    $query_update = mysqli_query($koneksi, "UPDATE nota_konsinyasi SET 
        kode_supplier = '$kode_supplier',
        tgl_masuk = '$tgl_masuk',
        tgl_jatuh_tempo = $tgl_jatuh_tempo,
        status = '$status', 
        keterangan = '$keterangan'
        WHERE kode_konsinyasi = '$kode_konsinyasi'") or die(mysqli_error($koneksi));

    echo '<script>alert("Berhasil Edit Data");
    window.location.href="../nota_konsinyasi_superadmin";</script>';
}
?>