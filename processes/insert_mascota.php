<?php
session_start();
require '../services/connection.php';

$id_propietario = $_SESSION['id_propietario'] ?? null;
if (!$id_propietario) {
    $_SESSION['error'] = "Debes iniciar sesión para registrar mascotas.";
    header("Location: ../view/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($conn)) {
        $_SESSION['error'] = "Error de conexión con la base de datos.";
        header("Location: ../view/mascotas.php");
        exit();
    }

    $nombre = trim($_POST['nombre'] ?? '');
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';
    $sexo = $_POST['sexo'] ?? '';
    $especie = trim($_POST['especie'] ?? '');
    $raza = trim($_POST['raza'] ?? '');

    // Validaciones básicas
    if (empty($nombre) || empty($fecha_nacimiento) || empty($sexo) || empty($especie) || empty($raza)) {
        $_SESSION['error'] = "Todos los campos son obligatorios.";
        header("Location: ../view/mascotas.php");
        exit();
    }

    if (strlen($nombre) < 3) {
        $_SESSION['error'] = "El nombre debe tener al menos 3 caracteres.";
        header("Location: ../view/mascotas.php");
        exit();
    }

    // Verificar si ya existe una mascota con ese nombre para este propietario
    $sql_check = "SELECT chip_mascota FROM mascota WHERE nombre_mascota = ? AND id_propietario = ?";
    $stmt_check = mysqli_prepare($conn, $sql_check);

    if (!$stmt_check) {
        $_SESSION['error'] = "Error al preparar la verificación.";
        header("Location: ../view/mascotas.php");
        exit();
    }

    mysqli_stmt_bind_param($stmt_check, "si", $nombre, $id_propietario);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    if (mysqli_stmt_num_rows($stmt_check) > 0) {
        $_SESSION['error'] = "Ya has registrado una mascota con ese nombre.";
        mysqli_stmt_close($stmt_check);
        header("Location: ../view/mascotas.php");
        exit();
    }
    mysqli_stmt_close($stmt_check);

    // Insertar mascota
    $fecha_registro = date('Y-m-d H:i:s');
    $sql_insert = "INSERT INTO mascota (nombre_mascota, fecha_nacimiento_mascota, sexo_mascota, especie_mascota, raza_mascota, fecha_registro_mascota, id_propietario)
                   VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt_insert = mysqli_prepare($conn, $sql_insert);

    if (!$stmt_insert) {
        $_SESSION['error'] = "Error al preparar el registro de mascota.";
        header("Location: ../view/mascotas.php");
        exit();
    }

    mysqli_stmt_bind_param($stmt_insert, "ssssssi", $nombre, $fecha_nacimiento, $sexo, $especie, $raza, $fecha_registro, $id_propietario);

    if (mysqli_stmt_execute($stmt_insert)) {
        $_SESSION['success'] = "Mascota registrada exitosamente.";
    } else {
        $_SESSION['error'] = "Error al registrar la mascota: " . mysqli_stmt_error($stmt_insert);
    }

    mysqli_stmt_close($stmt_insert);
    mysqli_close($conn);

    header("Location: ../view/mascotas.php");
    exit();
} else {
    $_SESSION['error'] = "Método de acceso no permitido.";
    header("Location: ../view/mascotas.php");
    exit();
}
?>
