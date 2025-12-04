<?php
include 'conexion.php'; // Incluye el archivo de conexión0.........................
$pdo=conectar();
// Configuración de la paginación
$registros_por_pagina = 5; // Número de registros a mostrar por página

// Determinar la página actual
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
// Asegurarse de que la página actual sea al menos 1
$pagina_actual = max(1, $pagina_actual);

// Calcular el registro inicial para la consulta (OFFSET)
$offset = ($pagina_actual - 1) * $registros_por_pagina;

// 1. Consultar el total de registros
$stmt_total = $pdo->query("SELECT COUNT(*) FROM alumnos");
$total_registros = $stmt_total->fetchColumn();

// Calcular el total de páginas
$total_paginas = ceil($total_registros / $registros_por_pagina);

// 2. Consultar los registros para la página actual usando LIMIT y OFFSET
$sql = "SELECT * FROM alumnos ORDER BY id_alumno DESC LIMIT :registros_por_pagina OFFSET :offset";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':registros_por_pagina', $registros_por_pagina, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$resultados = $stmt->fetchAll();
// $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Paginación PDO PHP</title>
    <style>
        .pagination a {
            padding: 5px 10px;
            border: 1px solid #ccc;
            text-decoration: none;
            margin: 2px;
        }
        .pagination a.active {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>
<body>

    <h1>Registros Paginados</h1>

    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <!-- Añadir más columnas según tu tabla -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($resultados as $fila){
                echo "<tr>
                    <td>".htmlspecialchars($fila["id_alumno"])."</td>
                    <td>".htmlspecialchars($fila["nombre"])."</td>
                    <!-- Mostrar más datos -->
                </tr>";
            }
            ?>
        </tbody>
    </table>

    <div class="pagination">
        <?php if ($total_paginas > 1){
            // Enlace a la primera página 
            
            if($pagina_actual == 1){
                echo "<a href='?pagina=1' class='active'>Priemra</a>";
            } else {
                echo "<a href='?pagina=1' class=''>Priemra</a>";
            }
            
            // Enlace a la página anterior 
            if ($pagina_actual > 1){
                echo "<a href='?pagina=".($pagina_actual - 1)."'>Anterior</a>";
            }

            // Mostrar enlaces para algunas páginas (ej: 5 páginas alrededor de la actual)
            
            $rango = 2; // Número de páginas a mostrar antes y después de la actual
            for ($i = max(1, $pagina_actual - $rango); $i <= min($total_paginas, $pagina_actual + $rango); $i++){
            
                echo "<a href='?pagina=$i' class='". (($i == $pagina_actual) ? 'active':'')."'>$i</a>";
            }

            // Enlace a la página siguiente 
            if ($pagina_actual < $total_paginas){
                echo "<a href='?pagina=".($pagina_actual + 1)."'>Siguiente</a>";
            }

            // Enlace a la última página 
            echo "<a href='?pagina=$total_paginas' class='".(($pagina_actual == $total_paginas) ? 'active':'')."'>Última</a>";
        }
        ?>
    </div>
