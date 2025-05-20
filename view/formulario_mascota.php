<?php
session_start();
include '../services/connection.php'; // Conexión a la base de datos

// Verificar si se está editando una mascota
$mascota = null;
if (isset($_GET['chip'])) {
    $chip = mysqli_real_escape_string($conn, $_GET['chip']);
    $sql_mascota = "SELECT * FROM mascota WHERE chip_mascota = '$chip'";
    $result_mascota = mysqli_query($conn, $sql_mascota);

    if ($result_mascota && mysqli_num_rows($result_mascota) > 0) {
        $mascota = mysqli_fetch_assoc($result_mascota);
    } else {
        echo "<p>Error: No se encontró la mascota con el chip proporcionado.</p>";
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Mascota</title>
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
    <link rel="icon" href="../resources/logo_perriatria.png" type="image/x-icon">
    <script src="../js/script_mascotas.js"></script> <!-- Archivo JS para validaciones -->
</head>
<body>
    
    <div class="form-container">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php 
                echo $_SESSION['error']; 
                unset($_SESSION['error']); // Limpiar el mensaje después de mostrarlo
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php 
                echo $_SESSION['success']; 
                unset($_SESSION['success']); // Limpiar el mensaje después de mostrarlo
                ?>
            </div>
        <?php endif; ?>
        <h3>
            <a href="../index.php" class="btn">Volver al inicio</a>
        </h3>
        <h1 class="form-title">Registrar Mascota</h1>
    
        <div class="form-content">
            <form action="../processes/insert_mascota.php" method="POST"  enctype="multipart/form-data">

            <label for="nombre" class="subtitulos">Nombre de la mascota:</label>
            <input type="text" id="nombre" name="nombre" onblur="valNombreMascota()" class="camposrellenar" required>
            <p id="nombreError" class="mensaje-error"></p>

            <label for="fecha_nacimiento" class="subtitulos">Fecha de nacimiento:</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" onblur="valNacimientoMascota()" class="camposrellenar" required>
            <p id="fechaNacimientoError" class="mensaje-error"></p>

            <label for="sexo" class="subtitulos">Sexo:</label>
            <select id="sexo" name="sexo" onblur="valSexoMascota()" class="camposrellenar" required>
                <option value="">Seleccione</option>
                <option value="M">Macho</option>
                <option value="F">Hembra</option>
            </select>
            <p id="sexoError" class="mensaje-error"></p>

            <label for="especie" class="subtitulos">Especie:</label>
            <input type="text" id="especie" name="especie" onblur="valEspecieMascota()" class="camposrellenar" required>
            <p id="especieError" class="mensaje-error"></p>

            <label for="raza" class="subtitulos">Raza:</label>
            <input type="text" id="raza" name="raza" onblur="valRazaMascota()" class="camposrellenar" required>
            <p id="razaError" class="mensaje-error"></p>


             
            
        <label for="veterinario" class="subtitulos">Veterinario asignado:</label>
        <select id="veterinario" name="veterinario" class="camposrellenar" required>
            <option value="">Seleccione un veterinario</option>
            <?php
            $result = mysqli_query($conn, "SELECT id_veterinario, nombre_veterinario FROM veterinario");

            while ($row = mysqli_fetch_assoc($result)) {
                echo '<option value="' . $row['id_veterinario'] . '">' . htmlspecialchars($row['nombre_veterinario']) . '</option>';
            }
            ?>
        </select>
        <p id="veterinarioError" class="mensaje-error"></p>

            
            <label for="foto" class="subtitulos">Foto de tu mascota:</label>
            <input type="file" id="foto" name="foto" accept="image/*" class="camposrellenar">
            <p id="fotoError" class="mensaje-error"></p>

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