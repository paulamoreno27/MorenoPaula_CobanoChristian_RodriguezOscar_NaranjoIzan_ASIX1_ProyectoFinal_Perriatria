<?php
session_start();

require '../services/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    if (!isset($conn)) {
        $_SESSION['error'] = "Error de conexión con la base de datos.";
        header("Location: ../view/login.php");
        exit();
    }

    $usuario = trim($_POST['usuario']);
    $password = $_POST['password'];

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

    // Verificar si el usuario es un propietario
    $query_propietario = "SELECT id_propietario, contraseña_propietario FROM propietario WHERE nombre_propietario = ?";
    $stmt_propietario = mysqli_prepare($conn, $query_propietario);
    mysqli_stmt_bind_param($stmt_propietario, "s", $usuario);
    mysqli_stmt_execute($stmt_propietario);
    $result_propietario = mysqli_stmt_get_result($stmt_propietario);

    if ($result_propietario && mysqli_num_rows($result_propietario) > 0) {
        $row = mysqli_fetch_assoc($result_propietario);
        $hash = $row['contraseña_propietario'];

        if (password_verify($password, $hash)) {
            $_SESSION['id_propietario'] = $row['id_propietario'];
            $_SESSION['usuario'] = $usuario;

            header("Location: ../index.php");
            exit();
        } else {
            $_SESSION['error'] = "Contraseña incorrecta.";
        }
    } else {
        // Verificar si el usuario es un veterinario
        $query_veterinario = "SELECT id_veterinario, contraseña_veterinario FROM veterinario WHERE nombre_veterinario = ?";
        $stmt_veterinario = mysqli_prepare($conn, $query_veterinario);
        mysqli_stmt_bind_param($stmt_veterinario, "s", $usuario);
        mysqli_stmt_execute($stmt_veterinario);
        $result_veterinario = mysqli_stmt_get_result($stmt_veterinario);

        if ($result_veterinario && mysqli_num_rows($result_veterinario) > 0) {
            $row = mysqli_fetch_assoc($result_veterinario);
            $hash = $row['contraseña_veterinario'];

            if (password_verify($password, $hash)) {
                $_SESSION['id_veterinario'] = $row['id_veterinario'];
                $_SESSION['usuario'] = $usuario;

                header("Location: ../index.php");
                exit();
            } else {
                $_SESSION['error'] = "Contraseña incorrecta.";
            }
        } else {
            $_SESSION['error'] = "El usuario no existe.";
        }

        mysqli_stmt_close($stmt_veterinario);
    }

    mysqli_stmt_close($stmt_propietario);
    mysqli_close($conn);

    header("Location: ../view/login.php");
    exit();
}
?>
