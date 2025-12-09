<?php
// Cargar path.php desde crud/alumnos (2 niveles arriba)
require_once dirname(__DIR__, 3) . '/config/path.php';

// Dependencias
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/config/csrf.php';
require_once BASE_PATH . '/include/header.php';

// Seguridad
requireLogin();

// Validar CSRF en POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    requireCSRFToken();
}

// Conexión
$conn = conectar();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XXXXX</title>
</head>
<body>
    <?php
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $id_horario = $_POST["id_horario"];
        // echo "ID_HORARIO:",$id_horario,$_POST["id_horario"];
        $id_curso = $_POST["id_curso"];
        // $id_curso = 1;
        $dia_semana = $_POST["dia_semana"];
        $hora_inicio = $_POST["hora_inicio"];
        $hora_fin = $_POST["hora_fin"];

        
        // echo "ID_HORARIO:",$id_horario,$_POST["id_horario"];
        try{
            $consulta = $conn->prepare("UPDATE horarios SET id_curso = ?, dia_semana = ?, hora_inicio = ?, hora_fin = ? WHERE id_horario = ?");
            $consulta->execute([$id_curso, $dia_semana, $hora_inicio, $hora_fin, $id_horario]);
            if($consulta->rowCount()>0){
                echo"<p>Horario modificado correctamente.</p>";

                // echo"<a href='index.php'>Volver al inicio.</a>";
            }else {
                echo "No se modificó ningún horario";
            }
                echo "<form action='index.php' method='POST'>
                <input type='hidden' value='$id_curso' name='id_curso'>
                <input type='submit' value='Volver al Listado de Horarios'>
                </form>";
        }catch (Exception $e) {
            echo "<p class='error'>Error al modificar el horario: " . $e->getMessage() . "</p>";
        }
    };
    ?>
</body>
</html>