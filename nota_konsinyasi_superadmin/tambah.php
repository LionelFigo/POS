<?php
require_once '../database/koneksi.php';

if(isset($_POST['btn_tambah'])){
    $kode_konsinyasi = trim(mysqli_real_escape_string($koneksi, $_POST['kode_konsinyasi']));
    $kode_supplier = trim(mysqli_real_escape_string($koneksi, $_POST['kode_supplier']));
    $tgl_masuk = trim(mysqli_real_escape_string($koneksi, $_POST['tgl_masuk']));
    $tgl_jatuh_tempo = !empty($_POST['tgl_jatuh_tempo']) ? "'".trim(mysqli_real_escape_string($koneksi, $_POST['tgl_jatuh_tempo']))."'" : "NULL";
    $total_nilai = 0;
    $status = !empty($_POST['status']) ? trim(mysqli_real_escape_string($koneksi, $_POST['status'])) : 'proses';
    $keterangan = trim(mysqli_real_escape_string($koneksi, $_POST['keterangan']));

    $query_cek = mysqli_query($koneksi, "SELECT kode_konsinyasi FROM nota_konsinyasi WHERE kode_konsinyasi = '$kode_konsinyasi'") or die(mysqli_error($koneksi));
    $rv = mysqli_num_rows($query_cek);
    if($rv > 0){
        echo '<script>alert("Kode Konsinyasi Sudah Ada");
        window.location.href="../nota_konsinyasi_superadmin";</script>';
    }else{
        $query_simpan = mysqli_query($koneksi, "INSERT INTO nota_konsinyasi (kode_konsinyasi, kode_supplier, tgl_masuk, tgl_jatuh_tempo, total_nilai, status, keterangan) VALUES ('$kode_konsinyasi', '$kode_supplier', '$tgl_masuk', $tgl_jatuh_tempo, '$total_nilai', '$status', '$keterangan')") or die(mysqli_error($koneksi));
        echo '<script>alert("Berhasil Menambah Nota Konsinyasi");
        window.location.href="../nota_konsinyasi_superadmin";</script>';
    }
}
?>