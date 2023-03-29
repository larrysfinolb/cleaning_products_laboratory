<?php
require('../../fpdf/fpdf.php');
include_once("../../database.php");

$idPurchase = $_GET["id"];

$query = "SELECT * FROM invoices WHERE id_purchases = $idPurchase";
$result = mysqli_query($connection, $query);

if (!$result) {
  die("Consulta fallida" . mysqli_error($connection));
} else {
  $data = array();
  while ($row = mysqli_fetch_array($result)) {
    $data[] = array(
      "id" => $row["id"],
      "idPurchases" => $row["id_purchases"],
      "idProducts" => $row["id_products"],
      "quantity" => $row["quantity"],
    );
  }

  // Calcular el total de la compra
  $total = 0;
  foreach ($data as $row) {
    $query = "SELECT price FROM products WHERE id = {$row["idProducts"]}";
    $result = mysqli_query($connection, $query);
    if (!$result) {
      die("Consulta fallida" . mysqli_error($connection));
    } else {
      $total += mysqli_fetch_array($result)["price"] * $row["quantity"];
    }
  }

  // Consultar el nombre y precio del producto
  foreach ($data as $key => $row) {
    $query = "SELECT name, price FROM products WHERE id = {$row["idProducts"]}";
    $result = mysqli_query($connection, $query);
    if (!$result) {
      die("Consulta fallida" . mysqli_error($connection));
    } else {
      $data[$key]["idProducts"] = mysqli_fetch_array($result);
    }
  }

  $pdf = new FPDF();
  $pdf->AddPage();
  $pdf->SetFont('Arial', 'B', 16);
  $pdf->Cell(40, 10, 'Reporte de detalles de la compra #' . $idPurchase);
  $pdf->Ln(15);
  $pdf->SetFont('Arial', 'B', 12);
  $pdf->Cell(20, 10, 'ID', 1);
  $pdf->Cell(60, 10, 'Nombre del producto', 1);
  $pdf->Cell(50, 10, 'Cantidad', 1);
  $pdf->Cell(50, 10, 'Precio del producto', 1);
  $pdf->Ln(10);
  $pdf->SetFont('Arial', '', 12);
  foreach ($data as $row) {
    $pdf->Cell(20, 10, $row["id"], 1);
    $pdf->Cell(60, 10, $row["idProducts"]["name"], 1);
    $pdf->Cell(50, 10, $row["quantity"], 1);
    $pdf->Cell(50, 10, $row["idProducts"]["price"], 1);
    $pdf->Ln(10);
  }
  $pdf->SetFont('Arial', 'B', 12);
  $pdf->Cell(130, 10, 'Total de la compra', 1);
  $pdf->Cell(50, 10, $total, 1);
  $pdf->Ln(10);
  $pdf->Output();
}
