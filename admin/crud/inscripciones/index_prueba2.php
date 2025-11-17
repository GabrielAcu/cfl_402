<?php include($_SERVER['DOCUMENT_ROOT'] . '/cfl_402/include/header.php'); ?>

<?php

include_once __DIR__ . '/../../config/conexion.php';
$conn = conectar();

// Obtener alumnos y cursos activos
$alumnos = $conn->query("SELECT id_alumno, CONCAT(apellido, ', ', nombre) AS nombre FROM alumnos WHERE activo = 1 ORDER BY apellido, nombre")->fetchAll(PDO::FETCH_ASSOC);
$cursos = $conn->query("SELECT id_curso, nombre_curso FROM cursos WHERE activo = 1 ORDER BY nombre_curso")->fetchAll(PDO::FETCH_ASSOC);

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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestión de Inscripciones</title>
  <link rel="stylesheet" href="inscripciones.css">
</head>
<body>
  <h2>Gestión de Inscripciones</h2>
    <!-- 🔍 Buscador dinámico -->
    <div class="contenedor">
  <div class="form-container">
    <!-- wrapper para el botón -->
    <div class="item btn-wrap">
      <a href="agregar.php" class="btn-agregar">+ Nueva inscripción</a>
    </div>

    <!-- wrapper para el input -->
    <div class="item input-wrap">
      <input
        type="text"
        id="buscar"
        placeholder="Buscar por alumno o curso..."
        aria-label="Buscar por alumno o curso"
      />
    </div>

    <!-- wrapper para el icono (ahora botón accesible) -->
    <div class="item icon-wrap">
      <button type="button" class="search-btn" aria-label="Buscar">🔍</button>
    </div>
  </div>
</div>

  <!-- loader -->
    <div id="spinner" aria-hidden="true" style="display:none;">
    <div class="spinner-inner" role="status" aria-live="polite">
        <div class="dot"></div>
        <div class="dot"></div>
        <div class="dot"></div>
        <span class="sr-only">Cargando...</span>
    </div>
    </div>


  <!-- 🧾 Tabla de resultados -->
  <div id="resultado">
    <!-- Aquí se cargan las inscripciones -->
  </div>

  <!-- 🧾 Modal de Nueva Inscripción -->
<div id="modal-inscripcion" class="modal">
  <div class="modal-content">
    <span id="cerrar-modal" class="close">&times;</span>
    <h2>Nueva Inscripción</h2>

    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST" id="form-inscripcion">
      <label>Alumno:</label>
      <select name="id_alumno" required>
        <option value="">Seleccione...</option>
        <?php foreach ($alumnos as $a): ?>
          <option value="<?= $a['id_alumno'] ?>"><?= htmlspecialchars($a['nombre']) ?></option>
        <?php endforeach; ?>
      </select>

      <label>Curso:</label>
      <select name="id_curso" required>
        <option value="">Seleccione...</option>
        <?php foreach ($cursos as $c): ?>
          <option value="<?= $c['id_curso'] ?>"><?= htmlspecialchars($c['nombre_curso']) ?></option>
        <?php endforeach; ?>
      </select>

      <label>Fecha de inscripción:</label>
      <input type="date" name="fecha_inscripcion" value="<?= date('Y-m-d') ?>" required>

      <label>Observaciones:</label>
      <textarea name="observaciones" rows="3"></textarea>

      <div class="acciones-form">
        <button type="submit">Guardar</button>
        <button type="button" id="cancelar-modal">Cancelar</button>
      </div>
    </form>
  </div>
</div>


  <script src="funciones.js" ></script>
</body>
</html>


