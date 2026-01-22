<?php
require_once dirname(__DIR__, 3) . '/config/path.php';
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/config/csrf.php';

requireLogin();

// Validar CSRF en POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    requireCSRFToken();
} else {
    // Si intentan entrar por GET, afuera
    header('Location: ' . BASE_URL . '/admin/index.php');
    exit();
}

$conn = conectar();

// VALIDAR TIPO
$tipo = $_POST["tipo"] ?? null;
if (!$tipo) {
    die("Error: Falta tipo.");
}

// Mapear parámetros
$map = [
    "curso"      => ["id_param" => "id_curso",      "redir_input" => "id_curso"],
    "alumno"     => ["id_param" => "id_alumno",     "redir_input" => "id_alumno"],
    "instructor" => ["id_param" => "id_instructor", "redir_input" => "id_instructor"],
];

if (!isset($map[$tipo])) {
    die("Error: Tipo inválido.");
}

// Recibir IDs
$id_param_name = $map[$tipo]["id_param"];
$id_main = $_POST[$id_param_name] ?? null;

if (!$id_main) {
    die("Error: Falta ID principal ($id_param_name).");
}
$id_main = intval($id_main);

// Variable para mensaje
$mensaje = "";

// PROCESAR SEGÚN TIPO
try {
    if ($tipo === "curso" || $tipo === "alumno") {
        // ambos insertan en inscripciones
        if ($tipo === "curso") {
            $id_curso = $id_main;
            $id_alumno = intval($_POST["id_alumno"] ?? 0);
        } else { // alumno
            $id_alumno = $id_main;
            $id_curso = intval($_POST["id_curso"] ?? 0);
        }

        if (!$id_curso || !$id_alumno) {
            $_SESSION['mensaje'] = "Error: Datos incompletos.";
        } else {
            // Verificar si ya existe
            $check = $conn->prepare("SELECT id_inscripcion FROM inscripciones WHERE id_alumno = ? AND id_curso = ?");
            $check->execute([$id_alumno, $id_curso]);
            if ($check->fetch()) {
                $_SESSION['mensaje'] = "Aviso: El alumno ya está inscripto en este curso.";
            } else {
                $stmt = $conn->prepare("INSERT INTO inscripciones (id_curso, id_alumno, fecha_inscripcion) VALUES (?, ?, NOW())");
                $stmt->execute([$id_curso, $id_alumno]);
                $_SESSION['mensaje'] = "Inscripción realizada con éxito.";
            }
        }

    } elseif ($tipo === "instructor") {
        $id_instructor = $id_main;
        $id_curso = intval($_POST["id_curso"] ?? 0);

        if (!$id_instructor || !$id_curso) {
             $_SESSION['mensaje'] = "Error: Datos incompletos.";
        } else {
            // Asignar instructor al curso (UPDATE cursos table)
            // Validar que el curso exista
            $stmt = $conn->prepare("UPDATE cursos SET id_instructor = ? WHERE id_curso = ?");
            $stmt->execute([$id_instructor, $id_curso]);
            $_SESSION['mensaje'] = "Curso asignado correctamente.";
        }
    } else {
        die("Tipo no soportado");
    }

} catch (Exception $e) {
    error_log("Error DB: " . $e->getMessage());
    $_SESSION['mensaje'] = "Error en base de datos. Intente nuevamente.";
}

// Redireccionar usando GET (parametro mapped)
$redir_param = $map[$tipo]["redir_input"];
header("Location: index.php?tipo=$tipo&$redir_param=$id_main");
exit();
?>
