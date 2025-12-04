<?php
// ==========================
//   CONFIGURACIÓN INICIAL
// ==========================
require_once dirname(__DIR__, 3) . '/config/path.php';
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/include/header.php';
require_once 'layouts.php';

// Autenticación
requireLogin();

// if (!isAdmin()) {
//     header('Location: /cfl_402/index.php');
//     exit();
// }

// Conexión
$conn = conectar();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="modal.css">
    <link rel="stylesheet" href="alumnos2.css">

</head>
<body>
    
</body>
</html>

<h1>Alumnos</h1>

<div class="search_container">
    <div class="search_block">

        <div class="search_row">

            <!-- Buscador -->
            <form class="search_form" action="/cfl_402/admin/crud/alumnos/index.php" method="post">
                <input class="search_bar" type="search" name="search" placeholder="Buscar Alumno..">
                <button class="boton_enviar" type="submit">Buscar</button>
            </form>

            <!-- Registrar nuevo alumno -->
           
                    <button id="btnAbrirModal" class="btn-primary">
                        <img class="svg_lite" src="/cfl_402/assets/svg/plus_circle.svg" alt="Nuevo">
                        Nuevo Alumno
                    </button>
            

        </div>

        <hr class="search_line">

        <!-- Filtro -->
        <form action="filtrar_alumnos.php">
            <select name="filtros" id="filtros">
                <option value="nombre_filtro">Nombre</option>
                <option value="nombre_filtro">Apellido</option>
                <option value="nombre_filtro">DNI</option>
            </select>
        </form>

    </div>
</div>

    <!-- </div>s -->
    <hr>
        <h2>Listado de Alumnos</h2> <!-- sección para mostrar la lista de alumnos -->
        <link rel="stylesheet" href="alumnos2.css">
    <hr>

<h2>Listado de Alumnos</h2>
<link rel="stylesheet" href="alumnos2.css">

<?php

// ==========================
//   BÚSQUEDA
// ==========================
$input = isset($_POST["search"]) ? $_POST["search"] : "";


// ==========================
//   PAGINACIÓN
// ==========================
$registros_por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$pagina_actual = max(1, $pagina_actual);
$offset = ($pagina_actual - 1) * $registros_por_pagina;


