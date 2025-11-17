<?php
// Cargar path.php
require_once dirname(__DIR__, 2) . '/../config/path.php';
require_once BASE_PATH . '/auth/check.php';
// Dependencias
require_once BASE_PATH . '/config/conexion.php';
// 3. Autenticación
requireLogin();

if (!isAdmin()) {
    header('Location: /cfl_402/index.php');
    exit();
}
$id_contacto = $_POST['id_contacto'] ?? null;

if ($id_contacto == null) {    
    header("Location: ../../index.php");
    exit();
}

$conexion = conectar();
$datos_por_id = "SELECT * FROM contactos WHERE id_contacto = $id_contacto";

$contacto = $conexion->query($datos_por_id);
$registro = $contacto->fetch();
// var_dump($contacto->fetch());
// exit();


echo "Modificar contacto<br><br>";

echo "
<form action='procesar_contacto.php' method='post'>
    <input type='text' name='nombre' value={$registro['nombre']}>
    <input type='text' name='apellido' value={$registro['apellido']}>
    <input type='number' name='dni' value={$registro['dni']}>
    <input type='number' name='telefono' value={$registro['telefono']}>
    <input type='email' name='correo' value={$registro['correo']}>
    <input type='adress' name='direccion' value={$registro['direccion']}>
    <input type='text' name='localidad' value={$registro['localidad']}>
    <input type='text' name='cp' value={$registro['cp']}>
    <input type='text' name='parentesco' value={$registro['parentesco']}>
    <textarea name='observaciones'>{$registro['observaciones']}</textarea>
    <input type='hidden' name='id_entidad' value={$registro['entidad_id']}>
    <input type='hidden' name='tipo' value={$registro['tipo']}>
    <input type='hidden' name='id_contacto' value={$registro['id_contacto']}>
    <input type='hidden' name='action' value='modificar'>

    <input type='submit' value='Guardar'>
</form>
";