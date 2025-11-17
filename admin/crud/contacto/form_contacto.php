<?php
session_start();
// 3. Autenticación
require_once dirname(__DIR__, 2) . '/../config/path.php';
require_once BASE_PATH . '/auth/check.php';
requireLogin();

if (!isAdmin()) {
    header('Location: /cfl_402/index.php');
    exit();
}

$id_entidad = $_POST['id_entidad'] ?? $_SESSION['entidad_id'] ?? null;
$tipo = $_POST['tipo'] ?? $_SESSION['tipo'] ?? null;
$mensaje_session = $_SESSION['mensaje_de_session'] ?? null;

// echo($id_entidad)."<br>";
// echo($tipo)."<br>";
// echo($mensaje_session);
// exit();

if ($tipo == null || $id_entidad == null) {    
    header("Location: ../../index.php");
    exit();
}


echo "Agregar contacto<br><br>";

echo "
<form action='procesar_contacto.php' method='post'>
    <input type='text' name='nombre' placeholder='Nombre'>
    <input type='text' name='apellido' placeholder='Apellido' >
    <input type='number' name='dni' placeholder='DNI' >
    <input type='number' name='telefono' placeholder='Telefeno' >
    <input type='email' name='correo' placeholder='Correo' >
    <input type='adress' name='direccion' placeholder='Dirección' >
    <input type='text' name='localidad' placeholder='Localidad' >
    <input type='text' name='cp' placeholder='Código Postal' >
    <input type='text' name='parentesco' placeholder='Parentesco'>
    <textarea name='observaciones' placeholder='Observaciones'></textarea>
    <input type='number' name='id_entidad' value='{$id_entidad}' readonly>
    <input type='text' name='tipo' value='{$tipo}' readonly>
    <input type='submit' value='Enviar'>
</form>
";

$individuo = $tipo.'s';
if($individuo == 'instructors'){
    $individuo = 'instructores';
}
echo "<br><hr>";
echo "
<form action='listar_contactos.php' method='post'>
    <input type='hidden' name='id_entidad' value='{$id_entidad}' readonly>
    <input type='hidden' name='tipo' value='{$tipo}' readonly>
    <input type='submit' value='Volver a lista de contactos'>
</form><br><br>
";
echo "<br><br>";


if (isset($mensaje_session)) {
    echo $mensaje_session."<br>";
    $id_entidad=$_SESSION['entidad_id'];
    $tipo = $_SESSION['tipo'];
    unset($_SESSION['entidad_id']);
    unset($_SESSION['tipo']);
    unset($_SESSION['mensaje_de_session']);

}

if (!$tipo || !$id_entidad) {
    die('faltan parametros');
}



?>
