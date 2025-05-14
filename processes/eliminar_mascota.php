<?php
include '../services/config.php';
include '../services/connection.php';

// Verificar si se ha recibido el Chip de la mascota
if (isset($_GET['Chip'])) {
    $id = intval($_GET['Chip']); // Asegurarse de que el Chip sea un número entero

    // Eliminamos el artista
    $sql_eliminar_artista = "DELETE FROM mascota WHERE chip_mascota = ?";
    $stmt_artista = mysqli_prepare($conn, $sql_eliminar_artista);
    mysqli_stmt_bind_param($stmt_artista, "i", $id);
    $resultado_artista = mysqli_stmt_execute($stmt_artista);
    mysqli_stmt_close($stmt_artista);

    // Verificamos si la consulta se ejecutó correctamente
    if ($resultado_artista) {
        echo "<p>Artista eliminado correctamente.</p>";
        header("Location: ../index.php?success=mascota_eliminada");
        exit();
    } else {
        echo "<p>Error al eliminar el artista: " . mysqli_error($conn) . "</p>";
        header("Location: ../index.php?error=eliminacion_fallida");
        exit();
    }
} else {
    echo "<p>ID no proporcionado para eliminar.</p>";
    header("Location: ../index.php?error=id_no_enviado");
    exit();
}

mysqli_close($conn);
?>