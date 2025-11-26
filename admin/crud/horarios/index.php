<?php
<<<<<<< HEAD
// Cargar path.php desde crud/alumnos (2 niveles arriba)
require_once dirname(__DIR__, 3) . '/config/path.php';

// Dependencias
=======
require_once dirname(__DIR__, 3) . '/config/path.php';
>>>>>>> 6dbbbf02e5d31fe234d00729d021a3048be77525
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/include/header.php';

requireLogin();
$conn = conectar();

// Validar que venga id_curso REAL
if (!isset($_POST["id_curso"])) {
    die("Error: no se recibió un curso válido.");
}

$id_curso = $_POST["id_curso"];

// Traer datos del curso
$consulta = $conn->prepare("
    SELECT cursos.codigo, cursos.nombre_curso, turnos.descripcion, cursos.id_curso
    FROM cursos
    LEFT JOIN turnos ON cursos.turno = turnos.id_turno
    WHERE cursos.id_curso = ?
");
$consulta->execute([$id_curso]);
$registro = $consulta->fetch();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
<<<<<<< HEAD
    <meta name="viewport" content="width=, initial-scale=1.0">
    <link rel="stylesheet" href="ver_h.css">
    <title>X</title>
</head>
<body>
    <h1>Horarios</h1>
    <?php
    $id_curso=$_POST["id_curso"];
    // $id_curso=1;

        require_once "conexion.php";
        $conexion=conectar();
        $consulta=$conexion->prepare("SELECT `cursos`.`codigo`, `cursos`.`nombre_curso`, `turnos`.`descripcion`, `cursos`.`id_curso`
            FROM `cursos`
            LEFT JOIN `turnos` ON `cursos`.`id_turno` = `turnos`.`id_turno`
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
        
        $consulta=$conexion->prepare("SELECT * FROM horarios where id_curso=?");
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
=======
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="ver_h.css">
    <title>Horarios</title>
</head>
<body>
    <h1>Horarios</h1>

    <?php if ($registro): ?>
        <h2>
            Del curso: 
            <?= $registro["codigo"] ?> - 
            <?= $registro["nombre_curso"] ?> - 
            <?= $registro["descripcion"] ?>
        </h2>
    <?php else: ?>
        <p>Curso no encontrado.</p>
    <?php endif; ?>

    <!-- Formulario para cargar horario -->
    <form action="crear_horario.php" method="POST">
        <input type="hidden" name="id_curso" value="<?= $id_curso ?>">

        <select name="dia_semana" id="dias">
            <option value="Lunes">Lunes</option>
            <option value="Martes">Martes</option>
            <option value="Miércoles">Miércoles</option>
            <option value="Jueves">Jueves</option>
            <option value="Viernes">Viernes</option>
            <option value="Sábado">Sábado</option>
        </select>

        <input type="time" name="hora_inicio" placeholder="Hora de inicio">
        <input type="time" name="hora_fin" placeholder="Hora de fin">

        <input type="submit" value="Agregar">
    </form>

    <h2>Registro de horarios</h2>

    <?php
        $consulta = $conn->prepare("SELECT * FROM horarios WHERE id_curso = ?");
        $consulta->execute([$id_curso]);

        if ($consulta->rowCount() > 0):
    ?>
        <table>
            <thead>
                <tr>
                    <th>Día</th>
                    <th>Inicio</th>
                    <th>Fin</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($reg = $consulta->fetch()): ?>
                    <tr>
                        <td><?= $reg["dia_semana"] ?></td>
                        <td><?= $reg["hora_inicio"] ?></td>
                        <td><?= $reg["hora_fin"] ?></td>
                        <td>
                            <form action="modificar_horario.php" method="POST" class="enlinea">
                                <input type="hidden" name="id_horario" value="<?= $reg["id_horario"] ?>">
                                <input type="submit" value="✏️ Modificar">
                            </form>

                            <form action="eliminar_horario.php" method="POST" class="enlinea">
                                <input type="hidden" name="id_horario" value="<?= $reg["id_horario"] ?>">
                                <input type="submit" value="❌ Eliminar">
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    <?php else: ?>
        <p>No hay horarios registrados.</p>
    <?php endif; ?>

>>>>>>> 6dbbbf02e5d31fe234d00729d021a3048be77525
</body>
</html>