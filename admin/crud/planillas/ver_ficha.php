<?php
require_once dirname(__DIR__, 3) . '/config/path.php';
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';

requireLogin();

if (!isset($_REQUEST["id_curso"])) {
    header("Location: index.php");
    exit;
}

$conn = conectar();
$id_curso = $_REQUEST["id_curso"];

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
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha de Curso - <?= htmlspecialchars($curso['nombre_curso']) ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/global.css?v=<?php echo time(); ?>">
    <style>
        body {
            background: var(--bg);
            color: var(--text);
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
            font-family: 'Roboto', sans-serif;
        }

        /* Barra de Acciones */
        .actions-bar {
            width: 100%;
            max-width: 210mm;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--card-bg);
            padding: 15px 20px;
            border-radius: 12px;
            border: 1px solid var(--border);
            margin-bottom: 30px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            flex-wrap: wrap;
            gap: 15px;
        }

        .actions-right {
            display: flex;
            gap: 10px;
        }

        /* Botones estilo Global */
        .btn-custom {
            padding: 10px 16px;
            border-radius: 8px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
            font-size: 0.95rem;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }

        .btn-back {
            background: transparent;
            color: var(--text);
            border: 1px solid var(--border);
        }
        .btn-back:hover {
            background: var(--border);
        }

        .btn-print {
            background: var(--accent);
            color: white;
        }
        .btn-print:hover {
            background: #0056b3;
            transform: translateY(-1px);
        }

        .btn-download {
            background: #10b981; /* Green */
            color: white;
        }
        .btn-download:hover {
            background: #059669;
            transform: translateY(-1px);
        }

        .svg_icon {
            width: 20px;
            height: 20px;
        }
        
        /* HOJA A4 (VISTA EN PANTALLA: MODERNA / DARK) */
        .sheet-container {
            background: var(--card-bg); /* Fondo oscuro */
            color: var(--text);         /* Texto claro */
            width: 210mm; 
            min-height: 297mm;
            padding: 20mm;
            box-shadow: 0 0 20px rgba(0,0,0,0.5); /* Sombra más fuerte */
            margin-bottom: 40px;
            position: relative;
            border: 1px solid var(--border); /* Borde sutil */
        }

        /* Estilos internos adaptados al tema */
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid var(--accent); /* Línea de acento */
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18pt;
            text-transform: uppercase;
            color: var(--accent) !important; /* Título en color acento */
        }
        .header p {
            margin: 5px 0 0;
            font-size: 12pt;
            color: var(--text);
            opacity: 0.8;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 25px;
            font-size: 11pt;
            border: 1px solid var(--border);
            padding: 15px;
            border-radius: 8px; /* Más redondeado */
            background: rgba(255, 255, 255, 0.03); /* Fondo sutil */
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
        }
        .data-table th, .data-table td {
            border: 1px solid var(--border);
            padding: 8px 10px;
        }
        .data-table th {
            background-color: rgba(255, 255, 255, 0.05); /* Fondo header tabla */
            color: var(--accent); 
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
        }
        .data-table td {
            color: var(--text);
        }

        /* IMPRESIÓN (CONFIRMAR QUE ESTO SE IMPRIME BIEN EN BLANCO) */
        @media print {
            body { 
                background: white; 
                padding: 0; 
                margin: 0;
                -webkit-print-color-adjust: exact;
            }
            .actions-bar { display: none; }
            
            .sheet-container { 
                background: white !important;
                color: black !important;
                box-shadow: none; 
                margin: 0; 
                width: 100%; 
                border: none;
                padding: 0; /* Reset padding for print if needed */
            }
            
            .header { border-bottom: 2px solid black !important; }
            .header h1 { color: black !important; }
            .header p { color: black !important; }
            
            .info-grid { 
                border: 1px solid #333 !important; 
                color: black !important; 
                background: none !important;
            }
            
            .data-table th, .data-table td { 
                border: 1px solid #333 !important; 
                color: black !important;
            }
            .data-table th { 
                background-color: #eee !important; 
                color: black !important;
            }
            
            @page { margin: 20mm; }
        }
    </style>
</head>
<body>

    <div class="actions-bar">
        <a href="index.php" class="btn-custom btn-back">
            <img src="<?= BASE_URL ?>/assets/svg/arrow-left.svg" class="svg_icon" style="filter: invert(1) brightness(0.5);"> Volver
        </a>
        
        <div class="actions-right">
            <button onclick="window.print()" class="btn-custom btn-print">
                 <img src="<?= BASE_URL ?>/assets/svg/file-text.svg" class="svg_icon" style="filter: brightness(0) invert(1);"> Imprimir
            </button>
            <form action="ficha_de_curso.php" method="POST" style="margin:0;">
                <input type="hidden" name="id_curso" value="<?= $id_curso ?>">
                <button type="submit" class="btn-custom btn-download">
                    <img src="<?= BASE_URL ?>/assets/svg/file-excel.svg" class="svg_icon" style="filter: brightness(0) invert(1);"> Guardar Excel
                </button>
            </form>
        </div>
    </div>

    <div class="sheet-container">
        <div class="header">
            <h1>Centro de Formación Laboral N° 402</h1>
            <p>Ficha de Curso</p>
        </div>

        <div class="info-grid">
            <div><strong>Curso:</strong> <?= htmlspecialchars($curso['nombre_curso']) ?></div>
            <div><strong>Código:</strong> <?= htmlspecialchars($curso['codigo']) ?></div>
            <div><strong>Instructor:</strong> <?= htmlspecialchars($curso['instructor'] ?? 'Sin asignar') ?></div>
            <div><strong>Turno:</strong> <?= htmlspecialchars($curso['turno']) ?></div>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 40px;">N°</th>
                    <th>APELLIDO</th>
                    <th>NOMBRE</th>
                    <th>DNI</th>
                    <th>TELÉFONO</th>
                    <th>FIRMA</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($alumnos) > 0): ?>
                    <?php $i = 1; foreach ($alumnos as $alumno): ?>
                    <tr>
                        <td style="text-align: center;"><?= $i++ ?></td>
                        <td><?= htmlspecialchars($alumno['apellido']) ?></td>
                        <td><?= htmlspecialchars($alumno['nombre']) ?></td>
                        <td style="text-align: center;"><?= htmlspecialchars($alumno['dni']) ?></td>
                        <td style="text-align: center;"><?= htmlspecialchars($alumno['telefono']) ?></td>
                        <td></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 20px;">No hay alumnos inscriptos en este curso.</td>
                    </tr>
                <?php endif; ?>
                
                <!-- Filas vacías para completar hoja visualmente -->
                <?php for($j=0; $j<5; $j++): ?>
                <tr>
                    <td style="height: 25px;"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <?php endfor; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
