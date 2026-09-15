<?php
require_once '../database/koneksi.php';

if (isset($_POST['btn_edit'])){
    $kode = trim(mysqli_real_escape_string($koneksi, $_POST['kode_barang']));
    $kode_supplier = trim(mysqli_real_escape_string($koneksi, $_POST['kode_supplier']));
    $nama_brg = trim(mysqli_real_escape_string($koneksi, $_POST['nama_barang']));
    $merk = trim(mysqli_real_escape_string($koneksi, $_POST['merk']));
    $stok = trim(mysqli_real_escape_string($koneksi, $_POST['stok']));
    $harga_jual = trim(mysqli_real_escape_string($koneksi, $_POST['harga_jual']));

    $query_edit = mysqli_query($koneksi, "UPDATE barang set
    kode_supplier = '$kode_supplier',
    nama_brg = '$nama_brg',
    merk = '$merk',
    stok = '$stok',
    harga_jual = '$harga_jual'
    where kode_brg = '$kode'
    ")or die(mysqli_error($koneksi));

    echo '<script>alert("Data Berhasil Diedit");
    window.location.href="../d_barang_superadmin";</script>';
}
?>