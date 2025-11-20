<?php
// Cargar path.php desde crud/alumnos (2 niveles arriba)
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
    <meta name="viewport" content="width=, initial-scale=1.0">
    <link rel="stylesheet" href="ver_h.css">
    <title>X</title>
</head>
<body>
    <h1>Horarios</h1>
    <?php
    $id_curso=$_POST["id_curso"];
    // $id_curso=1;
        $consulta=$conn->prepare("SELECT `cursos`.`codigo`, `cursos`.`nombre_curso`, `turnos`.`descripcion`, `cursos`.`id_curso`
            FROM `cursos`
            LEFT JOIN `turnos` ON `cursos`.`turno` = `turnos`.`id_turno`
            WHERE (`cursos`.`id_curso` =?)");
        $consulta->execute([$id_curso]);
        $registro=$consulta->fetch();
        if ($consulta->rowCount()>0){
            echo "<h2>Del curso: $registro[codigo] - $registro[nombre_curso] - $registro[descripcion]</h2>" ;
        }

    ?>
    <form action="crear_horario.php" method= "POST">
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
    </form> 

      
    <h2>Registro de horarios</h2>

    <?php
        
        $consulta=$conn->prepare("SELECT * FROM horarios where id_curso=?");
        $consulta->execute([$id_curso]);
        // $consulta=$conexion->query("SELECT * FROM horarios where id_curso=$id_curso");
        if ($consulta->rowCount()>0){
            echo "
            <table>
                <thead>
                    <tr>
                        <th>Días de la semnana</th>
                        <th>Horario de inicio</th>
                        <th>Horario de finalización</th>
                    </tr>
                </thead>
                <tbody>";
            while ($registro=$consulta->fetch()){
                echo "
                <tr>
                    <td>$registro[dia_semana]</td>
                    <td>$registro[hora_inicio]</td>
                    <td>$registro[hora_fin]</td>
                    <td>
                        <form action='modificar_horario.php' method='POST' class='enlinea'>
                            <input type='hidden' name='id_horario' value='$registro[id_horario]'>
                            <input type='submit' value='✏️ Modificar'>
                        </form>
                        <form action='eliminar_horario.php' method='POST' class='enlinea'>
                            <input type='hidden' name='id_horario' value='$registro[id_horario]'>
                            <input type='submit' value='❌ Eliminar'>
                        </form>
                    </td>
                </tr>
                ";
            }
            echo "
                </tbody>
            </table>
            ";
        } else {
            echo "<p>No hay horarios registrados.</p>";
        }

    ?>
</body>
</html>