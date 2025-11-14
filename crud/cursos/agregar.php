<?php
include_once __DIR__ . '/../../config/conexion.php';
$conn = conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $sql = "INSERT INTO cursos (codigo, nombre_curso, descripcion, turno, cupo, id_instructor, activo)
          VALUES (:codigo, :nombre_curso, :descripcion, :turno, :cupo, :id_instructor, 1)";
  $stmt = $conn->prepare($sql);
  $stmt->execute([
    ':codigo' => $_POST['codigo'],
    ':nombre_curso' => $_POST['nombre_curso'],
    ':descripcion' => $_POST['descripcion'],
    ':turno' => $_POST['turno'],
    ':cupo' => $_POST['cupo'],
    ':id_instructor' => $_POST['id_instructor']
  ]);
  header('Location: index.php');
  exit;
}
?>

<form method="POST">
  <h3>Nuevo Curso</h3>
  <input type="text" name="codigo" placeholder="Código" required>
  <input type="text" name="nombre_curso" placeholder="Nombre del curso" required>
  <textarea name="descripcion" placeholder="Descripción"></textarea>
  <input type="text" name="turno" placeholder="Turno">
  <input type="number" name="cupo" placeholder="Cupo">
  <input type="number" name="id_instructor" placeholder="ID del Instructor">
  <button type="submit">Guardar</button>
  <a href="index.php">Volver</a>
</form>