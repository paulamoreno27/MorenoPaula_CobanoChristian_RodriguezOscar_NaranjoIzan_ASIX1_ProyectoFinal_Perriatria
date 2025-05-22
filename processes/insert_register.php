<?php


include '../services/connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nombre = $_REQUEST['usuario'];
    $email = $_REQUEST['email'];
    $telefono = $_REQUEST['telefono'];
    $direccion = $_REQUEST['direccion'];

    // Validación PHP
    if (empty($nombre) || empty($email) || empty($telefono) || empty($direccion) || empty($_POST['password']) || empty($_POST['confirm_password'])) {
        $_SESSION['error'] = "Todos los campos son obligatorios.";
        header("Location:../view/register.php");
        exit;
    }
    if (strlen($nombre) < 3) {
        $_SESSION['error'] = "El nombre debe tener al menos 3 caracteres.";
        header("Location:../view/register.php");
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "El email no es válido.";
        header("Location:../view/register.php");
        exit;
    }
    if (strlen($telefono) != 9 || !is_numeric($telefono)) {
        $_SESSION['error'] = "El teléfono debe tener exactamente 9 dígitos numéricos.";
        header("Location:../view/register.php");
        exit;
    }
    if (strlen($direccion) < 5) {
        $_SESSION['error'] = "La dirección debe tener al menos 5 caracteres.";
        header("Location:../view/register.php");
        exit;
    }
    if (strlen($_POST['password']) < 8) {
        $_SESSION['error'] = "La contraseña debe tener al menos 8 caracteres.";
        header("Location:../view/register.php");
        exit;
    }
    if ($_POST['password'] !== $_POST['confirm_password']) {
        $_SESSION['error'] = "Las contraseñas no coinciden.";
        header("Location:../view/register.php");
        exit;
    }

    // Hashear la contraseña después de la validación
    $contrasena = password_hash($_POST['password'], PASSWORD_DEFAULT);

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
