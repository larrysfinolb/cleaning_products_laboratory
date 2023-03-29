<?php
include_once("../database.php");

if (
  isset($_POST['client']) && isset($_POST['total']) && isset($_POST["state"]) && !empty($_POST['client']) &&
  !empty($_POST['total']) && is_numeric($_POST['total'])
) {
  $client = $_POST['client'];
  $total = $_POST['total'];
  $state = $_POST['state'];
  $date = date('Y-m-d H:i:s', strtotime('now'));

  $query = "INSERT INTO purchases(client, total, state, date) VALUES ('$client', '$total', '$state', '$date')";
  $result = mysqli_query($connection, $query);

  if (!$result) {
    die("Consulta fallida" . mysqli_error($connection));
  } else {
    $id = mysqli_insert_id($connection);

    echo json_encode($id);
  }
} else {
  die("Error: El cliente, el total y el estado son obligatorios");
}
