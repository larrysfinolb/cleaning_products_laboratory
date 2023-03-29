<?php
include_once("../database.php");

$query = "SELECT * FROM invoices";
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
