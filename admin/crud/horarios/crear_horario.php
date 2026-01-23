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

// Validar Request y CSRF
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    requireCSRFToken();
    
    $id_curso = filter_var($_POST["id_curso"] ?? 0, FILTER_VALIDATE_INT);
    $dia_semana = $_POST["dia_semana"] ?? '';
    $hora_inicio = $_POST["hora_inicio"] ?? '';
    $hora_fin = $_POST["hora_fin"] ?? '';

    if ($id_curso && $dia_semana && $hora_inicio && $hora_fin) {
        $conn = conectar();
        try {
            $sql = "INSERT INTO horarios (id_curso, dia_semana, hora_inicio, hora_fin) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$id_curso, $dia_semana, $hora_inicio, $hora_fin]);
            // Mensaje éxito? Podes usar $_SESSION si queres, o query param
            $status = "ok";
        } catch (PDOException $e) {
            error_log("Error creando horario: " . $e->getMessage());
            $status = "error";
        }
    } else {
        $status = "error"; // Faltan datos
    }
    
    // Redirect de vuelta al indice del curso
    header("Location: index.php?id_curso=$id_curso&$status=1");
    exit();
} else {
    // Si entran por GET, afuera
    header('Location: ' . BASE_URL . '/admin/crud/cursos/index.php');
    exit();
}
?>