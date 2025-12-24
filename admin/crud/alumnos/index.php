<?php
// ==========================
//   CONFIGURACIÓN INICIAL
// ==========================
require_once dirname(__DIR__, 3) . '/config/path.php';
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/config/csrf.php';
require_once BASE_PATH . '/include/header.php';
require_once 'layouts.php';



// Autenticación
requireLogin();
// Si no es admin ni superadmin, afuera del panel
if (!isAdmin() && !isSuperAdmin()) {
    header('Location: /cfl_402/index.php');
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
    <title>Document</title>
    <link rel="stylesheet" href="modal.css">
    <link rel="stylesheet" href="alumnos2.css">
</head>
<body class="light">
    <h1>Alumnos</h1>

    <div class="search_container">
        <div class="search_block">
            <div class="search_row">
                            <!-- Buscador -->
                <form class="search_form" action="#" method="post">
                    <input class="search_bar" type="search" name="search" placeholder="Buscar Alumno..">
                    <button class="boton_enviar" type="submit">Buscar</button>
                </form>

                    <!-- Registrar nuevo alumno -->
                <button id="btnAbrirModal" class="btn-primary">
                    <img class="svg_lite" src="/cfl_402/assets/svg/plus_circle.svg" alt="Nuevo">Alumno
                </button>
            </div>
        </div>
    </div>


    <h2>Listado de Alumnos</h2>
    <button class="btn-faq" id="btnAbrirFaq">
        <img class="svg_faq" src="/cfl_402/assets/svg/faq.svg" alt="FAQ">
    </button>

    <!-- Contenido principal -->
    <div class="pagination">
    

        <?php
        require_once 'config_alumnos.php';

        require_once PATH_ALUMNOS . '/logica_alumnos.php'; // incluye la lógica para obtener los cursos y la paginación
        require_once PATH_ALUMNOS . '/tabla_alumnos.php'; // incluye la función de paginación
        require_once PATH_ALUMNOS . '/paginacion.php'; // incluye la tabla que muestra los cursos
        ?>
     </div>
    <div class="pagination">
        <?php echo paginacion($total_paginas, $pagina_actual); ?>
    </div>

     
<?php
    include 'modal.php'; //incluye el modal para crear un nuevo curso 
    include 'modal_modificar.php';
    include 'modalDetalles.php';
    include 'modal_faq.php';
       
?>

<div class="eliminados_block">
    <form class="eliminados_form" action="eliminados.php" method="post">
        <button type='submit' class='submit-button'>

        <h3> Ver Alumnos Eliminados</h3>
            <img class='svg_lite' src='/cfl_402/assets/svg/trash.svg' title='Contactos'>
        </button>
    </form>
</div>
<script src="delete.js"></script>

<script src="modal_nuevo.js"></script>
<script src="modal_detalles.js"> </script>
<script src="modal_ver.js"></script>
<script src="modal_faq.js"></script>
    
    </body>
</html>