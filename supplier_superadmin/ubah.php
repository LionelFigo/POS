<?php
require_once '../database/koneksi.php';

if (isset($_POST['btn_edit'])){
    $kode = trim(mysqli_real_escape_string($koneksi, $_POST['kode_supplier']));
    $nama = trim(mysqli_real_escape_string($koneksi, $_POST['nama_supplier']));
    $nama_pic = trim(mysqli_real_escape_string($koneksi, $_POST['nama_pic']));
    $kontak_pic = trim(mysqli_real_escape_string($koneksi, $_POST['kontak_pic']));
    $alamat = trim(mysqli_real_escape_string($koneksi, $_POST['alamat_supplier']));
    $website = trim(mysqli_real_escape_string($koneksi, $_POST['website']));
    $akun_ig = trim(mysqli_real_escape_string($koneksi, $_POST['akun_ig']));
    $akun_tiktok = trim(mysqli_real_escape_string($koneksi, $_POST['akun_tiktok']));

    $query_edit = mysqli_query($koneksi, "UPDATE supplier set
    nama_supplier = '$nama',
    nama_pic = '$nama_pic',
    kontak_pic = '$kontak_pic',
    alamat_supplier = '$alamat',
    website = '$website',
    akun_ig = '$akun_ig',
    akun_tiktok = '$akun_tiktok'
    where kode_supplier = '$kode'
    ")or die(mysqli_error($koneksi));

    echo '<script>alert("Data Berhasil Diedit");
    window.location.href="../supplier_superadmin";</script>';
}
?>