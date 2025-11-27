<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar</title>
    <link rel="stylesheet" href="instructores.css">
</head>
<body>
    <?php
    // Cargar path.php
require_once dirname(__DIR__, 2) . '/../config/path.php';


// Dependencias
// require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/include/header.php';

// 3. Autenticación
requireLogin();

if (!isAdmin()) {
    header('Location: cfl_402_ciro/cfl_402/index.php');
    exit();
}
    

    require_once "conexion.php";
    require_once "layouts.php";


    if ($_SERVER["REQUEST_METHOD"]=="POST"){
        $id_instructor=$_POST["id_instructor"];

        $conn=conectar();
        try {
            $consulta=$conn->prepare("UPDATE instructores SET activo=0 WHERE id_instructor=:id_instructor");
            $consulta->execute([':id_instructor'=>$id_instructor]);
            if ($consulta->rowCount()>0){
                exitoso("Instructor Eliminado Con Éxito");
                echo "<a href='index.php'>Volver al listado de instructores</a>";
            } else {
                fallido("El Instructor No Existe");
                echo "<a href='index.php'>Volver al listado de instructores</a>";
            }
        } catch (PDOException $e) {
            if ($e->errorInfo[1]==1451){
            fallido("se puede eliminar el Instructor porque esta inscrito en un curso/s");
        } else {
            fallido("No Se Pudo Eliminar. ").$e->getMessage();
        }
        }
    } else {
        echo "<p class='error'>solicitud no válida</p>";
    }
    ?>
</body>