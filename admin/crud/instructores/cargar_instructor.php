<?php
// Endpoint AJAX para cargar datos de un instructor
require_once dirname(__DIR__, 3) . '/config/path.php';
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';

// Autenticación
requireLogin();

if (!isAdmin() && !isSuperAdmin()) {
    http_response_code(403);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

// Verificar que sea una petición GET con ID
if ($_SERVER['REQUEST_METHOD'] !== 'GET' || !isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Solicitud inválida']);
    exit;
}

$id = filter_var($_GET['id'], FILTER_VALIDATE_INT);

if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'ID inválido']);
    exit;
}

try {
    $conn = conectar();
    $stmt = $conn->prepare("SELECT * FROM instructores WHERE id_instructor = :id AND activo = 1");
    $stmt->execute([':id' => $id]);
    $instructor = $stmt->fetch();

    if (!$instructor) {
        http_response_code(404);
        echo json_encode(['error' => 'Instructor no encontrado']);
        exit;
    }

    // Devolver JSON
    header('Content-Type: application/json');
    echo json_encode($instructor);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error al cargar instructor']);
}

