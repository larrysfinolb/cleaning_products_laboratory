<?php
include_once("../database.php");

if (
  isset($_POST['idPurchases']) && isset($_POST['products']) && !empty($_POST['idPurchases']) && !empty($_POST['products'])
  && is_numeric($_POST['idPurchases']) && is_array($_POST['products'])
) {
  $idPurchases = $_POST['idPurchases'];
  $products = $_POST['products'];

  // Recorrer el arreglo de productos y guardarlos en la tabla purchases
  foreach ($products as $key) {
    $quantity = $key["quantity"];
    $idProducts = $key["id"];

    // Obtener el producto
    $query = "SELECT stock, name FROM products WHERE id = $idProducts";
    $result = mysqli_query($connection, $query);
    if (!$result) {
      die("Consulta fallida" . mysqli_error($connection));
    } else {
      $product = mysqli_fetch_assoc($result);

      // Actaulizar el stock del producto
      $stock = $product["stock"] - $quantity;
      if ($stock < 0) {
        die("Error: No hay suficiente stock del producto " . $product["name"]);
      } else {
        $query = "UPDATE products SET stock = $stock WHERE id = $idProducts";
        $result = mysqli_query($connection, $query);
        if (!$result) {
          die("Consulta fallida" . mysqli_error($connection));
        } else {
          // Guardar el producto en la tabla invoices
          $query = "INSERT INTO invoices(id_products, id_purchases, quantity) VALUES ('$idProducts', '$idPurchases', '$quantity')";
          $result = mysqli_query($connection, $query);
          if (!$result) {
            die("Consulta fallida" . mysqli_error($connection));
          }

          echo "Productos registrados en la factura exitosamente.";
        }
      }
    }
  }
} else {
  die("Error: El id de la compra y el arreglo con los productos son obligatorios.");
}
