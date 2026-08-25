<?php
require_once '../database/koneksi.php';

if(isset($_POST['btn_tambah'])){
    $kode = trim(mysqli_real_escape_string($koneksi, $_POST['kode_supplier']));
    $nama = trim(mysqli_real_escape_string($koneksi, $_POST['nama_supplier']));
    $nama_pic = trim(mysqli_real_escape_string($koneksi, $_POST['nama_pic']));
    $kontak_pic = trim(mysqli_real_escape_string($koneksi, $_POST['kontak_pic']));
    $alamat = trim(mysqli_real_escape_string($koneksi, $_POST['alamat_supplier']));
    $website = trim(mysqli_real_escape_string($koneksi, $_POST['website']));
    $akun_ig = trim(mysqli_real_escape_string($koneksi, $_POST['akun_ig']));
    $akun_tiktok = trim(mysqli_real_escape_string($koneksi, $_POST['akun_tiktok']));

    $cek_supplier = mysqli_query($koneksi, "SELECT kode_supplier from supplier where kode_supplier = '$kode'")or die(mysqli_error($koneksi));
    $rv = mysqli_num_rows($cek_supplier);

    if($rv > 0){
        echo '<script>alert("Data Supplier Sudah Ada");
        window.location.href="../supplier_superadmin";</script>';
    }else{
        $query_simpan = mysqli_query($koneksi, "INSERT into supplier values ('$kode', '$nama', '$nama_pic', '$kontak_pic', '$alamat', 
        '$website', '$akun_ig', '$akun_tiktok')") or die(mysqli_error($koneksi));
        
        echo '<script>alert("Data Berhasil Ditambahkan");
        window.location.href="../supplier_superadmin";</script>';
    }

}

?>