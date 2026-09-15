<?php
require_once '../database/koneksi.php';
require('../asset_web/fpdf/fpdf.php');

$kode_konsinyasi = isset($_GET['kode_konsinyasi']) ? trim(mysqli_real_escape_string($koneksi, $_GET['kode_konsinyasi'])) : (isset($_GET['kode_nota']) ? trim(mysqli_real_escape_string($koneksi, $_GET['kode_nota'])) : '');
if (empty($kode_konsinyasi)) {
    echo '<script>alert("Kode Konsinyasi tidak ditemukan!"); window.history.back();</script>';
    exit;
}

$query_nota = mysqli_query($koneksi, "SELECT * FROM nota_konsinyasi WHERE kode_konsinyasi = '$kode_konsinyasi'") or die(mysqli_error($koneksi));
$nota = mysqli_fetch_assoc($query_nota);

if (!$nota) {
    echo '<script>alert("Data nota konsinyasi tidak ditemukan!"); window.history.back();</script>';
    exit;
}

$kode_supplier = $nota['kode_supplier'];
$query_supplier = mysqli_query($koneksi, "SELECT * FROM supplier WHERE kode_supplier = '$kode_supplier'") or die(mysqli_error($koneksi));
$data_supplier = mysqli_fetch_assoc($query_supplier);
$nama_supplier = $data_supplier ? $data_supplier['nama_supplier'] : $kode_supplier;

$query_detail = mysqli_query($koneksi, "SELECT * FROM detail_nota_konsinyasi WHERE kode_konsinyasi = '$kode_konsinyasi' ORDER BY id_detail ASC") or die(mysqli_error($koneksi));

$items = [];
$total_qty = 0;
$total_nilai = 0;
while ($row = mysqli_fetch_assoc($query_detail)) {
    $kode_barang = $row['kode_barang'];
    $query_brg = mysqli_query($koneksi, "SELECT * FROM barang WHERE kode_brg = '$kode_barang'") or die(mysqli_error($koneksi));
    $data_brg = mysqli_fetch_assoc($query_brg);
    $row['nama_brg'] = $data_brg ? $data_brg['nama_brg'] : $kode_barang;

    $items[] = $row;
    $total_qty += (int)$row['jumlah_titip'];
    $total_nilai += ((int)$row['harga_titip'] * (int)$row['jumlah_titip']);
}

// Hitung tinggi kertas dinamis untuk ukuran thermal 58mm
$item_count = count($items);
$page_height = max(120, 95 + ($item_count * 12));

$pdf = new FPDF('P', 'mm', array(58, $page_height));
$pdf->SetMargins(3, 3, 3);
$pdf->SetAutoPageBreak(true, 3);
$pdf->AddPage();

// ---------------- HEADER TOKO ----------------
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell(0, 4, 'TOKO POS', 0, 1, 'C');

$pdf->SetFont('Arial', '', 6);
$pdf->Cell(0, 3, 'Jl. Sudirman No. 123', 0, 1, 'C');
$pdf->Cell(0, 3, 'Telp: 0812-3456-7890', 0, 1, 'C');

$pdf->Cell(0, 2, '---------------------------------------------------', 0, 1, 'C');

// ---------------- JUDUL & KODE NOTA ----------------
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(0, 4, 'NOTA KONSINYASI', 0, 1, 'C');

$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(0, 3.5, 'Kode : ' . $kode_konsinyasi, 0, 1, 'C');

$pdf->SetFont('Arial', '', 6);
$pdf->Cell(0, 2, '---------------------------------------------------', 0, 1, 'C');

// Informasi Tanggal & Supplier
$tgl_masuk = !empty($nota['tgl_masuk']) ? date('d/m/Y', strtotime($nota['tgl_masuk'])) : date('d/m/Y');
$tgl_tempo = !empty($nota['tgl_jatuh_tempo']) ? date('d/m/Y', strtotime($nota['tgl_jatuh_tempo'])) : '-';
$supplier = !empty($nama_supplier) ? $nama_supplier : '-';

