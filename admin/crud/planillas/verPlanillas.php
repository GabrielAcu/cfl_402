<?php

// Ajusta la ruta al autoload según la estructura de tu proyecto.
// En tu caso: desde admin/crud/planillas hacia vendor era ../../../vendor/autoload.php
require_once dirname(__DIR__, 3) . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Html;

// Crear hoja de cálculo 
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Título centrado y combinado
$sheet->mergeCells('A1:D1');// sheet es la hoja activa, mergeCells combina celdas
$sheet->setCellValue('A1', 'PLANILLA DE CURSO');// setCellValue asigna valor a celda
$sheet->getStyle('A1')->getFont()->setBold(true);// Poner en negrita

// Instructor
$sheet->mergeCells('A2:B2');//celda desde A2 hasta C2
$sheet->setCellValue('A2', 'Instructor: Carlos Gómez');// Asignar valor

// Encabezados
$sheet->setCellValue('A4', 'Nombre completo');//asignamos Nombre Completo como encabezado
$sheet->setCellValue('B4', 'Legajo');//asignamos Legajo como encabezado

// Datos
$sheet->setCellValue('A5', 'Juan Pérez');   // Nombre + apellido juntos
$sheet->setCellValue('B5', '1234');


// Usamos el writer HTML para imprimir como tabla en el navegador
$writer = new Html($spreadsheet);

// No enviamos headers de descarga: solo devolvemos HTML
echo '<!doctype html><html><head><meta charset="utf-8"><title>Vista Planilla</title>';
// Puedes agregar estilos para que se vea mejor
echo '<style>
  body{font-family: Arial, Helvetica, sans-serif; margin:20px;}
  table{border-collapse: collapse;}
  table td, table th {border:1px solid #ccc; padding:6px 8px;}
  h1{font-size:18px;}
</style>';
echo '</head><body>';
$writer->save('php://output');
echo '</body></html>';
