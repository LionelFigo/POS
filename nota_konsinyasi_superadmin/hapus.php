<?php 
require_once '../database/koneksi.php';

$kode_konsinyasi = isset($_GET['kode_konsinyasi']) ? trim(mysqli_real_escape_string($koneksi, $_GET['kode_konsinyasi'])) : (isset($_GET['kode_nota']) ? trim(mysqli_real_escape_string($koneksi, $_GET['kode_nota'])) : '');

if(!empty($kode_konsinyasi)){
    $ambil_detail = mysqli_query($koneksi, "SELECT * FROM detail_nota_konsinyasi WHERE kode_konsinyasi = '$kode_konsinyasi'") or die(mysqli_error($koneksi));
    while($row = mysqli_fetch_assoc($ambil_detail)){
        $kode_barang = $row['kode_barang'];
        $sisa_titip = (int)$row['jumlah_titip'] - (int)$row['jumlah_retur'];
        mysqli_query($koneksi, "UPDATE barang SET stok = stok - '$sisa_titip' WHERE kode_brg = '$kode_barang'") or die(mysqli_error($koneksi));
    }

    mysqli_query($koneksi, "DELETE FROM detail_nota_konsinyasi WHERE kode_konsinyasi = '$kode_konsinyasi'") or die(mysqli_error($koneksi));
    mysqli_query($koneksi, "DELETE FROM nota_konsinyasi WHERE kode_konsinyasi = '$kode_konsinyasi'") or die(mysqli_error($koneksi));
}

echo '<script>alert("Berhasil Hapus Data");
window.location.href="../nota_konsinyasi_superadmin";</script>';
?>
