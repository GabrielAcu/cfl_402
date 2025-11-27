<?php
// Cargar path.php
require_once dirname(__DIR__, 2) . '/../config/path.php';

// Dependencias
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/include/header.php';
require_once 'layouts.php';


// 3. Autenticación
requireLogin();

if (!isAdmin()) {
    header('Location: /cfl_402/index.php');
    exit();
}

// Conexión
$conn = conectar();
    $id_alumno=$_POST["id_alumno"]; // obtener el id_alumno enviado desde el formulario
        // texto de la consulta SQL con marcador de posición
        $texto="SELECT * FROM alumnos WHERE id_alumno=:id_alumno";
        $consultinha=$conn->prepare($texto); // preparar la consulta
        $consultinha->execute([':id_alumno'=> $id_alumno]); // ejecutar la consulta
        $alumno= $consultinha->fetch();
        $cursos=$conn->prepare("SELECT `turnos`.`descripcion`, `cursos`.`nombre_curso`, `cursos`.`codigo`, `inscripciones`.`id_alumno`, `inscripciones`.`fecha_inscripcion`
            FROM `inscripciones`
                LEFT JOIN `cursos` ON `inscripciones`.`id_curso` = `cursos`.`id_curso`
                LEFT JOIN `turnos` ON `cursos`.`id_turno` = `turnos`.`id_turno`
            WHERE (`inscripciones`.`id_alumno` =:id_alumno)" );
        $cursos->execute([':id_alumno'=>$id_alumno]);
?>
 


    <h1>Alumnos</h1>
    <link rel="stylesheet" href="alumnos.css">
        
    <div class="search_container">
        <div class="search_block">

            <div class="search_row">
                <form class="search_form" action="ver_inscripciones.php" method="post">
                    <input class="search_bar" type="search" name="search" placeholder="Buscar Inscripciones .."> 
                    <button class="boton_enviar" type="submit"> Buscar </button>
                </form>
                
                <form action="../inscripciones/editar.php" method="post">
                    <button class='boton_enviar' id="register_button"> <img class='svg_lite' src='/crud-alumnos/assest/svg/plus_circle.svg' alt='Eliminar'> Inscribir a curso  </button>
                </form>
            </div>
            
            <hr class="search_line">

            <form action="filtrar_alumnos.php">
                <select  name="filtros" id="filtros">
                    <option class='option' value="nombre_filtro"> Nombre </option>
                    <option class='option' value="nombre_filtro"> Apellido </option>
                    <option class='option'  value="nombre_filtro"> DNI </option>
                </select>



            </form>

        </div>
    </div>

    <!-- </div>s -->
        <hr>
         <!-- sección para mostrar la lista de alumnos -->
        
        <?php
            
            if ($alumno) {
                echo"<h2>Listado de cursos a los que se inscribió: $alumno[nombre] $alumno[apellido] </h2>";
            }

            // $consulta=$conexion->query("SELECT * FROM inscripciones WHERE id_alumno='?'"); // consulta para obtener todos los alumnos
            if ($cursos ->rowCount()>0){ // si la cantidad de filas es mayor a 0, es porque hay alumnos
                echo "
                
            <main class='main_alumnos'>

            <table class='info_table'>
                <thead>
                    
                    <tr class='table_header'>
                        <th class='table_th'>Nombre Curso </th>
                        <th class='table_th'>Código </th>
                        <th class='table_th'>Turno</th>
                        <th class='table_th'>Fecha Inscripción</th>


                    </tr>
                    
                </thead>
                <tbody>"; // imprimimos el encabezado de la tabla
                while ($registro=$cursos->fetch()){ // recorremos cada registro obtenido de la consulta
                    // para cada registro, imprimimos una fila en la tabla con los datos del alumno 
                    // y los botones de acción, a los cuales les pasamos el id_alumno oculto mediante un campo hidden
                    // para que se pueda identificar qué alumno se quiere modificar o eliminar
                    // Las acciones envían los datos a modificar_alumno.php y eliminar_alumno.php respectivamente
                    $i=0;
                    echo "
                    <a href='/cfl_402/cruds/crud_alumnos/perfil_alumnos.php'>
                    <tr  title='Click para ver Perfil'>
                        <td class='td_name'>$registro[nombre_curso] </td>
                        <td class='td_name'>$registro[codigo] </td>
                        <td class='td_name'>$registro[descripcion] </td> 
                        <td class='td_name'>$registro[fecha_inscripcion] </td> 

                      ";
                }
                "<tfoot> <a href='alumnos_eliminados.php'>Mostrar Alumnos Eliminados</a>' </tfoot>";

                echo "</tbody>";
                echo "<tfoot>

            </table>
            
            </main>"; // cerramos la tabla
            } else {
                echo "<p>Este alumno no fue inscripto a ningún curso</p>"; // si no hay alumnos, mostramos este mensaje
            }
        ?>
        

    </body>
    </html>
