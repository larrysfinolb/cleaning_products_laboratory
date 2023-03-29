<?php
include_once("../database.php");

if (isset($_POST["id"]) && isset($_POST['username']) && !empty("id") && !empty($_POST['username']) && is_numeric($_POST["id"])) {

  $id = $_POST["id"];
  $username = $_POST['username'];

  if (
    isset($_POST["currentPassowrd"]) && isset($_POST["newPassword"]) && !empty($_POST["currentPassword"])
    && !empty($_POST["newPassword"])
  ) {
    $password = $_POST['password'];

    $password = password_hash($password, PASSWORD_DEFAULT);

    $query = "UPDATE users SET username = '$username', password = '$password' WHERE id = $id";
    $result = mysqli_query($connection, $query);

    if (!$result) {
      die("Consulta fallida" . mysqli_error($connection));
    } else {
      echo "Usuario actualizado sastifactoriamente";
    }
  } else {
    $query = "UPDATE users SET username = '$username' WHERE id = $id";
    $result = mysqli_query($connection, $query);

    if (!$result) {
      die("Consulta fallida" . mysqli_error($connection));
    } else {
      echo "Usuario actualizado sastifactoriamente";
    }
  }
} else {
  die("Error: El nombre de usuario y el id son obligatorios");
}
