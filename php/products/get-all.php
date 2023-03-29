<?php
include_once("../database.php");

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

  $json = json_encode($data);
  echo $json;
}
