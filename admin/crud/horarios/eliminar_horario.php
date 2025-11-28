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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XXX</title>
</head>
<body>
    <?php
    
    if ($_SERVER["REQUEST_METHOD"]=="POST"){
        $id_curso=$_POST["id_curso"];
        $id_horario=$_POST["id_horario"];
        $conn=conectar();
        try {
            $consulta=$conn->prepare("DELETE FROM horarios WHERE id_horario=?");
            $consulta->execute([$id_horario]);
            echo "Eliminado correctamente tilin";
            
        } catch (Exception $e) {
            echo "Ocurrio un error, no se elimino el registro.";
            echo $e->getMessage();

        }
    }
            echo "<form action='index.php' method='POST'>
                <input type='hidden' value='$id_curso' name='id_curso'>
                <input type='submit' value='Volver al Listado de Horarios'>
                </form>";
?>
    


</body>
</html>