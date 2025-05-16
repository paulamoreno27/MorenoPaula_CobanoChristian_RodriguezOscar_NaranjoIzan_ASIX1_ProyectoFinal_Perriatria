<?php
include "../services/connection.php";
session_start();

if (!isset($_SESSION['id_propietario'])) {
    header("Location: ../view/login.php");
    exit();
}

$id_propietario = $_SESSION['id_propietario'];

if (!isset($_GET['chip']) || !is_numeric($_GET['chip'])) {
    echo "ID de mascota no válido.";
    exit();
}

$chip = intval($_GET['chip']);

$sql = "SELECT * FROM mascota WHERE chip_mascota = ? AND id_propietario = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $chip, $id_propietario);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$mascota = mysqli_fetch_assoc($result);

if (!$mascota) {
    echo "Mascota no encontrada o no tienes permiso para editarla.";
    exit();
}

mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Modificar Mascota</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../css/styles.css" />
  <script src="../js/script_modificar.js"></script>
</head>
<body class="bg-light">

  <div class="container my-5 p-4 bg-white rounded shadow-sm" style="max-width: 600px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="text-success">Modificar Mascota</h2>
      <a href="../view/mascotas.php" class="btn btn-outline-secondary btn-sm">Atrás</a>
    </div>

    <form action="../processes/update_mascota.php" method="post" enctype="multipart/form-data" novalidate>
      <input type="hidden" name="chip" value="<?php echo htmlspecialchars($mascota['chip_mascota']); ?>">

      <div class="mb-3">
        <label for="nombre" class="form-label fw-bold">Nombre:</label>
        <input 
          type="text" 
          id="nombre" 
          name="nombre" 
          class="form-control" 
          value="<?php echo htmlspecialchars($mascota['nombre_mascota']); ?>" 
          required 
          onblur="valNombre()" />
        <p id="nombreError" class="mensaje-error"></p>
      </div>

      <div class="mb-3">
        <label for="fecha_nacimiento" class="form-label fw-bold">Fecha de nacimiento:</label>
        <input 
          type="date" 
          id="fecha_nacimiento" 
          name="fecha_nacimiento" 
          class="form-control" 
          value="<?php echo htmlspecialchars($mascota['fecha_nacimiento_mascota']); ?>" 
          required 
          onblur="valFechaNacimiento()" />
        <p id="fechaNacimientoError" class="mensaje-error"></p>
      </div>

      <div class="mb-3">
        <label for="sexo" class="form-label fw-bold">Sexo:</label>
        <select 
          id="sexo" 
          name="sexo" 
          class="form-select" 
          required 
          onblur="valSexoMascota()">
          <option value="">Seleccione</option>
          <option value="M" <?php if ($mascota['sexo_mascota'] === 'M') echo 'selected'; ?>>Macho</option>
          <option value="F" <?php if ($mascota['sexo_mascota'] === 'F') echo 'selected'; ?>>Hembra</option>
        </select>
        <p id="sexoError" class="mensaje-error"></p>
      </div>

      <div class="mb-3">
        <label for="especie" class="form-label fw-bold">Especie:</label>
        <input 
          type="text" 
          id="especie" 
          name="especie" 
          class="form-control" 
          value="<?php echo htmlspecialchars($mascota['especie_mascota']); ?>" 
          required 
          onblur="valEspecieMascota()" />
        <p id="especieError" class="mensaje-error"></p>
      </div>

      <div class="mb-3">
        <label for="raza" class="form-label fw-bold">Raza:</label>
        <input 
          type="text" 
          id="raza" 
          name="raza" 
          class="form-control" 
          value="<?php echo htmlspecialchars($mascota['raza_mascota']); ?>" 
          required 
          onblur="valRazaMascota()" />
        <p id="razaError" class="mensaje-error"></p>
      </div>

      <div class="mb-3">
        <label for="foto" class="form-label fw-bold">Foto de la mascota (opcional):</label>
        <input type="file" id="foto" name="foto" class="form-control" accept="image/*" onblur="valFotoMascota()">
        <p id="fotoError" class="mensaje-error"></p>
        <?php if (!empty($mascota['foto_mascota'])): ?>
          <img src="../resources/<?php echo htmlspecialchars($mascota['foto_mascota']); ?>" alt="Foto mascota" style="max-width: 150px; margin-top: 10px; border-radius: 5px;">
        <?php endif; ?>
      </div>

      <button type="submit" class="btn btn-success w-100">Guardar cambios</button>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
