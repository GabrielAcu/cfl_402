<?php
require_once dirname(__DIR__, 3) . '/config/path.php';
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/config/csrf.php';

requireLogin();
if (!isAdmin() && !isSuperAdmin()) {
    header('Location: ' . BASE_URL . '/index.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    requireCSRFToken();

    $id_horario = filter_var($_POST["id_horario"] ?? 0, FILTER_VALIDATE_INT);
    $id_curso = filter_var($_POST["id_curso"] ?? 0, FILTER_VALIDATE_INT);

    if ($id_horario) {
        $conn = conectar();
        try {
            $stmt = $conn->prepare("DELETE FROM horarios WHERE id_horario = ?");
            $stmt->execute([$id_horario]);
            // Mensaje éxito
            $status = "ok";
        } catch (PDOException $e) {
            error_log("Error borrando horario: " . $e->getMessage());
            $status = "error";
        }
    } else {
        $status = "error";
    }

    // Redirect
    if ($id_curso) {
        header("Location: index.php?id_curso=$id_curso&ok=1");
    } else {
        header("Location: ../cursos/index.php");
    }
    exit();
} else {
    header('Location: ../cursos/index.php');
    exit();
}
?>