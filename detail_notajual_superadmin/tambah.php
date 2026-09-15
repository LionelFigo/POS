<?php
require_once '../database/koneksi.php';

if(isset($_POST['btn_tambah'])){
    $kode_nota = trim(mysqli_real_escape_string($koneksi, $_POST['kode_nota']));
    $kode_brg = trim(mysqli_real_escape_string($koneksi, $_POST['kode_brg']));
    $jumlah = trim(mysqli_real_escape_string($koneksi, $_POST['jumlah']));

    $ambil_brg = mysqli_query($koneksi, "SELECT * from barang where kode_brg = '$kode_brg'")or die(mysqli_error($koneksi));
    $data_brg = mysqli_fetch_assoc($ambil_brg);
    $harga = $data_brg['harga_jual'];
    $stok = $data_brg['stok'];

    $total_harga = $harga * $jumlah;
    if($stok == 0){
        echo '<script>alert("Stok habis");
        window.location.href="index.php?kode_nota='.$kode_nota.'"</script>';
    }else{
        $query_simpan = mysqli_query($koneksi, "INSERT into detail_notajual values(null, '$kode_nota', '$kode_brg', '$jumlah', '$harga', '$total_harga')")or die(mysqli_error($koneksi));
        $simpan_total = mysqli_query($koneksi, "UPDATE nota_penjualan set total_penjualan = total_penjualan + '$total_harga' where kode_nota = '$kode_nota'")or die(mysqli_error($koneksi));
        $simpan_stok = mysqli_query($koneksi, "UPDATE barang set stok = stok - '$jumlah' where kode_brg = '$kode_brg'")or die(mysqli_error($koneksi));
        echo '<script>alert("Berhasil Menambah Data");
        window.location.href="index.php?kode_nota='.$kode_nota.'"</script>';
    }
    
}
?>