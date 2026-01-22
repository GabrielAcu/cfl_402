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
    requireCSRFToken();


    $id_usuario = $_POST["id"];
    $activo      = "0";
     

        try {

            // consulta SQL
            $sql = ("UPDATE `usuarios` SET `activo`='1' WHERE `id` = :id;");

            $consulta = $conn->prepare($sql);

            $consulta->execute([
                ':id' => $id_usuario
                
            ]);

            // header("location: index.php ");

        } catch (PDOException $e) {

            if ($e->getCode() == 23000) {
                fallido("El DNI ya existe");
            } elseif ($e->getCode() == '42S22') {
                fallido("El campo 'autos' no existe en tu tabla");
            } else {
                error_log("Error DB: " . $e->getMessage());
                echo "Ocurrió un error al insertar los datos. Por favor contacte al administrador.";
            }
        }
    }
     
    else { 
    echo "<h1 class='error'>Aha pillín!!!</h1>"; 
    echo "<p>{$_SERVER['REQUEST_METHOD']}</p>";
}

?>



<?php

    // ':id_instructor' => $_POST['id_instructor']
    header('Location: index.php');

    exit;

?>
