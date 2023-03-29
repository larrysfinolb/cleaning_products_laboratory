<?php
include_once("../database.php");

if (
  isset($_POST['id']) && isset($_POST['client']) && isset($_POST['total']) && isset($_POST["state"]) && !empty($_POST['id'])
  && !empty($_POST['client']) && !empty($_POST['total']) && is_numeric($_POST['total']) && is_numeric($_POST['id'])
) {
  $id = $_POST['id'];
  $client = $_POST['client'];
  $total = $_POST['total'];
  $state = $_POST['state'];
  $date = date('Y-m-d H:i:s', strtotime('now'));

  $query = "UPDATE purchases SET client = '$client', total = '$total', state = '$state', date = '$date' WHERE id = '$id'";
  $result = mysqli_query($connection, $query);

  if (!$result) {
    die("Consulta fallida" . mysqli_error($connection));
  } else {
    echo "Compra acutalizada exitosamente";
  }
} else {
  die("Error: El id, el cliente, el total y el estado son obligatorios");
}
