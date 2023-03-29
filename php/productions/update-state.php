<?php
include_once("../database.php");

if (
  isset($_POST['id']) && isset($_POST['quantity']) && isset($_POST['idProducts']) && !empty($_POST['id']) &&
  !empty($_POST['quantity']) && !empty($_POST['idProducts']) && is_numeric($_POST['id']) &&
  is_numeric($_POST['quantity']) && is_numeric($_POST['idProducts'])
) {
  $id = $_POST['id'];
  $quantity = $_POST['quantity'];
  $idProducts = $_POST['idProducts'];

  $query = "UPDATE productions SET state = 1 WHERE id = '$id'";
  $result = mysqli_query($connection, $query);
  if (!$result) {
    die("Consulta fallida" . mysqli_error($connection));
  } else {
    $query = "SELECT stock FROM products WHERE id = '$idProducts'";
    $result = mysqli_query($connection, $query);
    if (!$result) {
      die("Consulta fallida" . mysqli_error($connection));
    } else {
      $newStock = mysqli_fetch_assoc($result)['stock'] + $quantity;

      $query = "UPDATE products SET stock = $newStock WHERE id = '$idProducts'";
      $result = mysqli_query($connection, $query);
      if (!$result) {
        die("Consulta fallida" . mysqli_error($connection));
      } else {
        echo "Producción finalizada exitosamente";
      }
    }
  }
} else {
  die("Error: El id es obligatorio");
}
