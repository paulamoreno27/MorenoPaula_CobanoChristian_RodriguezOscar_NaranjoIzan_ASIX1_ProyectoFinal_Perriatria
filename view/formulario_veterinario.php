<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Veterinario</title>
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
    <link rel="icon" href="../resources/logo_perriatria.png" type="image/x-icon">
    <script src="../js/script_veterinario.js"></script>
</head>
<body>
    <div class="form-container">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <h3><a href="../index.php" class="btn">Volver al inicio</a></h3>
        <h1 class="form-title">Registro Veterinario</h1>

        <div class="form-content">
           <form action="../processes/insert_veterinario.php" method="POST">
                <label for="nombre_veterinario" class="subtitulos">Nombre:</label>
                <input type="text" id="nombre_veterinario" name="nombre_veterinario" class="camposrellenar" onblur="valNombreVet()" required>
                <p id="nombreError" class="mensaje-error"></p>

                <label for="apellidos_veterinario" class="subtitulos">Apellidos:</label>
                <input type="text" id="apellidos_veterinario" name="apellidos_veterinario" class="camposrellenar" onblur="valApellidosVet()" required>
                <p id="apellidosError" class="mensaje-error"></p>

                <label for="email_veterinario" class="subtitulos">Email:</label>
                <input type="email" id="email_veterinario" name="email_veterinario" class="camposrellenar" onblur="valEmailVet()" required>
                <p id="emailError" class="mensaje-error"></p>

                <label for="telefono_veterinario" class="subtitulos">Teléfono:</label>
                <input type="text" id="telefono_veterinario" name="telefono_veterinario" class="camposrellenar" onblur="valTelefonoVet()" required>
                <p id="telefonoError" class="mensaje-error"></p>

                <label for="especialidad_veterinario" class="subtitulos">Especialidad:</label>
                <input type="text" id="especialidad_veterinario" name="especialidad_veterinario" class="camposrellenar" onblur="valEspecialidadVet()" required>
                <p id="especialidadError" class="mensaje-error"></p>

                <label for="salario_veterinario" class="subtitulos">Salario:</label>
                <input type="number" id="salario_veterinario" name="salario_veterinario" class="camposrellenar" onblur="valSalarioVet()" step="0.01" min="0" required>
                <p id="salarioError" class="mensaje-error"></p>

                <input class="btn" type="submit" value="Registrar">
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
