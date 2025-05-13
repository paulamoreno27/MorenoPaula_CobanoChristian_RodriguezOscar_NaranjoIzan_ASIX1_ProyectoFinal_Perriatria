<?php
session_start();
require '../services/connection.php'; 

    // Validar email
    if (empty($email)) {
        $errors['email'] = "El campo no puede estar vacío.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "El formato del email no es válido.";
    }

    // Validar usuario
    if (strlen($usuario) < 3) {
        $errors['usuario'] = "El usuario debe tener al menos 3 caracteres.";
    }

    // Validar contraseña
    if (strlen($password) < 8) {
        $errors['password'] = "La contraseña debe tener al menos 8 caracteres.";
    } elseif ($password === strtolower($password)) {
        $errors['password'] = "La contraseña debe contener al menos una letra mayúscula.";
    }

    // Validar confirmación de contraseña
    if ($confirm_password !== $password) {
        $errors['confirm_password'] = "Las contraseñas no coinciden.";
    }

    // Mostrar errores o continuar con la inserción
    if (!empty($errors)) {
        // Guardar errores en sesión para mostrar en la vista si es necesario
        $_SESSION['errors'] = $errors;
        $_SESSION['old'] = $_POST;

        // Redirigir de vuelta al formulario
        header('Location: ../pages/register.php');
        exit();
    }

?>