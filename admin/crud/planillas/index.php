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
    <title>Cursos</title>
</head>
<body>
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

        $registros_por_pagina = 5; // Número de registros a mostrar por página

        // Determinar la página actual
        $pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        // Asegurarse de que la página actual sea al menos 1
        $pagina_actual = max(1, $pagina_actual);

        // Calcular el registro inicial para la consulta (OFFSET)
        $offset = ($pagina_actual - 1) * $registros_por_pagina;

        // 1. Consultar el total de registros
        // $stmt_total = $conn->query("SELECT COUNT(*) FROM cursos WHERE activo=1");
        $texto="SELECT COUNT(*) 
                    FROM cursos
                    LEFT JOIN instructores ON cursos.id_instructor = instructores.id_instructor
                    LEFT JOIN turnos ON cursos.id_turno=turnos.id_turno
                    WHERE (cursos.activo =1) AND (
                    cursos.nombre_curso LIKE :nombre_curso OR 
                    cursos.codigo LIKE :codigo OR
                    turnos.descripcion LIKE :descripcion OR
                    instructores.nombre LIKE :nombre OR
                    Instructores.apellido LIKE :apellido)";
        $stmt_total=$conn->prepare($texto);
        $stmt_total->execute([":nombre_curso"=>"%$dato%",":codigo"=>"%$dato%",":descripcion"=>"%$dato%",":nombre"=>"%$dato%",":apellido"=>"%$dato%"]);
        $total_registros = $stmt_total->fetchColumn();

        // Calcular el total de páginas
        $total_paginas = ceil($total_registros / $registros_por_pagina);

        $texto="SELECT cursos.*, instructores.nombre, instructores.apellido, turnos.descripcion
            FROM cursos
            LEFT JOIN instructores ON cursos.id_instructor = instructores.id_instructor
            LEFT JOIN turnos ON cursos.id_turno=turnos.id_turno
            WHERE (cursos.activo =1) AND (
            cursos.nombre_curso LIKE :nombre_curso OR 
            cursos.codigo LIKE :codigo OR
            turnos.descripcion LIKE :descripcion OR
            instructores.nombre LIKE :nombre OR
            Instructores.apellido LIKE :apellido)  ORDER BY id_curso DESC LIMIT :registros_por_pagina OFFSET :offset";
        $consulta=$conn->prepare($texto);
        $consulta->bindParam(':registros_por_pagina', $registros_por_pagina, PDO::PARAM_INT);
        $consulta->bindParam(':offset', $offset, PDO::PARAM_INT);
        $consulta->execute([":nombre_curso"=>"%$dato%",":codigo"=>"%$dato%",":descripcion"=>"%$dato%",":nombre"=>"%$dato%",":apellido"=>"%$dato%", ":registros_por_pagina"=> "$registros_por_pagina", ":offset"=> $offset]);
  

        
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
                    <th>Planillas</th>
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
                        <form action='planillas.php' method='POST' class='enlinea'>
                            <input type='hidden' name='id_curso' value='$registro[id_curso]'>
                            <input type='submit' value='📄 Planillas'>
                        </form>                        

                    </td>
                </tr>";
            }
            echo "</tbody>
        </table>"; 
        
        
        // cerramos la tabla
        } else {
            echo "<p>Aún no existen cursos</p>"; // si no hay cursos, mostramos este mensaje
        }
  
    ?>

        <div class="pagination">
        <?php if ($total_paginas > 1){
            // Enlace a la primera página 
            
            if($pagina_actual == 1){
                echo "<a href='?pagina=1' class='active'>Priemra</a>";
            } else {
                echo "<a href='?pagina=1' class=''>Priemra</a>";
            }
            
            // Enlace a la página anterior 
            if ($pagina_actual > 1){
                echo "<a href='?pagina=".($pagina_actual - 1)."'>Anterior</a>";
            }

            // Mostrar enlaces para algunas páginas (ej: 5 páginas alrededor de la actual)
            
            $rango = 2; // Número de páginas a mostrar antes y después de la actual
            for ($i = max(1, $pagina_actual - $rango); $i <= min($total_paginas, $pagina_actual + $rango); $i++){
            
                echo "<a href='?pagina=$i' class='". (($i == $pagina_actual) ? 'active':'')."'>$i</a>";
            }

            // Enlace a la página siguiente 
            if ($pagina_actual < $total_paginas){
                echo "<a href='?pagina=".($pagina_actual + 1)."'>Siguiente</a>";
            }

            // Enlace a la última página 
            echo "<a href='?pagina=$total_paginas' class='".(($pagina_actual == $total_paginas) ? 'active':'')."'>Última</a>";
        }
        ?>
    </div>
</body>
</html>