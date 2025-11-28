<?php

// Cargar path.php
require_once dirname(__DIR__, 2) . '/../config/path.php';


// Dependencias
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/include/header.php';

// 3. Autenticación
requireLogin();

if (!isAdmin()) {
    header('Location: cfl_402_ciro/cfl_402/index.php');
    exit();
}

// Conexión
$conn = conectar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cursos del instructor</title>
</head>
<body>
    <?php
        if ($_SERVER["REQUEST_METHOD"]=="POST"){
            $id_curso=$_POST["id_curso"];

            try {
                $consulta=$conn->prepare("SELECT * FROM cursos WHERE id_instructor=id_instructor");
            }
        }
    ?>
</body>
</html> 
