<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Instructor</title>
</head>
<body>
    <?php
    require_once "conexion.php";
if ($_SERVER["REQUEST_METHOD"]=="POST"){
    $conexion=conectar();
    $nombre=$_POST ["nombre"];
    $apellido=$_POST["apellido"];
    $dni=$_POST["dni"];
    $telefono=$_POST["telefono"];
    $correo=$_POST["correo"];
    try{
        $sql="INSERT INTO instructores (nombre, apellido, dni, telefono, correo) 
        VALUES (:nombre, :apellido, :dni, :telefono, :correo)";
        $consulta=$conexion->prepare($sql);
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