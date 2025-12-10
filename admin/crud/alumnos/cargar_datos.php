<?php
require_once dirname(__DIR__, 3) . '/config/path.php';
require_once BASE_PATH . '/config/conexion.php';

$conn = conectar();

// Validar GET
if (!isset($_GET["id_alumno"])) {
    echo json_encode(["error" => "Falta id_alumno"]);
    exit;
}

$id = $_GET["id_alumno"];

// ========== OBTENER DATOS DEL ALUMNO ==============
$sql = $conn->prepare("
      SELECT *
     FROM alumnos
    WHERE id_alumno = :id
");
$sql->execute([":id" => $id]);
$alumno = $sql->fetch(PDO::FETCH_ASSOC);

if (!$alumno) {
    echo json_encode(["error" => "Alumno no encontrado"]);
    exit;
}

    // SELECT *
    // FROM alumnos
    // WHERE id_alumno = :id

// SELECT alumnos.*,
//     CASE alumnos.nombre WHEN null THEN 'No Ingresado'
//     CASE alumnos.apellido WHEN null THEN 'No Ingresado'
//     CASE alumnos.dni WHEN null THEN 'No Ingresado'
//     CASE alumnos.fecha_nacimiento WHEN null THEN 'No Ingresado'
//     CASE alumnos.telefono WHEN null THEN 'No Ingresado'
//     CASE alumnos.correo WHEN null THEN 'No Ingresado'
//     CASE alumnos.direccion WHEN null THEN 'No Ingresado'
//     CASE alumnos.localidad WHEN null THEN 'No Ingresado'
//     CASE alumnos.cp WHEN null THEN 'No Ingresado'
//     CASE alumnos.vehiculo WHEN null THEN 'No Ingresado'
//     CASE alumnos.patente WHEN null THEN 'No Ingresado'
//     CASE alumnos.observaciones WHEN null THEN 'No Ingresado'
//     ELSE 'No existe'
//     END AS Datos
//     FROM alumnos
//     WHERE id_alumno = :id


// ========== RESPUESTA JSON ==============
echo json_encode([
    "alumno" => $alumno
]);
//      alumnos.apellido,
//      alumnos.dni ,
//      alumnos.fecha_nacimiento ,
//      alumnos.telefono ,
//      alumnos.correo ,
//      alumnos.direccion ,
//      alumnos.localidad,
//      alumnos.cp,
//      alumnos.vehiculo,
//     alumnos.patente,
//      alumnos.observaciones