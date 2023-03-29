<?php
include_once("../database.php");

if (
  isset($_POST['idProducts']) && isset($_POST['quantity']) && !empty($_POST['idProducts']) && !empty($_POST['quantity'])
  && is_numeric($_POST['idProducts']) && is_numeric($_POST["quantity"])
) {
  $idProducts = $_POST['idProducts'];
  $quantity = $_POST['quantity'];

  $query = "INSERT INTO productions(id_products, quantity, state) VALUES ('$idProducts', '$quantity', 0)";
  $result = mysqli_query($connection, $query);

  if (!$result) {
    die("Consulta fallida" . mysqli_error($connection));
  } else {
    echo "Produccion registrada exitosamente";
  }
} else {
  die("Error: El id del producto y la cantidad son obligatorias");
}
