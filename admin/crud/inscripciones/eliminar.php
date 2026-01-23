<?php
require_once dirname(__DIR__, 3) . '/config/path.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/config/csrf.php';
require_once BASE_PATH . '/config/conexion.php';

requireLogin();

if (!isAdmin() && !isSuperAdmin()) {
    header('Location: ' . BASE_URL . '/index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    requireCSRFToken();
}

$id_inscripcion = $_POST['id_inscripcion'] ?? null;
$tipo = $_POST['tipo_retorno'] ?? null;
$id_retorno = $_POST['id_retorno'] ?? null;

if (!$id_inscripcion || !$tipo || !$id_retorno) {
    $_SESSION['mensaje'] = "Error: Faltan datos para eliminar la inscripción.";
    header('Location: ' . BASE_URL . '/admin/index.php');
    exit();
}

$conn = conectar();
try {
    // Verificar que exista
    $check = $conn->prepare("SELECT id_inscripcion FROM inscripciones WHERE id_inscripcion = ?");
    $check->execute([$id_inscripcion]);
    if ($check->fetch()) {
        // Eliminar (Hard Delete, ya que es una tabla de relación)
        // Si tuviéramos 'activo', haríamos update. Pero structure.sql muestra que NO tiene 'activo'.
        $stmt = $conn->prepare("DELETE FROM inscripciones WHERE id_inscripcion = ?");
        $stmt->execute([$id_inscripcion]); // Fix: pass array
        $_SESSION['mensaje'] = "Inscripción eliminada correctamente.";
    } else {
        $_SESSION['mensaje'] = "Error: La inscripción no existe.";
    }
} catch (PDOException $e) {
    error_log("Error Eliminando Inscripción: " . $e->getMessage());
    $_SESSION['mensaje'] = "Error en base de datos al eliminar.";
}

// Redireccionar
// Mapear el param de vuelta
$map = [
    "curso"      => "id_curso",
    "alumno"     => "id_alumno",
    "instructor" => "id_instructor"
];
$param = $map[$tipo] ?? 'id';

header("Location: index.php?tipo=$tipo&$param=$id_retorno");
exit();
?>
