<?php
session_start();
if (
  !isset($_SESSION["username"]) || !isset($_SESSION["id"]) || !isset($_SESSION["role"]) || empty($_SESSION["username"])
  || empty($_SESSION["id"]) || empty($_SESSION["role"]) || $_SESSION["role"] != "admin"
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
      <h2 class="h1 text-center text-uppercase mt-3 mb-5">Fabricación de productos</h2>
      <div class="mb-2">
        <button type="button" id="showModalButton" class="mb-2 btn btn-primary" data-bs-toggle="modal" data-bs-target="#formModal" data-bs-whatever="@mdo">
          Fabricar producto
        </button>
        <button type="button" id="showDownloadPfdModalButton" class="mb-2 btn btn-primary" data-bs-toggle="modal" data-bs-target="#downloadPdfModal" data-bs-whatever="@mdo">
          Generar reporte PDF
        </button>
        <button id="graphicModalButton" type="button" class="mb-2 btn btn-primary">
          Generar gráfica de los productos más fabricados
        </button>
      </div>
      <table class="table table-hover">
        <thead>
          <tr class="table-primary">
            <th scope="col">ID</th>
            <th scope="col">Nombre</th>
            <th scope="col">Cantidad en producción</th>
            <th scope="col">Estado</th>
          </tr>
        </thead>
        <tbody id="tbody"></tbody>
      </table>
    </section>
    <div class="modal fade modal-lg" id="formModal" tabindex="-1" aria-labelledby="formModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title fs-5">Formulario para la producción</h3>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form id="form">
              <div class="mb-3">
                <label for="products" class="form-label">Selecciona el producto que desesas fabricar</label>
                <select id="products" class="form-select"></select>
                <div id="products" class="form-text">
                  Si el producto que deseas fabricar no esta aquí, debes ir a la sección de productos y crearlo.
                </div>
              </div>
              <div class="mb-3">
                <label for="quantity" class="form-label">Cantidad</label>
                <input type="number" class="form-control" id="quantity" placeholder="50" required min="1" step="1" />
              </div>
              <div class="text-end">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Confirmar</button>
              </div>
            </form>
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
            <input type="hidden" id="id" />
            <input type="hidden" id="product" />
            <input type="hidden" id="quantity" />
            <div class="mb-3">
              <button type="button" class="w-100 btn btn-primary" id="updateButton">Finalizar producción</button>
            </div>
            <div class="mb-3">
              <button type="button" class="w-100 btn btn-danger" id="deleteButton">Eliminar producción</button>
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
              <button type="button" class="w-100 btn btn-primary" id="downloadPdfAllProductionsButton">
                Todas las producciones
              </button>
            </div>
            <div class="mb-3">
              <button type="button" class="w-100 btn btn-primary" id="donwloadPdfInProgressProductionsButton">
                Solo las producciones en proceso
              </button>
            </div>
            <div class="mb-3">
              <button type="button" class="w-100 btn btn-primary" id="donwloadPdfFinishedProductionsButton">
                Solo las producciones terminadas
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
            <h5 class="modal-title fs-5">Grafico de barra de los productos más fabricados</h5>
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
  <script src="js/productions.js"></script>
  <script src="js/users.js"></script>
</body>

</html>