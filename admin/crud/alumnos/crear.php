<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="alumnos.css">
</head>
<body>
    
</body>
</html>
<?php
// ==========================
//   CONFIGURACIÓN INICIAL
// ==========================
require_once dirname(__DIR__, 3) . '/config/path.php';
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/config/csrf.php';
require_once BASE_PATH . '/include/header.php';
require_once 'layouts.php';

// Autenticación
requireLogin();

// Validar CSRF en POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCSRFToken();
}

// Conexión
$conn = conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre      = $_POST["nombre"]; 
    echo $nombre;
    $apellido    = $_POST["apellido"];
    $dni         = $_POST["dni"];
    $telefono    = $_POST["telefono"];
    $correo      = $_POST["email"];
    $nacimiento  = $_POST["nacimiento"];
    $domicilio   = $_POST["domicilio"];
    $localidad   = $_POST["localidad"];
    $postal      = $_POST["postal"];
    $autos       = $_POST["autos"];
    $patente     = $_POST["patente"];
    $observaciones = $_POST["observaciones"];
    $activo      = "1";

    if (!isset($nombre) || $nombre == '') {
        fallido("Sin Nombre");
        exit();
    } elseif (strlen($nombre) > 50) {
        fallido("El Nombre supera el límite de caractéres");
    } elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $nombre)) { 
        fallido("El nombre solo puede tener letras y espacios");
    } elseif (empty($apellido)) {
        fallido("Sin Apellido");
    } elseif (strlen($apellido) > 50) {
        fallido("El Apellido supera el límite de caractéres");
    } elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $apellido)) { 
        fallido("El apellido solo puede tener letras y espacios");
    } elseif (empty($dni)) {
        fallido("Sin DNI");
    } elseif (empty($telefono)) {
        fallido("Sin Télefono");
    } elseif (empty($correo)) {
        fallido("Sin Email");
    } elseif (empty($nacimiento)) {
        fallido("Sin Fecha de Nacimiento");
    } elseif (empty($domicilio)) {
        fallido("Sin Domicilio");
    } elseif (empty($localidad)) {
        fallido("El Domicilio no tiene localidad");
    } elseif (empty($postal)) {
        fallido("Sin Código Postal");
    } else {

        try {

            // consulta SQL
            $sql = "INSERT INTO alumnos (
                        nombre, apellido, dni, fecha_nacimiento,
                        telefono, correo, direccion, localidad, cp,
                        activo, vehiculo, patente, observaciones
                    ) VALUES (
                        :nombre, :apellido, :dni, :nacimiento,
                        :telefono, :correo, :direccion, :localidad, :cp,
                        :activo, :autos, :patente, :observaciones
                    )";

            $consulta = $conn->prepare($sql);

            $consulta->execute([
                ':nombre'        => $nombre,
                ':apellido'      => $apellido,
                ':dni'           => $dni,
                ':nacimiento'    => $nacimiento,
                ':telefono'      => $telefono,
                ':correo'        => $correo,
                ':direccion'     => $domicilio,
                ':localidad'     => $localidad,
                ':cp'            => $postal,
                ':activo'        => $activo,
                ':autos'         => $autos,
                ':patente'       => $patente,
                ':observaciones' => $observaciones
            ]);

            header("location: index.php ");

        } catch (PDOException $e) {

            if ($e->getCode() == 23000) {
                fallido("El DNI ya existe");
            } elseif ($e->getCode() == '42S22') {
                fallido("El campo 'autos' no existe en tu tabla");
            } else {
                echo "Ocurrió un error al insertar los datos: " . $e->getMessage();
            }
        }
    }

} else { 
    echo "<h1 class='error'>Aha pillín!!!</h1>"; 
    echo "<p>{$_SERVER['REQUEST_METHOD']}</p>";
}

?>



<?php

    // ':id_instructor' => $_POST['id_instructor']
    header('Location: index.php');
    exit;

?>