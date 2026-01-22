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

// Validar CSRF
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCSRFToken();

    $nombre = trim($_POST["nombre"] ?? ''); 
    $contrasenia = $_POST["contrasenia"] ?? '';
    $confcontrasenia = $_POST["contrasenia-conf"] ?? '';
    $rol = $_POST["rol"] ?? null;
    $activo = "1";
    
    // Validaciones
    $error = null;
    $roles = [0, 1, 2];

    if (!in_array($rol, $roles)) {
        $error = "El rol asignado no existe";
    } elseif (empty($nombre)) {
        $error = "El nombre es obligatorio";
    } elseif (strlen($nombre) > 50) {
        $error = "El nombre supera el límite de caracteres";
    } elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $nombre)) { 
        $error = "El nombre solo puede tener letras y espacios";
    } elseif (empty($contrasenia)) {
        $error = "La contraseña es obligatoria";
    } elseif (strlen($contrasenia) < 6) {
        $error = "La contraseña debe tener al menos 6 caracteres";
    } elseif (strlen($contrasenia) > 72) {
        $error = "La contraseña es demasiado larga";
    } elseif ($contrasenia !== $confcontrasenia) {
        $error = "Las contraseñas no coinciden";
    }

    if ($error) {
        header("Location: index.php?error=" . urlencode($error));
        exit();
    }

    // Insertar
    try {
        $conn = conectar();
        $sql = "INSERT INTO usuarios (nombre, contrasenia, rol, activo) VALUES (:nombre, :contrasenia, :rol, :activo)";
        $stmt = $conn->prepare($sql);
        
        $contrasenia_hash = password_hash($contrasenia, PASSWORD_BCRYPT);
        
        $stmt->execute([
            ':nombre'      => $nombre,
            ':contrasenia' => $contrasenia_hash,
            ':rol'         => $rol,
            ':activo'      => $activo,
        ]);

        header("Location: index.php?ok=creado");
        exit();

    } catch (PDOException $e) {
        error_log("Error crear usuario: " . $e->getMessage());
        header("Location: index.php?error=error_db");
        exit();
    }

} else { 
    header("Location: index.php");
    exit();
}
?>