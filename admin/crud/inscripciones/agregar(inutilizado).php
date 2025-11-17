<?php
include_once __DIR__ . '/../../config/conexion.php';
$conn = conectar();

// Obtener alumnos activos
$alumnos = $conn->query("SELECT id_alumno, CONCAT(apellido, ', ', nombre) AS nombre FROM alumnos WHERE activo = 1 ORDER BY apellido, nombre")
                ->fetchAll(PDO::FETCH_ASSOC);

// Obtener cursos activos
$cursos = $conn->query("SELECT id_curso, nombre_curso FROM cursos WHERE activo = 1 ORDER BY nombre_curso")
               ->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_alumno = $_POST['id_alumno'] ?? null;
    $id_curso = $_POST['id_curso'] ?? null;
    $fecha_inscripcion = $_POST['fecha_inscripcion'] ?? date('Y-m-d');
    $observaciones = $_POST['observaciones'] ?? '';

    if ($id_alumno && $id_curso) {
        $sql = "INSERT INTO inscripciones (id_alumno, id_curso, fecha_inscripcion, observaciones)
                VALUES (:id_alumno, :id_curso, :fecha_inscripcion, :observaciones)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_alumno', $id_alumno);
        $stmt->bindParam(':id_curso', $id_curso);
        $stmt->bindParam(':fecha_inscripcion', $fecha_inscripcion);
        $stmt->bindParam(':observaciones', $observaciones);
        $stmt->execute();

        header("Location: index.php");
        exit;
    } else {
        $error = "Debe seleccionar un alumno y un curso.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="inscripciones.css">
  <title>Nueva Inscripción</title>
</head>
<body>
  <h2>Agregar Nueva Inscripción</h2>
  <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
  <form method="POST">
    <label>Alumno:</label>
    <select name="id_alumno" required>
      <option value="">Seleccione...</option>
      <?php foreach ($alumnos as $a): ?>
        <option value="<?= $a['id_alumno'] ?>"><?= htmlspecialchars($a['nombre']) ?></option>
      <?php endforeach; ?>
    </select><br><br>

    <label>Curso:</label>
    <select name="id_curso" required>
      <option value="">Seleccione...</option>
      <?php foreach ($cursos as $c): ?>
        <option value="<?= $c['id_curso'] ?>"><?= htmlspecialchars($c['nombre_curso']) ?></option>
      <?php endforeach; ?>
    </select><br><br>

    <label>Fecha de inscripción:</label>
    <input type="date" name="fecha_inscripcion" value="<?= date('Y-m-d') ?>" required><br><br>

    <label>Observaciones:</label>
    <textarea name="observaciones" rows="3" cols="40"></textarea><br><br>

    <button type="submit">Guardar</button>
    <a href="index.php">Cancelar</a>
  </form>
</body>
</html>

