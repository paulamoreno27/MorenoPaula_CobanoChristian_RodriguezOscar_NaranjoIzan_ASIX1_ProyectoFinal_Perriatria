<?php
include "../services/connection.php";
session_start();

// Verificar que el usuario esté logueado
if (!isset($_SESSION['id_propietario'])) {
    header("Location: ../view/login.php");
    exit();
}

$id_propietario = $_SESSION['id_propietario'];

// Verificar que el formulario fue enviado correctamente
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['chip'], $_POST['nombre'], $_POST['fecha_nacimiento'], $_POST['sexo'], $_POST['especie'], $_POST['raza'])) {
    $chip = $_POST['chip'];
    $nombre = trim($_POST['nombre']);
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $sexo = $_POST['sexo'];
    $especie = trim($_POST['especie']);
    $raza = trim($_POST['raza']);

    // Validar que el chip es numérico
    if (!is_numeric($chip)) {
        echo "<p>ID de mascota no válido.</p>";
        exit();
    }

    // Actualizar mascota solo si pertenece al propietario logueado
    $sql = "UPDATE mascota SET nombre_mascota = ?, fecha_nacimiento_mascota = ?, sexo_mascota = ?, especie_mascota = ?, raza_mascota = ? 
            WHERE chip_mascota = ? AND id_propietario = ?";
    $stmt_update = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt_update, "sssssii", $nombre, $fecha_nacimiento, $sexo, $especie, $raza, $chip, $id_propietario);
    $resultado_update = mysqli_stmt_execute($stmt_update);
    mysqli_stmt_close($stmt_update);

    if ($resultado_update) {
        header("Location: ../view/mascotas.php");
        exit();
    } else {
        echo "<p>Error al actualizar la mascota: " . mysqli_error($conn) . "</p>";
    }
} else {
    echo "<p>Datos no proporcionados correctamente.</p>";
}

mysqli_close($conn);
?>
