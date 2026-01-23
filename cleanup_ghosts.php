<?php
require_once __DIR__ . '/config/path.php';
require_once BASE_PATH . '/config/conexion.php';

$conn = conectar();

echo "<h2>Limpieza de Huérfanos Históricos</h2>";

// 1. Encontrar cursos con instructores inactivos
$sql = "UPDATE cursos c
        JOIN instructores i ON c.id_instructor = i.id_instructor
        SET c.id_instructor = NULL
        WHERE i.activo = 0";

$stmt = $conn->prepare($sql);
$stmt->execute();
$count = $stmt->rowCount();

if ($count > 0) {
    echo "<strong style='color:green'>Se han desasignado $count cursos de instructores inactivos. Ahora aparecerán como 'Sin asignar'.</strong>";
} else {
    echo "No se encontraron cursos vinculados a instructores inactivos.";
}
?>
