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
        ?>