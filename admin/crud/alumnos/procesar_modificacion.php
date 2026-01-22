 <?php
    // Cargar path.php
    require_once dirname(__DIR__, 2) . '/../config/path.php';

    // Dependencias
    require_once BASE_PATH . '/config/conexion.php';
    require_once BASE_PATH . '/auth/check.php';
    require_once BASE_PATH . '/config/csrf.php';
    require_once BASE_PATH . '/include/header.php';
    require_once 'layouts.php';

    // Validar CSRF
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        requireCSRFToken();
    }

    $conn = conectar();
    if ($_SERVER["REQUEST_METHOD"] == "POST") { // verificar que el método de solicitud sea POST
        $id_alumno = $_POST['id_alumno']; // obtener el id_alumno enviado desde el formulario
        $nombre = $_POST['nombre']; // obtener los demás datos enviados desde el formulario
        $apellido = $_POST['apellido'];
        $dni = trim($_POST['dni']);

        if (!ctype_digit($dni) || strlen($dni) < 7 || strlen($dni) > 8) {
            echo "<p class='error'>DNI inválido (debe tener 7 u 8 números).</p>";
            echo "<a href='index.php'>Volver</a>";
            exit;
        }
        $telefono = $_POST['telefono'];
        $correo=trim($_POST["correo"]);
        
        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            echo "<p class='error'>Email inválido.</p>";
            echo "<a href='index.php'>Volver</a>";
            exit;
        }
        $nacimiento=$_POST["fecha_nacimiento"];
        $domicilio=$_POST["direccion"];
        $localidad=$_POST["localidad"];
        $postal=$_POST["cp"];
        $autos=$_POST["vehiculo"];
        $patente=$_POST["patente"];
        $observaciones=$_POST["observaciones"];
        // $activo= "1";

        try {
        // preparar la consulta de actualización con marcadores de posición
        // en este caso, usamos signos de interrogación como marcadores
        // preparar la consulta de actualización con marcadores de posición nombrados
        $consulta = $conn->prepare("UPDATE alumnos SET 
            nombre = :nombre, 
            apellido = :apellido, 
            dni = :dni, 
            telefono = :telefono, 
            correo = :correo, 
            fecha_nacimiento = :nacimiento, 
            direccion = :direccion, 
            localidad = :localidad, 
            cp = :cp, 
            vehiculo = :autos, 
            patente = :patente, 
            observaciones = :observaciones 
            WHERE id_alumno = :id_alumno");

        // ejecutar la consulta pasando un array asociativo
        $consulta->execute([
            ':nombre'       => $nombre,
            ':apellido'     => $apellido,
            ':dni'          => $dni,
            ':telefono'     => $telefono,
            ':correo'       => $correo,
            ':nacimiento'   => $nacimiento,
            ':direccion'    => $domicilio,
            ':localidad'    => $localidad,
            ':cp'           => $postal,
            ':autos'        => $autos,
            ':patente'      => $patente,
            ':observaciones'=> $observaciones,
            ':id_alumno'    => $id_alumno
        ]);

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
            // 🛡️ SEGURIDAD: No mostrar $e->getMessage() al usuario
            logError('Error al modificar alumno', $e, ['id_alumno' => $id_alumno]);
            
            echo "<div class='error'>
                    <div class='titulo-error'> Error del Sistema </div>
                    <div class='motivo'> Ocurrió un error interno. Por favor contacte al administrador. </div>
                    <a href='index.php'>Volver </a>
                  </div>";
        }
    } else {
        echo "<p class='error'>Solicitud inválida.</p>"; // mensaje de error si no es método POST
    }
    ?>

    <link rel="stylesheet" href="alumnos.css">
</body>
</html>