<?php
// Aquí escribes la contraseña que quieres cifrar
$contraseña_plana = 'Admin1234*';

// Esto genera el hash (código seguro)
$hash = password_hash($contraseña_plana, PASSWORD_DEFAULT);

// Muestra el hash para que lo copies
echo "Este es el hash: " . $hash;
?>
