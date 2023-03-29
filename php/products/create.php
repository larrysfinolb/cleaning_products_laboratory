<?php
include_once("../database.php");

if (isset($_POST['name']) && isset($_POST['price']) && !empty($_POST['name']) && !empty($_POST['price']) && is_numeric($_POST['price'])) {
  $name = $_POST['name'];;
  $price = $_POST['price'];

  $query = "INSERT INTO products(name, stock, price) VALUES ('$name', 0, '$price')";
  $result = mysqli_query($connection, $query);

  if (!$result) {
    die("Consulta fallida" . mysqli_error($connection));
  } else {
    echo "Producto creado sastifactoriamente";
  }
} else {
  die("Error: El nombre y el precio son obligatorios");
}
