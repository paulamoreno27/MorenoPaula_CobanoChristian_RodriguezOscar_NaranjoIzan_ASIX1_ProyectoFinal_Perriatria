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

    // Procesar la foto si se subió
    $foto_nombre = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $foto_tmp = $_FILES['foto']['tmp_name'];
        $foto_ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $foto_nombre = uniqid('mascota_') . '.' . $foto_ext;
        $destino = '../resources/' . $foto_nombre;

        if (!is_dir('../resources')) {
            mkdir('../resources', 0755, true);
        }

        if (!move_uploaded_file($foto_tmp, $destino)) {
            echo "<p>Error al subir la imagen.</p>";
            exit();
        }
    }

    if ($foto_nombre) {
        // Actualizar incluyendo la foto
        $sql = "UPDATE mascota SET nombre_mascota = ?, fecha_nacimiento_mascota = ?, sexo_mascota = ?, especie_mascota = ?, raza_mascota = ?, foto_mascota = ? WHERE chip_mascota = ? AND id_propietario = ?";
        $stmt_update = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt_update, "ssssssii", $nombre, $fecha_nacimiento, $sexo, $especie, $raza, $foto_nombre, $chip, $id_propietario);
    } else {
        // Actualizar sin cambiar la foto
        $sql = "UPDATE mascota SET nombre_mascota = ?, fecha_nacimiento_mascota = ?, sexo_mascota = ?, especie_mascota = ?, raza_mascota = ? WHERE chip_mascota = ? AND id_propietario = ?";
        $stmt_update = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt_update, "ssss sii", $nombre, $fecha_nacimiento, $sexo, $especie, $raza, $chip, $id_propietario);
    }

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
