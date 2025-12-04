<?php

require_once dirname(__DIR__, 3) . '/config/path.php';

// Dependencias
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/include/header.php';

// Seguridad
requireLogin();

// Conexión
$conn = conectar();


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos.css">
    <title>CFL 402 - Planillas</title>
</head>
<body>
    <?php
    if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["id_curso"])){
        $id_curso=$_POST["id_curso"];

        echo "<form action='ficha_de_curso.php' method='POST'>
            <input type='hidden' name='id_curso' value='$id_curso'>
            <input type='submit' value='Ficha de Curso'>
        </form>
        <form action='acta_de_examen.php' method='POST'>
            <input type='hidden' name='id_curso' value='$id_curso'>
            <input type='submit' value='Acta de examen'>
        </form>
        <form action='presentismo.php' method='POST'>
            <input type='hidden' name='id_curso' value='$id_curso'>
            <input type='submit' value='Presentismo'>
        </form>";
    } else {
        header("Location: index.php");
    }
    ?>
</body>
</html>