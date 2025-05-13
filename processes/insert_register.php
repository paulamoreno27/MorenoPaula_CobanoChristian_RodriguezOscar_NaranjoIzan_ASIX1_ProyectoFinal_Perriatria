<?php

include '../services/config.php';
include '../services/connection.php';

if (isset($_POST['nombre'], $_POST['email'], $_POST['password'])) {
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Encriptar la contraseña con MD5
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insertar el nuevo usuario en la base de datos
    $insertQuery = "INSERT INTO usuarios (username, email, password) VALUES ('$nombre', '$email', '$hashedPassword')";
    
    if (mysqli_query($conn, $insertQuery)) {
        header("Location: ../view/login.php?success=1"); // Registro exitoso
    } else {
        header("Location: ../view/register.php?error=1"); // Error al insertar en la base de datos
    }
} else {
    header("Location: ../view/register.php?error=2"); // Error: Campos incompletos
}
?>