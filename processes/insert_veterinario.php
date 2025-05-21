<?php
session_start();
require '../services/connection.php';

// if (!isset($_SESSION['id_propietario'])) {
//     $_SESSION['error'] = "Debes iniciar sesión para registrar veterinarios.";
//     header("Location: ../view/login.php");
//     exit();
// }

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($conn)) {
        $_SESSION['error'] = "Error de conexión con la base de datos.";
        header("Location: ../view/veterinarios.php");
        exit();
    }

    $nombre = trim($_POST['nombre_veterinario'] ?? '');
    $apellidos = trim($_POST['apellidos_veterinario'] ?? '');
    $email = trim($_POST['email_veterinario'] ?? '');
    $telefono = trim($_POST['telefono_veterinario'] ?? '');
    $especialidad = trim($_POST['especialidad_veterinario'] ?? '');
    $salario = floatval($_POST['salario_veterinario'] ?? 0);
    $password = trim($_POST['contraseña_veterinario'] ?? '');

    // Validación PHP
    if (empty($nombre) || empty($apellidos) || empty($email) || empty($telefono) || empty($especialidad) || $salario <= 0 || empty($password)) {
        $_SESSION['error'] = "Todos los campos son obligatorios y el salario debe ser mayor que 0.";
        header("Location: ../view/formulario_veterinario.php");
        exit();
    }
    if (strlen($nombre) < 3) {
        $_SESSION['error'] = "El nombre debe tener al menos 3 caracteres.";
        header("Location: ../view/formulario_veterinario.php");
        exit();
    }
    if (strlen($apellidos) < 3) {
        $_SESSION['error'] = "Los apellidos deben tener al menos 3 caracteres.";
        header("Location: ../view/formulario_veterinario.php");
        exit();
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "El email no es válido.";
        header("Location: ../view/formulario_veterinario.php");
        exit();
    }
    if (strlen($telefono) != 9 || !is_numeric($telefono)) {
        $_SESSION['error'] = "El teléfono debe tener exactamente 9 dígitos numéricos.";
        header("Location: ../view/formulario_veterinario.php");
        exit();
    }
    if (strlen($especialidad) < 3) {
        $_SESSION['error'] = "La especialidad debe tener al menos 3 caracteres.";
        header("Location: ../view/formulario_veterinario.php");
        exit();
    }
    if (strlen($password) < 6) {
        $_SESSION['error'] = "La contraseña debe tener al menos 6 caracteres.";
        header("Location: ../view/formulario_veterinario.php");
        exit();
    }

    // Verificar si ya existe personal con ese email
    $sql_check = "SELECT id_personal FROM personal WHERE email_personal = ?";
    $stmt_check = mysqli_prepare($conn, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "s", $email);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    if (mysqli_stmt_num_rows($stmt_check) > 0) {
        $_SESSION['error'] = "Ya existe un empleado con ese email.";
        mysqli_stmt_close($stmt_check);
        header("Location: ../view/formulario_veterinario.php");
        exit();
    }
    mysqli_stmt_close($stmt_check);

    // Encriptar la contraseña
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    // Insertar en personal
    $sql_personal = "INSERT INTO personal (nombre_personal, email_personal) VALUES (?, ?)";
    $stmt_personal = mysqli_prepare($conn, $sql_personal);
    mysqli_stmt_bind_param($stmt_personal, "ss", $nombre, $email);

    if (!mysqli_stmt_execute($stmt_personal)) {
        $_SESSION['error'] = "Error al registrar en personal: " . mysqli_stmt_error($stmt_personal);
        header("Location: ../view/formulario_veterinario.php");
        exit();
    }

    $id_personal = mysqli_insert_id($conn); // Obtener el id insertado
    mysqli_stmt_close($stmt_personal);

    // Insertar en veterinario
    $sql_veterinario = "INSERT INTO veterinario (
        id_personal, nombre_veterinario, apellidos_veterinario, 
        telefono_veterinario, email_veterinario, 
        especialidad_veterinario, salario_veterinario, contraseña_veterinario
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt_veterinario = mysqli_prepare($conn, $sql_veterinario);
    mysqli_stmt_bind_param($stmt_veterinario, "isssssss", $id_personal, $nombre, $apellidos, $telefono, $email, $especialidad, $salario, $password_hashed);

    if (mysqli_stmt_execute($stmt_veterinario)) {
        $_SESSION['success'] = "Veterinario registrado exitosamente.";
    } else {
        $_SESSION['error'] = "Error al registrar veterinario: " . mysqli_stmt_error($stmt_veterinario);
    }

    mysqli_stmt_close($stmt_veterinario);
    mysqli_close($conn);

    header("Location: ../view/veterinarios.php");
    exit();
} else {
    $_SESSION['error'] = "Método de acceso no permitido.";
    header("Location: ../view/veterinarios.php");
    exit();
}
?>
