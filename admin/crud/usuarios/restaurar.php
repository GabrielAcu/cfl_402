<?php
require_once dirname(__DIR__, 3) . '/config/path.php';
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/config/csrf.php';

requireLogin();

if (!isSuperAdmin()) {
    header('Location: ' . BASE_URL . '/index.php');
    exit();
}

// Conexión
$conn = conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCSRFToken();

    $id_usuario = filter_var($_POST["id"] ?? 0, FILTER_VALIDATE_INT);
    
    if (!$id_usuario) {
        header("Location: eliminados.php?error=id_invalido");
        exit();
    }

    try {
        $sql = "UPDATE usuarios SET activo = '1' WHERE id = :id";
        $consulta = $conn->prepare($sql);
        $consulta->execute([':id' => $id_usuario]);

        if ($consulta->rowCount() > 0) {
           header("Location: eliminados.php?ok=restaurado");
        } else {
           header("Location: eliminados.php?error=no_cambios");
        }
        exit();

    } catch (PDOException $e) {
        error_log("Error restaurando usuario: " . $e->getMessage());
        header("Location: eliminados.php?error=error_db");
        exit();
    }
} else { 
    header('Location: eliminados.php');
    exit();
}
?>
