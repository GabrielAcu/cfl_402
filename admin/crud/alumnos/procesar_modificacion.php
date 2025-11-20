 <?php
    include_once __DIR__ . '/../../../config/conexion.php';
require_once 'layouts.php';

    $conn = conectar();
    if ($_SERVER["REQUEST_METHOD"] == "POST") { // verificar que el método de solicitud sea POST
        $id_alumno = $_POST['id_alumno']; // obtener el id_alumno enviado desde el formulario
        $nombre = $_POST['nombre']; // obtener los demás datos enviados desde el formulario
        $apellido = $_POST['apellido'];
        $dni = $_POST['dni'];
        $telefono = $_POST['telefono'];
        $correo=$_POST["email"];
        $nacimiento=$_POST["nacimiento"];
        $domicilio=$_POST["domicilio"];
        $localidad=$_POST["localidad"];
        $postal=$_POST["postal"];
        $autos=$_POST["autos"];
        $patente=$_POST["patente"];
        $observaciones=$_POST["observaciones"];
        // $activo= "1";

        try {
        // preparar la consulta de actualización con marcadores de posición
        // en este caso, usamos signos de interrogación como marcadores
        $consulta = $conn->prepare("UPDATE alumnos SET nombre = ?, apellido = ?, dni = ?, telefono = ?,correo = ?, fecha_nacimiento = ?, direccion = ?, localidad = ?, cp = ?, vehiculo = ?, patente = ?, observaciones = ? WHERE id_alumno = ?");
        // ejecutar la consulta pasando un array con los valores a actualizar
        // el orden de los valores en el array debe coincidir con el orden de los marcadores en la consulta
        $consulta->execute([$nombre, $apellido, $dni, $telefono, $correo, $nacimiento, $domicilio, $localidad, $postal,$autos, $patente, $observaciones , $id_alumno]);

        if ($consulta->rowCount() > 0) { // si se modificó al menos una fila
            // si los datos a guardar en la modificación son iguales a los que ya estaban, 
            // MySQL se da cuenta que en realidad no hubo cambios y rowCount() devuelve 0
            echo"<div class='exitoso'>
            <div class='titulo-exitoso'> Modificación Exitosa </div>

            <div class='motivo'>  </div>
            <a href='index.php'>Volver </a>
            </div>
            
            "; // mensaje de éxito
            
        } else {
            echo"<div class='no-change'>
            <div class='titulo-exitoso'> Sin Cambios </div>

            <div class='motivo'> 
            <a href='index.php'>Volver </a> </div>

            
            </div>
            
            ";
        }
        } catch (Exception $e) {
            echo "<p class='error'>Error al modificar el alumno: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p class='error'>Solicitud inválida.</p>"; // mensaje de error si no es método POST
    }
    ?>
</body>
</html>