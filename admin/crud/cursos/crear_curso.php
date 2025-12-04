<?php

require_once dirname(__DIR__, 3) . '/config/path.php';

// Dependencias
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/include/header.php';

// Seguridad
requireLogin();
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======

>>>>>>> ca717327ce520a49869d51a6b2c86ec00a66c01d
>>>>>>> 91c34e664ec22601ab74ae2e0d046ef24f7aa0e4
// Conexión
$conn = conectar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos.css">
    <title>Curso Nuevo</title>
</head>
<body>
    <?php
<<<<<<< HEAD
if ($_SERVER["REQUEST_METHOD"]=="POST"){ // verificar que el método de solicitud sea POST
=======
<<<<<<< HEAD
if ($_SERVER["REQUEST_METHOD"]=="POST"){ // verificar que el método de solicitud sea POST
=======
   
if ($_SERVER["REQUEST_METHOD"]=="POST"){ // verificar que el método de solicitud sea POST
    
>>>>>>> ca717327ce520a49869d51a6b2c86ec00a66c01d
>>>>>>> 91c34e664ec22601ab74ae2e0d046ef24f7aa0e4
    $codigo=$_POST["codigo"]; // obtener los datos enviados desde el formulario
    $nombre_curso=$_POST["nombre_curso"];
    $descripcion=$_POST["descripcion"];
    $cupo=$_POST["cupo"];
    $id_instructor=$_POST["instructor"];
    $id_turno=$_POST["turno"];
    try {
        // texto de la consulta SQL con marcadores de posición
        $sql="INSERT INTO cursos (codigo, nombre_curso, descripcion, cupo,id_turno,id_instructor) 
        VALUES (:codigo, :nombre_curso, :descripcion, :cupo, :id_turno, :id_instructor)";
        
        $consulta=$conn->prepare($sql); // preparar la consulta
        // ejecutar la consulta pasando un array asociativo con los valores a insertar
        $consulta->execute([':codigo'=>$codigo,':nombre_curso'=>$nombre_curso,':descripcion'=>$descripcion,':cupo'=>$cupo,':id_turno'=>$id_turno,':id_instructor'=>$id_instructor]);
        echo "<p class='correcto'>Se registró exitosamente</p>"; // mensaje de éxito
        header('Location: index.php');
        echo "<a href='index.php'>Volver al Listado de Cursos</a>"; // enlace para volver al listado
    } catch (PDOException $e) {
        if ($e->getCode()==23000){ // código de error para violación de clave única (DNI repetido)
            echo "<p class='error'>No se pudo registrar porque repitió el código de curso</p>"; 
        } else {
            echo "Ocurrió un error al insertar los datos: ". $e->getMessage(); // mensaje de error genérico
        }    
    }
} else { // si no es método POST, mostrar mensaje de error
    echo "<h1 class='error'>No se puede acceder directamente a este archivo</h1>"; 
}
    ?>
</body>
</html>