
<?php
require('../../fpdf/fpdf.php');
include_once("../../database.php");

$query = "SELECT * FROM productions WHERE state = 0";
$result = mysqli_query($connection, $query);
if (!$result) {
  die("Consulta fallida" . mysqli_error($connection));
} else {
  $data = array();
  while ($row = mysqli_fetch_array($result)) {
    $data[] = array(
      "id" => $row["id"],
      "idProducts" => $row["id_products"],
      "quantity" => $row["quantity"],
      "state" => $row["state"]
    );
  }

  // Consultar el nombre del producto
  foreach ($data as $key => $row) {
    $query = "SELECT name FROM products WHERE id = {$row["idProducts"]}";
    $result = mysqli_query($connection, $query);
    if (!$result) {
      die("Consulta fallida" . mysqli_error($connection));
    } else {
      $data[$key]["idProducts"] = mysqli_fetch_array($result)["name"];
    }
  }

  $pdf = new FPDF();
  $pdf->AddPage();
  $pdf->SetFont('Arial', 'B', 16);
  $pdf->Cell(40, 10, 'Reporte de Producciones');
  $pdf->Ln(15);
  $pdf->SetFont('Arial', 'B', 12);
  $pdf->Cell(20, 10, 'ID', 1);
  $pdf->Cell(60, 10, 'Nombre del producto', 1);
  $pdf->Cell(50, 10, 'Cantidad', 1);
  $pdf->Cell(40, 10, 'Estado', 1);
  $pdf->Ln(10);
  $pdf->SetFont('Arial', '', 12);
  foreach ($data as $row) {
    $pdf->Cell(20, 10, $row["id"], 1);
    $pdf->Cell(60, 10, $row["idProducts"], 1);
    $pdf->Cell(50, 10, $row["quantity"], 1);
    $pdf->Cell(40, 10, $row["state"] == 0 ? "En produccion" : "Producida", 1);
    $pdf->Ln(10);
  }
  $pdf->Output();
}
