<?php
require_once dirname(__DIR__, 3) . '/config/path.php';
require_once BASE_PATH . '/config/conexion.php';

$conn = conectar();

// Validar GET
if (!isset($_GET["id_alumno"])) {
    echo json_encode(["error" => "Falta id_alumno"]);
    exit;
}

$id = $_GET["id_alumno"];

// ========== OBTENER DATOS DEL ALUMNO ==============
$sql = $conn->prepare("
    SELECT *
    FROM alumnos
    WHERE id_alumno = :id
");
$sql->execute([":id" => $id]);
$alumno = $sql->fetch(PDO::FETCH_ASSOC);

if (!$alumno) {
    echo json_encode(["error" => "Alumno no encontrado"]);
    exit;
}

// ========== RESPUESTA JSON ==============
echo json_encode([
    "alumno" => $alumno
]);
