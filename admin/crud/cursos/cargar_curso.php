<?php
require_once dirname(__DIR__, 3) . '/config/path.php';
require_once BASE_PATH . '/config/conexion.php';

$conn = conectar();
$id = $_GET["id"];

// ========== OBTENER DATOS DEL CURSO ==============
$sqlCurso = $conn->prepare("
    SELECT 
        cursos.codigo,
        cursos.nombre_curso,
        cursos.descripcion,
        cursos.cupo,
        instructores.nombre AS nom_ins,
        instructores.apellido AS ape_ins,
        turnos.descripcion AS turno_desc
    FROM cursos 
    LEFT JOIN instructores ON cursos.id_instructor = instructores.id_instructor
    LEFT JOIN turnos  ON cursos.id_turno = turnos.id_turno
    WHERE cursos.id_curso = ?   
");
$sqlCurso->execute([$id]);
$curso = $sqlCurso->fetch(PDO::FETCH_ASSOC);

// ========== OBTENER HORARIOS ==============
$sqlHor = $conn->prepare("
    SELECT 
        horarios.dia_semana AS dia,
        horarios.hora_inicio AS hora_desde,
        horarios.hora_fin AS hora_hasta
    FROM horarios 
    WHERE horarios.id_curso = ?
");
$sqlHor->execute([$id]);
$horarios = $sqlHor->fetchAll(PDO::FETCH_ASSOC);

// ========== RESPUESTA JSON ============
echo json_encode([
    "curso" => $curso,
    "horarios" => $horarios
]);
