<?php
include "../services/connection.php";
session_start();

// SOLO veterinario o admin
if (!isset($_SESSION['rol']) || !in_array($_SESSION['rol'], ['admin', 'veterinario'])) {
    $_SESSION['error'] = "Acceso restringido.";
    header("Location: ./login.php");
    exit();
}

$filtroSexo = $_GET['filtro-sexo'] ?? '';
$filtroEspecie = $_GET['filtro-especie'] ?? '';
$filtroRaza = $_GET['filtro-raza'] ?? '';

$condiciones = [];

if ($_SESSION['rol'] === 'veterinario' && isset($_SESSION['id_veterinario'])) {
    $condiciones[] = "m.id_veterinario_mascota = " . intval($_SESSION['id_veterinario']);
}

if ($filtroSexo !== '') {
    $condiciones[] = "m.sexo_mascota = '" . mysqli_real_escape_string($conn, $filtroSexo) . "'";
}
if ($filtroEspecie !== '') {
    $condiciones[] = "m.id_especie_mascota = " . intval($filtroEspecie);
}
if ($filtroRaza !== '') {
    $condiciones[] = "m.id_raza_mascota = " . intval($filtroRaza);
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

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Pacientes del Veterinario</title>
  <link href="https://fonts.googleapis.com/css2?family=Special+Gothic+Expanded+One&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Tuffy:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../css/styles.css" />
  <link rel="icon" href="../resources/logo_perriatria.png" type="image/x-icon">
</head>
<body>
<header class="text-center">
  <h1>Pacientes</h1>
    <div>
      <img src="../resources/logo_perriatria_blanco.png" alt="Logo Perriatria Blanco" class="logo-header">
    </div>
</header>
    <ul class="nav nav-tabs custom-navbar w-100">
        <div class="nav-left">
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="../index.php">Inicio</a>
        </li>
    <?php if (isset($_SESSION['rol']) && in_array($_SESSION['rol'], ['admin', 'propietario'])): ?>
        <li class="nav-item">
            <a class="nav-link" href="./mascotas.php">Mascotas</a>
        </li>
    <?php endif; ?>
    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
        <li class="nav-item">
            <a class="nav-link" href="./veterinarios.php">Veterinarios</a>
        </li>
    <?php endif; ?>
        <li class="nav-item">
            <a class="nav-link" href="./mascotas_veterinario.php">Pacientes</a>
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

    <main>
        <section class="container mt-">
            <h2 class="titulo-verde">Pacientes asignados:</h2>

            <!-- Filtros -->
            <form method="GET" class="row g-3 mb-4 ms-1 me-1">
                <div class="col-md-3">
                    <label for="filtro-sexo">Sexo:</label>
                    <select class="form-select" name="filtro-sexo" id="filtro-sexo">
                        <option value="">Todos</option>
                        <option value="M" <?= ($filtroSexo === 'M') ? 'selected' : '' ?>>Macho</option>
                        <option value="F" <?= ($filtroSexo === 'F') ? 'selected' : '' ?>>Hembra</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filtro-especie">Especie:</label>
                    <select class="form-select" name="filtro-especie" id="filtro-especie">
                        <option value="">Todas</option>
                        <?php
                        $especies = mysqli_query($conn, "SELECT id_especie, nombre_especie FROM especie");
                        while ($esp = mysqli_fetch_assoc($especies)) {
                            $sel = ($filtroEspecie == $esp['id_especie']) ? 'selected' : '';
                            echo "<option value='{$esp['id_especie']}' $sel>" . htmlspecialchars($esp['nombre_especie']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filtro-raza">Raza:</label>
                    <select class="form-select" name="filtro-raza" id="filtro-raza">
                        <option value="">Todas</option>
                        <?php
                        $razas = mysqli_query($conn, "SELECT id_raza, raza_nombre FROM raza ORDER BY raza_nombre");
                        while ($rz = mysqli_fetch_assoc($razas)) {
                            $sel = ($filtroRaza == $rz['id_raza']) ? 'selected' : '';
                            echo "<option value='{$rz['id_raza']}' $sel>" . htmlspecialchars($rz['raza_nombre']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-success me-2">Filtrar</button>
                <a href="mascotas_veterinario.php" class="btn btn-outline-secondary">Limpiar</a>
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
                    <th>Sexo</th>
                    <th>Especie</th>
                    <th>Raza</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && mysqli_num_rows($result) > 0): ?>
                    <?php while ($mascota = mysqli_fetch_assoc($result)): ?>
                        <tr>
                        <td>
                            <?php if (!empty($mascota['foto_mascota'])): ?>
                            <img src="../resources/<?php echo htmlspecialchars($mascota['foto_mascota']); ?>" alt="Foto" style="width: 60px;">
                            <?php else: ?>
                            Sin foto
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($mascota['nombre_mascota']) ?></td>
                        <td><?= htmlspecialchars($mascota['fecha_nacimiento_mascota']) ?></td>
                        <td><?= htmlspecialchars($mascota['nombre_propietario']) ?></td>
                        <td><?= ($mascota['sexo_mascota'] === 'M') ? 'Macho' : 'Hembra' ?></td>
                        <td><?= htmlspecialchars($mascota['especie']) ?></td>
                        <td><?= htmlspecialchars($mascota['raza']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                    <?php else: ?>
                    <tr><td colspan="9" class="text-center">No hay mascotas asignadas.</td></tr>
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
