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
    <script src="../js/validaciones.js"></script> 
</head>
<body>
    <div>
        <img src="../resources/logo_perriatria.png" alt="Logo Perriatria" class="logo-form-login">
    </div>
    <div class="form-container">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php 
                echo $_SESSION['error']; 
                unset($_SESSION['error']); // Esto limpia el mensaje después de mostrarlo
                ?>
            </div>
        <?php endif; ?>
        

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php 
                echo $_SESSION['success']; 
                unset($_SESSION['success']); 
                ?>
            </div>
        <?php endif; ?>
        <h3>
            <a href="../index.php" class="btn">Volver al inicio</a>
        </h3>
        <h1 class="form-title">Login</h1>
    
        <div class="form-content">
            <form action="../processes/login.proc.php" method="POST">

            <label for="nombre" class="subtitulos">Usuario:</label>
            <input type="text" id="usuario" name="usuario" onblur="valLogUsuario()" class="camposrellenar" required>
            <p id="nombreError" class="mensaje-error"></p>

            <label for="email" class="subtitulos">Email:</label>
            <input type="email" id="email" name="email" onblur="valLogEmail()" class="camposrellenar" required>
            <p id="emailError" class="mensaje-error"></p>

            <label for="password" class="subtitulos">Contraseña:</label>
            <input type="password" id="password" name="password" onblur="valLogPassword()" class="camposrellenar" required>
            <p id="passwordError" class="mensaje-error"></p>

            <input class="btn" type="submit" value="Entrar">
            <a href="./register.php" class="registrarse">¿No tienes cuenta? Regístrate aquí!</a>

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



