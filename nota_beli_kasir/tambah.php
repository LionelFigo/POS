<?php
require_once '../database/koneksi.php';

if(isset($_POST['btn_tambah'])){
    $kode_nota = trim(mysqli_real_escape_string($koneksi, $_POST['kode_nota']));
    $kode_supplier = trim(mysqli_real_escape_string($koneksi, $_POST['kode_supplier']));
    $tgl_pembelian = trim(mysqli_real_escape_string($koneksi, $_POST['tgl_pembelian']));
    $total_pembelian = 0;
    $status = trim(mysqli_real_escape_string($koneksi, $_POST['status']));
    $keterangan = trim(mysqli_real_escape_string($koneksi, $_POST['keterangan']));

    $query_cek = mysqli_query($koneksi, "SELECT kode_nota from nota_beli where kode_nota = '$kode_nota'")or die(mysqli_error($koneksi));
    $rv = mysqli_num_rows($query_cek);
    if($rv > 1){
        echo '<script>alert("Data Sudah Ada");
        window.location.href="../nota_beli_superadmin";</script>';
    }else{
        $query_simpan = mysqli_query($koneksi, "INSERT INTO nota_beli values ('$kode_nota', '$kode_supplier', '$tgl_pembelian', '$total_pembelian', '$status', '$keterangan')")or die(mysqli_error($koneksi));
        echo '<script>alert("Berhasil Menambah Nota");
        window.location.href="../nota_beli_superadmin"</script>';
    }
}
?>