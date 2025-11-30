<?php
require_once dirname(__DIR__, 3) . '/config/path.php';
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/include/header.php';

requireLogin();
$conn = conectar();

// echo "<pre>";
// print_r($_POST);
// echo "</pre>";
// exit;


// Validar entrada
// 

// Validar entrada
$tipo = $_POST["tipo"] ?? $_GET["tipo"] ?? null;
$volver = $_POST["volver"] ?? $_GET["volver"] ?? null;

if (!$tipo) {
    die("Error: acceso inválido a inscripciones (falta tipo).");
}

// buscar id por POST o GET
$map = [
    "curso"      => "id_curso",
    "alumno"     => "id_alumno",
    "instructor" => "id_instructor"
];

if (!isset($map[$tipo])) {
    die("Error: tipo inválido en inscripciones.");
}

$param = $map[$tipo];
$id = $_POST[$param] ?? $_GET[$param] ?? null;

if (!$id) {
    die("Error: falta $param.");
}

$id = intval($id);


// ==========================
// CARGA DE DATOS SEGÚN TIPO
// ==========================

switch ($tipo) {

    case "alumno":
        $stmt = $conn->prepare("SELECT id_alumno, apellido, nombre FROM alumnos WHERE id_alumno = ?");
        $stmt->execute([$id]);
        $origen = $stmt->fetch();
        if (!$origen) die("Alumno no encontrado.");

        // Cursos disponibles
        $cursos = $conn->query("SELECT * FROM cursos WHERE activo = 1 ORDER BY nombre_curso")->fetchAll();

        // Inscripciones previas del alumno
        $inscripciones = $conn->prepare("
            SELECT i.id_inscripcion, c.nombre_curso, i.fecha_inscripcion
            FROM inscripciones i
            JOIN cursos c ON i.id_curso = c.id_curso
            WHERE i.id_alumno = ?
        ");
        $inscripciones->execute([$id]);
    break;

    case "curso":
        $stmt = $conn->prepare("SELECT id_curso, nombre_curso FROM cursos WHERE id_curso = ?");
        $stmt->execute([$id]);
        $origen = $stmt->fetch();
        if (!$origen) die("Curso no encontrado.");

        // Alumnos disponibles
        $alumnos = $conn->query("SELECT * FROM alumnos WHERE activo = 1 ORDER BY apellido, nombre")->fetchAll();

        // Inscriptos en este curso
        $inscripciones = $conn->prepare("
            SELECT i.id_inscripcion, a.apellido, a.nombre, i.fecha_inscripcion
            FROM inscripciones i
            JOIN alumnos a ON i.id_alumno = a.id_alumno
            WHERE i.id_curso = ?
        ");
        $inscripciones->execute([$id]);
    break;

    case "instructor":
        $stmt = $conn->prepare("SELECT id_instructor, apellido, nombre FROM instructores WHERE id_instructor = ?");
        $stmt->execute([$id]);
        $origen = $stmt->fetch();
        if (!$origen) die("Instructor no encontrado.");

        // Cursos asignados al instructor
        $cursos = $conn->prepare("
            SELECT c.id_curso, c.nombre_curso
            FROM cursos c
            WHERE c.id_instructor = ?
        ");
        $cursos_disponibles = $conn->query("
            SELECT id_curso, nombre_curso 
            FROM cursos 
            WHERE activo = 1
        ")->fetchAll();
        $cursos->execute([$id]);

        // Alumnos de esos cursos
        $inscripciones = $conn->prepare("
            SELECT i.id_inscripcion, i.fecha_inscripcion, a.apellido, a.nombre, c.nombre_curso
            FROM inscripciones i
            JOIN alumnos a ON i.id_alumno = a.id_alumno
            JOIN cursos c ON i.id_curso = c.id_curso
            WHERE c.id_instructor = ?
        ");
        $inscripciones->execute([$id]);
    break;

    default:
        die("Origen inválido.");
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inscripciones</title>
    <link rel="stylesheet" href="inscripciones.css">
</head>
<body>

<h2>Inscripciones</h2>

<!-- ===================== -->
<!--     INFORMACIÓN       -->
<!-- ===================== -->
<!--  DESDE TABLA ALUMNOS  -->
<?php if ($tipo === "alumno"): ?>
    <h3>Alumno: <?= $origen["apellido"] . ", " . $origen["nombre"] ?></h3>

    <form action="crear.php" method="POST">
        <input type="hidden" name="tipo" value="alumno">
        <input type="hidden" name="id_alumno" value="<?= $id ?>">

        <label>Curso a inscribir:</label>
        <select name="id_curso">
            <?php foreach ($cursos as $c): ?>
                <option value="<?= $c["id_curso"] ?>"><?= $c["nombre_curso"] ?></option>
            <?php endforeach; ?>
        </select>

        <input type="submit" value="Inscribir">
    </form>

<!-- DESDE TABLA CURSOS -->

<?php elseif ($tipo === "curso"): ?>
    <h3>Curso: <?= $origen["nombre_curso"] ?></h3>

    <form action="crear.php" method="POST">
        <input type="hidden" name="tipo" value="curso">
        <input type="hidden" name="id_curso" value="<?= $id ?>">

        <label>Alumno a inscribir:</label>
        <select name="id_alumno">
            <?php foreach ($alumnos as $a): ?>
                <option value="<?= $a["id_alumno"] ?>">
                    <?= $a["apellido"] . ", " . $a["nombre"] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <input type="submit" value="Inscribir">
    </form>

<!-- DESDE TABLA INSTRUCTORES -->
 
<?php elseif ($tipo === "instructor"): ?>
    <h3>Instructor: <?= $origen["apellido"] . ", " . $origen["nombre"] ?></h3>

    <h4>Cursos a cargo:</h4>
    <ul>
        <?php foreach ($cursos as $c): ?>
            <li><?= $c["nombre_curso"] ?></li>
        <?php endforeach; ?>
    </ul>
    <h4>Asignar nuevo curso:</h4>

    <form action="crear.php" method="POST">
        <input type="hidden" name="tipo" value="instructor">
        <input type="hidden" name="id_instructor" value="<?= $id ?>">

        <label>Curso:</label>
        <select name="id_curso">
            <?php foreach ($cursos_disponibles as $c): ?>
                <option value="<?= $c["id_curso"] ?>"><?= $c["nombre_curso"] ?></option>
            <?php endforeach; ?>
        </select>

        <input type="submit" value="Asignar">
    </form>


<?php endif; ?>

<!-- ===================== -->
<!-- LISTA DE INSCRIPCIONES -->
<!-- ===================== -->

<h3>Registros</h3>

<table>
    <tr>
        <th>ID</th>
        <?php if ($tipo !== "curso"): ?><th>Curso</th><?php endif; ?>
        <?php if ($tipo !== "alumno"): ?><th>Alumno</th><?php endif; ?>
        <th>Fecha</th>
    </tr>

    <?php foreach ($inscripciones as $i): ?>
        <tr>
            <td><?= $i["id_inscripcion"] ?></td>

            <?php if ($tipo !== "curso"): ?>
                <td><?= $i["nombre_curso"] ?? '' ?></td>
            <?php endif; ?>

            <?php if ($tipo !== "alumno"): ?>
                <td><?= ($i["apellido"] ?? '') . " " . ($i["nombre"] ?? '') ?></td>
            <?php endif; ?>

            <td><?= $i["fecha_inscripcion"] ?? '' ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<?php if ($volver): ?>
    <a href="../<?= $volver ?>/index.php" class="btn-volver">⬅ Volver</a>
<?php endif; ?>
</body>
</html>
