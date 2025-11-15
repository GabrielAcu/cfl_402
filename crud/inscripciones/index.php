<?php
// Cargar path.php desde crud/alumnos (2 niveles arriba)
require_once dirname(__DIR__, 2) . '/config/path.php';

// Dependencias
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/include/header.php';

// Seguridad
requireLogin();

// Conexión
$conn = conectar(); 

$alumnos = $conn->query("SELECT id_alumno, CONCAT(apellido, ', ', nombre) AS nombre FROM alumnos WHERE activo = 1 ORDER BY apellido, nombre")->fetchAll(PDO::FETCH_ASSOC);
$cursos = $conn->query("SELECT id_curso, nombre_curso FROM cursos WHERE activo = 1 ORDER BY nombre_curso")->fetchAll(PDO::FETCH_ASSOC);

$inscripciones = $conn->query("
  SELECT i.id_inscripcion, a.apellido, a.nombre, c.nombre_curso, i.fecha_inscripcion, i.observaciones
  FROM inscripciones i
  JOIN alumnos a ON i.id_alumno = a.id_alumno
  JOIN cursos c ON i.id_curso = c.id_curso
  ORDER BY i.id_inscripcion DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Gestión de Inscripciones</title>
  <link rel="stylesheet" href="inscripciones.css" />
</head>
<body>
  <h2>Gestión de Inscripciones</h2>

  <div class="contenedor">
    <div class="form-container">
      <div class="item btn-wrap">
        <a href="agregar.php" class="btn-agregar">+ Nueva inscripción</a>
      </div>
      <div class="item input-wrap">
        <input type="text" id="buscar" placeholder="Buscar por alumno o curso..." />
      </div>
      <div class="item icon-wrap">
        <button type="button" class="search-btn">🔍</button>
      </div>
    </div>
  </div>

  <!-- 🧾 Tabla -->
  <div class="tabla-responsive">
    <table class="tabla-inscripciones">
      <thead>
        <tr>
          <th>ID</th>
          <th>Alumno</th>
          <th>Curso</th>
          <th>Fecha</th>
          <th>Observaciones</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($inscripciones as $i): ?>
          <tr>
            <td class="col-id"><?= htmlspecialchars($i['id_inscripcion']) ?></td>
            <td class="col-alumno"><?= htmlspecialchars($i['apellido'] . ', ' . $i['nombre']) ?></td>
            <td class="col-curso"><?= htmlspecialchars($i['nombre_curso']) ?></td>
            <td class="col-fecha"><?= htmlspecialchars($i['fecha_inscripcion']) ?></td>
            <td class="col-obs"><?= htmlspecialchars($i['observaciones']) ?></td>
            <td class="col-acciones">
              <button class="btn-ver" data-id="<?= $i['id_inscripcion'] ?>">🔎</button>
              <button class="btn-editar">✏️</button>
              <button class="btn-eliminar">🗑️</button>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <!-- 🪟 Modal Detalle -->
  <div id="modal-detalle" class="modal">
    <div class="modal-content">
      <span id="cerrar-detalle" class="close">&times;</span>
      <h3>Detalles de la inscripción</h3>
      <div class="detalle-contenido">
        <p><strong>ID:</strong> <span id="detalle-id"></span></p>
        <p><strong>Alumno:</strong> <span id="detalle-alumno"></span></p>
        <p><strong>Curso:</strong> <span id="detalle-curso"></span></p>
        <p><strong>Fecha de inscripción:</strong> <span id="detalle-fecha"></span></p>
        <p><strong>Observaciones:</strong> <span id="detalle-observaciones"></span></p>
      </div>
    </div>
  </div>

  <script src="funciones.js"></script>
</body>
</html>
