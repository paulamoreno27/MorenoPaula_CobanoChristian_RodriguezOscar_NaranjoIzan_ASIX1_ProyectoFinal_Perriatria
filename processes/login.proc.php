<?php
session_start();
require '../services/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // Primero: Verificamos en la tabla `usuarios` (para rol admin u otros)
    $query_admin = "SELECT id_usuario, password, rol_usuario FROM usuarios WHERE username = ?";
    $stmt_admin = mysqli_prepare($conn, $query_admin);
    mysqli_stmt_bind_param($stmt_admin, "s", $usuario);
    mysqli_stmt_execute($stmt_admin);
    $result_admin = mysqli_stmt_get_result($stmt_admin);

    if ($result_admin && mysqli_num_rows($result_admin) > 0) {
        $row = mysqli_fetch_assoc($result_admin);
        $hash = $row['password'];

        if (password_verify($password, $hash)) {
            $_SESSION['id_usuario'] = $row['id_usuario'];
            $_SESSION['usuario'] = $usuario;
            $_SESSION['rol'] = $row['rol_usuario']; // Muy importante

            header("Location: ../index.php");
            exit();
        } else {
            $_SESSION['error'] = "Contraseña incorrecta.";
        }
        mysqli_stmt_close($stmt_admin);
    } else {
        // Segundo: Verificar si es un propietario
        $query_propietario = "SELECT id_propietario, contraseña_propietario FROM propietario WHERE nombre_propietario = ?";
        $stmt_prop = mysqli_prepare($conn, $query_propietario);
        mysqli_stmt_bind_param($stmt_prop, "s", $usuario);
        mysqli_stmt_execute($stmt_prop);
        $result_prop = mysqli_stmt_get_result($stmt_prop);

        if ($result_prop && mysqli_num_rows($result_prop) > 0) {
            $row = mysqli_fetch_assoc($result_prop);
            if (password_verify($password, $row['contraseña_propietario'])) {
                $_SESSION['id_propietario'] = $row['id_propietario'];
                $_SESSION['usuario'] = $usuario;
                $_SESSION['rol'] = 'propietario';

                header("Location: ../index.php");
                exit();
            } else {
                $_SESSION['error'] = "Contraseña incorrecta.";
            }
            mysqli_stmt_close($stmt_prop);
        } else {
            // Tercero: Verificar si es veterinario
            $query_vet = "SELECT id_veterinario, contraseña_veterinario FROM veterinario WHERE nombre_veterinario = ?";
            $stmt_vet = mysqli_prepare($conn, $query_vet);
            mysqli_stmt_bind_param($stmt_vet, "s", $usuario);
            mysqli_stmt_execute($stmt_vet);
            $result_vet = mysqli_stmt_get_result($stmt_vet);

            if ($result_vet && mysqli_num_rows($result_vet) > 0) {
                $row = mysqli_fetch_assoc($result_vet);
                if (password_verify($password, $row['contraseña_veterinario'])) {
                    $_SESSION['id_veterinario'] = $row['id_veterinario'];
                    $_SESSION['usuario'] = $usuario;
                    $_SESSION['rol'] = 'veterinario';

                    header("Location: ../index.php");
                    exit();
                } else {
                    $_SESSION['error'] = "Contraseña incorrecta.";
                }
                mysqli_stmt_close($stmt_vet);
            } else {
                $_SESSION['error'] = "El usuario no existe.";
            }
        }
    }

    mysqli_close($conn);
    header("Location: ../view/login.php");
    exit();
}
?>
