<?php
require_once dirname(__DIR__, 3) . '/config/path.php';
// Dependencias
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/config/csrf.php';
require_once BASE_PATH . '/include/header.php';
// Seguridad
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
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/global.css?v=3.6">
    <link rel="stylesheet" href="cursos.css?v=3.6">
    <link rel="stylesheet" href="modal.css?v=3.6">
    <title>Cursos - CFL 402</title>
</head>
<body class="main_cursos_body">
    <?php require_once BASE_PATH . '/include/header.php'; ?>
    
    <h1>Cursos</h1>

    <div class="search_container">
        <div class="search_block">
            <div class="search_row">
                <!-- Buscador -->
                <form class="search_form" action="index.php" method="POST">
                    <input class="search_bar" type="search" name="dato" placeholder="Buscar Curso...">
                    <button class="boton_enviar" type="submit">Buscar</button>
                </form>

                <!-- Registrar nuevo curso -->
                <button id="btnAbrirModal" class="btn-primary">
                    <img class="svg_lite" src="/cfl_402/assets/svg/plus_circle.svg" alt="Nuevo">
                    Nuevo Curso
                </button>
            </div>
        </div>
    </div>

    <!-- sección para mostrar la lista de cursos -->
    <h2>Listado de Cursos</h2> 
    
    <!-- Contenido principal -->
     <div class="pagination">
        <?php
        include 'logica_cursos.php'; // incluye la lógica para obtener los cursos y la paginación
        include 'paginacion.php'; // incluye la función de paginación
        include 'tabla_cursos.php'; // incluye la tabla que muestra los cursos
        ?>
     </div>
    <div class="pagination">
        <?php echo paginacion($pagina_actual, $total_paginas); ?>
    </div>
    
    <?php include 'modal.php'; //incluye el modal para crear un nuevo curso  ?>
    <?php include 'modalDetalles.php'; ?>

    <script src="modalCurso.js"></script>
    <script src="modal_detalles.js"></script>
</body>
</html>