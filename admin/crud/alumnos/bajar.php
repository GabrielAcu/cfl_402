<link rel="stylesheet" href="alumnos.css">
    <?php
    include_once __DIR__ . '/../../../config/conexion.php';
    require_once 'layouts.php';


    $conn = conectar();
    $conn=conectar(); // establecer la conexión
    if ($_SERVER["REQUEST_METHOD"]=="POST"){ // verificar que el método de solicitud sea POST
        $id_alumno=$_POST['id_alumno']; // obtener el id_alumno enviado desde el formulario

        $conn=conectar(); // establecer la conexión
        try {
        
        // no le presten atención a esto, lo vamos a ver en classe, 
        // lo puse para no olvidarme
        // $consulta="DELETE FROM alumnos WHERE id_alumno='$id_alumno'";
        // echo $consulta;
        // Esto es para la inyección de SQL 6' OR '1
        // $conexion->query($consulta);

        // preparar la consulta de eliminación usando marcadores de posición ?
        $consulta = $conn->prepare("UPDATE `alumnos` SET `activo` = '0' WHERE `alumnos`.`id_alumno` = ?");
        // ejecutar la consulta pasando un array con los valores a actualizar
        // el orden de los valores en el array debe coincidir con el orden de los marcadores en la consulta
        $consulta->execute([$id_alumno]);

        if ($consulta->rowCount()>0){ // si se eliminó al menos una fila
            exitoso("Alumno eliminado correctamente.</p>");
        } else {
            echo "<p class='error'>Error al eliminar el alumno: ".$e->getMessage()."</p>";
        }
        } catch (Exception $e){
            if ($e->errorInfo[1 ]==1451){ // código de error para restricción de clave foránea

                nochange( "<p class='error'>No se puede eliminar el alumno porque está inscripto en curso/s.</p>");
            }else{ // otro error
            }
        }
    } else { // si no es método POST, mostrar mensaje de error
        echo "<p class='error'>Solicitud inválida.</p>";
    }
    ?>
</body>
</html>

            <!-- fallido("Error al eliminar el alumno:" . $e->getMessage() ); -->
