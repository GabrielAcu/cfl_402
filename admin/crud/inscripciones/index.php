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

// Validar entrada
$tipo = $_POST["tipo"] ?? $_GET["tipo"] ?? null;
$volver = $_POST["volver"] ?? $_GET["volver"] ?? null;

// Si no hay parametros, redirigir al panel principal
if (!$tipo) {
    header('Location: ' . BASE_URL . '/admin/index.php');
    exit();
}

$map = [
    "curso"      => "id_curso",
    "alumno"     => "id_alumno",
    "instructor" => "id_instructor"
];

if (!isset($map[$tipo])) {
    die("Error: Tipo inválido en inscripciones.");
}

$param = $map[$tipo];
$id = $_POST[$param] ?? $_GET[$param] ?? null;

if (!$id) {
    die("Error: Falta ID ($param).");
}

$id = intval($id);

// Mensajes de Sesión
$mensaje = $_SESSION['mensaje'] ?? '';
unset($_SESSION['mensaje']);

// ==========================
// CARGA DE DATOS SEGÚN TIPO
// ==========================

switch ($tipo) {
    case "alumno":
        $stmt = $conn->prepare("SELECT id_alumno, apellido, nombre FROM alumnos WHERE id_alumno = ?");
        $stmt->execute([$id]);
        $origen = $stmt->fetch();
        if (!$origen) die("Alumno no encontrado.");
        $titulo = "Alumno: " . $origen["apellido"] . ", " . $origen["nombre"];

        // Cursos disponibles
        $cursos = $conn->query("SELECT * FROM cursos WHERE activo = 1 ORDER BY nombre_curso")->fetchAll();

        // Inscripciones previas del alumno (con nombre del curso)
        $inscripciones = $conn->prepare("
            SELECT i.id_inscripcion, c.nombre_curso, i.fecha_inscripcion, c.id_curso
            FROM inscripciones i
            JOIN cursos c ON i.id_curso = c.id_curso
            WHERE i.id_alumno = ?
        ");
        $inscripciones->execute([$id]);
    break;

    case "curso":
        $stmt = $conn->prepare("SELECT id_curso, nombre_curso FROM cursos WHERE id_curso = ?");
        $stmt->execute([$id]);
        $origen = $stmt->fetch();
        if (!$origen) die("Curso no encontrado.");
        $titulo = "Curso: " . $origen["nombre_curso"];

        // Alumnos disponibles
        $alumnos = $conn->query("SELECT * FROM alumnos WHERE activo = 1 ORDER BY apellido, nombre")->fetchAll();

        // Inscriptos en este curso
        $inscripciones = $conn->prepare("
            SELECT i.id_inscripcion, a.apellido, a.nombre, i.fecha_inscripcion, a.id_alumno
            FROM inscripciones i
            JOIN alumnos a ON i.id_alumno = a.id_alumno
            WHERE i.id_curso = ?
        ");
        $inscripciones->execute([$id]);
    break;

    case "instructor":
        $stmt = $conn->prepare("SELECT id_instructor, apellido, nombre FROM instructores WHERE id_instructor = ?");
        $stmt->execute([$id]);
        $origen = $stmt->fetch();
        if (!$origen) die("Instructor no encontrado.");
        $titulo = "Instructor: " . $origen["apellido"] . ", " . $origen["nombre"];

        // Cursos asignados (pivot)
        $cursos_asignados_stmt = $conn->prepare("
             SELECT c.id_curso, c.nombre_curso
             FROM cursos c
             WHERE c.id_instructor = ?
        ");
        $cursos_asignados_stmt->execute([$id]);
        $cursos_asignados = $cursos_asignados_stmt->fetchAll();

        // Cursos disponibles para asignar (activos)
        $cursos_disponibles = $conn->query("
            SELECT id_curso, nombre_curso 
            FROM cursos 
            WHERE activo = 1
        ")->fetchAll();

        // Reutilizamos $inscripciones para mostrar la lista de cursos asignados
        // Nota: Esto no son "inscripciones" de alumnos, sino asignaciones docente-curso.
        // Adaptamos la vista de tabla para esto.
        $es_instructor = true;
    break;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscripciones - CFL 402</title>
    <!-- CSS Global -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/global.css?v=3.2">
    <!-- CSS Panel/Alumnos (Reutilizado) -->
    <link rel="stylesheet" href="../alumnos/alumnos2.css?v=3.2">
    <style>
        .form-card {
            background: var(--card-bg);
            padding: 20px;
            border-radius: 12px;
            border: 1px solid var(--border);
            margin-bottom: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .form-row {
            display: flex;
            gap: 10px;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
        }
        select {
            padding: 10px;
            border-radius: 6px;
            border: 1px solid var(--border);
            background: var(--bg);
            color: var(--text);
            min-width: 250px;
        }
        h3 { color: var(--accent); margin-bottom: 15px; text-align: center; }
    </style>
</head>
<body class="main_alumnos_body">

<?php require_once BASE_PATH . '/include/header.php'; ?>

<h1>Gestión de Inscripciones</h1>
<h2><?= htmlspecialchars($titulo) ?></h2>

<?php if ($mensaje): ?>
    <div style="max-width: 800px; margin: 0 auto 20px; padding: 15px; background: rgba(59, 130, 246, 0.1); color: var(--accent); border: 1px solid var(--accent); border-radius: 8px; text-align: center;">
        <?= htmlspecialchars($mensaje) ?>
    </div>
<?php endif; ?>

<main class="main_alumnos" style="flex-direction: column; align-items: center;">

    <!-- FORMULARIO DE INSCRIPCIÓN / ASIGNACIÓN -->
    <div class="search_container" style="margin-bottom: 10px;">
        <div class="form-card">
            <?php if ($tipo === "alumno"): ?>
                <h3>Inscribir a Curso</h3>
                <form action="crear.php" method="POST" class="form-row">
                    <?= getCSRFTokenField() ?>
                    <input type="hidden" name="tipo" value="alumno">
                    <input type="hidden" name="id_alumno" value="<?= $id ?>">
                    
                    <select name="id_curso" required>
                        <option value="">-- Seleccionar Curso --</option>
                        <?php foreach ($cursos as $c): ?>
                            <option value="<?= $c["id_curso"] ?>"><?= htmlspecialchars($c["nombre_curso"]) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <button type="submit" class="btn-primary">
                        <img class="svg_lite" src="/cfl_402/assets/svg/plus_circle.svg" alt="+"> Inscribir
                    </button>
                </form>

            <?php elseif ($tipo === "curso"): ?>
                <h3>Inscribir Alumno</h3>
                <form action="crear.php" method="POST" class="form-row">
                    <?= getCSRFTokenField() ?>
                    <input type="hidden" name="tipo" value="curso">
                    <input type="hidden" name="id_curso" value="<?= $id ?>">

                    <select name="id_alumno" required>
                        <option value="">-- Seleccionar Alumno --</option>
                        <?php foreach ($alumnos as $a): ?>
                            <option value="<?= $a["id_alumno"] ?>">
                                <?= htmlspecialchars($a["apellido"] . ", " . $a["nombre"]) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <button type="submit" class="btn-primary">
                        <img class="svg_lite" src="/cfl_402/assets/svg/plus_circle.svg" alt="+"> Inscribir
                    </button>
                </form>

            <?php elseif ($tipo === "instructor"): ?>
                <h3>Asignar Nuevo Curso</h3>
                 <form action="crear.php" method="POST" class="form-row">
                    <?= getCSRFTokenField() ?>
                    <input type="hidden" name="tipo" value="instructor">
                    <input type="hidden" name="id_instructor" value="<?= $id ?>">

                    <select name="id_curso" required>
                        <option value="">-- Seleccionar Curso --</option>
                        <?php foreach ($cursos_disponibles as $c): ?>
                            <option value="<?= $c["id_curso"] ?>"><?= htmlspecialchars($c["nombre_curso"]) ?></option>
                        <?php endforeach; ?>
                    </select>

                    <button type="submit" class="btn-primary">
                        <img class="svg_lite" src="/cfl_402/assets/svg/plus_circle.svg" alt="+"> Asignar
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <!-- LISTADO (TABLA) -->
    <?php if (isset($es_instructor) && $es_instructor): ?>
        <!-- TABLA ESPECIAL PARA INSTRUCTORES (CURSOS ASIGNADOS) -->
         <table class="info_table">
            <thead>
                <tr class="table_header">
                    <th class="text-left">Curso Asignado</th>
                    <th>ID Curso</th>
                    <th>Acciones</th> <!-- Para desasignar si fuera necesario -->
                </tr>
            </thead>
            <tbody>
            <?php foreach ($cursos_asignados as $c): ?>
                <tr>
                    <td class="text-left"><strong><?= htmlspecialchars($c["nombre_curso"]) ?></strong></td>
                    <td><?= $c["id_curso"] ?></td>
                    <td class="td_actions">
                        <!-- Implementar desasignar si se requiere -->
                        <span style="color: grey;">-</span>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

    <?php else: ?>
        <!-- TABLA STANDARD INSCRIPCIONES (ALUMNOS <-> CURSOS) -->
        <table class="info_table">
            <thead>
                <tr class="table_header">
                    <th>ID</th>
                    <?php if ($tipo !== "curso"): ?><th class="text-left">Curso</th><?php endif; ?>
                    <?php if ($tipo !== "alumno"): ?><th class="text-left">Alumno</th><?php endif; ?>
                    <th>Fecha Inscripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($inscripciones as $i): ?>
                <tr>
                    <td><?= $i["id_inscripcion"] ?></td>

                    <?php if ($tipo !== "curso"): ?>
                        <td class="text-left"><strong><?= htmlspecialchars($i["nombre_curso"] ?? '-') ?></strong></td>
                    <?php endif; ?>

                    <?php if ($tipo !== "alumno"): ?>
                        <td class="text-left"><strong><?= htmlspecialchars(($i["apellido"] ?? '') . " " . ($i["nombre"] ?? '')) ?></strong></td>
                    <?php endif; ?>

                    <td><?= htmlspecialchars($i["fecha_inscripcion"] ?? '-') ?></td>
                    
                    <td class="td_actions">
                        <div class="acciones_wrapper">
                            <form action="editar.php" method="GET" class="enlinea">
                                <input type="hidden" name="id" value="<?= $i['id_inscripcion'] ?>">
                                <button type="submit" class="submit-button">
                                    <img class="svg_lite" src="/cfl_402/assets/svg/edit-pencil.svg" title="Editar Inscripción">
                                </button>
                            </form>

                            <form action="eliminar.php" method="POST" class="enlinea confirm-delete">
                                <?= getCSRFTokenField() ?>
                                <input type="hidden" name="id_inscripcion" value="<?= $i['id_inscripcion'] ?>">
                                <!-- Necesitamos pasar params para volver correctamente -->
                                <input type="hidden" name="tipo_retorno" value="<?= $tipo ?>">
                                <input type="hidden" name="id_retorno" value="<?= $id ?>">
                                
                                <button type="submit" class="submit-button" onclick="return confirm('¿Estás seguro de eliminar esta inscripción?');">
                                    <img class="svg_lite" src="/cfl_402/assets/svg/trash-can.svg" title="Eliminar Inscripción">
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <br>
    <?php 
        // Determinar ruta de vuelta
        $backLink = "../$tipo/index.php"; 
        if ($tipo == 'instructor') $backLink = '../instructores/index.php'; // plural fix
        if ($tipo == 'curso') $backLink = '../cursos/index.php';
        if ($tipo == 'alumno') $backLink = '../alumnos/index.php';
    ?>
    <a href="<?= $backLink ?>" style="color: var(--accent); text-decoration: none; font-weight: bold; font-size: 1.1rem;">
        &larr; Volver
    </a>

</main>

</body>
</html>
