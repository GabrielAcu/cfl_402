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

?> 
<h1>Alumnos</h1>
    
        
    <div class="search_container">
        <div class="search_block">

            <div class="search_row">
                <form class="search_form" action="/cfl_402/admin/crud/alumnos/index.php" method="post">
                    <input class="search_bar" type="search" name="search" placeholder="Buscar Alumno.."> 
                    <button class="boton_enviar" type="submit"> Buscar </button>
                </form>
                
                <form action="registrar.php" method="post">
                    <button class='boton_enviar' id="register_button"> <img class='svg_lite' src='/crud-alumnos/assest/svg/plus_circle.svg' alt='Eliminar'> Registrar Nuevo Alumno   </button>
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
        <h2>Listado de Alumnos</h2> <!-- sección para mostrar la lista de alumnos -->
        <link rel="stylesheet" href="alumnos.css">
        
        <?php
            if (isset($_POST['search'])) {
               $input=$_POST["search"]; 
            } else {
                $input="";
            }
            // texto de la consulta SQL con marcadores de posición
            $sql="SELECT alumnos.*, alumnos.nombre, alumnos.apellido, alumnos.dni, alumnos.telefono FROM alumnos
                WHERE (alumnos.nombre LIKE :nombre 
                OR alumnos.apellido LIKE :apellido 
                OR alumnos.dni LIKE :dni
                OR alumnos.telefono LIKE :telefono)";
            
            $consulta=$conn->prepare($sql); 
            // $consulta->execute([':nombre'=>$nombre,':apellido'=>$apellido,':dni'=>$dni,':nacimiento' =>$nacimiento, ':correo' =>$correo, ':telefono'=>$telefono, ':direccion' =>$domicilio, ':localidad' => $localidad, ':cp' => $postal, ':activo'=> $activo, ':autos' =>$autos, ':patente' =>$patente, ':observaciones'=>$observaciones]);
            $consulta->execute( [
                ":nombre"=>"%$input%", 
                ":apellido"=>"%$input%", 
                ":dni"=>"%$input%", 
                ":telefono"=>"%$input%" ]); // consulta para obtener todos los alumnos
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
                        <th class='table_th_final'>Acciones</th>
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
                            <form action='/cfl_402/cruds/contacto/listar_contactos.php' method='POST' class='enlinea'>
                                <input type='hidden' name='id_alumno' value='$registro[id_alumno]'>
                                <input type='hidden' name='tipo' value='alumno'>
                                <button type='submit' class='submit-button'>
                                    <img class='svg_lite' src='/cfl_402/assets/svg/contact.svg' alt='Contactos' title='Contactos'>
                                </button>
                            </form>
                        

                            <form action='/cfl_402/cruds/crud_alumnos/inscripciones_alumno.php' method='POST' class='enlinea'>
                                <input type='hidden' name='id_alumno' value='$registro[id_alumno]'>
                                    <button type='submit' class='submit-button'>
                                     <img class='svg_lite' src='/cfl_402/assets/svg/book.svg' alt='Ver contactos' title='Cursos'>
                                    </button>
                            </form>
                        </td>

                        <!-- ACCIONES -->
                        <td class='td_actions' title='Eliminar Alumno'>
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
                        </td>

                        <td class='td_actions3' title='Inscribir a un curso'>
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
                "<tfoot> <a href='alumnos_eliminados.php'>Mostrar Alumnos Eliminados</a>' </tfoot>";

                echo "</tbody>";
                echo "<tfoot>;

            </table>
            
            </main>"; // cerramos la tabla
            } else {
                echo "<p>Aún no existen alumnos</p>"; // si no hay alumnos, mostramos este mensaje
            }
        ?>