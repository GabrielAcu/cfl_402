<?php
// Cargar path.php
require_once dirname(__DIR__, 3) . '/config/path.php';

// Dependencias
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/include/header.php';

// Autenticación
requireLogin();

if (!isAdmin() && !isSuperAdmin()) {
    header('Location: /cfl_402/index.php');
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
    <link rel="stylesheet" href="instructores.css">
    <title>Cursos del Instructor</title>
</head>
<body class="light">
    <h1>Cursos de <?= htmlspecialchars($instructor['apellido'] . ', ' . $instructor['nombre']) ?></h1>
    
    <div class="acciones-superiores">
        <a href="index.php" class="btn-secondary">← Volver al Listado de Instructores</a>
    </div>

    <hr>
    
    <?php if (count($cursos) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nombre del Curso</th>
                    <th>Descripción</th>
                    <th>Turno</th>
                    <th>Cupo</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cursos as $curso): ?>
                    <tr>
                        <td><?= htmlspecialchars($curso['codigo']) ?></td>
                        <td><?= htmlspecialchars($curso['nombre_curso']) ?></td>
                        <td><?= htmlspecialchars($curso['descripcion'] ?? 'Sin descripción') ?></td>
                        <td><?= htmlspecialchars($curso['turno'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($curso['cupo']) ?></td>
                        <td><?= htmlspecialchars($curso['fecha_inicio'] ?? 'N/A') ?></td>
                        <td><?= htmlspecialchars($curso['fecha_fin'] ?? 'N/A') ?></td>
                        <td>
                            <form action="../cursos/index.php" method="POST" class="enlinea">
                                <input type="hidden" name="id_curso" value="<?= htmlspecialchars($curso['id_curso']) ?>">
                                <button type="submit" class="btn-primary">Ver Detalles</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Este instructor no tiene cursos asignados actualmente.</p>
    <?php endif; ?>
</body>
</html> 
