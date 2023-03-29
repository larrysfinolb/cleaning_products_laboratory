<?php
include_once("../database.php");

$query = "SELECT * FROM purchases";
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

  $json = json_encode($data);
  echo $json;
}
