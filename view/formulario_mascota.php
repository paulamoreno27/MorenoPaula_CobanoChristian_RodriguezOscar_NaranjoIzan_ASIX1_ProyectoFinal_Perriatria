<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Mascota</title>
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
    <link rel="icon" href="../resources/logo_perriatria.png" type="image/x-icon">
    <script src="../js/script_mascota.js"></script> <!-- Archivo JS para validaciones -->
</head>
<body>
    
    <div class="form-container">
        <h3>
            <a href="../index.php" class="btn">Volver al inicio</a>
        </h3>
        <h1 class="form-title">Registrar Mascota</h1>
    
        <div class="form-content">
            <form action="../processes/insert_mascota.php" method="POST" onsubmit="return ValidarFormulario()">

            <label for="nombre" class="subtitulos">Nombre de la mascota:</label>
            <input type="text" id="nombre" name="nombre" onblur="valNombre()" class="camposrellenar" required>
            <p id="nombreError" class="mensaje-error"></p>

            <label for="fecha_nacimiento" class="subtitulos">Fecha de nacimiento:</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="camposrellenar" required>
            <p id="fechaNacimientoError" class="mensaje-error"></p>

            <label for="sexo" class="subtitulos">Sexo:</label>
            <select id="sexo" name="sexo" class="camposrellenar" required>
                <option value="">Seleccione</option>
                <option value="Macho">Macho</option>
                <option value="Hembra">Hembra</option>
            </select>
            <p id="sexoError" class="mensaje-error"></p>

            <label for="especie" class="subtitulos">Especie:</label>
            <input type="text" id="especie" name="especie" onblur="valEspecie()" class="camposrellenar" required>
            <p id="especieError" class="mensaje-error"></p>

            <label for="raza" class="subtitulos">Raza:</label>
            <input type="text" id="raza" name="raza" onblur="valRaza()" class="camposrellenar" required>
            <p id="razaError" class="mensaje-error"></p>

            <input class="btn" type="submit" value="Registrar">
            </form>
        </div>
    </div>
    <div class="logo-background"></div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>