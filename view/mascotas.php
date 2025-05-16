<?php
session_start();
require '../services/connection.php';

if (!isset($_SESSION['id_propietario'])) {
    $_SESSION['error'] = "Debes iniciar sesión para ver tus mascotas.";
    header("Location: ./login.php");
    exit();
}

$id_propietario = $_SESSION['id_propietario'];

// Filtros
$where = ["id_propietario = $id_propietario"];

if (!empty($_GET['filtro-sexo'])) {
    $sexo = mysqli_real_escape_string($conn, $_GET['filtro-sexo']);
    $where[] = "sexo_mascota = '$sexo'";
}

if (!empty($_GET['filtro-especie'])) {
    $especie = mysqli_real_escape_string($conn, $_GET['filtro-especie']);
    $where[] = "especie_mascota = '$especie'";
}

if (!empty($_GET['filtro-raza'])) {
    $raza = mysqli_real_escape_string($conn, $_GET['filtro-raza']);
    $where[] = "raza_mascota = '$raza'";
}

$condiciones = implode(" AND ", $where);
$sql = "SELECT chip_mascota, foto_mascota, nombre_mascota, fecha_nacimiento_mascota, sexo_mascota, especie_mascota, raza_mascota 
        FROM mascota WHERE $condiciones ORDER BY fecha_registro_mascota DESC";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../css/styles.css" />
  <link rel="icon" href="./resources/logo_perriatria.png" type="image/x-icon">
  <title>Mascotas</title>
</head>
<body>
  <header class="text-center">
    <h1>Mascotas</h1>
  </header>

  <ul class="nav nav-tabs custom-navbar w-100">
    <div class="nav-left">
      <li class="nav-item"><a class="nav-link active" href="../index.php">Inicio</a></li>
      <li class="nav-item"><a class="nav-link" href="./mascotas.php">Mascotas</a></li>
    </div>
    <div class="nav-right">
      <li class="nav-item"><a class="nav-link" href="./login.php">Iniciar sesión</a></li>
      <li class="nav-item"><a class="nav-link" href="./register.php">Darse de alta</a></li>
    </div>
  </ul>

  <div class="container">
    <?php if (isset($_SESSION['error'])): ?>
      <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
      <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
  </div>

  <main>
    <section>
      <a href="./formulario_mascota.php" class="btn btn-primary mb-3">Registra tu mascota!</a>
      <h2 class="titulo-verde">Mascotas registradas:</h2>

      <!-- Filtros -->
      <form method="GET" class="row g-3 mb-4 ms-1 me-1">
        <div class="col-md-3">
          <label for="filtro-sexo">Sexo:</label>
          <select class="form-select" name="filtro-sexo" id="filtro-sexo">
            <option value="">Todos</option>
            <option value="M" <?= (isset($_GET['filtro-sexo']) && $_GET['filtro-sexo'] === 'M') ? 'selected' : '' ?>>Macho</option>
            <option value="F" <?= (isset($_GET['filtro-sexo']) && $_GET['filtro-sexo'] === 'F') ? 'selected' : '' ?>>Hembra</option>
          </select>
        </div>

        <div class="col-md-3">
          <label for="filtro-especie">Especie:</label>
          <select class="form-select" name="filtro-especie" id="filtro-especie">
            <option value="">Todas</option>
            <?php
            $res_especie = mysqli_query($conn, "SELECT DISTINCT especie_mascota FROM mascota WHERE id_propietario = $id_propietario");
            while ($row = mysqli_fetch_assoc($res_especie)) {
              $val = $row['especie_mascota'];
              $sel = (isset($_GET['filtro-especie']) && $_GET['filtro-especie'] === $val) ? 'selected' : '';
              echo "<option value='$val' $sel>$val</option>";
            }
            ?>
          </select>
        </div>

        <div class="col-md-3">
          <label for="filtro-raza">Raza:</label>
          <select class="form-select" name="filtro-raza" id="filtro-raza">
            <option value="">Todas</option>
            <?php
            $res_raza = mysqli_query($conn, "SELECT DISTINCT raza_mascota FROM mascota WHERE id_propietario = $id_propietario");
            while ($row = mysqli_fetch_assoc($res_raza)) {
              $val = $row['raza_mascota'];
              $sel = (isset($_GET['filtro-raza']) && $_GET['filtro-raza'] === $val) ? 'selected' : '';
              echo "<option value='$val' $sel>$val</option>";
            }
            ?>
          </select>
        </div>

        <div class="col-md-3 d-flex align-items-end">
          <button type="submit" class="btn btn-success me-2">Aplicar filtros</button>
          <a href="mascotas.php" class="btn btn-outline-secondary">Limpiar</a>
        </div>
      </form>

      <!-- Tabla -->
      <div class="table-responsive">
        <table class="table table-striped table-hover align-middle mascotas-table">
          <thead class="table-success">
            <tr>
              <th>Foto</th>
              <th>Nombre</th>
              <th>Fecha de nacimiento</th>
              <th>Propietario</th>
              <th>Veterinario</th>
              <th>Sexo</th>
              <th>Especie</th>
              <th>Raza</th>
              <th>Acciones</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($result && mysqli_num_rows($result) > 0): ?>
              <?php while ($mascota = mysqli_fetch_assoc($result)): ?>
                <tr>
                  <td>
                    <?php if (!empty($mascota['foto_mascota'])): ?>
                      <img src="../resources/<?php echo htmlspecialchars($mascota['foto_mascota']); ?>" alt="Foto de <?php echo htmlspecialchars($mascota['nombre_mascota']); ?>" style="max-width:80px; border-radius:5px;">
                    <?php else: ?>
                      Sin foto
                    <?php endif; ?>
                  </td>
                  <td><?php echo htmlspecialchars($mascota['nombre_mascota']); ?></td>
                  <td><?php echo htmlspecialchars($mascota['fecha_nacimiento_mascota']); ?></td>
                  <td><?php echo ($mascota['sexo_mascota'] === 'M') ? 'Macho' : 'Hembra'; ?></td>
                  <td><?php echo htmlspecialchars($mascota['especie_mascota']); ?></td>
                  <td><?php echo htmlspecialchars($mascota['raza_mascota']); ?></td>
                  <td><a href="../processes/modificar_mascota.php?chip=<?php echo $mascota['chip_mascota']; ?>" class="btn btn-warning btn-sm">Editar</a></td>
                  <td><a href="../processes/eliminar_mascota.php?chip=<?php echo $mascota['chip_mascota']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que quieres eliminar esta mascota?');">Borrar</a></td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr><td colspan="8" class="text-center">No hay mascotas que coincidan con los filtros.</td></tr>
            <?php endif; ?>
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
  <footer class="footer">
  <p>&copy; 2023 Perriatria Veterinario. Todos los derechos reservados.</p>
</footer>
</body>
</html>
