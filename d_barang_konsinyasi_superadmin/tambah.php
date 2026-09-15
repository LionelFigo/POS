<?php
require_once '../database/koneksi.php';

if(isset($_POST['btn_tambah'])){
    $kode = trim(mysqli_real_escape_string($koneksi, $_POST['kode_barang']));
    $kode_supplier = trim(mysqli_real_escape_string($koneksi, $_POST['kode_supplier']));
    $nama_brg = trim(mysqli_real_escape_string($koneksi, $_POST['nama_barang']));
    $merk = trim(mysqli_real_escape_string($koneksi, $_POST['merk']));
    $harga_beli = 0;
    $harga_jual = trim(mysqli_real_escape_string($koneksi, $_POST['harga_jual']));
    $stok = trim(mysqli_real_escape_string($koneksi, $_POST['stok']));
    $jenis_brg = 'konsinyasi';

    $cek_barang = mysqli_query($koneksi, "SELECT kode_brg from barang where kode_brg = '$kode'")or die(mysqli_error($koneksi));
    $rv = mysqli_num_rows($cek_barang);

    if($rv > 0){
        echo '<script>alert("Data Barang Sudah Ada");
        window.location.href="../supplier_superadmin";</script>';
    }else{
        $query_simpan = mysqli_query($koneksi, "INSERT into barang values ('$kode', '$kode_supplier', '$nama_brg', '$merk', '$stok', 
        '$harga_beli', '$harga_jual', null, '$jenis_brg')") or die(mysqli_error($koneksi));
        
        echo '<script>alert("Data Berhasil Ditambahkan");
        window.location.href="../d_barang_konsinyasi_superadmin";</script>';
    }

}

?>