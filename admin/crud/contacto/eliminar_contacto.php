<?php

require_once dirname(__DIR__, 3) . '/config/path.php';

// Dependencias
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/include/header.php';

// Seguridad
requireLogin();

// Conexión
$conn = conectar();

// if (!isAdmin()) {
//     header('Location: /cfl_402/index.php');
//     exit();
// }

// CORRECCIÓN: Nos aseguramos de iniciar sesión si check.php no lo hizo
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id_contacto = $_POST['id_contacto'] ?? null;

// Validación básica
if (!$id_contacto) {
    header('Location: ../../index.php');
    exit();
}

// 1. Primero obtenemos los datos para saber a dónde volver
$datos_por_id = "SELECT * FROM contactos WHERE id_contacto_alumno = $id_contacto";
$contacto = $conn->query($datos_por_id);
$registro = $contacto->fetch();

if ($registro && $registro['activo']) {
    // 2. Realizamos el "Soft Delete" (Baja lógica)
    $sentencia = "UPDATE contactos SET activo = ? WHERE id_contacto_alumno = ?";
    $modificar = $conn->prepare($sentencia); 
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