<?php
session_start();

if (!isset($_SESSION['rol'])) {
    $_SESSION['error'] = "Debes iniciar sesión para acceder a esta página.";
    header("Location: ./login.php");
    exit();
}

require '../services/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($conn)) {
        $_SESSION['error'] = "Error de conexión con la base de datos.";
        header("Location: ../view/login.php");
        exit();
    }

    $usuario = trim($_POST['usuario']);
    $password = $_POST['password'];

    if (empty($usuario) || empty($password)) {
        $_SESSION['error'] = "Todos los campos son obligatorios.";
        header("Location: ../view/login.php");
        exit();
    }

    // Verificamos en la tabla `usuarios` (admin u otros roles permitidos)
    $query_admin = "SELECT id_usuario, password, rol_usuario FROM usuarios WHERE username = ?";
    $stmt_admin = mysqli_prepare($conn, $query_admin);
    mysqli_stmt_bind_param($stmt_admin, "s", $usuario);
    mysqli_stmt_execute($stmt_admin);
    $result_admin = mysqli_stmt_get_result($stmt_admin);

    if ($result_admin && mysqli_num_rows($result_admin) > 0) {
        $row = mysqli_fetch_assoc($result_admin);
        $hash = $row['password'];

        if (password_verify($password, $hash)) {
            $_SESSION['id_usuario'] = $row['id_usuario'];
            $_SESSION['usuario'] = $usuario;
            $_SESSION['rol'] = $row['rol_usuario'];

            header("Location: ../index.php");
            exit();
        } else {
            $_SESSION['error'] = "Contraseña incorrecta.";
        }
        mysqli_stmt_close($stmt_admin);
    } else {
        // Verificamos si es propietario
        $query_propietario = "SELECT id_propietario, contraseña_propietario FROM propietario WHERE nombre_propietario = ?";
        $stmt_prop = mysqli_prepare($conn, $query_propietario);
        mysqli_stmt_bind_param($stmt_prop, "s", $usuario);
        mysqli_stmt_execute($stmt_prop);
        $result_prop = mysqli_stmt_get_result($stmt_prop);

        if ($result_prop && mysqli_num_rows($result_prop) > 0) {
            $row = mysqli_fetch_assoc($result_prop);
            if (password_verify($password, $row['contraseña_propietario'])) {
                $_SESSION['id_propietario'] = $row['id_propietario'];
                $_SESSION['usuario'] = $usuario;
                $_SESSION['rol'] = 'propietario';

                header("Location: ../index.php");
                exit();
            } else {
                $_SESSION['error'] = "Contraseña incorrecta.";
            }
            mysqli_stmt_close($stmt_prop);
        } else {
            $_SESSION['error'] = "El usuario no existe.";
        }
    }

    mysqli_close($conn);
    header("Location: ../view/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../css/styles.css" />
  <link href="https://fonts.googleapis.com/css2?family=Special+Gothic+Expanded+One&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Tuffy:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet" />
  <link rel="icon" href="../resources/logo_perriatria.png" type="image/x-icon">
  <title>Mascotas</title>
</head>
<body>
<header class="text-center">
    <h1>Mascotas</h1>
    <div>
      <img src="../resources/logo_perriatria_blanco.png" alt="Logo Perriatria Blanco" class="logo-header">
    </div>
</header>
  <ul class="nav nav-tabs custom-navbar w-100">
    <div class="nav-left">
      <li class="nav-item">
        <a class="nav-link active" aria-current="page" href="../index.php">Inicio</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="./mascotas.php">Mascotas</a>
      </li>
    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
      <li class="nav-item">
        <a class="nav-link" href="./veterinarios.php">Veterinarios</a>
      </li>
    <?php endif; ?>
    <?php if (isset($_SESSION['rol']) && in_array($_SESSION['rol'], ['admin', 'veterinario'])): ?>
      <li class="nav-item">
        <a class="nav-link" href="./mascotas_veterinario.php">Pacientes</a>
      </li>
    <?php endif; ?>
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

<div class="container mt-3">
  <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
  <?php endif; ?>
  <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
  <?php endif; ?>
</div>
  <?php
    // CONSULTA dinámica de mascotas según el rol
    $filtroSexo = $_GET['filtro-sexo'] ?? '';
    $filtroEspecie = $_GET['filtro-especie'] ?? '';
    $filtroRaza = $_GET['filtro-raza'] ?? '';

    $condiciones = [];
    if ($filtroSexo !== '') {
        $condiciones[] = "m.sexo_mascota = '" . mysqli_real_escape_string($conn, $filtroSexo) . "'";
    }
    if ($filtroEspecie !== '') {
        $condiciones[] = "m.id_especie_mascota = " . intval($filtroEspecie);
    }
    if ($filtroRaza !== '') {
        $condiciones[] = "m.id_raza_mascota = " . intval($filtroRaza);
    }

    // Filtro de propietario si no es admin
    if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'propietario') {
        $condiciones[] = "m.id_propietario = " . intval($_SESSION['id_propietario']);
    }

    $where = '';
    if (!empty($condiciones)) {
        $where = 'WHERE ' . implode(' AND ', $condiciones);
    }

    $query = "
        SELECT m.*, 
              e.nombre_especie AS especie,
              r.raza_nombre AS raza,
              v.nombre_veterinario,
              p.nombre_propietario
        FROM mascota m
        LEFT JOIN especie e ON m.id_especie_mascota = e.id_especie
        LEFT JOIN raza r ON m.id_raza_mascota = r.id_raza
        LEFT JOIN veterinario v ON m.id_veterinario_mascota = v.id_veterinario
        LEFT JOIN propietario p ON m.id_propietario = p.id_propietario
        $where
        ORDER BY m.nombre_mascota ASC
    ";

    $result = mysqli_query($conn, $query);
    ?>


