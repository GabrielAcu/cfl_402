<?php

// Cargar path.php
require_once dirname(__DIR__, 3) . '/config/path.php';


// Dependencias
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/include/header.php';

// 3. Autenticación
requireLogin();

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
}
    ?>
</body>
</html> 