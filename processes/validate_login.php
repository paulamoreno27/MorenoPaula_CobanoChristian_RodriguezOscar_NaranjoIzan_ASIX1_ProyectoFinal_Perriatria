<?php
session_start();
require '../services/connection.php'; // Conexión a la BD

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($conn)) {
        $_SESSION['error'] = "Error de conexión con la base de datos.";
        header("Location: ../view/login.php");
        die();
    }

    $usuario = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    // Validaciones del lado del servidor
    if (empty($usuario) || empty($correo) || empty($password)) {
        $_SESSION['error'] = "Todos los campos son obligatorios.";
        header("Location: ../view/login.php");
        die();
    }

    if (is_numeric($usuario)) {
        $_SESSION['error'] = "El nombre no puede contener solo números.";
        header("Location: ../view/login.php");
        die();
    }

    if (strlen($usuario) < 3) {
        $_SESSION['error'] = "El nombre debe tener al menos 3 caracteres.";
        header("Location: ../view/login.php");
        die();
    }

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Introduce un email válido.";
        header("Location: ../view/login.php");
        die();
    }

    if (strlen($password) < 6) {
        $_SESSION['error'] = "La contraseña debe tener al menos 6 caracteres.";
        header("Location: ../view/login.php");
        die();
    }

    // Buscar usuario en la base de datos
    $sql = "SELECT id, password FROM usuarios WHERE username = ?";
    $sentencia1 = mysqli_prepare($conn, $sql);

    if ($sentencia1) {
        mysqli_stmt_bind_param($sentencia1, "s", $usuario);
        mysqli_stmt_execute($sentencia1);
        mysqli_stmt_store_result($sentencia1);

        if (mysqli_stmt_num_rows($sentencia1) > 0) {
            mysqli_stmt_bind_result($sentencia1, $id, $passwordHash);
            mysqli_stmt_fetch($sentencia1);

            if (password_verify($password, $passwordHash)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['username'] = $usuario;
                $_SESSION['artistas'] = "check";
                mysqli_stmt_close($sentencia1);
                mysqli_close($conn);
                header("Location: ../index.php");
                die();
            } else {
                $_SESSION['error'] = "Contraseña incorrecta.";
            }
        } else {
            $_SESSION['error'] = "El usuario no existe.";
        }

        mysqli_stmt_close($sentencia1);
    } else {
        $_SESSION['error'] = "Error en la consulta de usuario.";
    }

    mysqli_close($conn);
    header("Location: ../view/login.php");
    die();
}
?>
