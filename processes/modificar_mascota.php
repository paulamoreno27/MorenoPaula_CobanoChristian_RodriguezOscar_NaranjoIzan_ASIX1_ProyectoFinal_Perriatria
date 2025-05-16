<?php
include "../services/connection.php";
session_start();

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_propietario'])) {
    header("Location: ../view/login.php");
    exit();
}

$id_propietario = $_SESSION['id_propietario'];

// Verificar que se recibe el parámetro 'chip'
if (!isset($_GET['chip']) || !is_numeric($_GET['chip'])) {
    echo "ID de mascota no válido.";
    exit();
}

$chip = intval($_GET['chip']);

// Obtener datos de la mascota solo si pertenece al propietario logueado
$sql = "SELECT * FROM mascota WHERE chip_mascota = ? AND id_propietario = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $chip, $id_propietario);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$mascota = mysqli_fetch_assoc($result);

if (!$mascota) {
    echo "Mascota no encontrada o no tienes permiso para editarla.";
    exit();
}

mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Modificar Mascota</title>
    <link rel="stylesheet" type="text/css" href="../css/modificar.css">
</head>
<body>
    <div class="header">
        <h2>Modificar Mascota</h2>
        <a href="../view/mascotas.php"><button>Atrás</button></a>
    </div>

    <form action="../processes/update_mascota.php" method="post">
        <input type="hidden" name="chip" value="<?php echo $mascota['chip_mascota']; ?>">

        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?php echo htmlspecialchars($mascota['nombre_mascota']); ?>" required>
        <br>

        <label>Fecha de nacimiento:</label>
        <input type="date" name="fecha_nacimiento" value="<?php echo htmlspecialchars($mascota['fecha_nacimiento_mascota']); ?>" required>
        <br>

        <label>Sexo:</label>
        <select name="sexo" required>
            <option value="M" <?php if ($mascota['sexo_mascota'] === 'M') echo 'selected'; ?>>Macho</option>
            <option value="F" <?php if ($mascota['sexo_mascota'] === 'F') echo 'selected'; ?>>Hembra</option>
        </select>
        <br>

        <label>Especie:</label>
        <input type="text" name="especie" value="<?php echo htmlspecialchars($mascota['especie_mascota']); ?>" required>
        <br>

        <label>Raza:</label>
        <input type="text" name="raza" value="<?php echo htmlspecialchars($mascota['raza_mascota']); ?>" required>
        <br>

        <button type="submit">Guardar cambios</button>
    </form>  
</body>
</html>
