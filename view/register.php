<?php
session_start(); // Ensure session is started to access error messages
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="../styles/register_styles.css">
    <script src="../js/script_register.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <form method="post" action="../processes/insert_register.php">
        <div class="mb-3">
            <h2 class="form-label">Registro</h2><br>
        </div>

        <!-- Mensaje de error -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="mb-3">
            <label for="email">Email:</label>
            <input name="email" type="email" class="form-control" id="email" aria-describedby="emailHelp" onblur="validarEmail()"><br>
            <p id="error_email"></p>
        </div>

        <div class="mb-3">
            <label for="usuario" class="form-label">Usuario:</label>
            <input class="form-control" id="usuario" name="usuario" onblur="validarUsuario()"><br>
            <p id="error_usuario"></p>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Contraseña:</label>
            <input type="password" class="form-control" id="password" name="password" onblur="validarPassword()"><br>
            <p id="error_password"></p>
        </div>

        <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirmar Contraseña:</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" onblur="return validarConfirmPassword()"><br>
            <p id="error_confirm_password"></p>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>
        <br><br><a href="login.php">Login</a>
    </form>
</body>
</html>