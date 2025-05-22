<?php
include "../services/connection.php";
session_start();

// Cambia esto si los veterinarios también pueden editar
if (!isset($_SESSION['id_veterinario'])) {
    header("Location: ../view/login.php");
    exit();
}

// Validaciones
$nombre = trim($_POST['nombre']);
$apellidos = trim($_POST['apellidos']);
$telefono = trim($_POST['telefono']);
$email = trim($_POST['email']);
$especialidad = trim($_POST['especialidad']);
$salario = floatval($_POST['salario']);

if (empty($nombre) || strlen($nombre) < 2) {
    $_SESSION['error'] = "El nombre es obligatorio y debe tener al menos 2 caracteres.";
    header("Location: ../view/formulario_veterinario.php");
    exit();
}

if (empty($apellidos) || strlen($apellidos) < 2) {
    $_SESSION['error'] = "Los apellidos son obligatorios y deben tener al menos 2 caracteres.";
    header("Location: ../view/formulario_veterinario.php");
    exit();
}

if (empty($telefono) || !preg_match('/^[0-9]{9,15}$/', $telefono)) {
    $_SESSION['error'] = "El teléfono debe contener solo números y tener entre 9 y 15 dígitos.";
    header("Location: ../view/formulario_veterinario.php");
    exit();
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "El email no es válido.";
    header("Location: ../view/formulario_veterinario.php");
    exit();
}

if (empty($especialidad) || strlen($especialidad) < 3) {
    $_SESSION['error'] = "La especialidad es obligatoria y debe tener al menos 3 caracteres.";
    header("Location: ../view/formulario_veterinario.php");
    exit();
}

if (!is_numeric($salario) || $salario < 0) {
    $_SESSION['error'] = "El salario debe ser un número positivo.";
    header("Location: ../view/formulario_veterinario.php");
    exit();
}

// Verificar y ejecutar la actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_veterinario'])) {
    $id_veterinario = intval($_POST['id_veterinario']);

    $sql_update_vet = "UPDATE veterinario 
                       SET nombre_veterinario = ?, 
                           apellidos_veterinario = ?, 
                           telefono_veterinario = ?, 
                           email_veterinario = ?, 
                           especialidad_veterinario = ?, 
                           salario_veterinario = ? 
                       WHERE id_veterinario = ?";
    $stmt_update_vet = mysqli_prepare($conn, $sql_update_vet);
    mysqli_stmt_bind_param($stmt_update_vet, "ssssssi", $nombre, $apellidos, $telefono, $email, $especialidad, $salario, $id_veterinario);
    $resultado_update = mysqli_stmt_execute($stmt_update_vet);
    mysqli_stmt_close($stmt_update_vet);

    if ($resultado_update) {
        $_SESSION['success'] = "Veterinario actualizado correctamente.";
        header("Location: ../view/veterinarios.php");
        exit();
    } else {
        $_SESSION['error'] = "Error al actualizar el veterinario: " . mysqli_error($conn);
        header("Location: ../view/formulario_veterinario.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Datos no proporcionados correctamente.";
    header("Location: ../view/formulario_veterinario.php");
    exit();
}

mysqli_close($conn);
?>
