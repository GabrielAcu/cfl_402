<?php
include_once __DIR__ . '/../../config/conexion.php';
$conn = conectar();

$errores = [];
// Manejo POST: insertar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_curso = $_POST['id_curso'] ?? null;
    $dia_semana = trim($_POST['dia_semana'] ?? '');
    $hora_inicio = $_POST['hora_inicio'] ?? null;
    $hora_fin = $_POST['hora_fin'] ?? null;

    // Validaciones simples
    if (!$id_curso) $errores[] = "Seleccioná un curso.";
    if ($dia_semana === '') $errores[] = "Seleccioná el día.";
    if (!$hora_inicio || !$hora_fin) $errores[] = "Completá hora inicio y fin.";
    if ($hora_inicio >= $hora_fin) $errores[] = "La hora de inicio debe ser anterior a la hora fin.";

    if (empty($errores)) {
        $sql = "INSERT INTO horarios (id_curso, dia_semana, hora_inicio, hora_fin) VALUES (:id_curso, :dia, :hi, :hf)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_curso', $id_curso, PDO::PARAM_INT);
        $stmt->bindParam(':dia', $dia_semana, PDO::PARAM_STR);
        $stmt->bindParam(':hi', $hora_inicio, PDO::PARAM_STR);
        $stmt->bindParam(':hf', $hora_fin, PDO::PARAM_STR);
        $stmt->execute();
        header('Location: index.php');
        exit;
    }
}

// Cargar lista de cursos para el select
$cursoStmt = $conn->query("SELECT id_curso, nombre_curso FROM cursos ORDER BY nombre_curso");
$cursos = $cursoStmt->fetchAll();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Agregar horario</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="horarios.css">
</head>
<body>
  <div class="container form-page">
    <h2>Nuevo horario</h2>

    <?php if ($errores): ?>
      <div class="errors">
        <?php foreach ($errores as $e) echo "<p>" . htmlspecialchars($e) . "</p>"; ?>
      </div>
    <?php endif; ?>

    <form method="post" action="agregar.php" class="form">
      <label>Curso
        <select name="id_curso" required>
          <option value="">-- Seleccione --</option>
          <?php foreach ($cursos as $c): ?>
            <option value="<?= htmlspecialchars($c['id_curso']) ?>"><?= htmlspecialchars($c['nombre_curso']) ?></option>
          <?php endforeach; ?>
        </select>
      </label>

      <label>Día de la semana
        <select name="dia_semana" required>
          <option value="">-- Seleccione --</option>
          <option>Lunes</option>
          <option>Martes</option>
          <option>Miércoles</option>
          <option>Jueves</option>
          <option>Viernes</option>
          <option>Sábado</option>
          <option>Domingo</option>
        </select>
      </label>

      <label>Hora inicio
        <input type="time" name="hora_inicio" required>
      </label>

      <label>Hora fin
        <input type="time" name="hora_fin" required>
      </label>

      <div class="form-actions">
        <a class="btn secondary" href="index.php">Cancelar</a>
        <button class="btn primary" type="submit">Guardar</button>
      </div>
    </form>
  </div>
</body>
</html>
