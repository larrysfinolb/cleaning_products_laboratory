<?php
include_once("../database.php");

if (
  isset($_POST['id']) && isset($_POST['name']) && isset($_POST['price']) && !empty($_POST['id'])  && !empty($_POST['name'])
  && !empty($_POST['price']) && is_numeric($_POST['id']) && is_numeric($_POST['price'])
) {
  $id = $_POST['id'];
  $name = $_POST['name'];
  $price = $_POST['price'];

  $query = "UPDATE products SET name = '$name', price = '$price' WHERE id = '$id'";
  $result = mysqli_query($connection, $query);

  if (!$result) {
    die("Consulta fallida" . mysqli_error($connection));
  } else {
    echo "Producto actualizado exitosamente";
  }
} else {
  die("Error: El id, nombre y precio son obligatorios");
}
