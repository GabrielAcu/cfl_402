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
    <link rel="stylesheet" href="cursos.css">
    <title>Cursos</title>
</head>
<body>
    <h1>Cursos</h1>
    <form action="nuevo_curso.php" method="POST">
        <button type="submit">Nuevo Curso</button>
    </form>

    <hr>
    <form action="index.php" method="POST">
        <input type="search" name="dato" placeholder="Buscar...">
        <input type="submit" value="Buscar">
    </form>
    <h2>Listado de Cursos</h2> <!-- sección para mostrar la lista de cursos -->
    <?php
      
        
        
        if (isset($_POST["dato"])){
            $dato=$_POST["dato"];
        } else {
            $dato="";
        }

        $texto="SELECT cursos.*, instructores.nombre, instructores.apellido, turnos.descripcion
            FROM cursos
            LEFT JOIN instructores ON cursos.id_instructor = instructores.id_instructor
            LEFT JOIN turnos ON cursos.turno=turnos.id_turno
            WHERE (cursos.activo =1) AND (
            cursos.nombre_curso LIKE :nombre_curso OR 
            cursos.codigo LIKE :codigo OR
            turnos.descripcion LIKE :descripcion OR
            instructores.nombre LIKE :nombre OR
            Instructores.apellido LIKE :apellido)";
        $consulta=$conn->prepare($texto);
        $consulta->execute([":nombre_curso"=>"%$dato%",":codigo"=>"%$dato%",":descripcion"=>"%$dato%",":nombre"=>"%$dato%",":apellido"=>"%$dato%"]);
        
        if ($consulta->rowCount()>0){ // si la cantidad de filas es mayor a 0, es porque hay cursos
            echo "
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre Curso</th>
                    <th>Turno</th>
                    <th>Cupo</th>
                    <th colspan='2'>Instructor</th>
                    <th>Acciones</th>
                    <th>Datos Extras</th>
                </tr>
            </thead>
            <tbody>"; // imprimimos el encabezado de la tabla
            while ($registro=$consulta->fetch()){ // recorremos cada registro obtenido de la consulta
                // para cada registro, imprimimos una fila en la tabla con los datos del curso 
                // y los botones de acción, a los cuales les pasamos el id_curso oculto mediante un campo hidden
                // para que se pueda identificar qué curso se quiere modificar o eliminar
                // Las acciones envían los datos a modificar_curso.php y eliminar_curso.php respectivamente
                echo "
                <tr>
                    <td>$registro[codigo]</td> 
                    <td>$registro[nombre_curso]</td>
                    <td>$registro[descripcion]</td>
                    <td>$registro[cupo]</td>
                    <td>$registro[apellido]</td>
                    <td>$registro[nombre]</td>
                    <td>
                        <form action='modificar_curso.php' method='POST' class='enlinea'>
                            <input type='hidden' name='id_curso' value='$registro[id_curso]'>
                            <input type='submit' value='✏️ Modificar'>
                        </form>
                        <form action='eliminar_curso.php' method='POST' class='enlinea' onsubmit='return confirm(\"Está seguro que desea eliminar el curso?\")'>
                            <input type='hidden' name='id_curso' value='$registro[id_curso]'>
                            <input type='submit' value='❌ Eliminar'>
                        </form>
                    </td>
                    <td>
                        <form action='../horarios/index.php' method='POST' class='enlinea'>
                            <input type='hidden' name='id_curso' value='$registro[id_curso]'>
                            <input type='submit' value='👨‍👩‍👧‍👦 Horarios'>
                        </form>
                        <form action='../inscripciones/index.php' method='POST' class='enlinea'>
                            <input type='hidden' name='tipo' value='curso'>
                            <input type='hidden' name='id_curso' value='$registro[id_curso]'>
                            <input type='hidden' name='volver' value='cursos'>
                            <input type='submit' value='📖 Inscripciones'>
                        </form>

                    </td>
                </tr>";
            }
            echo "</tbody>
        </table>"; // cerramos la tabla
        } else {
            echo "<p>Aún no existen cursos</p>"; // si no hay cursos, mostramos este mensaje
        }
    ?>
    
</body>
</html>