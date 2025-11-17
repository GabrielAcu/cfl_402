<?php
include_once __DIR__ . '/../../config/conexion.php';
$conn = conectar();

$id = $_POST['id'] ?? null;

if ($id) {
  $stmt = $conn->prepare("UPDATE cursos SET activo = 0 WHERE id_curso = ?");
  $ok = $stmt->execute([$id]);
  echo json_encode(['success' => $ok]);
}
