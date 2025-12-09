<?php
require_once dirname(__DIR__, 3) . '/config/path.php';
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/config/csrf.php';

requireLogin();

// Validar CSRF en POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCSRFToken();
}

$conn = conectar();

$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID de inscripción no especificado.");
}

// Obtener datos actuales de la inscripción
$stmt = $conn->prepare("SELECT * FROM inscripciones WHERE id_inscripcion = :id");
$stmt->execute([':id' => $id]);
$inscripcion = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$inscripcion) {
    die("Inscripción no encontrada.");
}

// Cargar listas de alumnos y cursos
$alumnos = $conn->query("SELECT id_alumno, CONCAT(apellido, ', ', nombre) AS nombre FROM alumnos WHERE activo = 1 ORDER BY apellido, nombre")
                ->fetchAll(PDO::FETCH_ASSOC);
$cursos = $conn->query("SELECT id_curso, nombre_curso FROM cursos WHERE activo = 1 ORDER BY nombre_curso")
               ->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_alumno = filter_var($_POST['id_alumno'] ?? 0, FILTER_VALIDATE_INT);
    $id_curso = filter_var($_POST['id_curso'] ?? 0, FILTER_VALIDATE_INT);
    $fecha_inscripcion = $_POST['fecha_inscripcion'] ?? '';
    $observaciones = trim($_POST['observaciones'] ?? '');

    if (!$id_alumno || !$id_curso || !$fecha_inscripcion) {
        die("Datos inválidos.");
    }

    $sql = "UPDATE inscripciones 
            SET id_alumno = :id_alumno, 
                id_curso = :id_curso, 
                fecha_inscripcion = :fecha_inscripcion, 
                observaciones = :observaciones
            WHERE id_inscripcion = :id";

    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':id_alumno' => $id_alumno,
        ':id_curso' => $id_curso,
        ':fecha_inscripcion' => $fecha_inscripcion,
        ':observaciones' => $observaciones,
        ':id' => $id
    ]);

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="inscripciones.css">
  <title>Editar Inscripción</title>
</head>
<body>
  <h2>Editar Inscripción</h2>
  <form method="POST">
    <?= getCSRFTokenField() ?>
    <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
    <label>Alumno:</label>
    <select name="id_alumno" required>
      <?php foreach ($alumnos as $a): ?>
        <option value="<?= $a['id_alumno'] ?>" <?= $a['id_alumno'] == $inscripcion['id_alumno'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($a['nombre']) ?>
        </option>
      <?php endforeach; ?>
    </select><br><br>

    <label>Curso:</label>
    <select name="id_curso" required>
      <?php foreach ($cursos as $c): ?>
        <option value="<?= $c['id_curso'] ?>" <?= $c['id_curso'] == $inscripcion['id_curso'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($c['nombre_curso']) ?>
        </option>
      <?php endforeach; ?>
    </select><br><br>

    <label>Fecha de inscripción:</label>
    <input type="date" name="fecha_inscripcion" value="<?= htmlspecialchars($inscripcion['fecha_inscripcion']) ?>" required><br><br>

    <label>Observaciones:</label>
    <textarea name="observaciones" rows="3" cols="40"><?= htmlspecialchars($inscripcion['observaciones']) ?></textarea><br><br>

    <button type="submit">Actualizar</button>
    <a href="index.php">Cancelar</a>
  </form>
</body>
</html>
