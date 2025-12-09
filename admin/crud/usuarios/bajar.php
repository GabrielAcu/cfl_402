<?php
// ==========================
//   CONFIGURACIÓN INICIAL
// ==========================
require_once dirname(__DIR__, 3) . '/config/path.php';
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/config/csrf.php';
require_once BASE_PATH . '/include/header.php';
require_once 'layouts.php';

// Autenticación
requireLogin();

if (!isSuperAdmin()) {
    header('Location: /cfl_402/index.php');
    exit();
}

// Validar CSRF
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCSRFToken();
}

// Conexión
$conn = conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = filter_var($_POST["id"] ?? 0, FILTER_VALIDATE_INT);
    
    if (!$id_usuario) {
        header('Location: index.php?error=id_invalido');
        exit();
    }

    try {
        $sql = "UPDATE usuarios SET activo = 0 WHERE id = :id";
        $consulta = $conn->prepare($sql);
        $consulta->execute([':id' => $id_usuario]);

        if ($consulta->rowCount() > 0) {
            header("Location: index.php?ok=eliminado");
        } else {
            header("Location: index.php?error=no_encontrado");
        }
        exit();

    } catch (PDOException $e) {
        header("Location: index.php?error=error_db");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
