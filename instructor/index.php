<?php
// Mostrar directorio actual (solo para debug)
echo __DIR__;

// 1. Cargar la configuración de rutas
require_once __DIR__ . '/../config/path.php';

// 2. Cargar dependencias usando rutas absolutas
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/include/header.php';

// 3. Autenticación
requireLogin();

if (!isInstructor()) {
    header('Location: /cfl_402/index.php');
    exit();
}

// 4. Conexión a la base de datos
$conn = conectar();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <p>hola</p>
</body>
</html>
