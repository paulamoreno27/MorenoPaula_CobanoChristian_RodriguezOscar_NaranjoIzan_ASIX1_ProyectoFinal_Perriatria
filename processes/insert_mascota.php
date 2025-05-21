<?php
session_start();
require '../services/connection.php';

$id_propietario = (int)($_SESSION['id_propietario'] ?? 0);
if (!$id_propietario) {
    $_SESSION['error'] = "Debes iniciar sesión para registrar mascotas.";
    header("Location: ../view/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST['nombre'] ?? '');
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';
    $sexo = $_POST['sexo'] ?? '';
    $id_especie = (int)($_POST['id_especie_mascota'] ?? 0);
    $id_raza = (int)($_POST['id_raza_mascota'] ?? 0);
    $id_veterinario = (int)($_POST['veterinario'] ?? 0);

    // Validaciones
    if (!$nombre || !$fecha_nacimiento || !$sexo || !$id_especie || !$id_raza || !$id_veterinario) {
        $_SESSION['error'] = "Todos los campos son obligatorios.";
        header("Location: ../view/formulario_mascota.php");
        exit();
    }

    if (!in_array($sexo, ['M', 'F'])) {
        $_SESSION['error'] = "El sexo debe ser 'M' o 'F'.";
        header("Location: ../view/formulario_mascota.php");
        exit();
    }

    // Foto
    $foto_nombre = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $foto_tmp = $_FILES['foto']['tmp_name'];
        $foto_ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $foto_nombre = uniqid('mascota_') . '.' . $foto_ext;
        $destino = '../resources/' . $foto_nombre;

        if (!move_uploaded_file($foto_tmp, $destino)) {
            $_SESSION['error'] = "Error al subir la imagen.";
            header("Location: ../view/formulario_mascota.php");
            exit();
        }
    }

    // Evitar duplicados por nombre
    $check_sql = "SELECT chip_mascota FROM mascota WHERE nombre_mascota = ? AND id_propietario = ?";
    $check_stmt = mysqli_prepare($conn, $check_sql);
    mysqli_stmt_bind_param($check_stmt, "si", $nombre, $id_propietario);
    mysqli_stmt_execute($check_stmt);
    mysqli_stmt_store_result($check_stmt);
    if (mysqli_stmt_num_rows($check_stmt) > 0) {
        $_SESSION['error'] = "Ya tienes una mascota registrada con ese nombre.";
        mysqli_stmt_close($check_stmt);
        header("Location: ../view/formulario_mascota.php");
        exit();
    }
    mysqli_stmt_close($check_stmt);

    $fecha_registro = date('Y-m-d H:i:s');

    // Inserción
    $sql = "INSERT INTO mascota 
        (nombre_mascota, fecha_nacimiento_mascota, sexo_mascota, id_especie_mascota, id_raza_mascota, fecha_registro_mascota, id_propietario, id_veterinario_mascota, foto_mascota)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssiiisss", 
        $nombre,
        $fecha_nacimiento,
        $sexo,
        $id_especie,
        $id_raza,
        $fecha_registro,
        $id_propietario,
        $id_veterinario,
        $foto_nombre
    );

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Mascota registrada exitosamente.";
    } else {
        $_SESSION['error'] = "Error al registrar la mascota: " . mysqli_stmt_error($stmt);
        if ($foto_nombre && file_exists($destino)) {
            unlink($destino);
        }
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    header("Location: ../view/mascotas.php");
    exit();
} else {
    $_SESSION['error'] = "Método de acceso no permitido.";
    header("Location: ../view/formulario_mascota.php");
    exit();
}
