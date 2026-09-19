<?php
require_once '../database/koneksi.php';

if(isset($_POST['btn_tambah'])){
    $kode_nota = trim(mysqli_real_escape_string($koneksi, $_POST['kode_nota']));
    $kode_supplier = trim(mysqli_real_escape_string($koneksi, $_POST['kode_supplier']));
    $tgl_penjualan = trim(mysqli_real_escape_string($koneksi, $_POST['tgl_penjualan']));
    $total_penjualan = 0;
    $status = trim(mysqli_real_escape_string($koneksi, $_POST['status']));
    $keterangan = trim(mysqli_real_escape_string($koneksi, $_POST['keterangan']));

    $query_cek = mysqli_query($koneksi, "SELECT kode_nota from nota_penjualan where kode_nota = '$kode_nota'")or die(mysqli_error($koneksi));
    $rv = mysqli_num_rows($query_cek);
    if($rv > 1){
        echo '<script>alert("Data Sudah Ada");
        window.location.href="../nota_jual_superadmin";</script>';
    }else{
        $query_simpan = mysqli_query($koneksi, "INSERT INTO nota_penjualan values ('$kode_nota', '$kode_supplier', '$tgl_penjualan', '$total_penjualan', '$status', '$keterangan')")or die(mysqli_error($koneksi));
        echo '<script>alert("Berhasil Menambah Nota");
        window.location.href="../nota_jual_superadmin";</script>';
    }
}
?>