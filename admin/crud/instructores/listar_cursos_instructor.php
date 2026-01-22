<?php
// Cargar path.php
require_once dirname(__DIR__, 3) . '/config/path.php';

// Dependencias
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/config/csrf.php';

// Autenticación
requireLogin();

if (!isAdmin() && !isSuperAdmin()) {
    header('Location: ' . BASE_URL . '/index.php');
    exit();
}

// Conexión
$conn = conectar();

// Obtener ID del instructor
$id_instructor = null;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_instructor"])) {
    $id_instructor = filter_var($_POST["id_instructor"], FILTER_VALIDATE_INT);
} elseif (isset($_GET["id_instructor"])) {
    $id_instructor = filter_var($_GET["id_instructor"], FILTER_VALIDATE_INT);
}

if (!$id_instructor) {
    header('Location: index.php?error=id_faltante');
    exit();
}

// Obtener datos del instructor
$stmt_instructor = $conn->prepare("SELECT nombre, apellido FROM instructores WHERE id_instructor = :id AND activo = 1");
$stmt_instructor->execute([':id' => $id_instructor]);
$instructor = $stmt_instructor->fetch();

if (!$instructor) {
    header('Location: index.php?error=instructor_no_encontrado');
    exit();
}

// Obtener cursos del instructor
$stmt_cursos = $conn->prepare("
    SELECT 
        cursos.id_curso,
        cursos.codigo,
        cursos.nombre_curso,
        cursos.descripcion,
        cursos.cupo,
        cursos.fecha_inicio,
        cursos.fecha_fin,
        turnos.descripcion AS turno
    FROM cursos
    LEFT JOIN turnos ON cursos.id_turno = turnos.id_turno
    WHERE cursos.id_instructor = :id_instructor AND cursos.activo = 1
    ORDER BY cursos.fecha_inicio DESC, cursos.nombre_curso
");
$stmt_cursos->execute([':id_instructor' => $id_instructor]);
$cursos = $stmt_cursos->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cursos del Instructor - CFL 402</title>
    <!-- CSS Global y Alumnos (reutilizado) -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/global.css?v=3.2">
    <link rel="stylesheet" href="../alumnos/alumnos2.css?v=3.2">

</head>
<body class="main_alumnos_body">

    <?php require_once BASE_PATH . '/include/header.php'; ?>
    
    <div class="header-section">
        <h1 class="instructor-title">
            Cursos de <span style="color:var(--primary)"><?= htmlspecialchars($instructor['apellido'] . ', ' . $instructor['nombre']) ?></span>
        </h1>
        <a href="index.php" class="btn-back">
            <img class="svg_lite" src="/cfl_402/assets/svg/arrow-left.svg" alt="<" style="transform: rotate(180deg);" > Volver
        </a>
    </div>

    <?php if (count($cursos) > 0): ?>
        <table class="info_table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre del Curso</th>
                    <th>Descripción</th>
                    <th>Turno</th>
                    <th>Cupo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cursos as $curso): ?>
                    <tr>
                        <td><?= htmlspecialchars($curso['codigo']) ?></td>
                        <td class="text-left"><strong><?= htmlspecialchars($curso['nombre_curso']) ?></strong></td>
                        <td class="text-left"><?= htmlspecialchars($curso['descripcion'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($curso['turno'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($curso['cupo']) ?></td>
                        <td class="td_actions">
                            <form action="../horarios/index.php" method="POST" class="enlinea" title="Ver Horarios">
                                <input type="hidden" name="id_curso" value="<?= htmlspecialchars($curso['id_curso']) ?>">
                                <button type="submit" class="submit-button">
                                    <img class="svg_lite" src="/cfl_402/assets/svg/clock.svg" alt="Horarios">
                                </button>
                            </form>
                            
                            <form action="../planillas/planillas.php" method="POST" class="enlinea" title="Ver Planilla">
                                <input type="hidden" name="id_curso" value="<?= htmlspecialchars($curso['id_curso']) ?>">
                                <button type="submit" class="submit-button">
                                    <img class="svg_lite" src="/cfl_402/assets/svg/file-text.svg" alt="Planilla">
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="form-card" style="text-align: center;">
            <p>Este instructor no tiene cursos asignados actualmente.</p>
        </div>
    <?php endif; ?>

</body>
</html> 
