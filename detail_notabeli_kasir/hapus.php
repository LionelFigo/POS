<?php 
require_once '../database/koneksi.php';

$urut = @$_GET['urut'];
$ambil_detail = mysqli_query($koneksi, "SELECT * from detail_notabeli where urut = '$urut'")or die(mysqli_error($koneksi));
$data_detail = mysqli_fetch_assoc($ambil_detail);
$kode_nota = $data_detail['kode_nota'];
$ambil_supplier = mysqli_query($koneksi, "SELECT kode_supplier from nota_beli where kode_nota = '$kode_nota'")or die(mysqli_error($koneksi));
$data_supplier = mysqli_fetch_assoc($ambil_supplier);
$kode_supplier = $data_supplier['kode_supplier'];
$kode_brg = $data_detail['kode_brg'];
$harga = $data_detail['total_harga_beli'];
$jumlah = $data_detail['jumlah'];

$update_total = mysqli_query($koneksi, "UPDATE nota_beli set total_pembelian = total_pembelian - '$harga' where kode_nota = '$kode_nota'")or die(mysqli_error($koneksi));
$update_stok = mysqli_query($koneksi, "UPDATE barang set stok = stok - '$jumlah' where kode_brg = '$kode_brg'")or die(mysqli_error($koneksi));
$hapus = mysqli_query($koneksi, "DELETE from detail_notabeli where urut = '$urut'")or die(mysqli_error($koneksi));
echo '<script>alert("Berhasil Hapus Data");
window.location.href="index.php?kode_nota='.$kode_nota.'&kode_supplier='.$kode_supplier.'"</script>';
?>