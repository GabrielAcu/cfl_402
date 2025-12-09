 <?php
    // Cargar path.php
    require_once dirname(__DIR__, 2) . '/../config/path.php';

    // Dependencias
    require_once BASE_PATH . '/config/conexion.php';
    require_once BASE_PATH . '/auth/check.php';
    require_once BASE_PATH . '/config/csrf.php';
    require_once BASE_PATH . '/include/header.php';
    // require_once 'layouts.php';

if (!isSuperAdmin()) {
    header('Location: /cfl_402/index.php');
    exit();
}

    // Validar CSRF
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        requireCSRFToken();
    }

    $conn = conectar();
    if ($_SERVER["REQUEST_METHOD"] == "POST") { // verificar que el método de solicitud sea POST
        $id_usuario = $_POST['id']; // obtener el id_usuario enviado desde el formulario
        $nombre = $_POST['nombre']; // obtener los demás datos enviados desde el formulario
        $contrasenia = $_POST['contrasenia'] ?? '';
        $rol = $_POST['rol'];
        $roles =[0,1,2];
        
        if (!in_array($rol,$roles)) {
            die("el rol que quisiste asignar no existe");
        }
        
        // Validar contraseña si se proporcionó
        if (!empty($contrasenia)) {
            if (strlen($contrasenia) < 6) {
                die("La contraseña debe tener al menos 6 caracteres");
            }
            if (strlen($contrasenia) > 72) {
                die("La contraseña es demasiado larga (máximo 72 caracteres)");
            }
        }
        
        try {
        // Si se proporcionó una nueva contraseña, actualizarla hasheada
        // Si no, mantener la contraseña actual
        if (!empty($contrasenia)) {
            $contrasenia_hash = password_hash($contrasenia, PASSWORD_BCRYPT);
            if ($contrasenia_hash === false) {
                die("Error al procesar la contraseña");
            }
            $consulta = $conn->prepare("UPDATE usuarios SET nombre = ?, contrasenia = ?, rol = ? WHERE id = ?");
            $consulta->execute([$nombre, $contrasenia_hash, $rol, $id_usuario]);
        } else {
            // No actualizar contraseña si está vacía
            $consulta = $conn->prepare("UPDATE usuarios SET nombre = ?, rol = ? WHERE id = ?");
            $consulta->execute([$nombre, $rol, $id_usuario]);
        }

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