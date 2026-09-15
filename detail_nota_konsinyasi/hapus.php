<?php 
require_once '../database/koneksi.php';

$id_detail = isset($_GET['id_detail']) ? trim(mysqli_real_escape_string($koneksi, $_GET['id_detail'])) : (isset($_GET['urut']) ? trim(mysqli_real_escape_string($koneksi, $_GET['urut'])) : '');

if(!empty($id_detail)){
    $ambil_detail = mysqli_query($koneksi, "SELECT * FROM detail_nota_konsinyasi WHERE id_detail = '$id_detail'") or die(mysqli_error($koneksi));
    $data_detail = mysqli_fetch_assoc($ambil_detail);

    if($data_detail){
        $kode_konsinyasi = $data_detail['kode_konsinyasi'];
        $kode_barang = $data_detail['kode_barang'];
        $harga_titip = (int)$data_detail['harga_titip'];
        $jumlah_titip = (int)$data_detail['jumlah_titip'];
        $jumlah_retur = (int)$data_detail['jumlah_retur'];

        $subtotal = $harga_titip * $jumlah_titip;
        $stok_kembali = $jumlah_titip - $jumlah_retur;

        mysqli_query($koneksi, "UPDATE nota_konsinyasi SET total_nilai = total_nilai - '$subtotal' WHERE kode_konsinyasi = '$kode_konsinyasi'") or die(mysqli_error($koneksi));
        mysqli_query($koneksi, "UPDATE barang SET stok = stok - '$stok_kembali' WHERE kode_brg = '$kode_barang'") or die(mysqli_error($koneksi));
        mysqli_query($koneksi, "DELETE FROM detail_nota_konsinyasi WHERE id_detail = '$id_detail'") or die(mysqli_error($koneksi));

        echo '<script>alert("Berhasil Hapus Data");
        window.location.href="index.php?kode_konsinyasi='.$kode_konsinyasi.'"</script>';
        exit;
    }
}

echo '<script>alert("Data tidak ditemukan"); window.history.back();</script>';
?>