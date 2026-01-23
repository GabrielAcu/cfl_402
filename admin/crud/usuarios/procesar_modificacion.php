<?php
require_once dirname(__DIR__, 3) . '/config/path.php';
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/config/csrf.php';

// Autenticación
requireLogin();

if (!isSuperAdmin()) {
    header('Location: ' . BASE_URL . '/index.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    requireCSRFToken();

    $id_usuario = filter_var($_POST['id'] ?? 0, FILTER_VALIDATE_INT);
    $nombre = trim($_POST['nombre'] ?? '');
    $contrasenia = $_POST['contrasenia'] ?? '';
    $confcontrasenia = $_POST['contrasenia-conf'] ?? '';
    $rol = $_POST['rol'] ?? null;
    
    $error = null;

    if (!$id_usuario) {
        $error = "ID de usuario inválido";
    }

    // Validaciones basicas
    $roles = [0, 1, 2];
    if (!in_array($rol, $roles)) {
        $error = "Rol inválido";
    }

    // Validar pass solo si viene
    if (!empty($contrasenia)) {
        if (strlen($contrasenia) < 6) {
            $error = "La contraseña debe tener al menos 6 caracteres";
        } elseif (strlen($contrasenia) > 72) {
            $error = "La contraseña es muy larga";
        } elseif ($contrasenia !== $confcontrasenia) {
            $error = "Las contraseñas no coinciden";
        }
    }

    if ($error) {
        header("Location: index.php?error=" . urlencode($error));
        exit();
    }

    $conn = conectar();
    try {
        if (!empty($contrasenia)) {
            $passHash = password_hash($contrasenia, PASSWORD_BCRYPT);
            $sql = "UPDATE usuarios SET nombre = ?, contrasenia = ?, rol = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$nombre, $passHash, $rol, $id_usuario]);
        } else {
            $sql = "UPDATE usuarios SET nombre = ?, rol = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->execute([$nombre, $rol, $id_usuario]);
        }

        header("Location: index.php?ok=modificado");
        exit();

    } catch (PDOException $e) {
        error_log("Error actualizando usuario: " . $e->getMessage());
        header("Location: index.php?error=error_db");
        exit();
    }

} else {
    header("Location: index.php");
    exit();
}
?>