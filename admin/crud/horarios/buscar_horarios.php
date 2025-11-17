<?php
include_once __DIR__ . '/../../config/conexion.php';
$conn = conectar();

$q = isset($_GET['q']) ? trim($_GET['q']) : '';

$sql = "
SELECT h.id_horario, h.id_curso, h.dia_semana, h.hora_inicio, h.hora_fin,
       c.nombre_curso AS nombre_curso
FROM horarios h
LEFT JOIN cursos c ON h.id_curso = c.id_curso
WHERE (c.nombre_curso LIKE :q1 OR h.dia_semana LIKE :q2)
ORDER BY c.nombre_curso,
         FIELD(h.dia_semana, 'Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo'),
         h.hora_inicio
LIMIT 500
";

$stmt = $conn->prepare($sql);
$param = "%$q%";
$stmt->bindParam(':q1', $param, PDO::PARAM_STR);
$stmt->bindParam(':q2', $param, PDO::PARAM_STR);
$stmt->execute();
$rows = $stmt->fetchAll();

if (!$rows) {
    echo '';
    exit;
}

foreach ($rows as $r) {
    $id = htmlspecialchars($r['id_horario']);
    $curso = htmlspecialchars($r['nombre_curso'] ?? '—');
    $dia = htmlspecialchars($r['dia_semana']);
    $hi = htmlspecialchars(substr($r['hora_inicio'], 0, 5));
    $hf = htmlspecialchars(substr($r['hora_fin'], 0, 5));

    echo "<tr>";
    echo "<td>{$id}</td>";
    echo "<td>{$curso}</td>";
    echo "<td>{$dia}</td>";
    echo "<td>{$hi}</td>";
    echo "<td>{$hf}</td>";
    echo "<td class=\"acciones\">
            <a class=\"icon edit\" href=\"editar.php?id={$id}\" title=\"Editar\">✏️</a>
            <form class=\"inline-form\" method=\"post\" action=\"eliminar.php\" onsubmit=\"return confirm('¿Eliminar horario ID {$id}?');\">
              <input type=\"hidden\" name=\"id_horario\" value=\"{$id}\">
              <button class=\"icon del\" type=\"submit\" title=\"Eliminar\">🗑️</button>
            </form>
          </td>";
    echo "</tr>";
}
?>

