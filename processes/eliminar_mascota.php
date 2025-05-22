<?php
session_start();
require '../services/connection.php';

if (!isset($_SESSION['id_propietario'])) {
    header("Location: ../view/login.php");
    exit();
}

$id_propietario = $_SESSION['id_propietario'];

// Comprobar que se recibe el parámetro 'chip'
if (isset($_GET['chip_mascota'])) {
    $chip = intval($_GET['chip_mascota']);

    // Verificar que la mascota pertenece al propietario
    $sql_check = "SELECT chip_mascota FROM mascota WHERE chip_mascota = ? AND id_propietario = ?";
    $stmt_check = mysqli_prepare($conn, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "ii", $chip, $id_propietario);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    if (mysqli_stmt_num_rows($stmt_check) === 0) {
        mysqli_stmt_close($stmt_check);
        echo "<p>No tienes permiso para eliminar esta mascota o no existe.</p>";
        echo "<p><a href='../view/mascotas.php'>Volver a la lista</a></p>";
        exit();
    }
    mysqli_stmt_close($stmt_check);

    // Eliminar mascota
    $sql_delete = "DELETE FROM mascota WHERE chip_mascota = ? AND id_propietario = ?";
    $stmt_delete = mysqli_prepare($conn, $sql_delete);
    mysqli_stmt_bind_param($stmt_delete, "ii", $chip, $id_propietario);
    $resultado = mysqli_stmt_execute($stmt_delete);
    mysqli_stmt_close($stmt_delete);

    if ($resultado) {
        $_SESSION['success'] = "Mascota eliminada exitosamente.";
        header("Location: ../view/mascotas.php");
        exit();
        
    } else {
        $_SESSION['error'] = "Mascota no eliminada.";
        header("Location: ../view/mascotas.php");
        exit();
    }

} else {
    $_SESSION['error'] = "Error: No se ha recibido el chip de la mascota.";
    header("Location: ../view/mascotas.php");
    
}

mysqli_close($conn);
?>
