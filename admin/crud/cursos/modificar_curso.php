<?php
require_once dirname(__DIR__, 3) . '/config/path.php';
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/config/csrf.php';

requireLogin();
if (!isAdmin() && !isSuperAdmin()) {
    header('Location: ' . BASE_URL . '/index.php');
    exit();
}

$conn = conectar();
$id_curso = $_POST['id_curso'] ?? $_GET['id_curso'] ?? null;

if (!$id_curso) {
    // Si no hay ID, volvemos
    header('Location: index.php');
    exit();
}

// ==========================================
// PROCESAR FORMULARIO (POST)
// ==========================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'guardar') {
    requireCSRFToken();

    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre_curso'];
    $descripcion = $_POST['descripcion'];
    $cupo = $_POST['cupo'];
    $id_turno = $_POST['turno'];
    
    // Manejo de instructor (puede ser vacío para "Sin Asignar")
    $id_instructor = $_POST['instructor'] ?? '';
    if ($id_instructor === '' || $id_instructor === '0') {
        $id_instructor = null;
    }

    try {
        $sql = "UPDATE cursos SET 
                codigo = ?, 
                nombre_curso = ?, 
                descripcion = ?, 
                cupo = ?, 
                id_instructor = ?, 
                id_turno = ? 
                WHERE id_curso = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute([$codigo, $nombre, $descripcion, $cupo, $id_instructor, $id_turno, $id_curso]);

        $_SESSION['mensaje'] = "Curso actualizado correctamente.";
        header('Location: index.php');
        exit();

    } catch (PDOException $e) {
        error_log("Error actualizando curso: " . $e->getMessage());
        $error = "Error al guardar los cambios: " . $e->getMessage();
    }
}

// ==========================================
// OBTENER DATOS DEL CURSO
// ==========================================
$stmt = $conn->prepare("SELECT * FROM cursos WHERE id_curso = ?");
$stmt->execute([$id_curso]);
$curso = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$curso) {
    die("Curso no encontrado.");
}

// Obtener listas para selects
$instructores = $conn->query("SELECT id_instructor, nombre, apellido FROM instructores WHERE activo = 1 ORDER BY apellido, nombre")->fetchAll(PDO::FETCH_ASSOC);
$turnos = $conn->query("SELECT * FROM turnos")->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Curso - CFL 402</title>
    
    <!-- CSS Global y Alumnos (reusado para estilo unificado) -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/global.css?v=3.2">
    <link rel="stylesheet" href="../alumnos/alumnos2.css?v=3.2">
    
    <style>
        .form-card {
            background: var(--card-bg);
            padding: 30px;
            border-radius: 12px;
            border: 1px solid var(--border);
            max-width: 600px;
            margin: 20px auto;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: var(--text);
        }
        .form-group input, 
        .form-group select, 
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid var(--border);
            background: var(--bg);
            color: var(--text);
            box-sizing: border-box;
        }
        .btn-cancel {
            background-color: var(--surface);
            color: var(--text);
            border: 1px solid var(--border);
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
        }
        .actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }
    </style>
</head>
<body class="main_alumnos_body">

<?php require_once BASE_PATH . '/include/header.php'; ?>

<h1>Modificar Curso</h1>

<div class="form-card">
    <?php if (isset($error)): ?>
        <div style="background: rgba(220, 38, 38, 0.1); color: #ef4444; padding: 10px; border-radius: 6px; margin-bottom: 15px;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form action="modificar_curso.php" method="POST">
        <?= getCSRFTokenField() ?>
        <input type="hidden" name="accion" value="guardar">
        <input type="hidden" name="id_curso" value="<?= htmlspecialchars($curso['id_curso']) ?>">

        <div class="form-group">
            <label for="codigo">Código</label>
            <input type="text" name="codigo" id="codigo" value="<?= htmlspecialchars($curso['codigo']) ?>" required>
        </div>

        <div class="form-group">
            <label for="nombre_curso">Nombre del Curso</label>
            <input type="text" name="nombre_curso" id="nombre_curso" value="<?= htmlspecialchars($curso['nombre_curso']) ?>" required>
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <input type="text" name="descripcion" id="descripcion" value="<?= htmlspecialchars($curso['descripcion']) ?>">
        </div>

        <div class="form-group">
            <label for="cupo">Cupo</label>
            <input type="number" name="cupo" id="cupo" value="<?= htmlspecialchars($curso['cupo']) ?>" required>
        </div>

        <div class="form-group">
            <label for="turno">Turno</label>
            <select name="turno" id="turno" required>
                <?php foreach ($turnos as $t): ?>
                    <option value="<?= $t['id_turno'] ?>" <?= $t['id_turno'] == $curso['id_turno'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($t['descripcion']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="instructor">Instructor</label>
            <select name="instructor" id="instructor">
                <option value="">-- Sin Asignar --</option>
                <?php foreach ($instructores as $i): ?>
                    <option value="<?= $i['id_instructor'] ?>" <?= $i['id_instructor'] == $curso['id_instructor'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($i['apellido'] . ', ' . $i['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="actions">
            <a href="index.php" class="btn-cancel">Cancelar</a>
            <button type="submit" class="btn-primary">Guardar Cambios</button>
        </div>
    </form>
</div>

</body>
</html>