<?php
require_once dirname(__DIR__, 3) . '/config/path.php';
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';

requireLogin();
$conn = conectar();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id_alumno = $_POST['id_alumno'];

    try {
        $consulta = $conn->prepare("UPDATE alumnos SET activo = 0 WHERE id_alumno = ?");
        $consulta->execute([$id_alumno]);

        // Redirigir SIEMPRE al index
        header("Location: index.php?eliminado=1");
        exit;

    } catch (Exception $e) {

        // Si no se puede por FK
        header("Location: index.php?error=1");
        exit;
    }

} else {
    // Si se accede directo sin POST
    header("Location: index.php");
    exit;
}
