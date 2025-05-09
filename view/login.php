<?php
session_start(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/login_styles.css">
    <script src="../js/script_login.js"></script>
</head>
<body>
    <header class="text-center">
        <h2>Login</h2>
    </header>

    <div class="form-container">
        <form action="../processes/validar_login.php" method="POST">
            
            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" onblur="validateUsuario()" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" onblur="validateEmail()" required>
            
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" onblur="validatePassword()" required>
            
            <button type="submit">Log in</button>
        </form>
        <p>Si aún no tienes una cuenta, regístrate <a href="register.php">aquí</a>.</p>
    </div>
</body>
</html>
