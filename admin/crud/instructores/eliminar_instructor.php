<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar</title>
</head>
<body>
    <?php
    require_once "conexion.php";
    if ($_SERVER["REQUEST_METHOD"]=="POST"){
        $id_instructor=$_POST["id_instructor"];

        $conexion=conectar();
        try {
            $consulta=$conexion->prepare("UPDATE instructores SET activo=0 WHERE id_instructor=:id_instructor");
            $consulta->execute([':id_instructor'=>$id_instructor]);
            if ($consulta->rowCount()>0){
                echo "<h1>Instructor eliminado correctamente</h1>";
                echo "<a href='index.php'>Volver al listado de instructores</a>";
            } else {
                echo "<p class='error'>El instructor no existe</p>";
                echo "<a href='index.php'>Volver al listado de instructores</a>";
            }
        } catch (PDOException $e) {
            if ($e->errorInfo[1]==1451){
            echo "<p class= 'error'>No se puede eliminar el alumno porque esta inscrito en un curso/s</p>";
        } else {
            echo "<p class= 'error'>Error al eliminar el instructor: ".$e->getMessage()."</p>";
        }
        }
    } else {
        echo "<p class='error'>solicitud no válida</p>";
    }
    ?>
</body>