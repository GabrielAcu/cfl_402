<?php
include_once __DIR__ . '/../../config/conexion.php';
$conn = conectar();

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $conn->prepare("DELETE FROM inscripciones WHERE id_inscripcion = :id");
    $stmt->execute([':id' => $id]);
}

header("Location: index.php");
exit;
?>

