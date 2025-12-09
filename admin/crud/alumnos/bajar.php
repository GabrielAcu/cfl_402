<?php
// ==========================
//   CONFIGURACIÓN INICIAL
// ==========================
require_once dirname(__DIR__, 3) . '/config/path.php';
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/config/csrf.php';

// Autenticación
requireLogin();

// Si no es admin ni superadmin, afuera del panel
if (!isAdmin() && !isSuperAdmin()) {
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
    $id_alumno = filter_var($_POST['id_alumno'] ?? 0, FILTER_VALIDATE_INT);
    
    if (!$id_alumno) {
        header('Location: index.php?error=id_invalido');
        exit();
    }

    try {
        $consulta = $conn->prepare("UPDATE alumnos SET activo = 0 WHERE id_alumno = :id_alumno");
        $consulta->execute([':id_alumno' => $id_alumno]);
        
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
