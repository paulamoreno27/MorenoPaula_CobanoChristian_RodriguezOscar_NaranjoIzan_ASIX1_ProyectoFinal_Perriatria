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
  <script>
    const razasPorEspecie = {
    <?php
    $sql = "SELECT id_raza, raza_nombre, id_especie_raza FROM raza";
    $res = mysqli_query($conn, $sql);
    $razas = [];
    while ($r = mysqli_fetch_assoc($res)) {
        $razas[$r['id_especie_raza']][] = [
            'id' => $r['id_raza'],
            'nombre' => addslashes($r['raza_nombre']),
        ];
    }
    foreach ($razas as $idEspecie => $lista) {
        echo "'$idEspecie': [";
        foreach ($lista as $raza) {
            echo "{id: {$raza['id']}, nombre: '{$raza['nombre']}'},";
        }
        echo "],";
    }
    ?>
    };
    </script>
</head>
<body class="bg-light">

  <div class="container my-5 p-4 bg-white rounded shadow-sm" style="max-width: 600px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-success">Modificar Mascota</h2>
        <a href="../view/mascotas.php" class="btn btn-outline-secondary btn-sm">Atrás</a>
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
            <label for="id_especie_mascota" class="form-label fw-bold">Especie:</label>
            <select id="id_especie_mascota" name="id_especie_mascota" class="form-select" onchange="cargarRazas()" required>
                <option value="">Seleccione una especie</option>
                <?php
                $sql = "SELECT id_especie, nombre_especie FROM especie";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    $selected = ($row['id_especie'] == $mascota['id_especie_mascota']) ? 'selected' : '';
                    echo "<option value='{$row['id_especie']}' $selected>{$row['nombre_especie']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="id_raza_mascota" class="form-label fw-bold">Raza:</label>
            <select id="id_raza_mascota" name="id_raza_mascota" class="form-select" required data-selected="<?php echo $mascota['id_raza_mascota']; ?>">
                <option value="">Seleccione una raza</option>
            </select>

      <div class="mb-3">
        <label for="veterinario" class="form-label fw-bold">Veterinario:</label>
        <select 
            id="veterinario" 
            name="veterinario" 
            class="form-select" 
            required 
            onblur="valVeterinario()">
            <option value="">Seleccione un veterinario</option>
            <?php
            // Consulta para obtener los veterinarios
            $sql_veterinarios = "SELECT id_veterinario, nombre_veterinario FROM veterinario";
            $result_veterinarios = mysqli_query($conn, $sql_veterinarios);

            if ($result_veterinarios && mysqli_num_rows($result_veterinarios) > 0):
                while ($veterinario = mysqli_fetch_assoc($result_veterinarios)):
            ?>
                <option value="<?php echo htmlspecialchars($veterinario['nombre_veterinario']); ?>" 
                    <?php if ($mascota['id_veterinario_mascota'] == $veterinario['id_veterinario']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($veterinario['nombre_veterinario']); ?>
                </option>
            <?php
                endwhile;
            endif;
            ?>
        </select>
        <p id="veterinarioError" class="mensaje-error"></p>
      </div>

      <button type="submit" class="btn btn-success w-100">Guardar cambios</button>
    </form>
  </div>
  <script>
    function cargarRazas() {
      const especieId = document.getElementById('id_especie_mascota').value;
      const razaSelect = document.getElementById('id_raza_mascota');
      const razaActual = razaSelect.getAttribute('data-selected');

      razaSelect.innerHTML = '<option value="">Seleccione una raza</option>';

      if (razasPorEspecie[especieId]) {
        razasPorEspecie[especieId].forEach(function(raza) {
          const opt = document.createElement('option');
          opt.value = raza.id;
          opt.textContent = raza.nombre;
          if (raza.id == razaActual) opt.selected = true;
          razaSelect.appendChild(opt);
        });
      }
    }

    // Ejecutar al cargar la página
    window.onload = function () {
      cargarRazas();
    };
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
