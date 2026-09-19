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

$query_total = mysqli_query($koneksi, "SELECT SUM(total_penjualan) AS grand_total FROM nota_penjualan WHERE tgl_penjualan BETWEEN '$tgl_mulai_esc' AND '$tgl_selesai_esc'") or die(mysqli_error($koneksi));
$data_total  = mysqli_fetch_assoc($query_total);
$grand_total = $data_total['grand_total'] ?? 0;

$ambil_nota_jual = mysqli_query($koneksi, "SELECT * FROM nota_penjualan WHERE tgl_penjualan BETWEEN '$tgl_mulai_esc' AND '$tgl_selesai_esc' ORDER BY tgl_penjualan DESC") or die(mysqli_error($koneksi));

$pdf = new FPDF('P', 'mm', 'A4');
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(true, 15);
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 6, 'TOKO POS', 0, 1, 'C');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(0, 4, 'Jl. Sudirman No. 123 | Telp: 0812-3456-7890', 0, 1, 'C');
$pdf->Ln(2);
$pdf->SetLineWidth(0.4);
$pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());
$pdf->Ln(4);

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 6, 'LAPORAN TOTAL PENJUALAN', 0, 1, 'C');
$pdf->SetFont('Arial', '', 9);
$pdf->Cell(0, 5, 'Periode: ' . date('d/m/Y', strtotime($tgl_mulai)) . ' s/d ' . date('d/m/Y', strtotime($tgl_selesai)), 0, 1, 'C');
$pdf->Ln(4);

$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(10, 7, 'No', 1, 0, 'C');
$pdf->Cell(30, 7, 'Kode Nota', 1, 0, 'C');
$pdf->Cell(28, 7, 'Kode Supplier', 1, 0, 'C');
$pdf->Cell(24, 7, 'Tanggal Jual', 1, 0, 'C');
$pdf->Cell(33, 7, 'Total Jual', 1, 0, 'C');
$pdf->Cell(25, 7, 'Status', 1, 0, 'C');
$pdf->Cell(40, 7, 'Keterangan', 1, 1, 'C');

$pdf->SetFont('Arial', '', 8);
$no = 1;
if (mysqli_num_rows($ambil_nota_jual) > 0) {
    while ($row = mysqli_fetch_assoc($ambil_nota_jual)) {
        $status = $row['status_bayar'];
        if ($status == 'L' || $status == '1') {
            $status_text = 'Lunas';
        } elseif ($status == '2') {
            $status_text = 'Dibayar 75%';
        } elseif ($status == '3') {
            $status_text = 'Dibayar 50%';
        } else {
            $status_text = 'Dibayar 25%';
        }

        $pdf->Cell(10, 6, $no++, 1, 0, 'C');
        $pdf->Cell(30, 6, $row['kode_nota'], 1, 0, 'L');
        $pdf->Cell(28, 6, $row['kode_supplier'], 1, 0, 'L');
        $pdf->Cell(24, 6, date('d/m/Y', strtotime($row['tgl_penjualan'])), 1, 0, 'C');
        $pdf->Cell(33, 6, 'Rp ' . number_format($row['total_penjualan'], 0, ',', '.'), 1, 0, 'R');
        $pdf->Cell(25, 6, $status_text, 1, 0, 'C');
        $pdf->Cell(40, 6, !empty($row['keterangan']) ? $row['keterangan'] : '-', 1, 1, 'L');
    }

    $pdf->SetFont('Arial', 'B', 8);
    $pdf->Cell(92, 7, 'Grand Total', 1, 0, 'C');
    $pdf->Cell(33, 7, 'Rp ' . number_format($grand_total, 0, ',', '.'), 1, 0, 'R');
    $pdf->Cell(65, 7, '', 1, 1, 'C');
} else {
    $pdf->Cell(190, 8, 'Tidak ada data penjualan pada periode ini.', 1, 1, 'C');
}

$pdf->Ln(6);
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(0, 4, 'Dicetak pada: ' . date('d/m/Y H:i:s'), 0, 1, 'L');

if (ob_get_length()) {
    ob_end_clean();
}

$pdf->Output('I', 'Laporan_Total_Penjualan_' . $tgl_mulai . '_' . $tgl_selesai . '.pdf');
?>
