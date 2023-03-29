<?php
require('../../fpdf/fpdf.php');
include_once("../../database.php");

$query = "SELECT * FROM purchases WHERE state = 1";
$result = mysqli_query($connection, $query);
if (!$result) {
  die("Consulta fallida" . mysqli_error($connection));
} else {
  $data = array();
  while ($row = mysqli_fetch_array($result)) {
    $data[] = array(
      "id" => $row["id"],
      "client" => $row["client"],
      "total" => $row["total"],
      "state" => $row["state"],
      "date" => $row["date"],
    );
  }

  $pdf = new FPDF();
  $pdf->AddPage();
  $pdf->SetFont('Arial', 'B', 16);
  $pdf->Cell(40, 10, 'Reporte de Compras de compras abiertas');
  $pdf->Ln(15);
  $pdf->SetFont('Arial', 'B', 12);
  $pdf->Cell(20, 10, 'ID', 1);
  $pdf->Cell(40, 10, 'Cliente', 1);
  $pdf->Cell(40, 10, 'Total', 1);
  $pdf->Cell(40, 10, 'Estado', 1);
  $pdf->Cell(40, 10, 'Fecha', 1);
  $pdf->Ln(10);
  $pdf->SetFont('Arial', '', 12);
  foreach ($data as $row) {
    $pdf->Cell(20, 10, $row["id"], 1);
    $pdf->Cell(40, 10, $row["client"], 1);
    $pdf->Cell(40, 10, $row["total"], 1);
    $pdf->Cell(40, 10, $row["state"] == 0 ? "Cerrada" : "Abierta", 1);
    $pdf->Cell(40, 10, $row["date"], 1);
    $pdf->Ln(10);
  }
  $pdf->Output();
}
