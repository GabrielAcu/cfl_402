<?php

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
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XX</title>
</head>
<body>
    <!-- <h1>Crear horario</h1>
    <ul>
        <li><a href="index.php">Volver al inicio</a></li>
    </ul> -->

    <?php
    $id_curso=$_POST["id_curso"];
    // $id_curso=1;
   
    ?>
    <!-- <form action="crear_horario.php" method= "POST">
        <input type="hidden" name="id_curso" values="id_curso">
        <select name="dia_semana" id="dias">
            <option value="Lunes">Lunes</option>
            <option value="Martes">Martes</option>
            <option value="Miércoles">Miercoles</option>
            <option value="Jueves">Jueves</option>
            <option value="Viernes">Viernes</option>
            <option value="Sábado">Sabado</option>
        </select>
        <input type="time" name="hora_inicio" placeholpder="hora_inicio">
        <input type="time" name="hora_fin" placeholpder="hora_fin">
        <input type="submit">
    </form>  -->

    <?php
    
    if ($_SERVER["REQUEST_METHOD"]=="POST"){
        $conn=conectar();
        $dia_semana=$_POST["dia_semana"];
        $hora_inicio=$_POST["hora_inicio"];
        $hora_fin=$_POST["hora_fin"];

        try {
            $sql="INSERT INTO horarios (id_curso, dia_semana, hora_inicio, hora_fin) 
            VALUES (:id_curso, :dia_semana, :hora_inicio, :hora_fin)";
            $consulta=$conn->prepare($sql);
            $consulta->execute([':id_curso'=>$id_curso,':dia_semana'=>$dia_semana,':hora_inicio'=>$hora_inicio,':hora_fin'=>$hora_fin]);
            echo "<p class='correcto'>Se registró exitosamente</p>";     


            echo "<form action='index.php' method='POST'>
                <input type='hidden' value='$id_curso' name='id_curso'>
                <input type='submit' value='Volver al Listado de Horarios'>
                </form>";
            // <a href='index.php?id_curso=$id_curso'>Volver al Listado de Horarios</a>";
        } catch (PDOException $e) {
            error_log("Error DB: " . $e->getMessage());
            echo "Ocurrió un error al insertar los datos. Por favor contacte al administrador.";
        }
    } else {
        // echo "<h1 class='error'>Aha pillín!!!</h1>";
        "<p>$_SERVER[REQUEST_METHOD]</p>";
    }   
    ?>
</body>
</html>