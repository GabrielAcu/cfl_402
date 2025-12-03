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

// Conexión
$conn = conectar();

?>

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
            <form action="registrar.php" method="post">
                <button class="boton_enviar" id="register_button">
                    <img class="svg_lite" src="/cfl_402/assets/svg/plus_circle.svg" alt="Nuevo">
                    Nuevo Alumno
                </button>
            </form>

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
    WHERE (alumnos.nombre LIKE :nombre
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
?>
<script src="delete.js"></script>