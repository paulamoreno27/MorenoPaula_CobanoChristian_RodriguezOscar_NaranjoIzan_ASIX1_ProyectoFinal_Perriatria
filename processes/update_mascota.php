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
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['chip'], $_POST['nombre'], $_POST['fecha_nacimiento'], $_POST['sexo'], $_POST['id_especie_mascota'], $_POST['id_raza_mascota'], $_POST['veterinario'])) {
    $chip = $_POST['chip'];
    $nombre = trim($_POST['nombre']);
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $sexo = $_POST['sexo'];
    $especie = (int)$_POST['id_especie_mascota'];
    $raza = (int)$_POST['id_raza_mascota'];
    $nombre_veterinario = trim($_POST['veterinario']); // Nombre del veterinario enviado

    // Validar nombre
    if (empty($nombre) || strlen($nombre) < 3) {
        $_SESSION['error'] = 'El nombre de la mascota es obligatorio y debe tener al menos 3 caracteres.';
        header("Location: ../view/formulario_mascota.php");
        exit();
    }

    // Validar fecha de nacimiento
    if (strtotime($fecha_nacimiento) > time()) {
    $_SESSION['error'] = 'La fecha de nacimiento no puede ser posterior a la fecha actual.';
    header("Location: ../view/formulario_mascota.php");
    exit();
    }

    // Validar sexo
    if (empty($sexo) || !in_array($sexo, ['M', 'F'])) {
        $_SESSION['error'] = 'El sexo debe ser "M" o "F".';
        header("Location: ../view/formulario_mascota.php");
        exit();
    }

    // Validar especie
    if (empty($especie) || !is_numeric($especie)) {
        $_SESSION['error'] = 'Debes seleccionar una especie válida.';
        header("Location: ../view/formulario_mascota.php");
        exit();
    }

    // Validar raza
    if (empty($raza) || !is_numeric($raza)) {
        $_SESSION['error'] = 'Debes seleccionar una raza válida.';
        header("Location: ../view/formulario_mascota.php");
        exit();
    }

    // Validar veterinario
    if (empty($nombre_veterinario) || strlen($nombre_veterinario) < 3) {
        $_SESSION['error'] = 'El nombre del veterinario es obligatorio y debe tener al menos 3 caracteres.';
        header("Location: ../view/formulario_mascota.php");
        exit();
    }

    // Validar imagen si se sube
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $permitidas = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['foto']['type'], $permitidas)) {
            $_SESSION['error'] = 'La foto debe ser JPG, PNG o GIF.';
            header("Location: ../view/formulario_mascota.php");
            exit();
        }
        if ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
            $_SESSION['error'] = 'La foto no puede superar los 2MB.';
            header("Location: ../view/formulario_mascota.php");
            exit();
        }
    }

    // Depuración: verifica el nombre del veterinario enviado
    echo "<p>Nombre del veterinario enviado: " . htmlspecialchars($nombre_veterinario) . "</p>";

    // Validar que el chip es numérico
    if (!is_numeric($chip)) {
        $_SESSION['error'] = 'ID de mascota no válido.';
        header("Location: ../view/formulario_mascota.php");
        exit();
    }

    // Buscar el ID del veterinario por su nombre
    $sql_veterinario = "SELECT id_veterinario FROM veterinario WHERE nombre_veterinario = ?";
    $stmt_veterinario = mysqli_prepare($conn, $sql_veterinario);

    if (!$stmt_veterinario) {
        die("Error en la preparación de la consulta: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt_veterinario, "s", $nombre_veterinario);
    mysqli_stmt_execute($stmt_veterinario);
    mysqli_stmt_bind_result($stmt_veterinario, $id_veterinario);
    mysqli_stmt_fetch($stmt_veterinario);
    mysqli_stmt_close($stmt_veterinario);

    // Depuración: verifica el resultado de la consulta
    if (empty($id_veterinario)) {
        $_SESSION['error'] = 'El veterinario especificado no existe.';
        header("Location: ../view/formulario_mascota.php");
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
            $_SESSION['error'] = 'Error al subir la imagen.';
            header("Location: ../view/formulario_mascota.php");
            exit();
        }

        // Redimensionar a 300x300 sin importar el formato
        $origen = @imagecreatefromstring(file_get_contents($destino));
        if ($origen) {
            $ancho_orig = imagesx($origen);
            $alto_orig = imagesy($origen);
            $nuevo = imagecreatetruecolor(75, 75);
            imagecopyresampled($nuevo, $origen, 0, 0, 0, 0, 75, 100, $ancho_orig, $alto_orig);
            imagejpeg($nuevo, $destino, 85); // Guarda como JPG
            imagedestroy($origen);
            imagedestroy($nuevo);
        }
    }

    // Actualizar la mascota
    if ($foto_nombre) {
        $sql = "UPDATE mascota SET nombre_mascota = ?, fecha_nacimiento_mascota = ?, sexo_mascota = ?, id_especie_mascota = ?, id_raza_mascota = ?, foto_mascota = ?, id_veterinario_mascota = ? WHERE chip_mascota = ? AND id_propietario = ?";
        $stmt_update = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt_update, "sssiisiii", $nombre, $fecha_nacimiento, $sexo, $especie, $raza, $foto_nombre, $id_veterinario, $chip, $id_propietario);
    } else {
        $sql = "UPDATE mascota SET nombre_mascota = ?, fecha_nacimiento_mascota = ?, sexo_mascota = ?, id_especie_mascota = ?, id_raza_mascota = ?, id_veterinario_mascota = ? WHERE chip_mascota = ? AND id_propietario = ?";
        $stmt_update = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt_update, "sssiiiii", $nombre, $fecha_nacimiento, $sexo, $especie, $raza, $id_veterinario, $chip, $id_propietario);
    }

    $resultado_update = mysqli_stmt_execute($stmt_update);
    mysqli_stmt_close($stmt_update);

    if ($resultado_update) {
        header("Location: ../view/mascotas.php");
        exit();
    } else {
        $_SESSION['error'] = 'Error al actualizar la mascota.';
        header("Location: ../view/formulario_mascota.php");
        exit();
    }
} else {
    $_SESSION['error'] = 'Datos no proporcionados correctamente.';
    header("Location: ../view/formulario_mascota.php");
    exit();
}

mysqli_close($conn);
?>