<main>
  <section class="container">
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
        <select class="form-select" name="filtro-especie" id="filtro-especie" onchange="cargarRazasFiltro('filtro-especie', 'filtro-raza')">
          <option value="">Todas</option>
          <?php
          $res_especie = mysqli_query($conn, "SELECT id_especie, nombre_especie FROM especie");
          while ($row = mysqli_fetch_assoc($res_especie)) {
              $id = $row['id_especie'];
              $nombre = htmlspecialchars($row['nombre_especie']);
              $sel = (isset($_GET['filtro-especie']) && $_GET['filtro-especie'] == $id) ? 'selected' : '';
              echo "<option value='$id' $sel>$nombre</option>";
          }
          ?>
        </select>
      </div>

      <div class="col-md-3">
        <label for="filtro-raza">Raza:</label>
        <select class="form-select" name="filtro-raza" id="filtro-raza">
          <option value="">Todas</option>
          <?php
          $res_raza = mysqli_query($conn, "SELECT id_raza, raza_nombre FROM raza ORDER BY raza_nombre");
          while ($row = mysqli_fetch_assoc($res_raza)) {
              $id = $row['id_raza'];
              $nombre = htmlspecialchars($row['raza_nombre']);
              $sel = (isset($_GET['filtro-raza']) && $_GET['filtro-raza'] == $id) ? 'selected' : '';
              echo "<option value='$id' $sel>$nombre</option>";
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
      <table class="table table-striped table-hover align-middle mascotas-table table-responsive">
        <thead class="table-success table mascotas-table">
          <tr>
            <th>Foto</th>
            <th>Nombre</th>
            <th>Fecha de nacimiento</th>
            <th>Propietario</th>
            <th>Veterinario</th>
            <th>Sexo</th>
            <th>Especie</th>
            <th>Raza</th>
            <th>Modificar</th>
            <th>Eliminar</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <?php while ($mascota = mysqli_fetch_assoc($result)): ?>
              <tr>
                <td>
                  <?php if (!empty($mascota['foto_mascota'])): ?>
                    <img src="../resources/<?php echo htmlspecialchars($mascota['foto_mascota']); ?>" alt="Foto de <?php echo htmlspecialchars($mascota['nombre_mascota']); ?>" >
                  <?php else: ?>
                    Sin foto
                  <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($mascota['nombre_mascota']); ?></td>
                <td><?php echo htmlspecialchars($mascota['fecha_nacimiento_mascota']); ?></td>
                <td><?php echo htmlspecialchars($mascota['nombre_propietario'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($mascota['nombre_veterinario'] ?? 'Sin asignar'); ?></td>
                <td><?php echo ($mascota['sexo_mascota'] === 'M') ? 'Macho' : 'Hembra'; ?></td>
                <td><?php echo htmlspecialchars($mascota['especie']); ?></td>
                <td><?php echo htmlspecialchars($mascota['raza']); ?></td>
                <td><a href="../view/modificar_mascota.php?chip=<?php echo $mascota['chip_mascota']; ?>" class="btn btn-warning btn-sm">Editar</a></td>
                <td><a href="../processes/eliminar_mascota.php?chip_mascota=<?php echo $mascota['chip_mascota']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que quieres eliminar esta mascota?');">Borrar</a></td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr><td colspan="10" class="text-center">No hay mascotas que coincidan con los filtros.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </section>

  <?php if (isset($_SESSION['usuario'])): ?>
    <div class="user-panel mt-3 text-center">
      <span>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?></span>
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
