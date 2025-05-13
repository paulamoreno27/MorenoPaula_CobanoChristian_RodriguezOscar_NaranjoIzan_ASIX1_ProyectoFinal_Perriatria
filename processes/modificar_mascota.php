<?php
include "../services/connection.php";
session_start();

// Verificar si se ha recibido un ID válido
if (!isset($_GET['id'])) {
    echo "ID de Mascota no válido.";
    exit();
}

$id = $_GET['id'];

// Obtener datos de la mascota
$sql = "SELECT * FROM mascota WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$mascota = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$mascota) {
    echo "Artista no encontrado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
    <title>Modificar Mascota</title>
</head>
<body>
<div>
    <div>
        <h2>Modificar Mascota</h2>
    </div>
    <a href="../view/mascotas.php"><button>Atrás</button></a>
</div>

<form action="../processes/update_mascota.php" method="post">
    <input type="hidden" name="id" value="<?php echo $id; ?>">

    <label>Nombre:</label>
    <input type="text" name="nombre" value="<?php echo htmlspecialchars($mascota['nombre']); ?>" required>

    <label>Género:</label>
    <input type="text" name="genero" value="<?php echo htmlspecialchars($mascota['genero']); ?>" required>

    <label>País:</label>
    <input type="text" name="pais" value="<?php echo htmlspecialchars($mascota['pais']); ?>" required>

    <button type="submit">Guardar cambios</button>
</form>
</body>
</html>