<?php
include_once __DIR__ . '/../../config/conexion.php';
$conexion = conectar();
include __DIR__ . '/listar.php';

$columna = $_GET['columna'] ?? 'nombre_curso';
$texto = $_GET['texto'] ?? '';

$query = "SELECT cursos.*, instructores.nombre AS nombre_instructor, instructores.apellido AS apellido_instructor
          FROM cursos
          LEFT JOIN instructores ON cursos.id_instructor = instructores.id_instructor";

if ($texto != "") {
    $query .= " WHERE $columna LIKE :texto";
}

$stmt = $conexion->prepare($query);

if ($texto != "") {
    $stmt->bindValue(':texto', "%$texto%", PDO::PARAM_STR);
}

$stmt->execute();
$cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($cursos) > 0): ?>
<table border="1" cellpadding="8">
  <thead>
    <tr>
      <th>ID</th>
      <th>Código</th>
      <th>Nombre</th>
      <th>Descripción</th>
      <th>Turno</th>
      <th>Cupo</th>
      <th>Instructor</th>
      <th>Activo</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($cursos as $curso): ?>
      <tr>
        <td><?= $curso['id_curso'] ?></td>
        <td><?= htmlspecialchars($curso['codigo']) ?></td>
        <td><?= htmlspecialchars($curso['nombre_curso']) ?></td>
        <td><?= htmlspecialchars($curso['descripcion']) ?></td>
        <td><?= htmlspecialchars($curso['turno']) ?></td>
        <td><?= htmlspecialchars($curso['cupo']) ?></td>
        <td><?= htmlspecialchars($curso['nombre_instructor'] . " " . $curso['apellido_instructor']) ?></td>
        <td><?= $curso['activo'] ? "✅" : "❌" ?></td>
        <td>
          <a href="editar_curso.php?id=<?= $curso['id_curso'] ?>">✏️ Editar</a>
          <a href="eliminar_curso.php?id=<?= $curso['id_curso'] ?>" onclick="return confirm('¿Seguro que deseas eliminar este curso?');">🗑️ Eliminar</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php else: ?>
  <p style="text-align:center;">⚠️ No se encontraron resultados.</p>
<?php endif; ?>
