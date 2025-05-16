<?php
session_start();
require '../services/connection.php';

// Verificar que el usuario esté logueado y tenga id_propietario en sesión
if (!isset($_SESSION['id_propietario'])) {
    $_SESSION['error'] = "Debes iniciar sesión para ver tus mascotas.";
    header("Location: ./login.php");
    exit();
}

$id_propietario = $_SESSION['id_propietario'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Special+Gothic+Expanded+One&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Tuffy:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="../css/styles.css" />
    <title>Mascotas</title>
</head>
  <body>
    <header class="text-center">
      <h1>Mascotas</h1>
    </header>

    <ul class="nav nav-tabs custom-navbar w-100">
      <div class="nav-left">
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="../index.php">Inicio</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="./mascotas.php">Mascotas</a>
        </li>
      </div>
      <div class="nav-right">
        <li class="nav-item">
          <a class="nav-link" href="./login.php">Iniciar sesión</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="./register.php">Darse de alta</a>
        </li>
      </div>
    </ul>

    <div class="container">
      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
          <?php 
            echo $_SESSION['error']; 
            unset($_SESSION['error']);
          ?>
        </div>
      <?php endif; ?>

      <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success">
          <?php 
            echo $_SESSION['success']; 
            unset($_SESSION['success']);
          ?>
        </div>
      <?php endif; ?>
    </div>

    <main>
      <section>
        <a href="./formulario_mascota.php" class="btn btn-primary mb-3">Registra tu mascota!</a>
        <h2 class="titulo-verde">Mascotas registradas:</h2>

        <div class="table-responsive">
          <table class="table table-striped table-hover align-middle mascotas-table">
            <thead class="table-success">
              <tr>
                <th>Nombre</th>
                <th>Fecha de nacimiento</th>
                <th>Sexo</th>
                <th>Especie</th>
                <th>Raza</th>
                <th>Modificar</th>
                <th>Eliminar</th>
              </tr>
            </thead>
            <tbody>
              <?php
                $sql = "SELECT chip_mascota, nombre_mascota, fecha_nacimiento_mascota, sexo_mascota, especie_mascota, raza_mascota 
                        FROM mascota WHERE id_propietario = ?";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, "i", $id_propietario);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if ($result && mysqli_num_rows($result) > 0) {
                    while ($mascota = mysqli_fetch_assoc($result)) {
              ?>
              <tr>
                <td><?php echo htmlspecialchars($mascota['nombre_mascota']); ?></td>
                <td><?php echo htmlspecialchars($mascota['fecha_nacimiento_mascota']); ?></td>
                <td><?php echo ($mascota['sexo_mascota'] === 'M') ? 'Macho' : 'Hembra'; ?> </td>
                <td><?php echo htmlspecialchars($mascota['especie_mascota']); ?></td>
                <td><?php echo htmlspecialchars($mascota['raza_mascota']); ?></td>
                <td><a href="../processes/modificar_mascota.php?chip=<?php echo $mascota['chip_mascota']; ?>" class="btn btn-warning btn-sm">Modificar</a></td>
                <td><a href="../processes/eliminar_mascota.php?chip=<?php echo $mascota['chip_mascota']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que quieres eliminar esta mascota?');">Eliminar</a></td>
              </tr>
              <?php
                    }
                } else {
                    echo "<tr><td colspan='7' class='text-center'>No hay mascotas registradas.</td></tr>";
                }
                mysqli_stmt_close($stmt);
              ?>
            </tbody>
          </table>
        </div>
      </section>

      <?php if (isset($_SESSION['usuario'])): ?>
        <div class="user-panel">
          <span>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?></span>
          <form action="../processes/logout.proc.php" method="post">
            <button type="submit" class="btn btn-outline-secondary btn-sm">Cerrar sesión</button>
          </form>
        </div>
      <?php endif; ?>
    </main>

    <div class="scroll-top-panel">
      <button onclick="window.scrollTo({top: 0, behavior: 'smooth'});" class="btn btn-secondary btn-sm">Volver arriba</button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
  <footer class="footer text-center mt-5">
    <p>&copy; 2023 Perriatria Veterinario. Todos los derechos reservados.</p>
  </footer>
</html>
