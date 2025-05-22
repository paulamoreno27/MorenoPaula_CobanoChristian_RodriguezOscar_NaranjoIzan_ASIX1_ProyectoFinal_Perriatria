<?php
session_start(); // Asegúrate de que la sesión esté iniciada para acceder a los mensajes de error
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
    <script src="../js/script_register.js"></script>
    <link rel="icon" href="../resources/logo_perriatria.png" type="image/x-icon">
</head>
<body>    
    <div class="form-container2">
        <h3>
            <a href="../index.php" class="btn">Volver al inicio</a>
        </h3>
        <div>
            <img src="../resources/logo_perriatria.png" alt="Logo Perriatria" class="logo-form">
        </div>
        <h1 class="form-title">Registro</h1>

        <!-- Mostrar error de sesión si existe -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php 
                    echo htmlspecialchars($_SESSION['error']); 
                    unset($_SESSION['error']); // Limpiar el error después de mostrarlo
                ?>
            </div>
        <?php endif; ?>

        <div class="form-content">
            <form method="post" action="../processes/insert_register.php" onsubmit="return ValidarFormularioRegistro()">

                <label for="email" class="subtitulos">Email:</label>
                <input name="email" type="email" id="email" onblur="valEmail()" class="camposrellenar" required>
                <p id="error_email" class="mensaje-error"></p>

                <label for="usuario" class="subtitulos">Usuario:</label>
                <input id="usuario" name="usuario" onblur="valUsuario()" class="camposrellenar" required>
                <p id="error_usuario" class="mensaje-error"></p>

                <label for="telefono" class="subtitulos">Teléfono:</label>
                <input name="telefono" type="tel" id="telefono" onblur="valTelefono()" class="camposrellenar" required>
                <p id="error_telefono" class="mensaje-error"></p>

                <label for="direccion" class="subtitulos">Dirección:</label>
                <input type="text" id="direccion" name="direccion" onblur="validarDireccion()" class="camposrellenar" required>
                <p id="error_direccion" class="mensaje-error"></p>

                <label for="password" class="subtitulos">Contraseña:</label>
                <input type="password" id="password" name="password" onblur="valPassword()" class="camposrellenar" required>
                <p id="error_password" class="mensaje-error"></p>

                <label for="confirm_password" class="subtitulos">Confirmar Contraseña:</label>
                <input type="password" id="confirm_password" name="confirm_password" onblur="validarConfirmPassword()" class="camposrellenar" required>
                <p id="error_confirm_password" class="mensaje-error"></p>

                <input class="btn" type="submit" value="Registrarse">
                <a href="./login.php" class="registrarse">¿Ya tienes cuenta? Inicia sesión aquí!</a>

            </form>
        </div>
    </div>
    <div class="logo-background"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
<footer class="footer">
  <p>&copy; 2023 Perriatria Veterinario. Todos los derechos reservados.</p>
</footer>
</html>