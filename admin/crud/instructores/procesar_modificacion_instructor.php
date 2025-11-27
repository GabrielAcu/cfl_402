<?php

// Cargar path.php
require_once dirname(__DIR__, 2) . '/../config/path.php';


// Dependencias
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/include/header.php';

// 3. Autenticación
requireLogin();

if (!isAdmin()) {
    header('Location: cfl_402_ciro/cfl_402/index.php');
    exit();
}

// Conexión
$conn = conectar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>procesar</title>
</head>
<body>
    <?php
    if ($_SERVER["REQUEST_METHOD"]=="POST"){
        $id_instructor=$_POST["id_instructor"];
        $nombre=$_POST ["nombre"];
        $apellido=$_POST["apellido"];
        $dni=$_POST["dni"];
        $telefono=$_POST["telefono"];
        $correo=$_POST["correo"];
        try {
            $consulta=$conn->prepare("UPDATE instructores SET nombre=:nombre, apellido=:apellido, dni=:dni, telefono=:telefono, correo=:correo WHERE id_instructor=:id_instructor");
            $consulta->execute([':nombre'=>$nombre, ':apellido'=>$apellido, ':dni'=>$dni, ':telefono'=>$telefono, ':correo'=>$correo, ':id_instructor'=>$id_instructor]);
            if ($consulta->rowCount()>0){
                echo "<h1>Instructor modificado correctamente</h1>";
                echo "<a href='index.php'>Volver al listado de instructores</a>";
            } else {
                echo "<p class='error'>No se realizaron cambios o el instructor no existe</p>";
            }
            } catch (PDOException $e) {
            echo "<p class='error'>Error al modificar el instructor: ". $e->getMessage()."</p>";
        }
    } else {
        echo "<p class='error'>solicitud no válida</p>";
    }
    ?>
     
</body>
</html>