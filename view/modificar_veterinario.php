<?php
include "../services/connection.php";
session_start();


// SOLO ADMIN puede acceder
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    $_SESSION['error'] = "Acceso restringido. Solo administradores.";
    header("Location: ../view/login.php");
    exit();
}

$id_veterinario = intval($_GET['id_veterinario']);

$sql = "SELECT * FROM veterinario WHERE id_veterinario = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_veterinario);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$veterinario = mysqli_fetch_assoc($result);

if (!$veterinario) {
    echo "Veterinario no encontrado.";
    exit();
}

mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Modificar Veterinario</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="../css/styles.css" />
  <script src="../js/validaciones.js"></script>
</head>
<body class="bg-light">
  <div class="container my-5 p-4 bg-white rounded shadow-sm" style="max-width: 600px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2 class="text-success">Modificar Veterinario</h2>
      <a href="../view/veterinarios.php" class="btn btn-outline-secondary btn-sm">Atrás</a>
    </div>

    <!-- Mostrar error de sesión si existe -->
    <?php if (isset($_SESSION['error'])): ?>
      <div class="alert alert-danger">
        <?php 
          echo htmlspecialchars($_SESSION['error']); 
          unset($_SESSION['error']); // Limpiar el error después de mostrarlo
        ?>
      </div>
    <?php endif; ?>

    <form action="../processes/update_veterinario.php" method="post" novalidate>
      <input type="hidden" name="id_veterinario" value="<?php echo htmlspecialchars($veterinario['id_veterinario']); ?>">
      <div class="mb-3">
        <label for="nombre_veterinario" class="form-label fw-bold">Nombre:</label>
        <input type="text" id="nombre_veterinario" name="nombre_veterinario" onblur="valNombreVet()" class="form-control" value="<?php echo htmlspecialchars($veterinario['nombre_veterinario']); ?>" required />
        <p id="nombreError" class="mensaje-error"></p>
      </div>
      <div class="mb-3">
        <label for="apellidos_veterinario" class="form-label fw-bold">Apellidos:</label>
        <input type="text" id="apellidos_veterinario" name="apellidos_veterinario" class="form-control" onblur="valApellidosVet()" value="<?php echo htmlspecialchars($veterinario['apellidos_veterinario']); ?>" required />
        <p id="apellidosError" class="mensaje-error"></p>
      </div>
      <div class="mb-3">
        <label for="telefono_veterinario" class="form-label fw-bold">Teléfono:</label>
        <input type="text" id="telefono_veterinario" name="telefono_veterinario" onblur="valTelefonoVet()" class="form-control" value="<?php echo htmlspecialchars($veterinario['telefono_veterinario']); ?>" required />
        <p id="telefonoError" class="mensaje-error"></p>
      </div>
      <div class="mb-3">
        <label for="email_veterinario" class="form-label fw-bold">Email:</label>
        <input type="email" id="email_veterinario" name="email_veterinario" class="form-control" onblur="valEmailVet()" value="<?php echo htmlspecialchars($veterinario['email_veterinario']); ?>" required />
        <p id="emailError" class="mensaje-error"></p>
      </div>
      <div class="mb-3">
        <label for="especialidad_veterinario" class="form-label fw-bold">Especialidad:</label>
        <input type="text" id="especialidad_veterinario" name="especialidad_veterinario" onblur="valEspecialidadVet()" class="form-control" value="<?php echo htmlspecialchars($veterinario['especialidad_veterinario']); ?>" required />
        <p id="especialidadError" class="mensaje-error"></p>
      </div>
      <div class="mb-3">
        <label for="salario_veterinario" class="form-label fw-bold">Salario:</label>
        <input type="number" step="0.01" id="salario_veterinario" name="salario_veterinario" onblur="valSalarioVet()" class="form-control" value="<?php echo htmlspecialchars($veterinario['salario_veterinario']); ?>" required />
        <p id="salarioError" class="mensaje-error"></p>
      </div>
      <button type="submit" class="btn btn-success w-100">Guardar cambios</button>
    </form>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
