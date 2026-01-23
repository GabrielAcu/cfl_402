<?php
require_once 'conexion.php';
$conn = conectar();

$id = $_GET["id_curso"] ?? 0;

$consulta = $conn->prepare("
    SELECT codigo, nombre_curso, descripcion 
    FROM cursos
    WHERE id_curso = ?
");
$consulta->execute([$id]);
$curso = $consulta->fetch();

$consulta2 = $conn->prepare("
    SELECT dia_semana, hora_inicio, hora_fin
    FROM horarios
    WHERE id_curso = ?
");
$consulta2->execute([$id]);
$horarios = $consulta2->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    "curso" => $curso,
    "horarios" => $horarios
]);