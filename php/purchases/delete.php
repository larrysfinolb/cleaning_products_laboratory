<?php
include_once("../database.php");

if (isset($_POST['id']) && !empty($_POST['id']) && is_numeric($_POST['id'])) {

  $id = $_POST['id'];

  // Eliminar la compra
  $query = "DELETE FROM purchases WHERE id = $id";
  $result = mysqli_query($connection, $query);
  if (!$result) {
    die("Consulta fallida" . mysqli_error($connection));
  } else {
    echo "Compra eliminada satisfactoriamente";
  }
} else {
  die("Error: El id es obligatorios");
}
