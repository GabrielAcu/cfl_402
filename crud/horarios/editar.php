<?php
include_once __DIR__ . '/../../config/conexion.php';
$conn = conectar();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$id) {
    header('Location: index.php');
    exit;
}

// POST: actualizar
$errores = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_curso = $_POST['id_curso'] ?? null;
    $dia_semana = trim($_POST['dia_semana'] ?? '');
    $hora_inicio = $_POST['hora_inicio'] ?? null;
    $hora_fin = $_POST['hora_fin'] ?? null;

    if (!$id_curso) $errores[] = "Seleccioná un curso.";
    if ($dia_semana === '') $errores[] = "Seleccioná el día.";
    if (!$hora_inicio || !$hora_fin) $errores[] = "Completá hora inicio y fin.";
    if ($hora_inicio >= $hora_fin) $errores[] = "La hora de inicio debe ser anterior a la hora fin.";

    if (empty($errores)) {
        $sql = "UPDATE horarios SET id_curso = :id_curso, dia_semana = :dia, hora_inicio = :hi, hora_fin = :hf WHERE id_horario = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_curso', $id_curso, PDO::PARAM_INT);
        $stmt->bindParam(':dia', $dia_semana, PDO::PARAM_STR);
        $stmt->bindParam(':hi', $hora_inicio, PDO::PARAM_STR);
        $stmt->bindParam(':hf', $hora_fin, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        header('Location: index.php');
        exit;
    }
}

// Cargar datos actuales
$stmt = $conn->prepare("SELECT * FROM horarios WHERE id_horario = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$horario = $stmt->fetch();
if (!$horario) {
    header('Location: index.php');
    exit;
}

// Cursos para select
$cursoStmt = $conn->query("SELECT id_curso, nombre FROM cursos ORDER BY nombre");
$cursos = $cursoStmt->fetchAll();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Editar horario</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="horarios.css">
</head>
<body>
  <div class="container form-page">
    <h2>Editar horario #<?= htmlspecialchars($id) ?></h2>

    <?php if ($errores): ?>
      <div class="errors">
        <?php foreach ($errores as $e) echo "<p>" . htmlspecialchars($e) . "</p>"; ?>
      </div>
    <?php endif; ?>

    <form method="post" action="editar.php?id=<?= $id ?>" class="form">
      <label>Curso
        <select name="id_curso" required>
          <option value="">-- Seleccione --</option>
          <?php foreach ($cursos as $c): ?>
            <option value="<?= htmlspecialchars($c['id_curso']) ?>" <?= $c['id_curso'] == $horario['id_curso'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($c['nombre']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </label>

      <label>Día de la semana
        <select name="dia_semana" required>
          <option value="">-- Seleccione --</option>
          <?php
            $dias = ['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'];
            foreach ($dias as $d) {
                $sel = ($d == $horario['dia_semana']) ? 'selected' : '';
                echo "<option $sel>$d</option>";
            }
          ?>
        </select>
      </label>

      <label>Hora inicio
        <input type="time" name="hora_inicio" required value="<?= htmlspecialchars(substr($horario['hora_inicio'],0,5)) ?>">
      </label>

      <label>Hora fin
        <input type="time" name="hora_fin" required value="<?= htmlspecialchars(substr($horario['hora_fin'],0,5)) ?>">
      </label>

      <div class="form-actions">
        <a class="btn secondary" href="index.php">Cancelar</a>
        <button class="btn primary" type="submit">Actualizar</button>
      </div>
    </form>
  </div>
</body>
</html>
