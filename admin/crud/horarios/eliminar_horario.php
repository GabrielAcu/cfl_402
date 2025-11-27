<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XXX</title>
</head>
<body>
    <?php
    require_once "conexion.php";
    if ($_SERVER["REQUEST_METHOD"]=="POST"){
        $id_horario=$_POST["id_horario"];
        $conexiom=conectar();
        try {
            $consulta=$conexiom->prepare("DELETE FROM horarios WHERE id_horario=?");
            $consulta->execute([$id_horario]);
            echo "Eliminado correctamente tilin";
            
        } catch (Exception $e) {
            echo "Ocurrio un error, no se elimino el registro.";
            echo $e->getMessage();

        }
    }
    ?>
    
    <ul>
        <li><a href="index.php">volver al inicio</a></li>
    </ul>

</body>
</html>