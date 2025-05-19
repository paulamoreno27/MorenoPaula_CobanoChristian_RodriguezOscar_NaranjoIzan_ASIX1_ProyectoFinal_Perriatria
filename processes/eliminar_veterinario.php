<?php
session_start();
require '../services/connection.php';

// Comprobar que el usuario está logueado (puedes cambiar la validación según el rol que administre veterinarios)
if (!isset($_SESSION['id_propietario'])) {
    header("Location: ../view/login.php");
    exit();
}

// Comprobar que se recibe el parámetro 'id_veterinario' y es numérico
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_veterinario = intval($_GET['id']);

    // Verificar que el veterinario existe
    $sql_check = "SELECT id_veterinario FROM veterinario WHERE id_veterinario = ?";
    $stmt_check = mysqli_prepare($conn, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "i", $id_veterinario);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    if (mysqli_stmt_num_rows($stmt_check) === 0) {
        mysqli_stmt_close($stmt_check);
        echo "<p>El veterinario no existe.</p>";
        echo "<p><a href='../view/veterinarios.php'>Volver a la lista</a></p>";
        exit();
    }
    mysqli_stmt_close($stmt_check);

    // Eliminar veterinario
    $sql_delete = "DELETE FROM veterinario WHERE id_veterinario = ?";
    $stmt_delete = mysqli_prepare($conn, $sql_delete);
    mysqli_stmt_bind_param($stmt_delete, "i", $id_veterinario);
    $resultado = mysqli_stmt_execute($stmt_delete);
    mysqli_stmt_close($stmt_delete);

    if ($resultado) {
        $_SESSION['success'] = "Veterinario eliminado exitosamente.";
        header("Location: ../view/veterinarios.php");
        exit();
    } else {
        $_SESSION['error'] = "No se pudo eliminar el veterinario.";
        header("Location: ../view/veterinarios.php");
        exit();
    }

} else {
    $_SESSION['error'] = "Error: No se ha recibido el ID del veterinario.";
    header("Location: ../view/veterinarios.php");
}

mysqli_close($conn);
?>
