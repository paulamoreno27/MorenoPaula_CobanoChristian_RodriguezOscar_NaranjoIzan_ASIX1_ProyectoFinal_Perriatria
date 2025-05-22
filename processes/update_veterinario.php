<?php
include "../services/connection.php";
session_start();

if (!isset($_SESSION['id_propietario'])) {
    header("Location: ../view/login.php");
    exit();
}

// Validar nombre
$nombre = trim($_POST['nombre']);
if (empty($nombre) || strlen($nombre) < 2) {
    $_SESSION['error'] = "El nombre es obligatorio y debe tener al menos 2 caracteres.";
    header("Location: ../view/formulario_veterinario.php");
    exit();
}

// Validar apellidos
$apellidos = trim($_POST['apellidos']);
if (empty($apellidos) || strlen($apellidos) < 2) {
    $_SESSION['error'] = "Los apellidos son obligatorios y deben tener al menos 2 caracteres.";
    header("Location: ../view/formulario_veterinario.php");
    exit();
}

// Validar teléfono (solo dígitos y longitud mínima)
$telefono = trim($_POST['telefono']);
if (empty($telefono) || !preg_match('/^[0-9]{9,15}$/', $telefono)) {
    $_SESSION['error'] = "El teléfono debe contener solo números y tener entre 9 y 15 dígitos.";
    header("Location: ../view/formulario_veterinario.php");
    exit();
}

// Validar email
$email = trim($_POST['email']);
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "El email no es válido.";
    header("Location: ../view/formulario_veterinario.php");
    exit();
}

// Validar especialidad
$especialidad = trim($_POST['especialidad']);
if (empty($especialidad) || strlen($especialidad) < 3) {
    $_SESSION['error'] = "La especialidad es obligatoria y debe tener al menos 3 caracteres.";
    header("Location: ../view/formulario_veterinario.php");
    exit();
}

// Validar salario (positivo)
$salario = floatval($_POST['salario']);
if (!is_numeric($salario) || $salario < 0) {
    $_SESSION['error'] = "El salario debe ser un número positivo.";
    header("Location: ../view/formulario_veterinario.php");
    exit();
}

if (
    $_SERVER['REQUEST_METHOD'] == 'POST' &&
    isset(
        $_POST['id_veterinario'],
        $_POST['nombre'],     // Aunque no se actualice, viene del formulario
        $_POST['apellidos'],  // Aunque no se actualice, viene del formulario
        $_POST['telefono'],
        $_POST['email'],
        $_POST['especialidad'],
        $_POST['salario']
    )
) {
    $id_veterinario = intval($_POST['id_veterinario']);
    $telefono = trim($_POST['telefono']);
    $email = trim($_POST['email']);
    $especialidad = trim($_POST['especialidad']);
    $salario = floatval($_POST['salario']);

    // Solo se actualiza la tabla veterinario
    $sql_update_vet = "UPDATE veterinario 
                       SET telefono_veterinario = ?, 
                           email_veterinario = ?, 
                           especialidad_veterinario = ?, 
                           salario_veterinario = ? 
                       WHERE id_veterinario = ?";
    $stmt_update_vet = mysqli_prepare($conn, $sql_update_vet);
    mysqli_stmt_bind_param($stmt_update_vet, "sssdi", $telefono, $email, $especialidad, $salario, $id_veterinario);
    $resultado_update = mysqli_stmt_execute($stmt_update_vet);
    mysqli_stmt_close($stmt_update_vet);

    if ($resultado_update) {
        $_SESSION['success'] = "Veterinario actualizado correctamente.";
        header("Location: ../view/veterinarios.php");
        exit();
    } else {
        echo "<p>Error al actualizar el veterinario: " . mysqli_error($conn) . "</p>";
    }
} else {
    echo "<p>Datos no proporcionados correctamente.</p>";
}

mysqli_close($conn);
?>
