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
    <title>Usuarios Eliminados - CFL 402</title>
    <!-- CSS Global y Alumnos -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/global.css?v=3.2">
    <link rel="stylesheet" href="../alumnos/alumnos2.css?v=3.2">
</head>
<body class="main_alumnos_body">

<?php require_once BASE_PATH . '/include/header.php'; ?>
    
<h1>Usuarios Eliminados</h1>
    
<div class="search_container">
    <div class="search_block">
        <div class="search_row">
            <!-- Volver -->
            <form action="index.php" method="get">
                 <button class="btn-primary" type="submit">
                    <img class="svg_lite" src="/cfl_402/assets/svg/arrow-left.svg" alt="<" style="transform: rotate(180deg);">
                    Volver al Listado
                 </button>
            </form>
        </div>
    </div>
</div>

<div class="main_alumnos" style="flex-direction: column; align-items: center;">

<?php
// ==========================
//   PAGINACIÓN
// ==========================
$registros_por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;
$offset = ($pagina_actual - 1) * $registros_por_pagina;


// ==========================
//   TOTAL REGISTROS
// ==========================
$stmt_total = $conn->prepare("SELECT COUNT(*) FROM usuarios WHERE activo='0'");
$stmt_total->execute();

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
    WHERE activo = '0'
    ORDER BY rol ASC
    LIMIT :limit OFFSET :offset
";

$consulta = $conn->prepare($sql);
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
                    <form action="restaurar.php" method="POST" class="enlinea confirm-restore">
                        <?= getCSRFTokenField() ?>
                        <input type="hidden" name="id" value="<?= $registro['id'] ?>">
                        <button type="submit" class="submit-button" title="Restaurar Usuario" onclick="return confirm('¿Restaurar este usuario?');">
                            <img class="svg_lite" src="/cfl_402/assets/svg/restore.svg" alt="Restaurar">
                        </button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <div class="form-card" style="text-align: center;">
        <p>No hay usuarios eliminados.</p>
    </div>
<?php endif; ?>

<?php
    // Paginación
    if ($total_paginas > 1) {
        echo "<div class='pagination'>";
        
        echo "<a href='?pagina=1'><img class='svg_lite' src='/cfl_402/assets/svg/first_page.svg'></a>";
        
        if ($pagina_actual > 1) {
            echo "<a href='?pagina=" . ($pagina_actual - 1) . "'><img class='svg_lite' src='/cfl_402/assets/svg/arrow-left.svg'></a>";
        }

        $rango = 2;
        for ($i = max(1, $pagina_actual - $rango); $i <= min($total_paginas, $pagina_actual + $rango); $i++) {
            $active = ($i == $pagina_actual) ? 'active' : '';
            echo "<a href='?pagina=$i' class='$active'>$i</a>";
        }

        if ($pagina_actual < $total_paginas) {
            echo "<a href='?pagina=" . ($pagina_actual + 1) . "'><img class='svg_lite' src='/cfl_402/assets/svg/arrow-right.svg'></a>";
        }

        echo "<a href='?pagina=$total_paginas'><img class='svg_lite' src='/cfl_402/assets/svg/last_page.svg'></a>";
        echo "</div>";
    }
?>

</div> <!-- Fin Main -->

</body>
</html>