<?php
session_start();
if (
  !isset($_SESSION["username"]) || !isset($_SESSION["id"]) || !isset($_SESSION["role"]) || empty($_SESSION["username"])
  || empty($_SESSION["id"]) || empty($_SESSION["role"])
) {
  session_destroy();
  header("Location: ./index.php");
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
  <header>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
      <div class="container-fluid">
        <h1 class="navbar-brand mb-0" href="#">Laboratio de productos de limpieza</h1>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link" href="purchases.php">Compras</a>
            </li>
            <?php
            if ($_SESSION["role"] == "admin") {
            ?>
              <li class="nav-item">
                <a class="nav-link" href="products.php">Productos</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="productions.php">Producción</a>
              </li>
            <?php
            }
            ?>
          </ul>
          <div>
            <button id="userModalButton" data-bs-toggle="modal" data-bs-target="#userModal" data-bs-whatever="@mdo" class="btn btn-outline-primary" type="button">Perfil</button>
          </div>
        </div>
      </div>
    </nav>
  </header>

  <main>
    <section class="container-fluid">
      <h2 class="h1 text-center text-uppercase mt-3 mb-5">Ordenes de compras de productos</h2>
      <div class="mb-3">
        <button type="button" id="showModalButton" class="mb-2 btn btn-primary" data-bs-toggle="modal" data-bs-target="#formModal" data-bs-whatever="@mdo">
          Registrar una nueva orden de compra
        </button>
        <button type="button" id="showDownloadPfdModalButton" class="mb-2 btn btn-primary" data-bs-toggle="modal" data-bs-target="#downloadPdfModal" data-bs-whatever="@mdo" class="btn btn-primary">
          Generar reporte PDF
        </button>
        <button id="graphicModalButton" type="button" class="mb-2 btn btn-primary">
          Generar gráfica de los productos más vendidos
        </button>
      </div>
      <table class="table table-hover">
        <thead>
          <tr class="table-primary">
            <th scope="col">ID</th>
            <th scope="col">Cliente</th>
            <th scope="col">Total</th>
            <th scope="col">Estado</th>
            <th scope="col">Fecha</th>
          </tr>
        </thead>
        <tbody id="tbody"></tbody>
      </table>
    </section>
    <div class="modal fade modal-lg" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title fs-5" id="formModalLabel">Formulario para registrar una nueva orden de compra</h3>
          </div>
          <div class="modal-body">
            <form id="form">
              <input type="hidden" id="id" />
              <div class="mb-3">
                <label for="client" class="form-label">Cliente</label>
                <input type="text" class="form-control" id="client" placeholder="Juan" />
              </div>
              <div class="mb-3">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="state" />
                  <label class="form-check-label" for="state">Dejar orden de compra abierta.</label>
                </div>
              </div>
              <div class="mb-3">
                <label for="productsNumber" class="form-label">¿Cuantos productos llevara?</label>
                <input type="number" min="1" step="1" class="form-control" id="productsNumber" placeholder="3" />
                <div class="form-text">
                  Asegurate de tener productos suficientes en el inventario para poder realizar ordenes de compra, acá
                  solo se mostraran los disponibles.
                </div>
              </div>
              <div id="productsContainer"></div>
              <div class="text-end">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Confirmar</button>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <span class="h2">Total:</span>
            <span class="h2" id="total">0</span>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="actionModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title fs-5">¿Que deseas hacer?</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <button type="button" class="w-100 btn btn-primary" id="updateButton">Modificar orden compra</button>
              NOTA: Cada vez que modifiques una orden de compra, se actualizara la fecha de la misma.
            </div>
            <div class="mb-3">
              <button type="button" class="w-100 btn btn-danger" id="deleteButton">Eliminar orden de compra</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="downloadPdfModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title fs-5">¿Como quieres generar el PDF?</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <button type="button" class="w-100 btn btn-primary" id="downloadPdfAllPurchasesButton">
                Todas las ordenes de compras
              </button>
            </div>
            <div class="mb-3">
              <button type="button" class="w-100 btn btn-primary" id="donwloadPdfOpenPurchasesButton">
                Solo las ordenes de compras abiertas
              </button>
            </div>
            <div class="mb-3">
              <button type="button" class="w-100 btn btn-primary" id="donwloadPdfClosePurchasesButton">
                Solo las ordenes de compras cerradas
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="graphicModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title fs-5">Grafico de barra de los productos más vendidos</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <canvas id="chart"></canvas>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title fs-5">
              Hola
              <?php echo $_SESSION["username"] ?>
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="form-text">Tu rol es de <?php echo $_SESSION["role"] == "admin" ? "Administrador" : "Vendedor" ?></div>
            <?php
            if ($_SESSION["role"] == "admin") {
            ?>
              <section class="mt-3">
                <h3 class="h3">Registrar un nuevo usuario</h3>
                <form id="formNewUser">
                  <div class="mb-3">
                    <input type="text" id="usernameNew" class="form-control" placeholder="Nombre de usuario" required />
                  </div>
                  <div class="mb-3">
                    <select id="roleNew" class="form-select">
                      <option value="admin">Administrador</option>
                      <option value="seller">Vendedor</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <input type="password" id="newPasswordNew" class="form-control" placeholder="Contraseña" />
                  </div>
                  <div class="mb-3">
                    <input type="password" id="repeatPasswordNew" class="form-control" placeholder="Confirmar contraseña" />
                  </div>
                  <div>
                    <button type="submit" class="w-100 btn btn-primary">Registrar usuario</button>
                  </div>
                </form>
              </section>
            <?php
            }
            ?>
          </div>
          <div class="modal-footer">
            <button type="button" class="w-100 btn btn-danger" id="closeSesionButton">Cerrar sesión</button>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.6.4.js" integrity="sha256-a9jBBRygX1Bh5lt8GZjXDzyOB+bWve9EiO7tROUtj/E=" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="js/purchases.js"></script>
  <script src="js/users.js"></script>
</body>

</html>