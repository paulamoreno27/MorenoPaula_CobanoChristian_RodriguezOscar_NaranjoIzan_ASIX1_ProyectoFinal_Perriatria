<?php


include '../services/connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nombre = $_REQUEST['usuario'];
    $email = $_REQUEST['email'];
    $telefono = $_REQUEST['telefono'];
    $direccion = $_REQUEST['direccion'];
    $contrasena = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $confirmContrasena = password_hash($_POST['confirm_password'], PASSWORD_DEFAULT);

    $sql = "SELECT * FROM propietario";
    $result = mysqli_query($conn, $sql);

    foreach ($result as $usuario) {
        if ($usuario['nombre_propietario'] == $nombre || $usuario['email_propietario'] == $email) { 
            $_SESSION['error'] = "El usuario o email ya existen.";
            header("Location:../view/register.php");
            exit;
        }
    }

    // Insert en usuarios si no hay errores 
    $query1 = "INSERT INTO propietario (nombre_propietario, telefono_propietario, direccion_propietario, email_propietario, contraseña_propietario) VALUES (?, ?, ?, ?, ?)";
    $sentencia1 = mysqli_prepare($conn, $query1);

    if ($sentencia1) {
        mysqli_stmt_bind_param($sentencia1, "sisss", $nombre, $telefono, $direccion, $email, $contrasena);

        if (!mysqli_stmt_execute($sentencia1)) {
            $_SESSION['error'] = "Error al registrar el usuario.";
            header("Location:../view/register.php");
            exit;
        }
        mysqli_stmt_close($sentencia1); 
    } else {
        $_SESSION['error'] = "Error en la consulta."; 
        header("Location:../view/register.php");
        exit;
    }

    // Registro bien hecho
    unset($_SESSION['error']);
    $_SESSION['register'] = "check";
    header("Location:../index.php?usuario=$nombre");
} else {
    $_SESSION['error'] = "Solicitud no válida.";
    header("Location:../view/register.php");
}

// Close connection
mysqli_close($conn);

?>
