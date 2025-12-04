<?php

require_once dirname(__DIR__, 3) . '/config/path.php';

// Dependencias
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/include/header.php';

// Seguridad
requireLogin();

// Conexión
$conn = conectar();


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