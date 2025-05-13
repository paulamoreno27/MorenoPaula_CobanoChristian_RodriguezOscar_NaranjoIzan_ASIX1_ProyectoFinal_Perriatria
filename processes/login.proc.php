<?php
session_start();
require '../services/connection.php'; // Asegúrate de que conecta correctamente

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($conn)) {
        $_SESSION['error'] = "Error de conexión con la base de datos.";
        header("Location: ../view/login.php");
        exit();
    }

    // Sanitizar entradas
    $usuario = mysqli_real_escape_string($conn, trim($_POST['nombre'] ?? ''));
    $password = trim($_POST['password'] ?? '');

    // Validaciones
    if (empty($usuario) || empty($password)) {
        $_SESSION['error'] = "Todos los campos son obligatorios.";
        header("Location: ../view/login.php");
        exit();
    }

    if (is_numeric($usuario)) {
        $_SESSION['error'] = "El nombre no puede contener solo números.";
        header("Location: ../view/login.php");
        exit();
    }

    if (strlen($usuario) < 3) {
        $_SESSION['error'] = "El nombre debe tener al menos 3 caracteres.";
        header("Location: ../view/login.php");
        exit();
    }

    if (strlen($password) < 6) {
        $_SESSION['error'] = "La contraseña debe tener al menos 6 caracteres.";
        header("Location: ../view/login.php");
        exit();
    }

    // Consulta directa (con escape básico)
    $query = "SELECT id, password FROM db_perriatra.propietario WHERE nombre_propietario = '$usuario'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['contraseña_propietario'])) {
            $_SESSION['usuario'] = $usuario;
            $_SESSION['user_id'] = $user['id'];
            mysqli_close($conn);
            header("Location: ../index.php");
            exit();
        } else {
            $_SESSION['error'] = "Contraseña incorrecta.";
        }
    } else {
        $_SESSION['error'] = "El usuario no existe.";
    }

    mysqli_close($conn);
    header("Location: ../view/login.php");
    exit();
}
?>
