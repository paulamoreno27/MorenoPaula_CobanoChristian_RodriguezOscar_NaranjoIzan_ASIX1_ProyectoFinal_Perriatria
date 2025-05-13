<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
    <link rel="icon" href="../resources/logo_perriatria.png" type="image/x-icon">
    <script src="../js/script_login.js"></script> <!-- Archivo JS para validaciones -->
</head>
<body>
    
    <div class="form-container">
        <h3>
            <a href="../index.php" class="btn">Volver al inicio</a>
        </h3>
        <h1 class="form-title">Login</h1>
    
        <div class="form-content">
            <form action="../processes/validate_register.php" method="POST" onsubmit="return ValidarFormulario()">

            <label for="nombre" class="subtitulos">Usuario:</label>
            <input type="text" id="nombre" name="usuario" onblur="valUsuario()" class="camposrellenar" required>
            <p id="nombreError" class="mensaje-error"></p>

            <label for="email" class="subtitulos">Email:</label>
            <input type="email" id="email" name="email" onblur="valEmail()" class="camposrellenar" required>
            <p id="emailError" class="mensaje-error"></p>

            <label for="password" class="subtitulos">Contraseña:</label>
            <input type="password" id="password" name="password" onblur="valPassword()" class="camposrellenar" required>
            <p id="passwordError" class="mensaje-error"></p>

            <input class="btn" type="submit" value="Entrar">
            <a href="./register.php" class="registrarse">¿No tienes cuenta? Regístrate aquí!</a>

            </form>
        </div>
    </div>
    <div class="logo-background"></div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



