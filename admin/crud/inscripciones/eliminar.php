<?php
require_once dirname(__DIR__, 3) . '/config/path.php';
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/config/csrf.php';

requireLogin();

// Validar CSRF - aunque sea GET, es una operación destructiva, mejor usar POST
// Por ahora validamos si viene por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    requireCSRFToken();
}

$conn = conectar();

$id = $_GET['id'] ?? $_POST['id'] ?? null;

if ($id) {
    $id = filter_var($id, FILTER_VALIDATE_INT);
    if ($id) {
        try {
            $stmt = $conn->prepare("DELETE FROM inscripciones WHERE id_inscripcion = :id");
            $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            // Error al eliminar
        }
    }
}

header("Location: index.php");
exit;
?>

