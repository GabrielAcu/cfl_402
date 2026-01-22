<?php
require_once dirname(__DIR__, 3) . '/config/path.php';
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';

// Cargar Autoload de Composer (ajustando la ruta relativa)
require_once BASE_PATH . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

requireLogin();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_curso"])) {
    $conn = conectar();
    $id_curso = $_POST["id_curso"];

    // 1. Obtener Datos del Curso
    $stmtCurso = $conn->prepare("
        SELECT c.*, t.descripcion as turno, CONCAT(i.apellido, ', ', i.nombre) as instructor 
        FROM cursos c
        LEFT JOIN turnos t ON c.id_turno = t.id_turno
        LEFT JOIN instructores i ON c.id_instructor = i.id_instructor
        WHERE c.id_curso = :id
    ");
    $stmtCurso->execute([':id' => $id_curso]);
    $curso = $stmtCurso->fetch(PDO::FETCH_ASSOC);

    if (!$curso) {
        die("Curso no encontrado.");
    }

    // 2. Obtener Alumnos
    $stmtAlumnos = $conn->prepare("
        SELECT a.apellido, a.nombre, a.dni, a.telefono, a.correo
        FROM alumnos a
        JOIN inscripciones ins ON a.id_alumno = ins.id_alumno
        WHERE ins.id_curso = :id_curso
        ORDER BY a.apellido, a.nombre
    ");
    $stmtAlumnos->execute([':id_curso' => $id_curso]);
    $alumnos = $stmtAlumnos->fetchAll(PDO::FETCH_ASSOC);

    // 3. Crear Excel
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Ficha de Curso');

    // --- ENCABEZADO ---
    $sheet->setCellValue('A1', 'CENTRO DE FORMACIÓN LABORAL 402');
    $sheet->mergeCells('A1:E1');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

    $sheet->setCellValue('A3', 'CURSO: ' . $curso['nombre_curso']);
    $sheet->setCellValue('D3', 'CÓDIGO: ' . $curso['codigo']);
    $sheet->setCellValue('A4', 'INSTRUCTOR: ' . ($curso['instructor'] ?? 'Sin asignar'));
    $sheet->setCellValue('D4', 'TURNO: ' . $curso['turno']);

    // --- TABLA DE ALUMNOS ---
    $row = 6;
    $headers = ['N°', 'APELLIDO', 'NOMBRE', 'DNI', 'TELÉFONO'];
    
    // Escribir cabeceras
    $col = 'A';
    foreach ($headers as $header) {
        $sheet->setCellValue($col . $row, $header);
        $sheet->getStyle($col . $row)->getFont()->setBold(true);
        $sheet->getStyle($col . $row)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle($col . $row)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFCCCCCC');
        $col++;
    }

    // Escribir datos
    $row++;
    $contador = 1;
    foreach ($alumnos as $alumno) {
        $sheet->setCellValue('A' . $row, $contador);
        $sheet->setCellValue('B' . $row, $alumno['apellido']);
        $sheet->setCellValue('C' . $row, $alumno['nombre']);
        $sheet->setCellValue('D' . $row, $alumno['dni']);
        $sheet->setCellValue('E' . $row, $alumno['telefono']);

        // Bordes
        $sheet->getStyle("A$row:E$row")->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        
        $row++;
        $contador++;
    }

    // Autoajustar columnas
    foreach (range('A', 'E') as $columnID) {
        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    }

    // 4. Descargar
    $filename = "Ficha_" . preg_replace('/[^a-zA-Z0-9]/', '_', $curso['nombre_curso']) . ".xlsx";

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;

} else {
    header("Location: index.php");
    exit;
}
?>

