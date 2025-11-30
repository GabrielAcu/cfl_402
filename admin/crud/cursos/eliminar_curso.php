<?php

require_once dirname(__DIR__, 3) . '/config/path.php';

// Dependencias
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/include/header.php';

// Seguridad
requireLogin();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos.css">
    <title>Eliminar Curso</title>
</head>
<body>
    <?php
    if ($_SERVER["REQUEST_METHOD"]=="POST"){ // verificar que el método de solicitud sea POST
        $id_curso=$_POST['id_curso']; // obtener el id_curso enviado desde el formulario

        $conexion=conectar(); // establecer la conexión
        try {
        
        
        $consulta=$conexion->prepare("UPDATE cursos SET activo=0 WHERE id_curso=?");
        
        $consulta->execute([$id_curso]);

        if ($consulta->rowCount()>0){ // si se eliminó al menos una fila
            echo "<p class='correcto'>Curso eliminado correctamente.</p>";
        } else {
            echo "<p class='error'>No se pudo eliminar el curso.</p>";
        }
        echo "<a href='index.php'>Volver al listado de cursos</a>";
        } catch (Exception $e){
            if ($e->errorInfo[1]==1451){ // código de error para restricción de clave foránea
                echo "<p class='error'>No se puede eliminar el curso porque está inscripto en curso/s.</p>";
            }else{ // otro error
            echo "<p class='error'>Error al eliminar el curso: ".$e->getMessage()."</p>";
            }
        }
    } else { // si no es método POST, mostrar mensaje de error
        echo "<p class='error'>Solicitud inválida.</p>";
    }
    ?>
</body>
</html>