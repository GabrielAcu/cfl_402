<?php
require_once dirname(__DIR__, 3) . '/config/path.php';
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
    LEFT JOIN turnos ON cursos.id_turno = turnos.id_turno
    WHERE cursos.id_curso = ?
");
$consulta->execute([$id_curso]);
$registro = $consulta->fetch();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="horarios.css">
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
                                <input type="hidden" name="id_curso" value="<?= $reg["id_curso"] ?>">
                                <input type="hidden" name="id_horario" value="<?= $reg["id_horario"] ?>">
                                <input type="submit" value="✏️ Modificar">
                            </form>

                            <form action="eliminar_horario.php" method="POST" class="enlinea">
                                <input type="hidden" name="id_curso" value="<?= $reg["id_curso"] ?>">
                                <input type="hidden" name="id_horario" value="<?= $reg["id_horario"] ?>">
                                <input type="submit" value="❌ Eliminar">
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="../cursos">Volver al Listado de Cursos</a>
    <?php else: ?>
        <p>No hay horarios registrados.</p>
    <?php endif; ?>
    
    <?php
        json_encode([
            "nombre" => $registro["nombre_curso"],
            "codigo" => $registro["codigo"],
            "dias" => $registro["dia_semana"],
            "hora_inicio" => $registro["hora_inicio"],
            "hora_fin" => $registro["hora_fin"],



            
        ])
    ?>
</body>
</html>