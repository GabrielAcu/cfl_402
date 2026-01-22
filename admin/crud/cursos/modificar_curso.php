<?php

require_once dirname(__DIR__, 3) . '/config/path.php';

// Dependencias
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/config/csrf.php';
require_once BASE_PATH . '/include/header.php';

// Seguridad
requireLogin();

// Validar CSRF en POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    requireCSRFToken();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="cursos.css">
    <title>Modificar Curso</title>
</head>
<body>
    <?php
    if ($_SERVER["REQUEST_METHOD"]=="POST"){ // verificar que el método de solicitud sea POST
        $conexion=conectar(); 
        $id_curso=$_POST["id_curso"]; // obtener el id_curso enviado desde el formulario
        // texto de la consulta SQL con marcador de posición
        $texto="SELECT * FROM cursos WHERE id_curso=:id_curso"; 
        $consulta=$conexion->prepare($texto); // preparar la consulta
        $consulta->bindParam(':id_curso',$id_curso); // vincular el parámetro
        $consulta->execute(); // ejecutar la consulta
        $curso=$consulta->fetch(); // obtener el registro del curso
        if ($curso){ // si el curso existe, mostrar el formulario de modificación con los datos actuales
            // Asegurar salida
            $c_id = htmlspecialchars($curso['id_curso']);
            $c_cod = htmlspecialchars($curso['codigo']);
            $c_nom = htmlspecialchars($curso['nombre_curso']);
            $c_desc = htmlspecialchars($curso['descripcion']);
            $c_cupo = htmlspecialchars($curso['cupo']);

            echo "<h2>Modificar curso</h2>
                <form action='procesar_modificacion_curso.php' method='POST'>
                <input type='hidden' name='id_curso' value='$c_id'>
        <div>
            <label for='codigo'>Código</label>
            <input type='text' name='codigo' id='codigo' placeholder='Código' required value='$c_cod'>
        </div>
        <div>
            <label for='nombre_curso'>Nombre del Curso</label>
            <input type='text' name='nombre_curso' id='nombre_curso' placeholder='Nombre del Curso' required value='$c_nom'>
        </div>
        <div>
            <label for='descripcion'>Descripción</label>
            <input type='text' name='descripcion' id='descripcion' placeholder='Descripción' required value='$c_desc'>
        </div>
        <div>
            <label for='cupo'>Cupo</label>
            <input type='text' name='cupo' id='cupo' placeholder='Cupo' required value='$c_cupo'>
        </div>";
        
        $instructores=$conexion->query("SELECT nombre, apellido, id_instructor FROM instructores");
        echo "
        <div>
            <label for='instructor'>Instructor</label>
            <select name='instructor' id='instructor'>";
        while ($instructor=$instructores->fetch()){
            $i_id = htmlspecialchars($instructor['id_instructor']);
            $i_nom = htmlspecialchars($instructor['nombre']);
            $i_ape = htmlspecialchars($instructor['apellido']);
            
            if ($instructor["id_instructor"]==$curso["id_instructor"]){
                echo "<option value='$i_id' selected>$i_ape, $i_nom</option>";
            } else {
                echo "<option value='$i_id'>$i_ape, $i_nom</option>";
            }
        
        }
            echo"</select>
        </div>";
        $turnos=$conexion->query("SELECT * FROM turnos");
        
        echo "<div>
            <label for='turno'>Turno</label>
            <select name='turno' id='turno'>";
        while ($turno=$turnos->fetch()){
            $t_id = htmlspecialchars($turno['id_turno']);
            $t_desc = htmlspecialchars($turno['descripcion']);

            if($turno["id_turno"]==$curso["id_turno"]){
                echo "<option value='$t_id' selected>$t_desc</option>";
            } else {
                echo "<option value='$t_id'>$t_desc</option>";
            }
        }
        echo "    </select>
        </div>";

        echo "<input type='submit' value='Guardar'>
    </form>
            "; // cuando se envíe el formulario, los datos se enviarán a procesar_modificacion.php mediante el método POST
        } else { // si el curso no existe, mostrar mensaje de error
            echo "<p class='error'>El curso no existe</p>";
        }
    } else { // si no es método POST, mostrar mensaje de error
        echo "<h1 class='error'>Debe acceder a través del listado de cursos</h1>";
        echo "<p>$_SERVER[REQUEST_METHOD]</p>";
    }
    ?>

</body>
</html>