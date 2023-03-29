<?php
include_once("../database.php");

if (isset($_POST['idPurchase']) && !empty($_POST['idPurchase']) && is_numeric($_POST['idPurchase'])) {
  $idPurchase = $_POST['idPurchase'];

  // Obtener los productos de la factura
  $query = "SELECT id_products, quantity FROM invoices WHERE id_purchases = $idPurchase";
  $result = mysqli_query($connection, $query);
  if (!$result) {
    die("Consulta fallida" . mysqli_error($connection));
  } else {
    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Actualizar la cantidad de productos
    foreach ($products as $product) {
      $idProduct = $product['id_products'];
      $quantity = $product['quantity'];

      // Actualizar la cantidad del producto
      $query = "UPDATE products SET stock = stock + $quantity WHERE id = $idProduct";
      $result = mysqli_query($connection, $query);
      if (!$result) {
        die("Consulta fallida" . mysqli_error($connection));
        break;
      } else {
        // Eliminar el producto de la factura
        $query = "DELETE FROM invoices WHERE id_products = $idProduct AND id_purchases = $idPurchase";
        $result = mysqli_query($connection, $query);
        if (!$result) {
          die("Consulta fallida" . mysqli_error($connection));
          break;
        }
      }
    }
  }
} else {
  die("Error: El id la compra es obligatorios");
}
