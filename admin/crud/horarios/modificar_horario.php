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

// ID Horario puede venir por POST (desde index) o por el mismo POST del formulario
$id_horario = $_POST["id_horario"] ?? null;
$id_curso = $_POST["id_curso"] ?? null;

if (!$id_horario) {
    // Si no hay horario, volver
    header('Location: ' . BASE_URL . '/admin/crud/cursos/index.php');
    exit();
}

// ==========================================
// PROCESAR MODIFICACIÓN (POST GUARDAR)
// ==========================================
if (isset($_POST["accion"]) && $_POST["accion"] === "guardar") {
    requireCSRFToken();
    
    $dia_semana = $_POST["dia_semana"];
    $hora_inicio = $_POST["hora_inicio"];
    $hora_fin = $_POST["hora_fin"];
    
    try {
        $sql = "UPDATE horarios SET dia_semana = ?, hora_inicio = ?, hora_fin = ? WHERE id_horario = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$dia_semana, $hora_inicio, $hora_fin, $id_horario]);
        
        // Redirect a index.php con id_curso
        header("Location: index.php?id_curso=$id_curso&ok=updated");
        exit();

    } catch (PDOException $e) {
        error_log("Error actualizando horario: " . $e->getMessage());
        $error = "Error al actualizar.";
    }
}

// ==========================================
// OBTENER DATOS
// ==========================================
$stmt = $conn->prepare("SELECT * FROM horarios WHERE id_horario = ?");
$stmt->execute([$id_horario]);
$horario = $stmt->fetch();

if (!$horario) {
    die("Horario no encontrado.");
}
// Asegurar que tengamos id_curso
if (!$id_curso) {
    $id_curso = $horario['id_curso'];
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Horario - CFL 402</title>
    <!-- CSS Global -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/global.css?v=3.2">
    <link rel="stylesheet" href="../alumnos/alumnos2.css?v=3.2">

</head>
<body class="main_alumnos_body">

<?php require_once BASE_PATH . '/include/header.php'; ?>

<h1>Modificar Horario</h1>

<div class="form-card">
    <?php if (isset($error)) echo "<p style='color:red'>$error</p>"; ?>

    <form action="modificar_horario.php" method="POST">
        <?= getCSRFTokenField() ?>
        <input type="hidden" name="accion" value="guardar">
        <input type="hidden" name="id_horario" value="<?= $id_horario ?>">
        <input type="hidden" name="id_curso" value="<?= $id_curso ?>">

        <div class="form-group">
            <label>Día de la Semana</label>
            <select name="dia_semana">
                <?php 
                $dias = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"];
                foreach($dias as $d): ?>
                    <option value="<?= $d ?>" <?= $d === $horario['dia_semana'] ? 'selected' : '' ?>>
                        <?= $d ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Hora Inicio</label>
            <input type="time" name="hora_inicio" value="<?= $horario['hora_inicio'] ?>" required>
        </div>

        <div class="form-group">
            <label>Hora Fin</label>
            <input type="time" name="hora_fin" value="<?= $horario['hora_fin'] ?>" required>
        </div>

        <div class="actions">
            <!-- Volver enviando POST para mantener contexto del curso en index -->
            <!-- Link simple usando GET podría ser problemático si index.php exige POST, 
                 pero index.php modificado ahora acepta $_GET['id_curso'] también. -->
            <a href="index.php?id_curso=<?= $id_curso ?>" class="btn-cancel">Cancelar</a>
            
            <button type="submit" class="btn-primary">Guardar Cambios</button>
        </div>
    </form>
</div>

</body>
</html>