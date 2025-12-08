<?php
// ==========================
//   CONFIGURACIÓN INICIAL
// ==========================
require_once dirname(__DIR__, 3) . '/config/path.php';
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/include/header.php';
// require_once 'layouts.php';

// Autenticación
requireLogin();
if (!isSuperAdmin()) {
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
    <link rel="stylesheet" href="usuarios.css">

</head>
<body>
    

<h1>Usuarios</h1>

<div class="search_container">
    <div class="search_block">

        <div class="search_row">

            <!-- Buscador -->
            <form class="search_form" action="/cfl_402/admin/crud/usuarios/index.php" method="post">
                <input class="search_bar" type="search" name="search" placeholder="Buscar Usuario..">
                <button class="boton_enviar" type="submit">Buscar</button>
            </form>

            <!-- Registrar nuevo alumno -->
           
                    <button id="btnAbrirModal" class="btn-primary">
                        <img class="svg_lite" src="/cfl_402/assets/svg/plus_circle.svg" alt="Nuevo">
                        Nuevo Usuario
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

<hr>

<h2>Listado de Usuarios</h2>
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
    SELECT COUNT(*) FROM usuarios
    WHERE activo='1' AND (usuarios.nombre LIKE :nombre
        OR usuarios.contrasenia LIKE :contrasenia)
");

$stmt_total->execute([
    ":nombre" => "%$input%",
    ":contrasenia" => "%$input%",

]);

$total_registros = $stmt_total->fetchColumn();
$total_paginas = ceil($total_registros / $registros_por_pagina);


// ==========================
//   CONSULTA PRINCIPAL
// ==========================
$sql = "
    SELECT usuarios.*, usuarios.nombre, usuarios.contrasenia, CASE usuarios.rol
    WHEN 0 THEN 'SuperAdmin'
    WHEN 1 THEN 'Administrador'
    WHEN 2 THEN 'Instructor'
    ELSE 'Rol Desconocido'
    END AS rol_text
    FROM usuarios
    WHERE activo = '1'
        AND (usuarios.nombre LIKE :nombre
            OR usuarios.contrasenia LIKE :contrasenia)
    ORDER BY rol ASC
    LIMIT :registros_por_pagina OFFSET :offset
";

$consulta = $conn->prepare($sql);

// IMPORTANTE → NO usamos bindParam duplicado, solo execute([])

$consulta->execute([
    ":nombre" => "%$input%",
    ":contrasenia" => "%$input%",
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
                <th>Contraseña</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
    ";

    while ($registro = $consulta->fetch()) : ?>
    <tr>
        <td><?= $registro['nombre'] ?></td>
        <td><?= $registro['contrasenia'] ?></td>
        <td><?= $registro['rol_text'] ?></td>


    
            <div class="acciones_wrapper">

       
            </div>
      

        <td class="td_actions2">

            
            <button class="btnModificarAlumno" <?php echo "data-id=$registro[id]"?> >
                <img class="svg_lite" src="/cfl_402/assets/svg/pencil.svg" title="Modificar">
            </button>

            <form action="bajar.php" method="POST" class="enlinea confirm-delete">
                <input type="hidden" name="id" value="<?= $registro['id'] ?>">
                <button type="submit" class="submit-button">
                    <img class="svg_lite" src="/cfl_402/assets/svg/trash.svg" title="Eliminar">
                </button>
            </form>

            <!-- <form action="../inscripciones/index.php" method="POST" class="enlinea">
                <input type="hidden" name="tipo" value="alumno">
                <input type="hidden" name="id_alumno" value=" $registro['id_alumno'] ">
                <input type="hidden" name="volver" value="alumnos">
                <button type="submit" class="submit-button">
                    <img class="svg_lite" src="/cfl_402/assets/svg/plus.svg" title="Inscribir a un curso">
                </button>
            </form> -->

        </td>
    </tr>
    
    
<?php endwhile; 
echo"
        </tbody>
    </table>
    </main>";
    
}
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
    include 'modal_modificar.php';
       
?>

<div class="eliminados_block">
    <form class="eliminados_form" action="eliminados.php" method="post">
        <button type='submit' class='submit-button'>

        <h3> Ver Usuarios Eliminados</h3>
            <img class='svg_lite' src='/cfl_402/assets/svg/trash.svg' title='Contactos'>
        </button>
    </form>
</div>
<script src="delete.js"></script>

<script src="modal_nuevo.js"></script>
<script src="modal_detalles.js"> </script>
<script src="carteles.js"></script>