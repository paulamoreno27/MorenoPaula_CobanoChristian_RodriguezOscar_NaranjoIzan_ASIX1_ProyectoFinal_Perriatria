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
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['chip'], $_POST['nombre'], $_POST['fecha_nacimiento'], $_POST['sexo'], $_POST['especie'], $_POST['raza'], $_POST['veterinario'])) {
    $chip = $_POST['chip'];
    $nombre = trim($_POST['nombre']);
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $sexo = $_POST['sexo'];
    $especie = trim($_POST['especie']);
    $raza = trim($_POST['raza']);
    $nombre_veterinario = trim($_POST['veterinario']); // Nombre del veterinario enviado

    // Validación de datos del formulario
$errores = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validar nombre
    if (empty($_POST['nombre']) || strlen(trim($_POST['nombre'])) < 2) {
        $errores[] = 'El nombre de la mascota es obligatorio y debe tener al menos 2 caracteres.';
    }
    // Validar fecha de nacimiento
    if (empty($_POST['fecha_nacimiento']) || !preg_match('/^\\d{4}-\\d{2}-\\d{2}$/', $_POST['fecha_nacimiento'])) {
        $errores[] = 'La fecha de nacimiento no es válida.';
    }
    // Validar sexo
    if (empty($_POST['sexo']) || !in_array($_POST['sexo'], ['M', 'F'])) {
        $errores[] = 'El sexo debe ser "M" o "F".';
    }
    // Validar especie
    if (empty($_POST['especie']) || strlen(trim($_POST['especie'])) < 2) {
        $errores[] = 'La especie es obligatoria y debe tener al menos 2 caracteres.';
    }
    // Validar raza
    if (empty($_POST['raza']) || strlen(trim($_POST['raza'])) < 2) {
        $errores[] = 'La raza es obligatoria y debe tener al menos 2 caracteres.';
    }
    // Validar veterinario
    if (empty($_POST['veterinario'])) {
        $errores[] = 'Debes seleccionar un veterinario.';
    }
    // Validar imagen si se sube
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $permitidas = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['foto']['type'], $permitidas)) {
            $errores[] = 'La foto debe ser JPG, PNG o GIF.';
        }
        if ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
            $errores[] = 'La foto no puede superar los 2MB.';
        }
    }
    // Mostrar errores y detener si hay alguno
    if (!empty($errores)) {
        foreach ($errores as $error) {
            echo '<p style="color:red;">' . htmlspecialchars($error) . '</p>';
        }
        exit();
    }
}

    // Depuración: verifica el nombre del veterinario enviado
    echo "<p>Nombre del veterinario enviado: " . htmlspecialchars($nombre_veterinario) . "</p>";

    // Validar que el chip es numérico
    if (!is_numeric($chip)) {
        echo "<p>ID de mascota no válido.</p>";
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
        echo "<p>El veterinario especificado no existe. Nombre enviado: " . htmlspecialchars($nombre_veterinario) . "</p>";
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
        // Actualizar incluyendo la foto y el veterinario
        $sql = "UPDATE mascota SET nombre_mascota = ?, fecha_nacimiento_mascota = ?, sexo_mascota = ?, especie_mascota = ?, raza_mascota = ?, foto_mascota = ?, id_veterinario_mascota = ? WHERE chip_mascota = ? AND id_propietario = ?";
        $stmt_update = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt_update, "ssssssiii", $nombre, $fecha_nacimiento, $sexo, $especie, $raza, $foto_nombre, $id_veterinario, $chip, $id_propietario);
    } else {
        // Actualizar sin cambiar la foto pero incluyendo el veterinario
        $sql = "UPDATE mascota SET nombre_mascota = ?, fecha_nacimiento_mascota = ?, sexo_mascota = ?, especie_mascota = ?, raza_mascota = ?, id_veterinario_mascota = ? WHERE chip_mascota = ? AND id_propietario = ?";
        $stmt_update = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt_update, "sssssiis", $nombre, $fecha_nacimiento, $sexo, $especie, $raza, $id_veterinario, $chip, $id_propietario);
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
