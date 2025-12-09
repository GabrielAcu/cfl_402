<?php
    require_once dirname(__DIR__, 3) . '/config/path.php';
require_once BASE_PATH . '/config/conexion.php';

// ==========================
//   CONFIGURACIÓN INICIAL
// ==========================

require_once BASE_PATH . '/auth/check.php';
// require_once BASE_PATH . '/include/header.php';
// require_once 'layouts.php';

if (!isSuperAdmin()) {
    header('Location: /cfl_402/index.php');
    exit();
}


$conn = conectar();

// Validar GET
if (!isset($_GET["id"])) {
    echo json_encode(["error" => "Falta id"]);
    exit;
}

$id = $_GET["id"];

// ========== OBTENER DATOS DEL ALUMNO ==============
$sql = $conn->prepare("
    SELECT *
    FROM usuarios
    WHERE id = :id
");
$sql->execute([":id" => $id]);
$usuario = $sql->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    echo json_encode(["error" => "Usuario no encontrado"]);
    exit;
}

// ========== RESPUESTA JSON ==============
echo json_encode([
    "usuario" => $usuario
]);
