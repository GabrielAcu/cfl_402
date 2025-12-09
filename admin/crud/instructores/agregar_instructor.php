<?php
require_once dirname(__DIR__, 3) . '/config/path.php';
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/config/csrf.php';

// Autenticación
requireLogin();

<<<<<<< HEAD
<<<<<<< HEAD
// if (!isAdmin()) {
//     header('Location: cfl_402/cfl_402/index.php');
//     exit();
// }
=======
>>>>>>> ca717327ce520a49869d51a6b2c86ec00a66c01d

// Conexión
$conn = conectar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Instructor</title>
</head>
<body>
    <?php

if ($_SERVER["REQUEST_METHOD"]=="POST"){
    $nombre=$_POST ["nombre"];
    $apellido=$_POST["apellido"];
    $dni=$_POST["dni"];
    $telefono=$_POST["telefono"];
    $correo=$_POST["correo"];
    try{
        $sql="INSERT INTO instructores (nombre, apellido, dni, telefono, correo) 
        VALUES (:nombre, :apellido, :dni, :telefono, :correo)";
        $consulta=$conn->prepare($sql);
        $consulta->execute([':nombre'=>$nombre,':apellido'=>$apellido,':dni'=>$dni,':telefono'=>$telefono, ':correo'=>$correo]);
        echo "<p>Se registró exitosamente</p>";
        echo "<a href='index.php'>volver al listado anterior</a>";
    } catch (PDOException $e) {
        if ($e->getCode()==23000){
            echo "<p class='Error'> Error al registrarse, DNI duplicado</p>";
            echo "<a href='index.php'>volver al listado anterior</a>";
        } else {
        echo "Ocurrió un error al insertar los datos: ". $e->getMessage();
        }
    }
=======
if (!isAdmin() && !isSuperAdmin()) {
    header('Location: /cfl_402/index.php');
    exit;
}

// Validar CSRF
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    requireCSRFToken();
}

$conn = conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validar campos requeridos
    $nombre = trim($_POST['nombre'] ?? '');
    $apellido = trim($_POST['apellido'] ?? '');
    $dni = $_POST['dni'] ?? '';
    $telefono = trim($_POST['telefono'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? '';
    $direccion = trim($_POST['direccion'] ?? '');
    $localidad = trim($_POST['localidad'] ?? '');
    $cp = trim($_POST['cp'] ?? '');
    $vehiculo = trim($_POST['vehiculo'] ?? '');
    $patente = trim($_POST['patente'] ?? '');
    $observaciones = trim($_POST['observaciones'] ?? '');

    // Validaciones básicas
    if (empty($nombre) || empty($apellido) || empty($dni) || empty($telefono) || empty($correo) || 
        empty($fecha_nacimiento) || empty($direccion) || empty($localidad) || empty($cp) || 
        empty($vehiculo) || empty($patente)) {
        header("Location: index.php?error=campos_vacios");
        exit;
    }

    // Validar email
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        header("Location: index.php?error=email_invalido");
        exit;
    }

    // Validar DNI
    $dni = filter_var($dni, FILTER_VALIDATE_INT);
    if (!$dni || $dni <= 0) {
        header("Location: index.php?error=dni_invalido");
        exit;
    }

    $sql = "INSERT INTO instructores (nombre, apellido, dni, telefono, correo, fecha_nacimiento, 
            direccion, localidad, cp, vehiculo, patente, observaciones, activo)
            VALUES (:nombre, :apellido, :dni, :telefono, :correo, :fecha_nacimiento, 
            :direccion, :localidad, :cp, :vehiculo, :patente, :observaciones, 1)";
    $stmt = $conn->prepare($sql);

    try {
        $stmt->execute([
            ':nombre' => $nombre,
            ':apellido' => $apellido,
            ':dni' => $dni,
            ':telefono' => $telefono,
            ':correo' => $correo,
            ':fecha_nacimiento' => $fecha_nacimiento,
            ':direccion' => $direccion,
            ':localidad' => $localidad,
            ':cp' => $cp,
            ':vehiculo' => $vehiculo,
            ':patente' => $patente,
            ':observaciones' => $observaciones
        ]);

        header("Location: index.php?ok=1");
        exit;

    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            header("Location: index.php?error=dni_duplicado");
        } else {
            header("Location: index.php?error=error_db");
        }
        exit;
    }
} else {
    header("Location: index.php");
    exit;
>>>>>>> 27ce5aef1313346b8e4f895e4860920b8f71e2e0
}
