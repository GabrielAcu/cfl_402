<?php
include_once __DIR__ . '/../../config/conexion.php';
$conn = conectar();

$texto = isset($_GET['texto']) ? trim($_GET['texto']) : '';

$sql = "SELECT i.id_inscripcion, 
               a.nombre AS nombre_alumno, 
               a.apellido AS apellido_alumno,
               c.nombre_curso, 
               i.fecha_inscripcion, 
               i.observaciones
        FROM inscripciones i
        INNER JOIN alumnos a ON i.id_alumno = a.id_alumno
        INNER JOIN cursos c ON i.id_curso = c.id_curso";

$params = [];

if ($texto !== '') {
    // usamos 3 parámetros distintos para evitar problemas con PDO al reutilizar el mismo nombre
    $sql .= " WHERE a.nombre LIKE :t1
              OR a.apellido LIKE :t2
              OR c.nombre_curso LIKE :t3";
    $like = "%$texto%";
    $params[':t1'] = $like;
    $params[':t2'] = $like;
    $params[':t3'] = $like;
}

$sql .= " ORDER BY i.id_inscripcion DESC";

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$inscripciones = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Alumno</th>
      <th>Curso</th>
      <th>Fecha de inscripción</th>
      <th>Observaciones</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php if (count($inscripciones) > 0): ?>
      <?php foreach ($inscripciones as $fila): ?>
        <tr>
          <td><?= htmlspecialchars($fila['id_inscripcion']) ?></td>
          <td><?= htmlspecialchars($fila['nombre_alumno'] . ' ' . $fila['apellido_alumno']) ?></td>
          <td><?= htmlspecialchars($fila['nombre_curso']) ?></td>
          <td><?= htmlspecialchars($fila['fecha_inscripcion']) ?></td>
          <td><?= htmlspecialchars($fila['observaciones']) ?></td>
          <td class="acciones">
            <!-- Icono lápiz (editar) -->
            <a href="editar.php?id=<?= $fila['id_inscripcion'] ?>" title="Editar" class="icon edit">
              <!-- simple SVG lápiz -->
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25z" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M20.71 7.04a1 1 0 0 0 0-1.41L18.37 3.29a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </a>

            <!-- Icono papelera (eliminar) -->
            <a href="eliminar.php?id=<?= $fila['id_inscripcion'] ?>" class="icon delete" title="Eliminar" onclick="return confirm('¿Seguro que deseas eliminar esta inscripción?')">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M3 6h18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M8 6v12a2 2 0 0 0 2 2h4a2 2 0 0 0 2-2V6" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M10 11v6" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M14 11v6" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M9 6l1-2h4l1 2" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr><td colspan="6">No se encontraron inscripciones.</td></tr>
    <?php endif; ?>
  </tbody>
</table>


