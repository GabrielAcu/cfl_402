<?php
include_once __DIR__ . '/../../config/conexion.php';
$conn = conectar();

$id = $_GET['id'] ?? null;
if (!$id) { header('Location: index.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $sql = "UPDATE cursos SET 
            codigo = :codigo,
            nombre_curso = :nombre_curso,
            descripcion = :descripcion,
            turno = :turno,
            cupo = :cupo,
            id_instructor = :id_instructor
          WHERE id_curso = :id";
  $stmt = $conn->prepare($sql);
  $stmt->execute([
    ':codigo' => $_POST['codigo'],
    ':nombre_curso' => $_POST['nombre_curso'],
    ':descripcion' => $_POST['descripcion'],
    ':turno' => $_POST['turno'],
    ':cupo' => $_POST['cupo'],
    ':id_instructor' => $_POST['id_instructor'],
    ':id' => $id
  ]);
  header('Location: index.php');
  exit;
}

$stmt = $conn->prepare("SELECT * FROM cursos WHERE id_curso = ?");
$stmt->execute([$id]);
$curso = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<form method="POST">
  <h3>Editar Curso</h3>

  <label for="codigo">Código del curso</label>
  <input type="text" id="codigo" name="codigo" value="<?= htmlspecialchars($curso['codigo']) ?>" required>

  <label for="nombre_curso">Nombre del curso</label>
  <input type="text" id="nombre_curso" name="nombre_curso" value="<?= htmlspecialchars($curso['nombre_curso']) ?>" required>

  <label for="descripcion">Descripción</label>
  <textarea id="descripcion" name="descripcion"><?= htmlspecialchars($curso['descripcion']) ?></textarea>

  <label for="turno">Turno</label>
  <input type="text" id="turno" name="turno" value="<?= htmlspecialchars($curso['turno']) ?>">

  <label for="cupo">Cupo</label>
  <input type="number" id="cupo" name="cupo" value="<?= htmlspecialchars($curso['cupo']) ?>">

  <label for="id_instructor">ID del instructor</label>
  <input type="number" id="id_instructor" name="id_instructor" value="<?= htmlspecialchars($curso['id_instructor']) ?>">

  <div class="acciones">
    <button type="submit">💾 Guardar</button>
    <a href="index.php">⬅ Volver</a>
  </div>
</form>