<?php
require_once dirname(__DIR__, 3) . '/config/path.php';
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/config/csrf.php';

requireLogin();
if (!isAdmin() && !isSuperAdmin()) {
    header('Location: ' . BASE_URL . '/index.php');
    exit();
}

$conn = conectar();
// Solo admin puede entrar aquí.
// Validamos ID curso
$id_curso = $_POST["id_curso"] ?? $_GET["id_curso"] ?? null;

if (!$id_curso) {
    // Si no hay curso, redirect a listado de cursos
    header('Location: ' . BASE_URL . '/admin/crud/cursos/index.php');
    exit();
}

// Datos del curso
$stmt = $conn->prepare("
    SELECT c.codigo, c.nombre_curso, c.descripcion, t.descripcion as turno
    FROM cursos c
    LEFT JOIN turnos t ON c.id_turno = t.id_turno
    WHERE c.id_curso = ?
");
$stmt->execute([$id_curso]);
$curso = $stmt->fetch();

if (!$curso) {
    die("Curso no encontrado.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horarios - CFL 402</title>
    <!-- CSS Global -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/global.css?v=3.2">
    <!-- Reutilizamos estilos de alumnos para tabla y cards -->
    <link rel="stylesheet" href="../alumnos/alumnos2.css?v=3.2"> 

</head>
<body class="main_alumnos_body">

<?php require_once BASE_PATH . '/include/header.php'; ?>

<h1>Gestión de Horarios</h1>

<div class="course-info">
    Curso: <strong><?= htmlspecialchars($curso['nombre_curso']) ?></strong> 
    (<?= htmlspecialchars($curso['codigo']) ?>) - 
    Turno: <strong><?= htmlspecialchars($curso['turno'] ?? 'No Asignado') ?></strong>
</div>

<!-- Mensajes -->
<?php if (isset($_GET['ok'])): ?>
    <div style="text-align:center; color: green; margin-bottom: 15px; font-weight:bold;">Acción realizada con éxito.</div>
<?php endif; ?>
<?php if (isset($_GET['error'])): ?>
    <div style="text-align:center; color: red; margin-bottom: 15px; font-weight:bold;">Ocurrió un error.</div>
<?php endif; ?>

<main class="main_alumnos" style="flex-direction: column; align-items: center;">

    <!-- FORMULARIO AGREGAR HORARIO -->
    <div class="form-card">
        <h2 class="subtitle">Agregar Nuevo Horario</h2>
        <form action="crear_horario.php" method="POST" class="form-row">
            <?= getCSRFTokenField() ?>
            <input type="hidden" name="id_curso" value="<?= htmlspecialchars($id_curso) ?>">

            <select name="dia_semana" required>
                <option value="">-- Día --</option>
                <option value="Lunes">Lunes</option>
                <option value="Martes">Martes</option>
                <option value="Miércoles">Miércoles</option>
                <option value="Jueves">Jueves</option>
                <option value="Viernes">Viernes</option>
                <option value="Sábado">Sábado</option>
            </select>

            <input type="time" name="hora_inicio" required title="Hora Inicio">
            <span style="font-weight:bold;">a</span>
            <input type="time" name="hora_fin" required title="Hora Fin">

            <button type="submit" class="btn-primary">
                <img class="svg_lite" src="<?= BASE_URL ?>/assets/svg/plus_circle.svg" alt="+"> Agregar
            </button>
        </form>
    </div>

    <!-- LISTADO -->
    <?php
        $stmt = $conn->prepare("SELECT * FROM horarios WHERE id_curso = ? ORDER BY FIELD(dia_semana, 'Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'), hora_inicio ASC");
        $stmt->execute([$id_curso]);
        $horarios = $stmt->fetchAll();
    ?>

    <?php if (count($horarios) > 0): ?>
        <table class="info_table" style="max-width: 800px;">
            <thead>
                <tr class="table_header">
                    <th>Día</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($horarios as $h): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($h['dia_semana']) ?></strong></td>
                        <td><?= htmlspecialchars(substr($h['hora_inicio'], 0, 5)) ?></td>
                        <td><?= htmlspecialchars(substr($h['hora_fin'], 0, 5)) ?></td>
                        <td class="td_actions">
                            <form action="eliminar_horario.php" method="POST" class="enlinea confirm-delete">
                                <?= getCSRFTokenField() ?>
                                <input type="hidden" name="id_horario" value="<?= $h['id_horario'] ?>">
                                <input type="hidden" name="id_curso" value="<?= $id_curso ?>">
                                <button type="submit" class="submit-button" onclick="return confirm('¿Eliminar este horario?');">
                                    <img class="svg_lite" src="<?= BASE_URL ?>/assets/svg/trash-can.svg" title="Eliminar">
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align: center; margin-top: 20px;">No hay horarios registrados para este curso.</p>
    <?php endif; ?>

    <br>
    <a href="../cursos/index.php" style="color: var(--accent); text-decoration: none; font-weight: bold; font-size: 1.1rem;">
        &larr; Volver a Cursos
    </a>

</main>

</body>
</html>