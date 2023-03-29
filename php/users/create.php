<?php
include_once("../database.php");

if (
  isset($_POST['username']) && isset($_POST['role']) && !empty($_POST['password']) && !empty($_POST['username'])
  && !empty($_POST['role']) && !empty($_POST['password'])
) {
  $username = $_POST['username'];;
  $role = $_POST['role'];
  $password = $_POST['password'];

  $password = password_hash($password, PASSWORD_DEFAULT);

  $query = "INSERT INTO users(username, role, password) VALUES ('$username', '$role', '$password')";
  $result = mysqli_query($connection, $query);

  if (!$result) {
    die("Consulta fallida" . mysqli_error($connection));
  } else {
    echo "Usuario registrado sastifactoriamente";
  }
} else {
  die("Error: El nombre de usuario, el rol y la contraseña son requeridos");
}
