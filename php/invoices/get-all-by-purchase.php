<?php
include_once("../database.php");

if (isset($_POST["idPurchase"]) && !empty($_POST["idPurchase"]) && is_numeric($_POST["idPurchase"])) {

  $idPurchase = $_POST["idPurchase"];

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

    $json = json_encode($data);
    echo $json;
  }
} else {
  die("Errror: El id de la compra es obligatorio");
}
