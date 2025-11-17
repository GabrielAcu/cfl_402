<?php
include_once __DIR__ . '/../../config/conexion.php';
$conn = conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id_horario']) ? (int)$_POST['id_horario'] : 0;
    if ($id) {
        $stmt = $conn->prepare("DELETE FROM horarios WHERE id_horario = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}
header('Location: index.php');
exit;
