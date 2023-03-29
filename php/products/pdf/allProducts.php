<?php
require('../../fpdf/fpdf.php');
include_once("../../database.php");

$query = "SELECT * FROM products";
$result = mysqli_query($connection, $query);
if (!$result) {
  die("Consulta fallida" . mysqli_error($connection));
} else {
  $data = array();
  while ($row = mysqli_fetch_array($result)) {
    $data[] = array(
      "id" => $row["id"],
      "name" => $row["name"],
      "stock" => $row["stock"],
      "price" => $row["price"]
    );
  }

  $pdf = new FPDF();
  $pdf->AddPage();
  $pdf->SetFont('Arial', 'B', 16);
  $pdf->Cell(40, 10, 'Reporte de Productos');
  $pdf->Ln(15);
  $pdf->SetFont('Arial', 'B', 12);
  $pdf->Cell(20, 10, 'ID', 1);
  $pdf->Cell(60, 10, 'Nombre', 1);
  $pdf->Cell(50, 10, 'Cantidad disponible', 1);
  $pdf->Cell(40, 10, 'Precio', 1);
  $pdf->Ln(10);
  $pdf->SetFont('Arial', '', 12);
  foreach ($data as $row) {
    $pdf->Cell(20, 10, $row["id"], 1);
    $pdf->Cell(60, 10, $row["name"], 1);
    $pdf->Cell(50, 10, $row["stock"], 1);
    $pdf->Cell(40, 10, $row["price"], 1);
    $pdf->Ln(10);
  }
  $pdf->Output();

}
