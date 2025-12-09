<?php
// Cargar path.php
require_once dirname(__DIR__, 2) . '/../config/path.php';
// Autenticación
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/config/csrf.php';
requireLogin();

if (!isAdmin() && !isSuperAdmin()) {
    header('Location: /cfl_402/index.php');
    exit();
}

// CORRECCIÓN: Nos aseguramos de iniciar sesión si check.php no lo hizo
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Dependencias
require_once BASE_PATH . '/config/conexion.php';

// Validar CSRF
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    requireCSRFToken();
}

$id_contacto = $_POST['id_contacto'] ?? null;

// Validación básica
if (!$id_contacto) {
    header('Location: ../../index.php');
    exit();
}

$conexion = conectar();

// 1. Primero obtenemos los datos para saber a dónde volver
$datos_por_id = "SELECT * FROM contactos WHERE id_contacto_alumno = $id_contacto";
$contacto = $conexion->query($datos_por_id);
$registro = $contacto->fetch();

if ($registro && $registro['activo']) {
    // 2. Realizamos el "Soft Delete" (Baja lógica)
    $sentencia = "UPDATE contactos SET activo = ? WHERE id_contacto_alumno = ?";
    $modificar = $conexion->prepare($sentencia); 
    $modificar->execute([0, $id_contacto]);

    // CORRECCIÓN: Guardamos el ID DE LA ENTIDAD (Alumno/Instructor), no el del contacto.
    // Si guardas el ID del contacto borrado, el listar no sabrá qué alumno mostrar.
    $_SESSION['id_entidad_temp'] = $registro['entidad_id']; 
    $_SESSION['tipo_temp']       = $registro['tipo'];

    // Redireccionamos
    header("Location: listar_contactos.php");
    exit();

} else {
    // Si no existe o ya estaba borrado
    header('Location: listar_contactos.php');
    exit();
}
?>