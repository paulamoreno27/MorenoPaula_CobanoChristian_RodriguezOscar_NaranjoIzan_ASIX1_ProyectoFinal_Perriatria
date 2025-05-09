<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="../css/login.css">
    <script src="../js/script_login.js"></script> <!-- Archivo JS para validaciones -->
</head>
<body>
    <div class="loginbox">
        <form action="../processes/login.proc.php" method="POST">
            <h1>Login</h1>
            <div class="form-group">
                <label for="nombre">Usuario:</label>
                <input type="text" id="nombre" name="usuario" onblur="valUsuario()" required>
                <div class="error" id="nombreError"></div>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" onblur="valEmail()" required>
                <div class="error" id="emailError"></div>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" onblur="valPassword()" required>
                <div class="error" id="passwordError"></div>
            </div>
            <button type="submit">Enviar</button>
        </form>
        <br>
        <a href="../view/register.php">Register</a>
    </div>
</body>
</html>



