<?php
require_once '../database/koneksi.php';
require('../asset_web/fpdf/fpdf.php');

$authority = @$_SESSION['peran'];
if ($authority != 's') {
    echo '<script>alert("User melakukan Cross Authority")</script>';
    echo '<script>window.location.href="../logout.php"</script>';
    exit;
}

$tgl_mulai   = isset($_GET['tgl_mulai']) && !empty($_GET['tgl_mulai']) ? $_GET['tgl_mulai'] : date('Y-m-01');
$tgl_selesai = isset($_GET['tgl_selesai']) && !empty($_GET['tgl_selesai']) ? $_GET['tgl_selesai'] : date('Y-m-d');

$tgl_mulai_esc   = mysqli_real_escape_string($koneksi, $tgl_mulai);
$tgl_selesai_esc = mysqli_real_escape_string($koneksi, $tgl_selesai);

$query_beli = mysqli_query($koneksi, "SELECT SUM(total_pembelian) AS total_beli, COUNT(*) AS jml_beli FROM nota_beli WHERE tgl_pembelian BETWEEN '$tgl_mulai_esc' AND '$tgl_selesai_esc'") or die(mysqli_error($koneksi));
$data_beli  = mysqli_fetch_assoc($query_beli);
$total_beli = $data_beli['total_beli'] ?? 0;
$jml_beli   = $data_beli['jml_beli'] ?? 0;

$query_jual = mysqli_query($koneksi, "SELECT SUM(total_penjualan) AS total_jual, COUNT(*) AS jml_jual FROM nota_penjualan WHERE tgl_penjualan BETWEEN '$tgl_mulai_esc' AND '$tgl_selesai_esc'") or die(mysqli_error($koneksi));
$data_jual  = mysqli_fetch_assoc($query_jual);
$total_jual = $data_jual['total_jual'] ?? 0;
$jml_jual   = $data_jual['jml_jual'] ?? 0;

$laba_rugi = $total_jual - $total_beli;
$status_label = ($laba_rugi >= 0) ? 'LABA BERSIH' : 'RUGI BERSIH';

$pdf = new FPDF('P', 'mm', 'A4');
$pdf->SetMargins(15, 12, 15);
$pdf->SetAutoPageBreak(true, 15);
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 6, 'TOKO POS', 0, 1, 'C');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(0, 4, 'Jl. Sudirman No. 123 | Telp: 0812-3456-7890', 0, 1, 'C');
$pdf->Ln(2);
$pdf->SetLineWidth(0.4);
$pdf->Line(15, $pdf->GetY(), 195, $pdf->GetY());
$pdf->Ln(4);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 6, 'LAPORAN LABA RUGI', 0, 1, 'C');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(0, 5, 'Periode: ' . date('d/m/Y', strtotime($tgl_mulai)) . ' s/d ' . date('d/m/Y', strtotime($tgl_selesai)), 0, 1, 'C');
$pdf->Ln(6);

$pdf->SetFont('Arial', 'B', 9);

$pdf->Cell(12, 8, 'No', 1, 0, 'C');
$pdf->Cell(88, 8, 'Komponen Keuangan', 1, 0, 'L');
$pdf->Cell(35, 8, 'Jumlah Transaksi', 1, 0, 'C');
$pdf->Cell(45, 8, 'Nominal (Rp)', 1, 1, 'C');

$pdf->SetFont('Arial', '', 9);
$pdf->Cell(12, 7, '1', 1, 0, 'C');
$pdf->Cell(88, 7, 'Total Pendapatan Penjualan', 1, 0, 'L');
$pdf->Cell(35, 7, $jml_jual . ' transaksi', 1, 0, 'C');
$pdf->Cell(45, 7, number_format($total_jual, 0, ',', '.'), 1, 1, 'R');

$pdf->Cell(12, 7, '2', 1, 0, 'C');
$pdf->Cell(88, 7, 'Total Beban Pembelian', 1, 0, 'L');
$pdf->Cell(35, 7, $jml_beli . ' transaksi', 1, 0, 'C');
$pdf->Cell(45, 7, number_format($total_beli, 0, ',', '.'), 1, 1, 'R');

$pdf->SetFont('Arial', 'B', 9);

$pdf->Cell(135, 8, 'Hasil Akhir (' . $status_label . ')', 1, 0, 'R');
$pdf->Cell(45, 8, 'Rp ' . number_format($laba_rugi, 0, ',', '.'), 1, 1, 'R');

$pdf->Ln(8);

$pdf->SetFont('Arial', 'B', 10);
if ($laba_rugi >= 0) {
    $pdf->SetTextColor(0, 128, 0);
    $pdf->Cell(0, 6, 'STATUS: USAHA MENGALAMI KEUNTUNGAN (LABA)', 0, 1, 'L');
} else {
    $pdf->SetTextColor(200, 0, 0);
    $pdf->Cell(0, 6, 'STATUS: USAHA MENGALAMI KERUGIAN (RUGI)', 0, 1, 'L');
}
$pdf->SetTextColor(0, 0, 0);

$pdf->Ln(6);
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(0, 4, 'Dicetak pada: ' . date('d/m/Y H:i:s'), 0, 1, 'L');

if (ob_get_length()) {
    ob_end_clean();
}

$pdf->Output('I', 'Laporan_Laba_Rugi_' . $tgl_mulai . '_' . $tgl_selesai . '.pdf');
?>