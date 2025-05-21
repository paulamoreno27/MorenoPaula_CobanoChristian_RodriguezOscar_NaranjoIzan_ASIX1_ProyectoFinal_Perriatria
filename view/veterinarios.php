<?php
session_start();
require '../services/connection.php';

if (!isset($_SESSION['id_usuario'])) { //Verificar  que solo pueda entrar el admin 
    $_SESSION['error'] = "Debes iniciar sesión como admin para ver los veterinarios.";
    header("Location: ./login.php");
    exit();
}

$id_propietario = $_SESSION['id_propietario'];

$where = "1=1";
if (!empty($_GET['filtro-especialidad'])) {
    $especialidad = mysqli_real_escape_string($conn, $_GET['filtro-especialidad']);
    $where .= " AND especialidad_veterinario = '$especialidad'";
}

$sql = "SELECT id_veterinario, nombre_veterinario, apellidos_veterinario, telefono_veterinario, email_veterinario, salario_veterinario, especialidad_veterinario 
        FROM veterinario WHERE $where ORDER BY nombre_veterinario ASC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../css/styles.css" />
  <link href="https://fonts.googleapis.com/css2?family=Special+Gothic+Expanded+One&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Tuffy:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet" />
  <link rel="icon" href="../resources/logo_perriatria.png" type="image/x-icon">
  <title>Veterinarios</title>
</head>
<body>

<header class="text-center">
  <h1>Veterinarios</h1>
  <div>
    <img src="../resources/logo_perriatria_blanco.png" alt="Logo Perriatria Blanco" class="logo-header">
  </div>
</header>

<ul class="nav nav-tabs custom-navbar w-100">
  <div class="nav-left">
    <li class="nav-item"><a class="nav-link" href="../index.php">Inicio</a></li>
    <li class="nav-item"><a class="nav-link" href="./mascotas.php">Mascotas</a></li>
    <li class="nav-item"><a class="nav-link active" href="./veterinarios.php">Veterinarios</a></li>
  </div>
  <div class="nav-right">
    <li class="nav-item"><a class="nav-link" href="./login.php">Iniciar sesión</a></li>
    <li class="nav-item"><a class="nav-link" href="./register.php">Darse de alta</a></li>
  </div>
</ul>

<div class="container mt-3">
  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
  <?php endif; ?>
  <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
  <?php endif; ?>
</div>

<main>
  <section class="container">
    <a href="./formulario_veterinario.php" class="btn btn-primary mb-3">Registro veterinario!</a>
    <h2 class="titulo-verde">Veterinarios registrados:</h2>

    <!-- Filtro -->
    <form method="GET" class="row g-3 mb-4 ms-1 me-1">
      <div class="col-md-4">
        <label for="filtro-especialidad">Especialidad:</label>
        <select class="form-select" name="filtro-especialidad" id="filtro-especialidad">
          <option value="">Todas</option>
          <?php
          $res_especialidades = mysqli_query($conn, "SELECT DISTINCT especialidad_veterinario FROM veterinario");
          while ($row = mysqli_fetch_assoc($res_especialidades)) {
            $val = htmlspecialchars($row['especialidad_veterinario']);
            $sel = (isset($_GET['filtro-especialidad']) && $_GET['filtro-especialidad'] === $val) ? 'selected' : '';
            echo "<option value='$val' $sel>$val</option>";
          }
          ?>
        </select>
      </div>

      <div class="col-md-3 d-flex align-items-end">
        <button type="submit" class="btn btn-success me-2">Aplicar filtros</button>
        <a href="veterinarios.php" class="btn btn-outline-secondary">Limpiar</a>
      </div>
    </form>

    <!-- Tabla -->
    <div class="table-responsive">
      <table class="table table-striped table-hover align-middle mascotas-table">
        <thead class="table-success">
          <tr>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Teléfono</th>
            <th>Email</th>
            <th>Especialidad</th>
            <th>Salario</th>
            <th>Modificar</th>
            <th>Eliminar</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <?php while ($vet = mysqli_fetch_assoc($result)): ?>
              <tr>
                <td><?= htmlspecialchars($vet['nombre_veterinario']) ?></td>
                <td><?= htmlspecialchars($vet['apellidos_veterinario']) ?></td>
                <td><?= htmlspecialchars($vet['telefono_veterinario']) ?></td>
                <td><?= htmlspecialchars($vet['email_veterinario']) ?></td>
                <td><?= htmlspecialchars($vet['especialidad_veterinario']) ?></td>
                <td><?= htmlspecialchars(number_format($vet['salario_veterinario'], 2)) ?></td>
                <td><a href="../view/modificar_veterinario.php?id_veterinario=<?php echo $vet['id_veterinario']; ?>" class="btn btn-warning btn-sm">Editar</a></td>
                <td><a href="../processes/eliminar_veterinario.php?id_veterinario=<?php echo $vet['id_veterinario']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que quieres eliminar esta mascota?');">Borrar</a></td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="6" class="text-center">No hay veterinarios disponibles para este filtro.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </section>

  <?php if (isset($_SESSION['usuario'])): ?>
    <div class="user-panel mt-3 text-center">
      <span>Bienvenido, <?= htmlspecialchars($_SESSION['usuario']) ?></span>
      <form action="../processes/logout.proc.php" method="post" class="d-inline ms-2">
        <button type="submit" class="btn btn-outline-secondary btn-sm">Cerrar sesión</button>
      </form>
    </div>
  <?php endif; ?>
</main>

  <div class="scroll-top-panel">
      <button onclick="window.scrollTo({top: 0, behavior: 'smooth'});">Volver arriba</button>
  </div>
  
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<footer class="footer text-center mt-4">
  <p>&copy; 2023 Perriatria Veterinario. Todos los derechos reservados.</p>
</footer>
</body>
</html>
