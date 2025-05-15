<?php
session_start();
require '../services/connection.php'; // Asegúrate de que conecta correctamente

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    if (!isset($conn)) {
        $_SESSION['error'] = "Error de conexión con la base de datos.";
        header("Location: ../view/login.php");
        exit();
    }

    // Sanitizar entradas
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];

    // Validaciones
    if (empty($usuario) || empty($password)) {
        // Verifica que ambos campos estén llenos
        $_SESSION['error'] = "Todos los campos son obligatorios.";
        header("Location: ../view/login.php");
        exit();
    }

    if (is_numeric($usuario)) {
        // El nombre de usuario no puede ser solo números
        $_SESSION['error'] = "El nombre no puede contener solo números.";
        header("Location: ../view/login.php");
        exit();
    }

    if (strlen($usuario) < 3) {
        // El nombre debe tener al menos 3 caracteres
        $_SESSION['error'] = "El nombre debe tener al menos 3 caracteres.";
        header("Location: ../view/login.php");
        exit();
    }

    if (strlen($password) < 6) {
        // La contraseña debe tener al menos 6 caracteres
        $_SESSION['error'] = "La contraseña debe tener al menos 6 caracteres.";
        header("Location: ../view/login.php");
        exit();
    }

    $query = "SELECT contraseña_propietario FROM propietario WHERE nombre_propietario = '$usuario'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        
        $row = mysqli_fetch_assoc($result);
        $hash = $row['contraseña_propietario'];

        if (password_verify($password, $hash)) {

            $_SESSION['usuario'] = $usuario;
            header("Location: ../index.php?usuario=$usuario"); // Redirige al index
            exit();

        } else { 
            // Contraseña incorrecta
            $_SESSION['error'] = "Contraseña incorrecta.";
        }
    } 
    else 
    {
        // Usuario no encontrado
        $_SESSION['error'] = "El usuario no existe.";
    }

    // Cierra la conexión y redirige al login si hubo error
    mysqli_close($conn);
    header("Location: ../view/login.php");
    exit();
}
?>
