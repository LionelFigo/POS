<?php
require_once '../database/koneksi.php';
require('../asset_web/fpdf/fpdf.php');

$kode_nota = isset($_GET['kode_nota']) ? trim(mysqli_real_escape_string($koneksi, $_GET['kode_nota'])) : '';
if (empty($kode_nota)) {
    echo '<script>alert("Kode Nota tidak ditemukan!"); window.history.back();</script>';
    exit;
}

$query_nota = mysqli_query($koneksi, "SELECT * FROM nota_beli WHERE kode_nota = '$kode_nota'") or die(mysqli_error($koneksi));
$nota = mysqli_fetch_assoc($query_nota);

if (!$nota) {
    echo '<script>alert("Data nota beli tidak ditemukan!"); window.history.back();</script>';
    exit;
}

$kode_supplier = $nota['kode_supplier'];
$query_supplier = mysqli_query($koneksi, "SELECT * FROM supplier WHERE kode_supplier = '$kode_supplier'") or die(mysqli_error($koneksi));
$data_supplier = mysqli_fetch_assoc($query_supplier);
$nama_supplier = $data_supplier ? $data_supplier['nama_supplier'] : $kode_supplier;

$query_detail = mysqli_query($koneksi, "SELECT * FROM detail_notabeli WHERE kode_nota = '$kode_nota' ORDER BY urut ASC") or die(mysqli_error($koneksi));

$items = [];
$total_qty = 0;
$total_harga = 0;
while ($row = mysqli_fetch_assoc($query_detail)) {
    $kode_brg = $row['kode_brg'];
    $query_brg = mysqli_query($koneksi, "SELECT * FROM barang WHERE kode_brg = '$kode_brg'") or die(mysqli_error($koneksi));
    $data_brg = mysqli_fetch_assoc($query_brg);
    $row['nama_brg'] = $data_brg ? $data_brg['nama_brg'] : $kode_brg;

    $items[] = $row;
    $total_qty += (int)$row['jumlah'];
    $total_harga += (int)$row['total_harga_beli'];
}

// Hitung tinggi kertas dinamis untuk ukuran thermal 58mm
$item_count = count($items);
$page_height = max(110, 85 + ($item_count * 9));

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
$pdf->Cell(0, 4, 'LAPORAN NOTA BELI', 0, 1, 'C');

$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(0, 3.5, 'Kode Nota : ' . $kode_nota, 0, 1, 'C');

$pdf->SetFont('Arial', '', 6);
$pdf->Cell(0, 2, '---------------------------------------------------', 0, 1, 'C');

// Informasi Tanggal & Supplier
$tgl = !empty($nota['tgl_pembelian']) ? date('d/m/Y', strtotime($nota['tgl_pembelian'])) : date('d/m/Y');
$supplier = !empty($nama_supplier) ? $nama_supplier : '-';

$pdf->Cell(14, 3, 'Tanggal', 0, 0, 'L');
$pdf->Cell(3, 3, ':', 0, 0, 'C');
$pdf->Cell(0, 3, $tgl, 0, 1, 'L');

$pdf->Cell(14, 3, 'Supplier', 0, 0, 'L');
$pdf->Cell(3, 3, ':', 0, 0, 'C');
$pdf->Cell(0, 3, $supplier, 0, 1, 'L');

$pdf->Cell(0, 2, '---------------------------------------------------', 0, 1, 'C');

$pdf->SetFont('Arial', 'B', 6);
$pdf->Cell(28, 3.5, 'Barang', 0, 0, 'L');
$pdf->Cell(24, 3.5, 'Total', 0, 1, 'R');
$pdf->Cell(0, 1, '---------------------------------------------------', 0, 1, 'C');

if (count($items) > 0) {
    foreach ($items as $item) {
        $nama = !empty($item['nama_brg']) ? $item['nama_brg'] : $item['kode_brg'];
        $qty = $item['jumlah'];
        $harga_satuan = number_format($item['harga_beli'], 0, ',', '.');
        $subtotal = number_format($item['total_harga_beli'], 0, ',', '.');

        $pdf->SetFont('Arial', 'B', 6);
        $pdf->Cell(0, 3.5, $nama, 0, 1, 'L');
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell(28, 3, ' ' . $qty . ' x Rp ' . $harga_satuan, 0, 0, 'L');
        $pdf->Cell(24, 3, 'Rp ' . $subtotal, 0, 1, 'R');
    }
} else {
    $pdf->SetFont('Arial', 'I', 6);
    $pdf->Cell(0, 4, 'Tidak ada detail barang', 0, 1, 'C');
}

// ---------------- TOTAL DI BAWAH ----------------
$pdf->Cell(0, 2, '---------------------------------------------------', 0, 1, 'C');

$pdf->SetFont('Arial', '', 6);
$pdf->Cell(26, 3.5, 'Total Barang', 0, 0, 'L');
$pdf->Cell(4, 3.5, ':', 0, 0, 'C');
$pdf->Cell(22, 3.5, $total_qty . ' item', 0, 1, 'R');

$pdf->SetFont('Arial', 'B', 7);
$pdf->Cell(26, 4, 'Total Harga', 0, 0, 'L');
$pdf->Cell(4, 4, ':', 0, 0, 'C');
$pdf->Cell(22, 4, 'Rp ' . number_format($total_harga, 0, ',', '.'), 0, 1, 'R');

$status = $nota['status_bayar'] ?? '';
$status_text = 'Lunas';
if ($status == 'L') {
    $status_text = 'Lunas';
} elseif ($status == '2') {
    $status_text = 'Dibayar 75%';
} elseif ($status == '3') {
    $status_text = 'Dibayar 50%';
} else {
    $status_text = 'Dibayar 25%';
}

$pdf->SetFont('Arial', '', 6);
$pdf->Cell(26, 3.5, 'Status Bayar', 0, 0, 'L');
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

$pdf->Output('I', 'Nota_Beli_' . $kode_nota . '.pdf');
?>