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
    $veterinario = (int)($_POST['veterinario'] ?? 0);

    // Validación
    if (empty($nombre) || empty($fecha_nacimiento) || empty($sexo) || empty($especie) || empty($raza) || !$veterinario) {
        $_SESSION['error'] = "Todos los campos son obligatorios.";
        header("Location: ../view/mascotas.php");
        exit();
    }

    if (strlen($nombre) < 3) {
        $_SESSION['error'] = "El nombre debe tener al menos 3 caracteres.";
        header("Location: ../view/mascotas.php");
        exit();
    }

    if (!in_array($sexo, ['M', 'F'])) {
        $_SESSION['error'] = "El sexo debe ser 'M' o 'F'.";
        header("Location: ../view/mascotas.php");
        exit();
    }

    if (strlen($especie) < 3 || strlen($raza) < 3) {
        $_SESSION['error'] = "La especie y la raza deben tener al menos 3 caracteres.";
        header("Location: ../view/mascotas.php");
        exit();
    }

    // Procesar foto
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
            $_SESSION['error'] = "Error al subir la imagen.";
            header("Location: ../view/mascotas.php");
            exit();
        }
    }

    // Comprobar duplicado
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

    $fecha_registro = date('Y-m-d H:i:s');

    // Registro de mascota
    $sql_insert = "INSERT INTO mascota 
    (nombre_mascota, fecha_nacimiento_mascota, sexo_mascota, especie_mascota, raza_mascota, fecha_registro_mascota, id_propietario, id_veterinario_mascota, foto_mascota)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt_insert = mysqli_prepare($conn, $sql_insert);

    if (!$stmt_insert) {
        $_SESSION['error'] = "Error al preparar el registro de mascota.";
        header("Location: ../view/mascotas.php");
        exit();
    }

    // Orden correcto
    mysqli_stmt_bind_param($stmt_insert, "ssssssiss",
        $nombre,
        $fecha_nacimiento,
        $sexo,
        $especie,
        $raza,
        $fecha_registro,
        $id_propietario,
        $veterinario,
        $foto_nombre
    );

    if (mysqli_stmt_execute($stmt_insert)) {
        $_SESSION['success'] = "Mascota registrada exitosamente.";
    } else {
        $_SESSION['error'] = "Error al registrar la mascota: " . mysqli_stmt_error($stmt_insert);
        if ($foto_nombre && file_exists($destino)) {
            unlink($destino);
        }
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
