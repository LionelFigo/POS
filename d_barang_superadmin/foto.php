<?php 
require_once '../database/koneksi.php';

if(isset($_POST['btn_foto'])){
    $kode = trim(mysqli_real_escape_string($koneksi, $_POST['kode_barang']));
    $file = $_FILES['file_foto']['name'];
    $ekstensi = explode('.', $file);
    $nama_file = 'foto-brg'.round(microtime(true)).'.'.end($ekstensi);
    $alamat_sumber = $_FILES['file_foto']['tmp_name'];
    $alamat_tujuan = '../asset_web/img/'.$nama_file;
    move_uploaded_file($alamat_sumber, $alamat_tujuan);

    $query_edit = mysqli_query($koneksi, "UPDATE barang set foto_brg = '$alamat_tujuan' where kode_brg = '$kode'")or die(mysqli_error($koneksi));
    echo '<script>alert("Foto berhasil diupload");
    window.location.href="../d_barang_superadmin";</script>';
}else{
    echo '<script>alert("Gagal upload foto");
    window.location.href="../d_barang_superadmin";</script>';
}
?>