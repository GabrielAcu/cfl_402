<?php
require_once dirname(__DIR__, 3) . '/config/path.php';
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';

requireLogin();
if (!isAdmin() && !isSuperAdmin()) {
    header('Location: /cfl_402/index.php');
    exit();
}

$conn = conectar();

// Obtener ID de curso si viene por GET o POST
$id_curso_filtro = $_REQUEST['id_curso'] ?? null;

$sql = "
    SELECT c.id_curso, c.nombre_curso, c.codigo, c.fecha_inicio, c.fecha_fin, 
           CONCAT(i.apellido, ', ', i.nombre) as instructor,
           t.descripcion as turno
    FROM cursos c
    LEFT JOIN instructores i ON c.id_instructor = i.id_instructor
    LEFT JOIN turnos t ON c.id_turno = t.id_turno
    WHERE c.activo = 1
";

if ($id_curso_filtro) {
    $sql .= " AND c.id_curso = :id_curso";
}

$sql .= " ORDER BY c.nombre_curso";

$stmt = $conn->prepare($sql);

if ($id_curso_filtro) {
    $stmt->bindValue(':id_curso', $id_curso_filtro, PDO::PARAM_INT);
}

$stmt->execute();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planillas y Reportes - CFL 402</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/global.css?v=<?php echo time(); ?>">
    <!-- Reutilizamos estilos de alumnos para tabla y cards -->
    <link rel="stylesheet" href="../alumnos/alumnos2.css?v=<?php echo time(); ?>">
    <style>
        .report-section {
            background: var(--card-bg);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid var(--border);
        }
        .report-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .report-card {
            background: var(--bg);
            border: 1px solid var(--border);
            padding: 15px;
            border-radius: 8px;
            transition: transform 0.2s;
        }
        .report-card:hover {
            transform: translateY(-2px);
            border-color: var(--accent);
        }
        .report-title {
            font-size: 1.1rem;
            font-weight: bold;
            color: var(--text);
            margin-bottom: 5px;
        }
        .report-meta {
            font-size: 0.9rem;
            color: var(--text-muted);
            margin-bottom: 15px;
        }
        .btn-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .btn-report {
            flex: 1;
            padding: 8px;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            white-space: nowrap;
        }
    </style>
</head>
<body class="main_alumnos_body">
    <?php require_once BASE_PATH . '/include/header.php'; ?>

    <h1>Gestión de Planillas y Actas</h1>
    <p style="text-align:center; color: var(--text);">Seleccione un curso para generar sus documentos.</p>

    <div class="search_container" style="max-width: 1200px;">
        <div class="report-grid" style="width: 100%;">
            <?php while ($curso = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                <div class="report-card">
                    <div class="report-title"><?= htmlspecialchars($curso['nombre_curso']) ?> (<?= $curso['codigo'] ?>)</div>
                    <div class="report-meta">
                        Instructor: <strong><?= htmlspecialchars($curso['instructor'] ?? 'Sin Asignar') ?></strong><br>
                        Turno: <?= htmlspecialchars($curso['turno']) ?>
                    </div>
                    
                    <div class="btn-group">
                        <!-- Ficha de Curso (Ver primero) -->
                        <form action="ver_ficha.php" method="POST" style="flex:1;">
                            <input type="hidden" name="id_curso" value="<?= $curso['id_curso'] ?>">
                            <button type="submit" class="submit-button btn-primary btn-report" title="Ver Ficha">
                                <img src="<?= BASE_URL ?>/assets/svg/file-excel.svg" class="svg_lite" style="filter: brightness(0) invert(1);"> 
                                Ficha
                            </button>
                        </form>

                        <!-- Presentismo (HTML Print) -->
                        <form action="presentismo.php" method="POST" target="_blank" style="flex:1;">
                            <input type="hidden" name="id_curso" value="<?= $curso['id_curso'] ?>">
                            <button type="submit" class="submit-button btn-secondary btn-report" title="Ver Presentismo">
                                <img src="<?= BASE_URL ?>/assets/svg/calendar-check.svg" class="svg_lite" style="filter: invert(1);"> 
                                Asistencia
                            </button>
                        </form>

                        <!-- Acta (HTML Print) -->
                        <form action="acta_examen.php" method="POST" target="_blank" style="flex:1;">
                            <input type="hidden" name="id_curso" value="<?= $curso['id_curso'] ?>">
                            <button type="submit" class="submit-button btn-secondary btn-report" title="Acta de Examen">
                                <img src="<?= BASE_URL ?>/assets/svg/file-certificate.svg" class="svg_lite" style="filter: invert(1);"> 
                                Acta
                            </button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

</body>
</html>
