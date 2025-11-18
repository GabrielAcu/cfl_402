<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>procesar</title>
</head>
<body>
    <?php
    require_once "conexion.php";
    if ($_SERVER["REQUEST_METHOD"]=="POST"){
        $conexion=conectar();
        $id_instructor=$_POST["id_instructor"];
        $nombre=$_POST ["nombre"];
        $apellido=$_POST["apellido"];
        $dni=$_POST["dni"];
        $telefono=$_POST["telefono"];
        $correo=$_POST["correo"];

        $conexion=conectar();
        try {
            $consulta=$conexion->prepare("UPDATE instructores SET nombre=:nombre, apellido=:apellido, dni=:dni, telefono=:telefono, correo=:correo WHERE id_instructor=:id_instructor");
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