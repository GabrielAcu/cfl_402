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
    <link rel="stylesheet" href="nuevoCurso.css">
    <title>Nuevo Curso</title>
</head>
<body>
    <h1>Nuevo Curso</h1>
    <!-- formulario para crear un nuevo curso -->
    <form action="crear_curso.php" method="POST"> 
        <div>
            <label for="codigo">Código</label>
            <input type="text" name="codigo" id="codigo" placeholder="Código" required>
        </div>
        <div>
            <label for="nombre_curso">Nombre del Curso</label>
            <input type="text" name="nombre_curso" id="nombre_curso" placeholder="Nombre del Curso" required>
        </div>
        <div>
            <label for="descripcion">Descripción</label>
            <input type="text" name="descripcion" id="descripcion" placeholder="Descripción" required>
        </div>
        <div>
            <label for="cupo">Cupo</label>
            <input type="text" name="cupo" id="cupo" placeholder="Cupo" required>
        </div>
        <?php
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
        
>>>>>>> ca717327ce520a49869d51a6b2c86ec00a66c01d
>>>>>>> 91c34e664ec22601ab74ae2e0d046ef24f7aa0e4

        $instructores=$conn->query("SELECT nombre, apellido, id_instructor FROM instructores");
        echo "
        <div>
            <label for='instructor'>Instructor</label>
            <select name='instructor' id='instructor'>";
        while ($instructor=$instructores->fetch()){
            echo "<option value='$instructor[id_instructor]'>$instructor[apellido],$instructor[nombre]</option>";
        
        }
            echo"</select>
        </div>";
        $turnos=$conn->query("SELECT * FROM turnos");
        
        echo "<div>
            <label for='turno'>Turno</label>
            <select name='turno' id='turno'>";
        while ($turno=$turnos->fetch()){
            echo "<option value='$turno[id_turno]'>$turno[descripcion]</option>";
        }
        echo "    </select>
        </div>";
        ?>
        <input type="submit" value="Guardar">
        <a href="index.php">Volver al listado</a>
        <!-- Al enviar el formulario, se envían los datos a crear_curso.php mediante el método POST -->
    </form> 
</body>
</html>