<?php
// ==========================
//   CONFIGURACIÓN INICIAL
// ==========================
require_once dirname(__DIR__, 3) . '/config/path.php';
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/config/csrf.php';
require_once BASE_PATH . '/include/header.php';

// Autenticación
requireLogin();
if (!isSuperAdmin()) {
    header('Location: ' . BASE_URL . '/index.php');
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
    <title>Usuarios - CFL 402</title>
    <!-- CSS Global, Alumnos (reutilizado) y Modal -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/global.css?v=3.2">
    <link rel="stylesheet" href="../alumnos/alumnos2.css?v=3.2">
    <link rel="stylesheet" href="modal.css?v=<?php echo time(); ?>"> 
</head>
<body class="main_alumnos_body">
    
<?php require_once BASE_PATH . '/include/header.php'; ?>

<h1>Gestión de Usuarios</h1>

<div class="search_container">
    <div class="search_block">

        <div class="search_row">

            <!-- Buscador -->
            <form class="search_form" action="index.php" method="post">
                <input class="search_bar" type="search" name="search" placeholder="Buscar Usuario..." value="<?= htmlspecialchars($_POST['search'] ?? '') ?>">
                <button class="boton_enviar" type="submit">Buscar</button>
            </form>

            <!-- Registrar nuevo alumno -->
            <button id="btnAbrirModal" class="btn-primary">
                <img class="svg_lite" src="<?= BASE_URL ?>/assets/svg/plus_circle.svg" alt="Nuevo">
                Nuevo Usuario
            </button>

        </div>

    </div>
</div>

<div class="main_alumnos" style="flex-direction: column; align-items: center;">

<?php
// ==========================
//   BÚSQUEDA
// ==========================
$input = $_POST["search"] ?? "";

// ==========================
//   PAGINACIÓN
// ==========================
$registros_por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
$offset = ($pagina_actual - 1) * $registros_por_pagina;


// ==========================
//   TOTAL REGISTROS
// ==========================
$stmt_total = $conn->prepare("
    SELECT COUNT(*) FROM usuarios
    WHERE activo='1' AND usuarios.nombre LIKE :nombre
");

$stmt_total->execute([
    ":nombre" => "%$input%"
]);

$total_registros = $stmt_total->fetchColumn();
$total_paginas = ceil($total_registros / $registros_por_pagina);


// ==========================
//   CONSULTA PRINCIPAL
// ==========================
$sql = "
    SELECT usuarios.*, 
    CASE usuarios.rol
        WHEN 0 THEN 'SuperAdmin'
        WHEN 1 THEN 'Administrador'
        WHEN 2 THEN 'Instructor'
        ELSE 'Rol Desconocido'
    END AS rol_text
    FROM usuarios
    WHERE activo = '1'
        AND usuarios.nombre LIKE :nombre
    ORDER BY rol ASC
    LIMIT :limit OFFSET :offset
";

// Use bindValue for limit/offset to ensure they are integers (PDO restriction in emulation mode sometimes)
$consulta = $conn->prepare($sql);
$consulta->bindValue(':nombre', "%$input%", PDO::PARAM_STR);
$consulta->bindValue(':limit', (int)$registros_por_pagina, PDO::PARAM_INT);
$consulta->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
$consulta->execute();

?>

<?php if ($consulta->rowCount() > 0): ?>
    <table class="info_table">
        <thead>
            <tr class="table_header">
                <th>Nombre</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($registro = $consulta->fetch()): ?>
            <tr>
                <td><?= htmlspecialchars($registro['nombre']) ?></td>
                <td><?= htmlspecialchars($registro['rol_text']) ?></td>

                <td class="td_actions">
                    <div class="acciones_wrapper" style="display: flex; gap: 10px; justify-content: center; align-items: center;">
                        <button class="submit-button btnModificarAlumno" data-id="<?= $registro['id'] ?>" title="Modificar">
                             <img class="svg_lite" src="<?= BASE_URL ?>/assets/svg/edit-pencil.svg" alt="Modificar">
                        </button>

                        <form action="bajar.php" method="POST" class="enlinea confirm-delete">
                            <?= getCSRFTokenField() ?>
                            <input type="hidden" name="id" value="<?= $registro['id'] ?>">
                            <button type="submit" class="submit-button" onclick="return confirm('¿Está seguro de eliminar este usuario?');">
                                <img class="svg_lite" src="<?= BASE_URL ?>/assets/svg/trash-can.svg" title="Eliminar">
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="form-card" style="text-align: center;">
        <p>No se encontraron usuarios activos.</p>
    </div>
<?php endif; ?>

<?php
    // Función de paginación simple inline
    if ($total_paginas > 1) {
        echo "<div class='pagination'>";
        
        // Primera página
        echo "<a href='?pagina=1'><img class='svg_lite' src='<?= BASE_URL ?>/assets/svg/first_page.svg' alt='<<'></a>"; // Ajustar path SVG si es necesario
        
        // Anterior
        if ($pagina_actual > 1) {
            echo "<a href='?pagina=" . ($pagina_actual - 1) . "'><img class='svg_lite' src='<?= BASE_URL ?>/assets/svg/arrow-left.svg' alt='<'></a>";
        }

        // Rango
        $rango = 2;
        for ($i = max(1, $pagina_actual - $rango); $i <= min($total_paginas, $pagina_actual + $rango); $i++) {
            $active = ($i == $pagina_actual) ? 'active' : '';
            echo "<a href='?pagina=$i' class='$active'>$i</a>";
        }

        // Siguiente
        if ($pagina_actual < $total_paginas) {
            echo "<a href='?pagina=" . ($pagina_actual + 1) . "'><img class='svg_lite' src='<?= BASE_URL ?>/assets/svg/arrow-right.svg' alt='>'></a>";
        }

        // Última
        echo "<a href='?pagina=$total_paginas'><img class='svg_lite' src='<?= BASE_URL ?>/assets/svg/last_page.svg' alt='>>'></a>";

        echo "</div>";
    }
?>

<div class="eliminados_block" style="margin-top: 40px;">
    <form class="eliminados_form" action="eliminados.php" method="post" style="display: flex; gap: 10px; align-items: center; justify-content: center;">
        <h3 style="margin: 0;">Ver Usuarios Eliminados</h3>
        <button type='submit' class='submit-button'>
            <img class='svg_lite' src='<?= BASE_URL ?>/assets/svg/trash.svg' title='Ver Eliminados' style="width: 24px; height: 24px;">
        </button>
    </form>
</div>

</div> <!-- Fin main content -->

<!-- Inclusión de Modales -->
<?php include 'modal.php'; ?>
<?php include 'modal_modificar.php'; ?>

<!-- Scripts -->
<script src="modal_nuevo.js"></script>
<script src="modal_editar.js"></script>

</body>
</html>