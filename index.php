<?php
session_start();
if (
  isset($_SESSION["username"]) && isset($_SESSION["id"]) && isset($_SESSION["role"])
) {
  header("Location: ./purchases.php");
}
?>


<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Laboratorio de productos de limpieza</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous" />
</head>

<body>
  <main style="height: 100vh; display: flex; align-items: center">
    <section class="container-sm" style="max-width: 540px">
      <h1 class="h1 text-center">Laboratorio de productos de limpieza</h1>
      <form action="./php/sesion/login.php" method="POST">
        <div class="mb-3">
          <label for="username" class="form-label">Usuario</label>
          <input type="text" class="form-control" id="username" name="username" placeholder="El usuario maestro es: admin" />
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Contraseña</label>
          <input type="password" class="form-control" id="password" name="password" placeholder="La contraseña maestra es: 12345" />
        </div>
        <?php
        if (isset($_GET["error"])) {
        ?>
          <div class="alert alert-danger" role="alert">
            Tus datos son incorrectos. Intenta de nuevo.
          </div>
        <?php
        }
        ?>
        <button type="submit" class="w-100 btn btn-primary">Iniciar sesión</button>
        <div class="form-text">
          NOTA: Existen dos tipos de usuarios. Los administadores y vendedores. Los administradores tienen acceso a todos
          las secciones y pueden registrar nuevos usuarios, los vendedores solo tienen acceso a la seccion de "Ordenes de compra".
        </div>
      </form>
    </section>
  </main>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
</body>

</html>