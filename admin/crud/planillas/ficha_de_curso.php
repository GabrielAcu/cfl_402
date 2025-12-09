<?php

require_once dirname(__DIR__, 3) . '/config/path.php';

// Dependencias
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
<<<<<<< HEAD
require_once BASE_PATH . '/include/header.php';
=======
// require_once BASE_PATH . '/include/header.php';
>>>>>>> 27ce5aef1313346b8e4f895e4860920b8f71e2e0

// Seguridad
requireLogin();

// Conexión
$conn = conectar();
<<<<<<< HEAD


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos.css">
    <title>CFL 402 - Planillas - Ficha de Curso</title>
</head>
<body>
<?php
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory';
$documento = IOFactory::load("plantilla.xlsx");
$hoja = $documento->getActiveSheet();
// Agregar datos donde quieras
$hoja->setCellValue('B5', 'Dato agregado por PHP');
$hoja->setCellValue('B6', date('Y-m-d'));
// Descargar como archivo nuevo
header('Content-Type:
application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;
filename="plantilla_editada.xlsx"');
$writer = IOFactory::createWriter($documento, 'Xlsx');
$writer->save('php://output');
exit;
?>

</body>
</html>
=======
?>
<?php
use PhpOffice\PhpSpreadsheet\IOFactory;
if ($_SERVER["REQUEST_METHOD"]=="POST" && isset($_POST["id_curso"])){
    require 'vendor/autoload.php';

    

    $documento = IOFactory::load("plantillas/plantilla_ficha_de_curso.xlsx");
    $hoja = $documento->getActiveSheet();

    // Agregar datos donde quieras
    // $hoja->setCellValue('B5', 'Dato agregado por PHP');
    // $hoja->setCellValue('B6', date('Y-m-d'));
    $id_curso=$_POST["id_curso"];
    $consulta=$conn->prepare("SELECT * FROM alumnos
    JOIN inscripciones
    ON alumnos.id_alumno=inscripciones.id_alumno
    WHERE inscripciones.id_curso=:id_curso");
    $consulta->execute([":id_curso"=>$id_curso]);
    
    $i=5;
    while ($registro=$consulta->fetch()){
        $hoja->setCellValue('B'.$i, $registro["nombre"]);
        $hoja->setCellValue('C'.$i, $registro["apellido"]);
        $i++;
    }
    // Descargar como archivo nuevo
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="planilla_de_curso.xlsx"');

    $writer = IOFactory::createWriter($documento, 'Xlsx');
    $writer->save('php://output');
    exit;
} else {
    header("Location: index.php");
}

?>

>>>>>>> 27ce5aef1313346b8e4f895e4860920b8f71e2e0
