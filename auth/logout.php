<?php
session_start();

// Verificar si la sesión existe antes de acceder
if (isset($_SESSION['user'])) {
    unset($_SESSION['user']);
}

// Destruir la sesión completamente
session_destroy();

// Iniciar nueva sesión para mensajes
session_start();
$_SESSION['mensaje'] = 'Sesión cerrada correctamente';

header('Location: ../index.php');
exit();
