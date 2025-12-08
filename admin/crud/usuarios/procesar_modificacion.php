 <?php
    // Cargar path.php
    require_once dirname(__DIR__, 2) . '/../config/path.php';

    // Dependencias
    require_once BASE_PATH . '/config/conexion.php';
    require_once BASE_PATH . '/auth/check.php';
    require_once BASE_PATH . '/include/header.php';
    // require_once 'layouts.php';

if (!isSuperAdmin()) {
    header('Location: /cfl_402/index.php');
    exit();
}

    $conn = conectar();
    if ($_SERVER["REQUEST_METHOD"] == "POST") { // verificar que el método de solicitud sea POST
        $id_usuario = $_POST['id']; // obtener el id_alumno enviado desde el formulario
        $nombre = $_POST['nombre']; // obtener los demás datos enviados desde el formulario
        $contrasenia = $_POST['contrasenia'];
        $rol = $_POST['rol'];
        $roles =[0,1,2];
        // $activo= "1";
        if (!in_array($rol,$roles)) {
            die("el rol  que quisiste asignar no existe");
        }
        
        try {
        // preparar la consulta de actualización con marcadores de posición
        // en este caso, usamos signos de interrogación como marcadores
        $consulta = $conn->prepare("UPDATE usuarios SET nombre = ?, contrasenia = ?, rol = ? WHERE id = ?");
        // ejecutar la consulta pasando un array con los valores a actualizar
        // el orden de los valores en el array debe coincidir con el orden de los marcadores en la consulta
        $consulta->execute([$nombre, $contrasenia, $rol, $id_usuario]);

        if ($consulta->rowCount() > 0) { // si se modificó al menos una fila
            // si los datos a guardar en la modificación son iguales a los que ya estaban, 
            // MySQL se da cuenta que en realidad no hubo cambios y rowCount() devuelve 0
            $respuesta= 'si';
            header("location: index.php"); // mensaje de éxito
            echo json_encode([
                "respuesta" => $respuesta
            ]);

        } else {
            $respuesta= 'no';
            header("location: index.php");
            echo json_encode([
                "respuesta" => $respuesta
            ]); 
        }
        } catch (Exception $e) {
            echo "<p class='error'>Error al modificar el alumno: " . $e->getMessage() . "</p>";
        }
    } else {
        echo "<p class='error'>Solicitud inválida.</p>"; // mensaje de error si no es método POST
    }
    ?>

    <link rel="stylesheet" href="alumnos.css">
</body>
</html>