<?php
// Cargar path.php
require_once dirname(__DIR__, 2) . '/../config/path.php';

// Dependencias
require_once BASE_PATH . '/config/conexion.php';

$id_contacto = $_POST['id_contacto'] ?? null;

$conexion = conectar();
$datos_por_id = "SELECT * FROM contactos WHERE id_contacto = $id_contacto";

$contacto = $conexion->query($datos_por_id);
$registro = $contacto->fetch();
// var_dump($registro['activo']);
// exit();
if ($registro['activo']) {
    //cambiar activo en 0
    $sentencia = "UPDATE contactos SET activo = ? WHERE id_contacto = ?";
    $modificar = $conexion->prepare($sentencia); 
    $modificar->execute([0, $id_contacto]);
    header('Location: listar_contactos.php');
    exit();

} else {
    header('Location: listar_contactos.php');
    exit();
}

           