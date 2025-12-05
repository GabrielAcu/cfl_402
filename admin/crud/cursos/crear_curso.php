<?php

require_once dirname(__DIR__, 3) . '/config/path.php';

// Dependencias
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/include/header.php';

// Seguridad
requireLogin();

// Conexión
$conn = conectar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos.css">
    <title>Curso Nuevo</title>
</head>
<body>
<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    
    // Datos del formulario
    $codigo         = $_POST["codigo"];
    $nombre_curso   = $_POST["nombre_curso"];
    $descripcion    = $_POST["descripcion"];
    $cupo           = $_POST["cupo"];
    $id_instructor  = $_POST["instructor"];
    $id_turno       = $_POST["turno"];

    // 🔵 Nuevos campos:
    $fecha_inicio   = $_POST["fecha_inicio"];
    $fecha_fin      = $_POST["fecha_fin"];

    // ✔ Validación básica de fechas
    if ($fecha_inicio > $fecha_fin) {
        echo "<p class='error'>La fecha de inicio no puede ser posterior a la fecha de fin.</p>";
        echo "<a href='index.php'>Volver al listado</a>";
        exit;
    }

    try {
        // SQL con las nuevas columnas
        $sql = "INSERT INTO cursos 
                (codigo, nombre_curso, descripcion, cupo, id_turno, id_instructor, fecha_inicio, fecha_fin)
                VALUES 
                (:codigo, :nombre_curso, :descripcion, :cupo, :id_turno, :id_instructor, :fecha_inicio, :fecha_fin)";
        
        $consulta = $conn->prepare($sql);

        $consulta->execute([
            ':codigo'        => $codigo,
            ':nombre_curso'  => $nombre_curso,
            ':descripcion'   => $descripcion,
            ':cupo'          => $cupo,
            ':id_turno'      => $id_turno,
            ':id_instructor' => $id_instructor,
            ':fecha_inicio'  => $fecha_inicio,
            ':fecha_fin'     => $fecha_fin,
        ]);

        // Éxito
        header('Location: index.php');
        exit;

    } catch (PDOException $e) {

        if ($e->getCode() == 23000) {
            echo "<p class='error'>No se pudo registrar porque repitió el código del curso.</p>";
        } else {
            echo "Ocurrió un error al insertar los datos: " . $e->getMessage();
        }
    }

} else {
    echo "<h1 class='error'>No se puede acceder directamente a este archivo</h1>";
}

?>
</body>
</html>
