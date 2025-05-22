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

    // Validar nombre
    if (empty($nombre) || strlen($nombre) < 3) {
        $_SESSION['error'] = "El nombre de la mascota es obligatorio y debe tener al menos 3 caracteres.";
        header("Location: ../view/formulario_mascota.php");
        exit();
    }

    // Validar fecha de nacimiento
    if (strtotime($fecha_nacimiento) > time()) {
        $_SESSION['error'] = "La fecha de nacimiento no puede ser posterior a la fecha actual.";
        header("Location: ../view/formulario_mascota.php");
        exit();
    }

    // Validar sexo
    if (empty($sexo) || !in_array($sexo, ['M', 'F'])) {
        $_SESSION['error'] = "El sexo es obligatorio y debe ser 'M' o 'F'.";
        header("Location: ../view/formulario_mascota.php");
        exit();
    }

    // Validar especie
    if ($id_especie <= 0) {
        $_SESSION['error'] = "Debes seleccionar una especie válida.";
        header("Location: ../view/formulario_mascota.php");
        exit();
    }

    // Validar raza
    if ($id_raza <= 0) {
        $_SESSION['error'] = "Debes seleccionar una raza válida.";
        header("Location: ../view/formulario_mascota.php");
        exit();
    }

    // Validar veterinario
    if ($id_veterinario <= 0) {
        $_SESSION['error'] = "Debes seleccionar un veterinario válido.";
        header("Location: ../view/formulario_mascota.php");
        exit();
    }

    // Validar foto
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
        
        $origen = @imagecreatefromstring(file_get_contents($destino));
        if ($origen) {
            $ancho_orig = imagesx($origen);
            $alto_orig = imagesy($origen);
            $nuevo = imagecreatetruecolor(7, 75);
            imagecopyresampled($nuevo, $origen, 0, 0, 0, 0, 75, 75, $ancho_orig, $alto_orig);
            imagejpeg($nuevo, $destino, 85); // Se guarda sobre el mismo archivo
            imagedestroy($origen);
            imagedestroy($nuevo);
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
