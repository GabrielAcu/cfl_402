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
require_once BASE_PATH . '/include/header.php';
require_once 'layouts.php';

// Autenticación
requireLogin();

// Conexión
$conn = conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre      = $_POST["nombre"]; 
    $contrasenia    = $_POST["contrasenia"];
    $rol = $_POST["rol"];
    $activo      = "1";
    $roles =[0,1,2];
        // $activo= "1";
    if (!in_array($rol,$roles)) {
            die("el rol  que quisiste asignar no existe");
    }
        

    if (!isset($nombre) || $nombre == '') {
        fallido("Sin Nombre");
        exit();
    } elseif (strlen($nombre) > 50) {
        fallido("El Nombre supera el límite de caractéres");
    } elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $nombre)) { 
        fallido("El nombre solo puede tener letras y espacios");
    } elseif (empty($contrasenia)) {
        fallido("Sin contraseña");
    } elseif (strlen($contrasenia) > 50) {
        fallido("La Contraseña supera el límite de caractéres");
    } elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $contrasenia)) { 
        fallido("El apellido solo puede tener letras y espacios");
    } elseif (empty($rol)) {
        fallido("Sin Rol Designado");
    } else {

        try {

            // consulta SQL
            $sql = "INSERT INTO usuarios (
                        nombre, contrasenia, rol, activo
                        
                    ) VALUES (
                        :nombre, :contrasenia, :rol, :activo
                    )";

            $consulta = $conn->prepare($sql);

            $consulta->execute([
                ':nombre'        => $nombre,
                ':contrasenia'      => $contrasenia,
                ':rol'           => $rol,
                ':activo'        => $activo,

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