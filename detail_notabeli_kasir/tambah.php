<?php
require_once '../database/koneksi.php';

if(isset($_POST['btn_tambah'])){
    $kode_nota = trim(mysqli_real_escape_string($koneksi, $_POST['kode_nota']));
    $kode_supplier = trim(mysqli_real_escape_string($koneksi, $_POST['kode_supplier']));
    $kode_brg = trim(mysqli_real_escape_string($koneksi, $_POST['kode_brg']));
    $jumlah = trim(mysqli_real_escape_string($koneksi, $_POST['jumlah']));

    $ambil_harga = mysqli_query($koneksi, "SELECT rata_harga_beli from barang where kode_brg = '$kode_brg'")or die(mysqli_error($koneksi));
    $data_harga = mysqli_fetch_assoc($ambil_harga);
    $harga = $data_harga['rata_harga_beli'];

    $total_harga = $harga * $jumlah;

    $query_simpan = mysqli_query($koneksi, "INSERT into detail_notabeli values(null, '$kode_nota', '$kode_brg', '$jumlah', '$harga', '$total_harga')")or die(mysqli_error($koneksi));
    $simpan_total = mysqli_query($koneksi, "UPDATE nota_beli set total_pembelian = total_pembelian + '$total_harga' where kode_nota = '$kode_nota'")or die(mysqli_error($koneksi));
    $simpan_stok = mysqli_query($koneksi, "UPDATE barang set stok = stok + '$jumlah' where kode_brg = '$kode_brg'")or die(mysqli_error($koneksi));
    echo '<script>alert("Berhasil Menambah Data");
    window.location.href="index.php?kode_nota='.$kode_nota.'&kode_supplier='.$kode_supplier.'"</script>';
}
?>