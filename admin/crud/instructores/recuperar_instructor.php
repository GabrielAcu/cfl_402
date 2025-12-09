<?php
// Cargar path.php
require_once dirname(__DIR__, 3) . '/config/path.php';

// Dependencias
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/include/header.php';

// Autenticación
requireLogin();

if (!isAdmin() && !isSuperAdmin()) {
    header('Location: /cfl_402/index.php');
    exit();
}

// Conexión
$conn = conectar();

// Procesar recuperación si viene ID
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["id_instructor"])) {
    $id_instructor = filter_var($_POST["id_instructor"], FILTER_VALIDATE_INT);
    
    if (!$id_instructor) {
        header("Location: recuperar_instructor.php?error=id_invalido");
        exit;
    }

    try {
        $consulta = $conn->prepare("UPDATE instructores SET activo = 1 WHERE id_instructor = :id_instructor");
        $consulta->execute([':id_instructor' => $id_instructor]);
        
        if ($consulta->rowCount() > 0) {
            header("Location: index.php?ok=recuperado");
            exit;
        } else {
            header("Location: recuperar_instructor.php?error=no_encontrado");
            exit;
        }
    } catch (PDOException $e) {
        header("Location: recuperar_instructor.php?error=error_db");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="instructores.css">
    <title>Instructores Eliminados</title>
</head>
<body class="light">
    <h1>Instructores Eliminados</h1>
    
    <!-- Mensajes de error -->
    <?php if (isset($_GET['error'])): ?>
        <div class="mensaje-error">
            <?php
            $errores = [
                'id_invalido' => 'ID de instructor inválido',
                'no_encontrado' => 'Instructor no encontrado',
                'error_db' => 'Error en la base de datos'
            ];
            echo htmlspecialchars($errores[$_GET['error']] ?? 'Ocurrió un error');
            ?>
        </div>
    <?php endif; ?>

    <div class="acciones-superiores">
        <a href="index.php" class="btn-secondary">← Volver al Listado</a>
    </div>

    <hr>
    
    <?php
    $consulta = $conn->query("SELECT * FROM instructores WHERE activo = 0 ORDER BY apellido, nombre");
    
    if ($consulta->rowCount() > 0) {
        echo "<table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>DNI</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>";
        
        while ($registro = $consulta->fetch()) {
            echo "
                <tr>
                    <td>" . htmlspecialchars($registro['nombre']) . "</td>
                    <td>" . htmlspecialchars($registro['apellido']) . "</td>
                    <td>" . htmlspecialchars($registro['dni']) . "</td>
                    <td>" . htmlspecialchars($registro['telefono']) . "</td>
                    <td>" . htmlspecialchars($registro['correo']) . "</td>
                    <td>
                        <form action='recuperar_instructor.php' method='POST' class='enlinea' onsubmit='return confirm(\"¿Está seguro de recuperar este instructor?\");'>
                            <input type='hidden' name='id_instructor' value='" . htmlspecialchars($registro['id_instructor']) . "'>
                            <button type='submit' class='btn-primary'>RECUPERAR ♻️</button>
                        </form>
                    </td>
                </tr>";
        }
        
        echo "</tbody></table>";
    } else {
        echo "<p>No hay instructores eliminados para recuperar.</p>";
    }
    ?>
</body>
</html>