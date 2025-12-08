<?php
// Cargar path.php
require_once dirname(__DIR__, 3) . '/config/path.php';

// Dependencias
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';

// Autenticación
requireLogin();

if (!isAdmin() && !isSuperAdmin()) {
    header('Location: /cfl_402/index.php');
    exit();
}

// Conexión
$conn = conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_instructor = filter_var($_POST["id_instructor"] ?? 0, FILTER_VALIDATE_INT);
    
    if (!$id_instructor) {
        header("Location: index.php?error=id_invalido");
        exit;
    }

    try {
        $consulta = $conn->prepare("UPDATE instructores SET activo = 0 WHERE id_instructor = :id_instructor");
        $consulta->execute([':id_instructor' => $id_instructor]);
        
        if ($consulta->rowCount() > 0) {
            header("Location: index.php?ok=eliminado");
        } else {
            header("Location: index.php?error=no_encontrado");
        }
        exit;

    } catch (PDOException $e) {
        if ($e->errorInfo[1] == 1451) {
            header("Location: index.php?error=curso_asociado");
        } else {
            header("Location: index.php?error=error_db");
        }
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}