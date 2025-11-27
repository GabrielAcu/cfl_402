<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>modificar</title>
</head>
<body>
    <?php
    require_once "conexion.php";
    if ($_SERVER["REQUEST_METHOD"]=="POST"){
        $conexion=conectar();
        $id=$_POST["id"];
        $texto="SELECT * FROM instructores WHERE id_instructor=:id";
        $consulta=$conexion->prepare($texto);
        $consulta->bindParam(':id',$id);
        $consulta->execute();
        $instructor=$consulta->fetch();
        if ($instructor){
            echo "<h2>Modificar Instructor</h2>
            <form action='procesar_modificacion_instructor.php' method='POST'>
        <input type='hidden' name='id_instructor' value='$instructor[id_instructor]'>
        <input type='text' name='nombre' placeholder='$instructor[nombre]'>
        <input type='text' name='apellido' placeholder='$instructor[apellido]'>
        <input type='number' name='dni'placeholder='$instructor[dni]'>
        <input type='text' name='telefono'placeholder='$instructor[telefono]'>
        <input type='text' name='correo' placeholder='$instructor[correo]'>
        <input type='submit'>
    </form>
            ";
        } else {
            echo "<p class='error'>El instructor no existe</p>";
        }
    } else {
        echo "<h1 Instructor modificado correctamente</h1>";
        echo "<a href='index.php'>Volver al listado de instructores</a>";
    }
    ?>
    </form>
</body>