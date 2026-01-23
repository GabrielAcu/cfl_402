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

$id = $_GET['id'] ?? null;
if (!$id) {
    die("Error: ID de inscripción no especificado.");
}

// Obtener datos actuales
$stmt = $conn->prepare("SELECT * FROM inscripciones WHERE id_inscripcion = ?");
$stmt->execute([$id]);
$inscripcion = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$inscripcion) {
    die("Error: Inscripción no encontrada.");
}

// Cargar listas para selects
$alumnos = $conn->query("SELECT id_alumno, nombre, apellido FROM alumnos WHERE activo = 1 ORDER BY apellido, nombre")->fetchAll();
$cursos = $conn->query("SELECT id_curso, nombre_curso FROM cursos WHERE activo = 1 ORDER BY nombre_curso")->fetchAll();

// PROCESAR POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCSRFToken();

    $id_alumno = intval($_POST['id_alumno']);
    $id_curso = intval($_POST['id_curso']);
    $fecha_inscripcion = $_POST['fecha_inscripcion'];
    $observaciones = trim($_POST['observaciones'] ?? '');

    if (!$id_alumno || !$id_curso || !$fecha_inscripcion) {
        $error = "Faltan datos obligatorios.";
    } else {
        $sql = "UPDATE inscripciones 
                SET id_alumno = ?, id_curso = ?, fecha_inscripcion = ?, observaciones = ?
                WHERE id_inscripcion = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id_alumno, $id_curso, $fecha_inscripcion, $observaciones, $id]);
        
        // Redirigir a la vista de inscripciones del alumno (default)
        header("Location: index.php?tipo=alumno&id_alumno=$id_alumno");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Inscripción - CFL 402</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/global.css?v=3.2">
    <link rel="stylesheet" href="../alumnos/alumnos2.css?v=3.2">
    <style>
        .form-card {
            background: var(--card-bg);
            padding: 30px;
            border-radius: 16px;
            border: 1px solid var(--border);
            max-width: 600px;
            margin: 20px auto;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label { font-weight: bold; margin-bottom: 5px; display: block; }
        input, select, textarea {
            padding: 10px;
            border: 1px solid var(--border);
            border-radius: 8px;
            width: 100%;
            background: var(--bg);
            color: var(--text);
            box-sizing: border-box;
        }
    </style>
</head>
<body class="main_alumnos_body">

<?php require_once BASE_PATH . '/include/header.php'; ?>

<h1>Editar Inscripción</h1>

<div class="form-card">
    <form method="POST">
        <?= getCSRFTokenField() ?>
        
        <div>
            <label>Alumno:</label>
            <select name="id_alumno" required>
                <?php foreach ($alumnos as $a): ?>
                    <option value="<?= $a['id_alumno'] ?>" <?= $a['id_alumno'] == $inscripcion['id_alumno'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($a['apellido'] . ", " . $a['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
             <label>Curso:</label>
            <select name="id_curso" required>
                <?php foreach ($cursos as $c): ?>
                    <option value="<?= $c['id_curso'] ?>" <?= $c['id_curso'] == $inscripcion['id_curso'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['nombre_curso']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label>Fecha de inscripción:</label>
            <input type="date" name="fecha_inscripcion" value="<?= htmlspecialchars($inscripcion['fecha_inscripcion']) ?>" required>
        </div>

        <div>
            <label>Observaciones:</label>
            <textarea name="observaciones" rows="4"><?= htmlspecialchars($inscripcion['observaciones']) ?></textarea>
        </div>

        <div style="display: flex; justify-content: space-between; margin-top: 10px;">
            <a href="index.php?tipo=alumno&id_alumno=<?= $inscripcion['id_alumno'] ?>" style="padding: 10px 20px; border: 1px solid var(--border); border-radius: 8px; color: var(--text); text-decoration: none;">Cancelar</a>
            <button type="submit" class="btn-primary">Actualizar</button>
        </div>
    </form>
</div>

</body>
</html>
