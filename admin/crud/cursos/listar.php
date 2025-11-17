<?php
include_once __DIR__ . '/../../config/conexion.php';
$conn = conectar();

$buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';

if ($buscar !== '') {
  $sql = "SELECT c.*, CONCAT(i.nombre, ' ', i.apellido) AS instructor
          FROM cursos c
          LEFT JOIN instructores i ON c.id_instructor = i.id_instructor
          WHERE c.activo = 1
            AND (c.nombre_curso LIKE :busca1 OR c.codigo LIKE :busca2)
          ORDER BY c.id_curso DESC";

  $stmt = $conn->prepare($sql);
  $stmt->execute([
    ':busca1' => "%$buscar%",
    ':busca2' => "%$buscar%"
  ]);
} else {
  $sql = "SELECT c.*, CONCAT(i.nombre, ' ', i.apellido) AS instructor
          FROM cursos c
          LEFT JOIN instructores i ON c.id_instructor = i.id_instructor
          WHERE c.activo = 1
          ORDER BY c.id_curso DESC";

  $stmt = $conn->prepare($sql);
  $stmt->execute();
}

$cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($cursos) === 0) {
  echo '<p style="text-align:center; padding:12px;">No se encontraron cursos.</p>';
  exit;
}
?>

<table class="tabla-cursos">
  <thead>
    <tr>
      <th>ID</th>
      <th>Código</th>
      <th>Nombre</th>
      <th>Descripción</th>
      <th>Turno</th>
      <th>Cupo</th>
      <th>Instructor</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($cursos as $c): ?>
      <tr>
        <td><?= htmlspecialchars($c['id_curso']) ?></td>
        <td><?= htmlspecialchars($c['codigo']) ?></td>
        <td><?= htmlspecialchars($c['nombre_curso']) ?></td>
        <td><?= htmlspecialchars($c['descripcion']) ?></td>
        <td><?= htmlspecialchars($c['turno']) ?></td>
        <td><?= htmlspecialchars($c['cupo']) ?></td>
        <td><?= htmlspecialchars($c['instructor']) ?></td>
        <td>
          <a href="editar.php?id=<?= $c['id_curso'] ?>" class="btn-editar">✏️</a>
          <a href="eliminar.php?id=<?= $c['id_curso'] ?>" class="btn-eliminar" onclick="return confirm('¿Deseas eliminar este curso? (se desactivará)')">❌</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
