<?php
session_start();
require '../services/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($conn)) {
        $_SESSION['error'] = "Error de conexión con la base de datos.";
        header("Location: ../view/mascotas.php");
        die();
    }

    $nombre = trim($_POST['nombre']);
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $sexo = $_POST['sexo'];
    $especie = trim($_POST['especie']);
    $raza = trim($_POST['raza']);
    $usuario_id = $_SESSION['user_id'] ?? null;

    if (!$usuario_id) {
        $_SESSION['error'] = "Usuario no autenticado.";
        header("Location: ../view/mascotas.php");
        die();
    }

    if (empty($nombre) || empty($fecha_nacimiento) || empty($sexo) || empty($especie) || empty($raza)) {
        $_SESSION['error'] = "Todos los campos son obligatorios.";
        header("Location: ../view/mascotas.php");
        die();
    }

    if (strlen($nombre) < 2) {
        $_SESSION['error'] = "El nombre debe tener al menos 2 caracteres.";
        header("Location: ../view/mascotas.php");
        die();
    }

    // Verificar si ya existe una mascota con ese nombre para este usuario
    $sql_check = "SELECT chip_mascota FROM mascota WHERE nombre = '$nombre' AND usuario_id = $usuario_id";
    $result = mysqli_query($conn, $sql_check);

    if ($result && mysqli_num_rows($result) > 0) {
        $_SESSION['error'] = "Ya has registrado una mascota con ese nombre.";
        header("Location: ../view/mascotas.php");
        die();
    }

    // Insertar mascota
    $fecha_registro = date('Y-m-d H:i:s');
    $sql_insert = "INSERT INTO mascota (nombre, fecha_nacimiento, sexo, especie, raza, usuario_id, fecha_registro)
                   VALUES ('$nombre', '$fecha_nacimiento', '$sexo', '$especie', '$raza', $usuario_id, '$fecha_registro')";

    if (mysqli_query($conn, $sql_insert)) {
        $_SESSION['success'] = "Mascota registrada exitosamente.";
    } else {
        $_SESSION['error'] = "Error al registrar la mascota: " . mysqli_error($conn);
    }

    mysqli_close($conn);
    header("Location: ../view/mascotas.php");
    die();
}
?>
