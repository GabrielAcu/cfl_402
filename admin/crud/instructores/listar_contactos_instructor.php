<?php
// Este archivo redirige al sistema principal de contactos
// que maneja tanto alumnos como instructores

require_once dirname(__DIR__, 3) . '/config/path.php';
require_once BASE_PATH . '/auth/check.php';

// Autenticación
requireLogin();

if (!isAdmin() && !isSuperAdmin()) {
    header('Location: /cfl_402/index.php');
    exit();
}

// Obtener parámetros
$id_instructor = $_POST['id_instructor'] ?? $_GET['id_instructor'] ?? null;
$tipo = $_POST['tipo'] ?? $_GET['tipo'] ?? 'instructor';

// Validar
if (!$id_instructor) {
    header('Location: index.php?error=id_faltante');
    exit();
}

// Redirigir al sistema principal de contactos
header("Location: ../contacto/listar_contactos.php?id_entidad=" . urlencode($id_instructor) . "&tipo=" . urlencode($tipo));
exit();