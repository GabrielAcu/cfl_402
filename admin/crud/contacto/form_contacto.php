<?php
session_start();
// 3. Autenticación
require_once dirname(__DIR__, 2) . '/../config/path.php';
require_once BASE_PATH . '/auth/check.php';
requireLogin();

<<<<<<< HEAD
if (!isAdmin() || !isSuperAdmin()) {
=======
if (!isAdmin() && !isSuperAdmin()) {
>>>>>>> 27ce5aef1313346b8e4f895e4860920b8f71e2e0
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

require_once BASE_PATH . '/config/csrf.php';

echo "
<form action='procesar_contacto.php' method='post'>
    " . getCSRFTokenField() . "
    <input type='text' name='nombre' placeholder='Nombre' required>
    <input type='text' name='apellido' placeholder='Apellido' required>
    <input type='number' name='dni' placeholder='DNI' required>
    <input type='number' name='telefono' placeholder='Telefeno' required>
    <input type='email' name='correo' placeholder='Correo' required>
    <input type='adress' name='direccion' placeholder='Dirección' required>
    <input type='text' name='localidad' placeholder='Localidad' required>
    <input type='text' name='cp' placeholder='Código Postal' required>
    <input type='text' name='parentesco' placeholder='Parentesco'required>
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
