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

$pdo=conectar();
// Configuración de la paginación
$registros_por_pagina = 15; // Número de registros a mostrar por página

// Determinar la página actual
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
// Asegurarse de que la página actual sea al menos 1
$pagina_actual = max(1, $pagina_actual);

// Calcular el registro inicial para la consulta (OFFSET)
$offset = ($pagina_actual - 1) * $registros_por_pagina;

// 1. Consultar el total de registros
$stmt_total = $pdo->query("SELECT COUNT(*) FROM alumnos");
$total_registros = $stmt_total->fetchColumn();

// Calcular el total de páginas
$total_paginas = ceil($total_registros / $registros_por_pagina);

// 2. Consultar los registros para la página actual usando LIMIT y OFFSET
$sql = "SELECT * FROM alumnos ORDER BY id_alumno ASC LIMIT :registros_por_pagina OFFSET :offset";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':registros_por_pagina', $registros_por_pagina, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$resultados = $stmt->fetchAll();
// $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Paginación PDO PHP</title>
    <link rel="stylesheet" href="alumnos.css">
</head>
<body>

    <h1>Registros Paginados</h1>

    <main class="main_alumnos">

    <div class="paginacion-container">
    <table >
        <thead class='table_header'>
            <tr>
                <th class='table_th'>ID</th>
                <th class='table_th'>Nombre</th>
                <th class='table_th'>Apellido</th>
                <th class='table_th_final'>DNI</th>
                <th colspan='2' class='table_th'>Dirección</th>
                <th class='table_th'>Fecha Nac.</th>
                <th class='table_th'>Teléfono</th>
                <th class='table_th'>Correo</th>
                <th class='table_th'>Datos Extra</th>
                <th class='table_th_final'>Acciones</th>
                <!-- Añadir más columnas según tu tabla -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resultados as $fila){
                echo "<tr>
                    <td class='td_name'>".htmlspecialchars($fila["id_alumno"])."</td>
                    <td class='td_name'>".htmlspecialchars($fila["nombre"])."</td>
                    <td class='td_name2'>".htmlspecialchars($fila["apellido"])."</td>
                    <td class='td_data'>".htmlspecialchars($fila["dni"])."</td>
                    <td class='td_data'>".htmlspecialchars($fila["direccion"])."</td>
                    <td class='td_data'>".htmlspecialchars($fila["localidad"])."</td>
                    <td class='td_name2'>".htmlspecialchars($fila["fecha_nacimiento"])."</td>
                    
                    <td class='td_data'>".htmlspecialchars($fila["telefono"])."</td>
                    <td class='td_data'>".htmlspecialchars($fila["correo"])."</td>
                    <!-- DATOS EXTRA -->
                        <td class='td_actions' >
                            <form action='/cfl_402/admin/crud/contacto/listar_contactos.php' method='POST' class='enlinea'>
                                <input type='hidden' name='id_alumno' value='$fila[id_alumno]'>
                                <input type='hidden' name='tipo' value='alumno'>
                                <button type='submit' class='submit-button'>
                                    <img class='svg_lite' src='/cfl_402/assets/svg/contact.svg' alt='Contactos' title='Contactos'>
                                </button>
                            </form>
                        

                            <form action='/cfl_402/admin/crud/alumnos/ver_inscripciones.php' method='POST' class='enlinea'>
                                <input type='hidden' name='id_alumno' value='$fila[id_alumno]'>
                                    <button type='submit' class='submit-button'>
                                     <img class='svg_lite' src='/cfl_402/assets/svg/book.svg' alt='Ver contactos' title='Cursos'>
                                    </button>
                            </form>
                        </td>

                        <!-- ACCIONES -->
                        <td class='td_actions' title='Eliminar Alumno'>
                            <form action='../../crud/alumnos/modificar.php' method='POST' class='enlinea'>
                                <input type='hidden' name='id_alumno' value='$fila[id_alumno]'>
                                <button type='submit' class='submit-button'>
                                    <img class='svg_lite2' src='/cfl_402/assets/svg/pencil.svg' alt='Modificar' title='Modificar'>
                                </button>
                            </form>

                            <form action='../../crud/alumnos/bajar.php' method='POST' class='enlinea'>
                                <input type='hidden' name='id_alumno' value='$fila[id_alumno]'>
                                <button type='submit' class='submit-button'>
                                    <img class='svg_lite' src='/cfl_402/assets/svg/trash.svg' alt='Eliminar' title='Eliminar'>
                                </button>
                            </form>
                        </td>

                        <td class='td_actions3' title='Inscribir a un curso'>
                            <form action='../inscripciones/index.php' method='POST' class='enlinea'>
                                <input type='hidden' name='tipo' value='alumno'>
                                <input type='hidden' name='id_alumno' value='$fila[id_alumno]'>
                                <input type='hidden' name='volver' value='alumnos'>
                                <button type='submit' class='submit-button'>
                                    <img class='svg_lite' src='/cfl_402/assets/svg/plus.svg' alt='Modificar' title='Inscribir a un curso'>
                                </button>
                            </form>
                        </td>


                    <!-- Mostrar más datos -->
                </tr>";
            }
            ?>
        </tbody>
    </table>

    <!-- Controles de Paginación -->
    <div class="pagination">
        <?php if ($total_paginas > 1){
            // Enlace a la primera página 
            
            if($pagina_actual == 1){
                echo "<a href='?pagina=1' class='active'>Primera</a>";
            } else {
                echo "<a href='?pagina=1' class=''>Primera</a>";
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

    </div>

    </main>
</body>