$pdf->Cell(14, 3, 'Tgl Masuk', 0, 0, 'L');
$pdf->Cell(3, 3, ':', 0, 0, 'C');
$pdf->Cell(0, 3, $tgl_masuk, 0, 1, 'L');

$pdf->Cell(14, 3, 'Jatuh Tempo', 0, 0, 'L');
$pdf->Cell(3, 3, ':', 0, 0, 'C');
$pdf->Cell(0, 3, $tgl_tempo, 0, 1, 'L');

$pdf->Cell(14, 3, 'Supplier', 0, 0, 'L');
$pdf->Cell(3, 3, ':', 0, 0, 'C');
$pdf->Cell(0, 3, $supplier, 0, 1, 'L');

$pdf->Cell(0, 2, '---------------------------------------------------', 0, 1, 'C');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(28, 3.5, 'Barang Titip', 0, 0, 'L');
$pdf->Cell(24, 3.5, 'Total', 0, 1, 'R');
$pdf->Cell(0, 1, '---------------------------------------------------', 0, 1, 'C');

if (count($items) > 0) {
    foreach ($items as $item) {
        $nama = !empty($item['nama_brg']) ? $item['nama_brg'] : $item['kode_barang'];
        $qty = $item['jumlah_titip'];
        $harga_satuan = number_format($item['harga_titip'], 0, ',', '.');
        $subtotal = number_format($item['harga_titip'] * $item['jumlah_titip'], 0, ',', '.');
        $laku = (int)$item['jumlah_laku'];
        $retur = (int)$item['jumlah_retur'];

        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(0, 3.5, $nama, 0, 1, 'L');
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(28, 3, ' ' . $qty . ' x Rp ' . $harga_satuan, 0, 0, 'L');
        $pdf->Cell(24, 3, 'Rp ' . $subtotal, 0, 1, 'R');
        $pdf->Cell(0, 2.5, '  (Laku: ' . $laku . ' | Retur: ' . $retur . ')', 0, 1, 'L');
    }
} else {
    $pdf->SetFont('Arial', 'I', 6);
    $pdf->Cell(0, 4, 'Tidak ada detail barang', 0, 1, 'C');
}

// ---------------- TOTAL DI BAWAH ----------------
$pdf->Cell(0, 2, '---------------------------------------------------', 0, 1, 'C');

$pdf->SetFont('Arial', '', 6);
$pdf->Cell(26, 3.5, 'Total Titip', 0, 0, 'L');
$pdf->Cell(4, 3.5, ':', 0, 0, 'C');
$pdf->Cell(22, 3.5, $total_qty . ' item', 0, 1, 'R');

$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(26, 4, 'Total Nilai', 0, 0, 'L');
$pdf->Cell(4, 4, ':', 0, 0, 'C');
$pdf->Cell(22, 4, 'Rp ' . number_format($total_nilai, 0, ',', '.'), 0, 1, 'R');

$status = $nota['status'] ?? 'proses';
$status_text = ($status == 'selesai') ? 'Selesai' : 'Proses';

$pdf->SetFont('Arial', '', 6);
$pdf->Cell(26, 3.5, 'Status', 0, 0, 'L');
$pdf->Cell(4, 3.5, ':', 0, 0, 'C');
$pdf->Cell(22, 3.5, $status_text, 0, 1, 'R');

if (!empty($nota['keterangan'])) {
    $pdf->Cell(26, 3.5, 'Keterangan', 0, 0, 'L');
    $pdf->Cell(4, 3.5, ':', 0, 0, 'C');
    $pdf->Cell(22, 3.5, $nota['keterangan'], 0, 1, 'R');
}

$pdf->Cell(0, 2, '================================', 0, 1, 'C');

$pdf->SetFont('Arial', 'I', 5.5);
$pdf->Cell(0, 3, 'Terima kasih atas kerja samanya', 0, 1, 'C');
$pdf->Cell(0, 2.5, 'Dicetak: ' . date('d/m/Y H:i:s'), 0, 1, 'C');

if (ob_get_length()) {
    ob_end_clean();
}

$pdf->Output('I', 'Nota_Konsinyasi_' . $kode_konsinyasi . '.pdf');
?>