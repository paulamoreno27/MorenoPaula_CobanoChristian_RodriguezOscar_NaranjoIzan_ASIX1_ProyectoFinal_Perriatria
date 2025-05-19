<?php
include "../services/connection.php";
session_start();

if (!isset($_SESSION['id_propietario'])) {
    header("Location: ../view/login.php");
    exit();
}

if (
    $_SERVER['REQUEST_METHOD'] == 'POST' &&
    isset(
        $_POST['id_veterinario'],
        $_POST['nombre'],     // Aunque no se actualice, viene del formulario
        $_POST['apellidos'],  // Aunque no se actualice, viene del formulario
        $_POST['telefono'],
        $_POST['email'],
        $_POST['especialidad'],
        $_POST['salario']
    )
) {
    $id_veterinario = intval($_POST['id_veterinario']);
    $telefono = trim($_POST['telefono']);
    $email = trim($_POST['email']);
    $especialidad = trim($_POST['especialidad']);
    $salario = floatval($_POST['salario']);

    // Solo se actualiza la tabla veterinario
    $sql_update_vet = "UPDATE veterinario 
                       SET telefono_veterinario = ?, 
                           email_veterinario = ?, 
                           especialidad_veterinario = ?, 
                           salario_veterinario = ? 
                       WHERE id_veterinario = ?";
    $stmt_update_vet = mysqli_prepare($conn, $sql_update_vet);
    mysqli_stmt_bind_param($stmt_update_vet, "sssdi", $telefono, $email, $especialidad, $salario, $id_veterinario);
    $resultado_update = mysqli_stmt_execute($stmt_update_vet);
    mysqli_stmt_close($stmt_update_vet);

    if ($resultado_update) {
        $_SESSION['success'] = "Veterinario actualizado correctamente.";
        header("Location: ../view/veterinarios.php");
        exit();
    } else {
        echo "<p>Error al actualizar el veterinario: " . mysqli_error($conn) . "</p>";
    }
} else {
    echo "<p>Datos no proporcionados correctamente.</p>";
}

mysqli_close($conn);
?>