// ==========================
//   TOTAL REGISTROS
// ==========================
$stmt_total = $conn->prepare("
    SELECT COUNT(*) FROM alumnos
    WHERE activo='1' AND (alumnos.nombre LIKE :nombre
        OR alumnos.apellido LIKE :apellido
        OR alumnos.dni LIKE :dni
        OR alumnos.telefono LIKE :telefono)
");

$stmt_total->execute([
    ":nombre" => "%$input%",
    ":apellido" => "%$input%",
    ":dni" => "%$input%",
    ":telefono" => "%$input%"
]);

$total_registros = $stmt_total->fetchColumn();
$total_paginas = ceil($total_registros / $registros_por_pagina);


// ==========================
//   CONSULTA PRINCIPAL
// ==========================
$sql = "
    SELECT alumnos.*, alumnos.nombre, alumnos.apellido, alumnos.dni, alumnos.telefono
    FROM alumnos
    WHERE activo = '1'
        AND (alumnos.nombre LIKE :nombre
            OR alumnos.apellido LIKE :apellido
            OR alumnos.dni LIKE :dni
            OR alumnos.telefono LIKE :telefono)
    ORDER BY id_alumno ASC
    LIMIT :registros_por_pagina OFFSET :offset
";

$consulta = $conn->prepare($sql);

// IMPORTANTE → NO usamos bindParam duplicado, solo execute([])

$consulta->execute([
    ":nombre" => "%$input%",
    ":apellido" => "%$input%",
    ":dni" => "%$input%",
    ":telefono" => "%$input%",
    ":registros_por_pagina" => $registros_por_pagina,
    ":offset" => $offset
]);

render_pagination($total_paginas, $pagina_actual); 

// ==========================
//   MOSTRAR TABLA
// ==========================
if ($consulta->rowCount() > 0) {

    echo "
    <main class='main_alumnos'>
    <table class='info_table'>
        <thead>
            <tr class='table_header'>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>DNI</th>
                <th>Fecha Nac.</th>
                <th>Teléfono</th>
                <th>Dirección</th>
                <th>Correo</th>
                <th>Datos Extra</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
    ";

    while ($registro = $consulta->fetch()) {

        echo "
        <tr>
            <td>{$registro['nombre']}</td>
            <td>{$registro['apellido']}</td>
            <td>{$registro['dni']}</td>
            <td>{$registro['fecha_nacimiento']}</td>
            <td>{$registro['telefono']}</td>
            <td>{$registro['direccion']}</td>
            <td>{$registro['correo']}</td>
            
            <td class='td_actions'>
                <div class='acciones_wrapper'>
                    <form action='../contacto/listar_contactos.php' method='POST' class='enlinea'>
                        <input type='hidden' name='id_entidad' value='{$registro['id_alumno']}'>
                        <input type='hidden' name='tipo' value='alumno'>
                        <button type='submit' class='submit-button'>
                            <img class='svg_lite' src='/cfl_402/assets/svg/contact.svg' title='Contactos'>
                        </button>
                    </form>

                    <form action='/cfl_402/admin/crud/cursos/index.php' method='POST' class='enlinea'>
                        <input type='hidden' name='id_alumno' value='{$registro['id_alumno']}'>
                        <button type='submit' class='submit-button'>
                            <img class='svg_lite' src='/cfl_402/assets/svg/book.svg' title='Cursos'>
                        </button>
                    </form>
                </div>
            </td>

            <td class='td_actions2'>
                <form action='../alumnos/modificar.php' method='POST' class='enlinea'>
                    <input type='hidden' name='id_alumno' value='{$registro['id_alumno']}'>
                    <button type='submit' class='submit-button'>
                        <img class='svg_lite2' src='/cfl_402/assets/svg/pencil.svg' title='Modificar'>
                    </button>
                </form>

                <form action='../alumnos/bajar.php' method='POST' class='enlinea confirm-delete'>
                    <input type='hidden' name='id_alumno' value='{$registro['id_alumno']}'>
                    <button type='submit' class='submit-button'>
                        <img class='svg_lite' src='/cfl_402/assets/svg/trash.svg' title='Eliminar'>
                    </button>
                </form>

                <form action='../inscripciones/index.php' method='POST' class='enlinea'>
                    <input type='hidden' name='tipo' value='alumno'>
                    <input type='hidden' name='id_alumno' value='{$registro['id_alumno']}'>
                    <input type='hidden' name='volver' value='alumnos'>
                    <button type='submit' class='submit-button'>
                        <img class='svg_lite' src='/cfl_402/assets/svg/plus.svg' title='Inscribir a un curso'>
                    </button>
                </form>
            </td>
        </tr>        
        ";
    }

    echo "
        </tbody>
    </table>
    </main>
    ";


            if (isset($_POST['search'])) {
               $input=$_POST["search"]; 
            } else {
                $input="";
            }

            // Configuración de la paginación
            $registros_por_pagina = 10; // Número de registros a mostrar por página

            // Determinar la página actual
            $pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
            // Asegurarse de que la página actual sea al menos 1
            $pagina_actual = max(1, $pagina_actual);

            // Calcular el registro inicial para la consulta (OFFSET)
            $offset = ($pagina_actual - 1) * $registros_por_pagina;

            // 1. Consultar el total de registros
            $stmt_total = $conn->prepare("SELECT COUNT(*) FROM alumnos WHERE (alumnos.nombre LIKE :nombre 
                OR alumnos.apellido LIKE :apellido 
                OR alumnos.dni LIKE :dni
                OR alumnos.telefono LIKE :telefono)");
            $stmt_total->execute([":nombre"=>"%$input%", 
                ":apellido"=>"%$input%", 
                ":dni"=>"%$input%", 
                ":telefono"=>"%$input%"]);
            $total_registros = $stmt_total->fetchColumn();

            // Calcular el total de páginas
            $total_paginas = ceil($total_registros / $registros_por_pagina);

            // texto de la consulta SQL con marcadores de posición
            $sql="SELECT alumnos.*, alumnos.nombre, alumnos.apellido, alumnos.dni, alumnos.telefono FROM alumnos
                WHERE `activo`='1' AND (alumnos.nombre LIKE :nombre 
                OR alumnos.apellido LIKE :apellido 
                OR alumnos.dni LIKE :dni
                OR alumnos.telefono LIKE :telefono) ORDER BY id_alumno ASC LIMIT :registros_por_pagina OFFSET :offset";
                
            $consulta=$conn->prepare($sql); 
            $consulta->bindParam(':registros_por_pagina', $registros_por_pagina, PDO::PARAM_INT);

            $consulta->bindParam(':offset', $offset, PDO::PARAM_INT);
            
            // $consulta->execute([':nombre'=>$nombre,':apellido'=>$apellido,':dni'=>$dni,':nacimiento' =>$nacimiento, ':correo' =>$correo, ':telefono'=>$telefono, ':direccion' =>$domicilio, ':localidad' => $localidad, ':cp' => $postal, ':activo'=> $activo, ':autos' =>$autos, ':patente' =>$patente, ':observaciones'=>$observaciones]);
            $consulta->execute( [
                ":nombre"=>"%$input%", 
                ":apellido"=>"%$input%", 
                ":dni"=>"%$input%", 
                ":telefono"=>"%$input%",
                ':registros_por_pagina' => $registros_por_pagina,
                ':offset' => $offset
             ]);
            // consulta para obtener todos los alumnos
                // $consulta=$conn->query("SELECT * FROM alumnos WHERE activo='1'"); // consulta para obtener todos los alumnos
            if ($consulta->rowCount()>0){ // si la cantidad de filas es mayor a 0, es porque hay alumnos
                echo "
                
            <main class='main_alumnos'>

            <table class='info_table'>
                <thead>
                    
                    <tr class='table_header'>
                        <th class='table_th'>Nombre</th>
                        <th class='table_th'>Apellido</th>
                        <th class='table_th'>DNI</th>
                        <th colspan='2' class='table_th'>Dirección</th>
                        <th class='table_th'>Fecha Nac.</th>
                        <th class='table_th'>Teléfono</th>
                        <th class='table_th'>Correo</th>
                        <th class='table_th'>Datos Extra</th>
                        <th class='table_th_final' >Acciones</th>
                       

                    </tr>
                    
                </thead>
                <tbody>"; // imprimimos el encabezado de la tabla
                while ($registro=$consulta->fetch()){ // recorremos cada registro obtenido de la consulta
                    // para cada registro, imprimimos una fila en la tabla con los datos del alumno 
                    // y los botones de acción, a los cuales les pasamos el id_alumno oculto mediante un campo hidden
                    // para que se pueda identificar qué alumno se quiere modificar o eliminar
                    // Las acciones envían los datos a modificar_alumno.php y eliminar_alumno.php respectivamente
                    $i=0;
                    echo "
                    <a href='/cfl_402/cruds/crud_alumnos/perfil_alumnos.php'>
                    <tr  title='Click para ver Perfil'>
                        <td class='td_name'>$registro[nombre]</td> 
                        <td class='td_name2'>$registro[apellido]</td>
                        <td class='td_data'>$registro[dni]</td>
                        <td class='td_dir2'>$registro[direccion]</td>
                        <td class='td_dir'>$registro[localidad]</td>
                        <td class='td_data'>$registro[fecha_nacimiento]</td>
                        <td class='td_data'>$registro[telefono]</td>
                        <td class='td_data'>$registro[correo]</td>

                        <!-- DATOS EXTRA -->
                        <td class='td_actions' >
                            <form action='../contacto/listar_contactos.php' method='POST' class='enlinea'>
                                <input type='hidden' name='id_entidad' value='$registro[id_alumno]'>
                                <input type='hidden' name='tipo' value='alumno'>
                                <button type='submit' class='submit-button'>
                                    <img class='svg_lite' src='/cfl_402/assets/svg/contact.svg' alt='Contactos' title='Contactos'>
                                </button>
                            </form>
                        

                            <form action='/cfl_402/admin/crud/cursos/index.php' method='POST' class='enlinea'>
                                <input type='hidden' name='id_alumno' value='$registro[id_alumno]'>
                                    <button type='submit' class='submit-button'>
                                     <img class='svg_lite' src='/cfl_402/assets/svg/book.svg' alt='Ver contactos' title='Cursos'>
                                    </button>
                            </form>
                        </td>

                        <!-- ACCIONES -->
                        <td class='td_actions2' title='Eliminar Alumno'>
                            <form action='../../crud/alumnos/modificar.php' method='POST' class='enlinea'>
                                <input type='hidden' name='id_alumno' value='$registro[id_alumno]'>
                                <button type='submit' class='submit-button'>
                                    <img class='svg_lite2' src='/cfl_402/assets/svg/pencil.svg' alt='Modificar' title='Modificar'>
                                </button>
                            </form>

                            <form action='../../crud/alumnos/bajar.php' method='POST' class='enlinea'>
                                <input type='hidden' name='id_alumno' value='$registro[id_alumno]'>
                                <button type='submit' class='submit-button'>
                                    <img class='svg_lite' src='/cfl_402/assets/svg/trash.svg' alt='Eliminar' title='Eliminar'>
                                </button>
                            </form>

                            <form action='../inscripciones/index.php' method='POST' class='enlinea'>
                                <input type='hidden' name='tipo' value='alumno'>
                                <input type='hidden' name='id_alumno' value='$registro[id_alumno]'>
                                <input type='hidden' name='volver' value='alumnos'>
                                <button type='submit' class='submit-button'>
                                    <img class='svg_lite' src='/cfl_402/assets/svg/plus.svg' alt='Modificar' title='Inscribir a un curso'>
                                </button>
                            </form>
                        </td>

                    </tr>

                    </a> ";
                }
                echo "</tbody>";
                echo "</table>";    
            echo"</main>";       
            ?>
            
                <div class='pagination'>
                <?php if ($total_paginas > 1){
                    // Enlace a la primera página 
                    
                    if($pagina_actual == 1){
                        echo "<a href='?pagina=1' class='active'> <img class='svg_lite' src='/cfl_402/assets/svg/left_arrow.svg' alt='Primera Página ' title='Primer Página'>
                        </a>";
                    } else {
                        echo "<a href='?pagina=1' class=''> <img class='svg_lite' src='/cfl_402/assets/svg/left_arrow.svg' alt='Primera Página ' title='Primer Página'>   
                        </a>";
                    }
                    
                    // Enlace a la página anterior 
                    if ($pagina_actual > 1){
                        echo "<a href='?pagina=".($pagina_actual - 1)."'> <img class='svg_lite' src='/cfl_402/assets/svg/left_one_arrow.svg' alt='Página Anterior' title='Página Anterior'>
                        </a>";
                    }

                    // Mostrar enlaces para algunas páginas (ej: 5 páginas alrededor de la actual)
                    
                    $rango = 2; // Número de páginas a mostrar antes y después de la actual
                    for ($i = max(1, $pagina_actual - $rango); $i <= min($total_paginas, $pagina_actual + $rango); $i++){
                    
                        echo "<a href='?pagina=$i' class='". (($i == $pagina_actual) ? 'active':'')."'>$i</a>";
                    }

                    // Enlace a la página siguiente 
                    if ($pagina_actual < $total_paginas){
                        echo "<a href='?pagina=".($pagina_actual + 1)."'> <img class='svg_lite' src='/cfl_402/assets/svg/right_one_arrow.svg' alt='Página Siguiente' title='Página Siguiente'>
                        </a>";
                    }

                    // Enlace a la última página 
                    echo "<a href='?pagina=$total_paginas' class='".(($pagina_actual == $total_paginas) ? 'active':'')."'> <img class='svg_lite' src='/cfl_402/assets/svg/right_arrow.svg' alt='Última Página' title='Última Página'>
                    </a>";
                }
            // cerramos la tabla
            } else {
                echo "<p>Aún no existen alumnos</p>"; // si no hay alumnos, mostramos este mensaje
            }
        }
        ?>
        </div>            
<?php
    // ==========================
    //   PAGINACIÓN
    // ==========================
    function render_pagination($total_paginas, $pagina_actual) {
        if ($total_paginas <= 1) {
            return; // No mostrar nada si no hay más páginas
        }
    
        echo "<div class='pagination'>";
    
        // 👉 Primera página
        echo "<a href='?pagina=1' class='" . ($pagina_actual == 1 ? "active" : "") . "'>
                <img class='svg_lite' src='/cfl_402/assets/svg/left_arrow.svg'>
              </a>";
    
        // 👉 Página anterior
        if ($pagina_actual > 1) {
            echo "<a href='?pagina=" . ($pagina_actual - 1) . "'>
                    <img class='svg_lite' src='/cfl_402/assets/svg/left_one_arrow.svg'>
                  </a>";
        }
    
        // 👉 Rango de páginas centrado
        $rango = 2;
        for ($i = max(1, $pagina_actual - $rango); $i <= min($total_paginas, $pagina_actual + $rango); $i++) {
            echo "<a href='?pagina=$i' class='" . (($i == $pagina_actual) ? 'active' : '') . "'>$i</a>";
        }
    
        // 👉 Página siguiente
        if ($pagina_actual < $total_paginas) {
            echo "<a href='?pagina=" . ($pagina_actual + 1) . "'>
                    <img class='svg_lite' src='/cfl_402/assets/svg/right_one_arrow.svg'>
                  </a>";
        }
    
        // 👉 Última página
        echo "<a href='?pagina=$total_paginas' class='" . (($pagina_actual == $total_paginas) ? 'active' : '') . "'>
                <img class='svg_lite' src='/cfl_402/assets/svg/right_arrow.svg'>
              </a>";
    
        echo "</div>";
    }
    render_pagination($total_paginas, $pagina_actual);    

    include 'modal.php'; //incluye el modal para crear un nuevo curso 
    
?>
<script src="delete.js"></script>
</body>
</html>
