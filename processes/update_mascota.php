<?php
include "../services/connection.php";
session_start();

// Verificar si el formulario fue enviado correctamente con datos válidos
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'], $_POST['nombre'], $_POST['genero'], $_POST['pais'])) {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $genero = $_POST['genero'];
    $pais = $_POST['pais'];

    // Actualizar datos del artista
    $sql = "UPDATE mascota SET nombre = ?, genero = ?, pais = ? WHERE id = ?";
    $stmt_update = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt_update, "sssi", $nombre, $genero, $pais, $id);
    $resultado_update = mysqli_stmt_execute($stmt_update);
    mysqli_stmt_close($stmt_update);

    // Comprobar errores
    if ($resultado_update) {
        header("Location: ../view/mascotas.php");
        exit();
    } else {
        echo "<p>Error al actualizar el la mascota: " . mysqli_error($conn) . "</p>";
    }
} else {
    echo "<p>Datos no proporcionados correctamente.</p>";
}
mysqli_close($conn);
?>