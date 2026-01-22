<?php
require_once dirname(__DIR__, 3) . '/config/path.php';
require_once BASE_PATH . '/config/conexion.php';

$conn = conectar();

echo "<h2>Diagnóstico de Integridad: Cursos vs Instructores</h2>";

// 1. Cursos con instructores que NO existen en la tabla instructores (Orphans)
$sql_orphans = "
    SELECT c.id_curso, c.nombre_curso, c.id_instructor
    FROM cursos c
    LEFT JOIN instructores i ON c.id_instructor = i.id_instructor
    WHERE i.id_instructor IS NULL AND c.id_instructor != 0
";

$stmt = $conn->query($sql_orphans);
$orphans = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($orphans) > 0) {
    echo "<h3 style='color:red;'>¡ALERTA! Se encontraron cursos con instructores inexistentes:</h3>";
    echo "<table border='1' cellspacing='0' cellpadding='5'>";
    echo "<tr><th>ID Curso</th><th>Nombre Curso</th><th>ID Instructor (Huérfano)</th></tr>";
    foreach ($orphans as $o) {
        echo "<tr>";
        echo "<td>{$o['id_curso']}</td>";
        echo "<td>{$o['nombre_curso']}</td>";
        echo "<td>{$o['id_instructor']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<h3 style='color:green;'>No se encontraron huérfanos absolutos (ID no existe en tabla).</h3>";
}

// 2. Cursos con instructores marcados como INACTIVOS (Soft Delete)
$sql_inactive = "
    SELECT c.id_curso, c.nombre_curso, i.id_instructor, i.nombre, i.apellido
    FROM cursos c
    JOIN instructores i ON c.id_instructor = i.id_instructor
    WHERE i.activo = 0
";

$stmt2 = $conn->query($sql_inactive);
$inactive = $stmt2->fetchAll(PDO::FETCH_ASSOC);

if (count($inactive) > 0) {
    echo "<h3 style='color:orange;'>Aviso: Cursos asignados a instructores INACTIVOS:</h3>";
    echo "<table border='1' cellspacing='0' cellpadding='5'>";
    echo "<tr><th>ID Curso</th><th>Nombre Curso</th><th>Instructor</th><th>ID Instructor</th></tr>";
    foreach ($inactive as $in) {
        echo "<tr>";
        echo "<td>{$in['id_curso']}</td>";
        echo "<td>{$in['nombre_curso']}</td>";
        echo "<td>{$in['nombre']} {$in['apellido']}</td>";
        echo "<td>{$in['id_instructor']}</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<h3>No hay cursos asignados a instructores inactivos.</h3>";
}
?>
