<?php
session_start();
require '../services/connection.php';

// Asegúrate de que el veterinario ha iniciado sesión
if (!isset($_SESSION['id_veterinario'])) {
    header("Location: ../view/login.php");
    exit();
}

$id_veterinario = $_SESSION['id_veterinario'];
$where = ["m.id_veterinario_mascota = $id_veterinario"];

// Filtros opcionales
if (!empty($_GET['filtro-especie'])) {
    $especie = (int)$_GET['filtro-especie'];
    $where[] = "m.id_especie_mascota = $especie";
}
if (!empty($_GET['filtro-raza'])) {
    $raza = (int)$_GET['filtro-raza'];
    $where[] = "m.id_raza_mascota = $raza";
}
$condiciones = implode(" AND ", $where);

// Consulta con filtros
$query = "
    SELECT m.nombre_mascota, m.fecha_nacimiento_mascota, m.sexo_mascota,
           e.nombre_especie AS especie, r.nombre_raza AS raza,
           p.nombre_propietario, m.foto_mascota
    FROM mascota m
    JOIN especie e ON m.id_especie_mascota = e.id_especie
    JOIN raza r ON m.id_raza_mascota = r.id_raza
    LEFT JOIN propietario p ON m.id_propietario = p.id_propietario
    WHERE $condiciones
    ORDER BY m.nombre_mascota ASC
";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mascotas a Cargo</title>
    <link rel="icon" href="../resources/logo_perriatria.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body class="bg-light">
<div class="container py-4">
    <header class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="text-success">Bienvenido, Dr. <?php echo htmlspecialchars($_SESSION['usuario']); ?></h1>
        <a href="../services/logout.php" class="btn btn-outline-danger">Cerrar sesión</a>
    </header>

    <h2 class="mb-3">Mascotas asignadas a tu cuidado</h2>

    <!-- Formulario de filtros -->
    <form method="GET" class="row g-3 mb-4">
        <div class="col-md-4">
            <label for="filtro-especie" class="form-label">Especie</label>
            <select name="filtro-especie" class="form-select">
                <option value="">Todas</option>
                <?php
                $res_especie = mysqli_query($conn, "SELECT id_especie, nombre_especie FROM especie");
                while ($row = mysqli_fetch_assoc($res_especie)) {
                    $sel = (isset($_GET['filtro-especie']) && $_GET['filtro-especie'] == $row['id_especie']) ? 'selected' : '';
                    echo "<option value='{$row['id_especie']}' $sel>{$row['nombre_especie']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-4">
            <label for="filtro-raza" class="form-label">Raza</label>
            <select name="filtro-raza" class="form-select">
                <option value="">Todas</option>
                <?php
                $res_raza = mysqli_query($conn, "SELECT id_raza, nombre_raza FROM raza");
                while ($row = mysqli_fetch_assoc($res_raza)) {
                    $sel = (isset($_GET['filtro-raza']) && $_GET['filtro-raza'] == $row['id_raza']) ? 'selected' : '';
                    echo "<option value='{$row['id_raza']}' $sel>{$row['nombre_raza']}</option>";
                }
                ?>
            </select>
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-success me-2">Filtrar</button>
            <a href="mascotas_veterinario.php" class="btn btn-outline-secondary">Limpiar</a>
        </div>
    </form>

    <!-- Tabla de mascotas -->
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-success">
                <tr>
                    <th>Foto</th>
                    <th>Nombre</th>
                    <th>Fecha de nacimiento</th>
                    <th>Sexo</th>
                    <th>Especie</th>
                    <th>Raza</th>
                    <th>Propietario</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td>
                            <?php if (!empty($row['foto_mascota'])): ?>
                                <img src="../resources/<?php echo htmlspecialchars($row['foto_mascota']); ?>" alt="Foto" style="max-width: 80px;">
                            <?php else: ?>
                                <span class="text-muted">Sin foto</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['nombre_mascota']); ?></td>
                        <td><?php echo htmlspecialchars($row['fecha_nacimiento_mascota']); ?></td>
                        <td><?php echo ($row['sexo_mascota'] === 'M') ? 'Macho' : 'Hembra'; ?></td>
                        <td><?php echo htmlspecialchars($row['especie']); ?></td>
                        <td><?php echo htmlspecialchars($row['raza']); ?></td>
                        <td><?php echo htmlspecialchars($row['nombre_propietario'] ?? 'Sin propietario'); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <footer class="text-center mt-5">
        <a href="../index.php" class="btn btn-outline-primary">Volver al inicio</a>
    </footer>
</div>
</body>
</html>

<?php
mysqli_close($conn);
?>
