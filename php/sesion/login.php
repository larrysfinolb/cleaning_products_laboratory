<?php
include_once("../database.php");

if (isset($_POST["username"]) && isset($_POST["password"]) && !empty($_POST["username"]) && !empty($_POST["password"])) {

  $username = $_POST["username"];
  $password = $_POST["password"];

  $query = "SELECT * FROM users WHERE username = '$username'";
  $result = mysqli_query($connection, $query);
  if (!$result) {
    die("Consulta fallida" . mysqli_error($connection));
  } else {
    $data = mysqli_fetch_array($result);

    if (password_verify($password, $data["password"])) {
      session_start();
      $_SESSION["username"] = $username;
      $_SESSION["role"] = $data["role"];
      $_SESSION["id"] = $data["id"];
      header("Location: ../../purchases.php");
    } else {
      header("Location: ../../index.php?error=1");
    }
  }
} else {
  header("Location: ../../index.php?error=1");
}
