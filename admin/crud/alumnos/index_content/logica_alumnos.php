<!-- // ==========================
//   BÚSQUEDA
// ========================== -->
<?php
$input = isset($_POST["search"]) ? $_POST["search"] : "";


// ==========================
//   PAGINACIÓN
// ==========================
$registros_por_pagina = 10;
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$pagina_actual = max(1, $pagina_actual);
$offset = ($pagina_actual - 1) * $registros_por_pagina;


// ==========================
//   TOTAL REGISTROS
// ==========================
$stmt_total = $conn->prepare("
    SELECT COUNT(*) FROM alumnos
    WHERE activo='1' AND (alumnos.nombre LIKE :nombre
        OR alumnos.apellido LIKE :apellido
        OR alumnos.dni LIKE :dni
        OR alumnos.telefono LIKE :telefono)
");

$stmt_total->execute([
    ":nombre" => "%$input%",
    ":apellido" => "%$input%",
    ":dni" => "%$input%",
    ":telefono" => "%$input%"
]);

$total_registros = $stmt_total->fetchColumn();
$total_paginas = ceil($total_registros / $registros_por_pagina);


// ==========================
//   CONSULTA PRINCIPAL
// ==========================
$sql = "
    SELECT alumnos.*, alumnos.nombre, alumnos.apellido, alumnos.dni, alumnos.telefono
    FROM alumnos
    WHERE activo = '1'
        AND (alumnos.nombre LIKE :nombre
            OR alumnos.apellido LIKE :apellido
            OR alumnos.dni LIKE :dni
            OR alumnos.telefono LIKE :telefono)
    ORDER BY id_alumno ASC
    LIMIT :registros_por_pagina OFFSET :offset
";

$consulta = $conn->prepare($sql);

// IMPORTANTE → NO usamos bindParam duplicado, solo execute([])

$consulta->execute([
    ":nombre" => "%$input%",
    ":apellido" => "%$input%",
    ":dni" => "%$input%",
    ":telefono" => "%$input%",
    ":registros_por_pagina" => $registros_por_pagina,
    ":offset" => $offset
]);

 
?>