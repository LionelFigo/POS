<?php
require_once '../database/koneksi.php';

if(isset($_POST['btn_tambah'])){
    $kode_konsinyasi = trim(mysqli_real_escape_string($koneksi, $_POST['kode_konsinyasi']));
    $kode_barang = trim(mysqli_real_escape_string($koneksi, $_POST['kode_barang']));
    $harga_titip = (int)trim(mysqli_real_escape_string($koneksi, $_POST['harga_titip']));
    $jumlah_titip = (int)trim(mysqli_real_escape_string($koneksi, $_POST['jumlah_titip']));

    $subtotal = $harga_titip * $jumlah_titip;

    $query_simpan = mysqli_query($koneksi, "INSERT INTO detail_nota_konsinyasi (kode_konsinyasi, kode_barang, harga_titip, jumlah_titip, jumlah_laku, jumlah_retur) VALUES ('$kode_konsinyasi', '$kode_barang', '$harga_titip', '$jumlah_titip', 0, 0)") or die(mysqli_error($koneksi));
    
    $simpan_total = mysqli_query($koneksi, "UPDATE nota_konsinyasi SET total_nilai = total_nilai + '$subtotal' WHERE kode_konsinyasi = '$kode_konsinyasi'") or die(mysqli_error($koneksi));
    
    $simpan_stok = mysqli_query($koneksi, "UPDATE barang SET stok = stok + '$jumlah_titip', rata_harga_beli = '$harga_titip' WHERE kode_brg = '$kode_barang'") or die(mysqli_error($koneksi));

    echo '<script>alert("Berhasil Menambah Data Barang Konsinyasi");
    window.location.href="index.php?kode_konsinyasi='.$kode_konsinyasi.'"</script>';
}
?>