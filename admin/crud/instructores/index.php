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

// Conexión
$conn = conectar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crud Instructores</title>
    <link rel="stylesheet" href="instructores.css">
</head>
<body>
    <h1>Instructor</h1>

    <div class="search_container">
        <div class="search_block">

            <div class="search_row">
                <form class="search_form" action="/cfl_402/admin/crud/alumnos/index.php" method="post">
                    <input class="search_bar" type="search" name="search" placeholder="Buscar Alumno.."> 
                    <button class="boton_enviar" type="submit"> Buscar </button>
                </form>
                
                <form action="registrar.php" method="post">
                    <button class='boton_enviar' id="register_button"> <img class='svg_lite' src='/crud-alumnos/assest/svg/plus_circle.svg' alt='Eliminar'> Registrar Nuevo Instructor   </button>
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
    <h2>Nuevo Instructor</h2>


    
    <hr>Listado de Instructores<hr>
    <td>
        
    <?php
    $consulta=$conn->query("SELECT * FROM instructores WHERE activo=1");
    if ($consulta->rowCount()>0){
        echo "<table class='info_table'>
                <thead>
                    <tr class='table_header'>
                        <th class='table_th'>nombre</th>
                        <th class='table_th'>apellido</th>
                        <th class='table_th'>dni</th>
                        <th class='table_th'>telefono</th>
                        <th class='table_th'>correo</th>
                        <th class='table_th'>acciones</th>
                        <th  colspan='2' class='table_th_final'>Datos</th>
                    
                    </tr>
                </thead>
                <tbody>";
                while ($registro=$consulta->fetch()){
                    echo "
                    <tr>
                        <td class='td_name'>$registro[nombre]</td>
                        <td class='td_name2'>$registro[apellido]</td>
                        <td class='td_data'>$registro[dni]</td>
                        <td  class='td_data'>$registro[telefono]</td>
                        <td  class='td_data'>$registro[correo]</td>
                        <td class='td_actions'>
                            <form action='modificar_instructor.php' method='POST' class='enlinea'>
                                <input type='hidden' name='id' value=$registro[id_instructor]>
                                <button type='submit' class='submit-button'>
                                    <img class='svg_lite2' src='/cfl_402/assets/svg/pencil.svg' alt='Modificar' title='Modificar'>
                                </button>
                            </form>
                        
                            <form action='eliminar_instructor.php' method='POST' class='enlinea'>
                                <input type='hidden' name='id_instructor' value=$registro[id_instructor]>
                                <input type='hidden' name='id_instructor' value=$registro[id_instructor]>
                                <button type='submit' class='submit-button'>
                                    <img class='svg_lite' src='/cfl_402/assets/svg/trash.svg' alt='Eliminar' title='Eliminar'>
                                </button>
                            </form>
                        </td>

                        


                        <td>
                            <form action='listar_contactos_instructor.php' method='POST' class='enlinea'>
                                <input type='hidden' name='id_instructor' value=$registro[id_instructor]>
                                <input type='hidden' name='tipo' value='instructor'>
                                <button type='submit' class='submit-button'>
                                    <img class='svg_lite' src='/cfl_402/assets/svg/contact.svg' alt='Eliminar' title='Eliminar'>
                                </button>
                            </form>
                        
                        
                            <form action='listar_cursos_instructor.php' method='POST' class='enlinea'>
                                <input type='hidden' name='id_instructor' value=$registro[id_instructor]>
                                <button type='submit' class='submit-button'>
                                    <img class='svg_lite' src='/cfl_402/assets/svg/file.svg' alt='Eliminar' title='Eliminar'>
                                </button>
                            </form>
                        </td>
                    </tr>";
        } "</tbody></table>";
    }else {
        echo "<p>Aún no existen Instructores</p>";
    }
    ?>

    <?php

    echo"
    <div class='eliminados'>

        <div class='block_text'>
            <h3 class='eliminados_title'> Eliminados </h3>
                <p class='light_text'> Gestionar Instructores Eliminados </p>
        </div>

        <form action='recuperar_instructor.php' method='POST' class='form_eliminado'>
                <input type='hidden' name='id_instructor' value=$registro[id_instructor]>
                <button type='submit' class='submit-button'>
                    <img class='svg_lite' src='/cfl_402/assets/svg/red_trash.svg' alt='Eliminar' title='Eliminar'>
                </button>      
        </form>
    </div>

    ?>";
    ?>
</body>
</html>
