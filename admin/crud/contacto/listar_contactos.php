<?php
// 1. Configuración y Auth
require_once dirname(__DIR__, 2) . '/../config/path.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/config/csrf.php';
requireLogin();

// Si no es admin ni superadmin, afuera del panel
if (!isAdmin() && !isSuperAdmin()) {
    header('Location: /cfl_402/index.php');
    exit();
}

// Evitar error de "session already active"
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once BASE_PATH . '/config/conexion.php';

// 2. LÓGICA DE RECUPERACIÓN DE DATOS (Vital para la redirección)
// Si venimos de procesar/eliminar, usamos la sesión. Si venimos del menú, usamos POST.
if(isset($_SESSION['id_entidad_temp'])){
    $id_entidad = $_SESSION['id_entidad_temp'];
    $tipo       = $_SESSION['tipo_temp'];
    
    // Limpiamos inmediatamente para no dejar basura en la sesión
    unset($_SESSION['id_entidad_temp']);
    unset($_SESSION['tipo_temp']);
} else {
    // Usamos $_REQUEST para aceptar tanto GET (url) como POST (formularios)
    $id_entidad = $_REQUEST['id_entidad'] ?? null;
    $tipo       = $_REQUEST['tipo'] ?? null;
}

// Validación de seguridad
if ($tipo == null || $id_entidad == null) {    
    header("Location: ../../index.php");
    exit();
}

// 4. Preparar Consultas
$individuo = $tipo . 's';
$id_individuo = ($individuo == 'instructors') ? 'id_instructor' : 'id_alumno';
if ($individuo == 'instructors') $individuo = 'instructores';

$conexion = conectar();

// Traer solo los activos (=1)
$consulta_contactos = "SELECT * FROM contactos WHERE entidad_id = ? AND tipo = ? AND activo = 1";
$stmt_contactos = $conexion->prepare($consulta_contactos);
$stmt_contactos->execute([$id_entidad, $tipo]);

// Traer datos del alumno/instructor para el título
$consulta_entidad = "SELECT * FROM $individuo WHERE $id_individuo = ?";
$stmt_entidad = $conexion->prepare($consulta_entidad);
$stmt_entidad->execute([$id_entidad]);
$datos_entidad = $stmt_entidad->fetch();

$nombre_mostrar = $datos_entidad ? $datos_entidad['nombre'] . ' ' . $datos_entidad['apellido'] : 'Desconocido';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactos - CFL 402</title>
    <!-- CSS Global -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/global.css?v=3.2">
    <!-- Reusamos alumnos2.css para la tabla y estilos generales -->
    <link rel="stylesheet" href="../alumnos/alumnos2.css?v=3.2">
</head>
<body class="main_alumnos_body">
    
    <?php require_once BASE_PATH . '/include/header.php'; ?>

    <h1>Contactos de: <?= htmlspecialchars($nombre_mostrar) ?></h1>

    <!-- 3. MOSTRAR MENSAJES -->
    <?php if (isset($_SESSION['mensaje'])): ?>
        <div style="max-width: 900px; margin: 0 auto 20px auto; padding: 15px; border-radius: 8px; background: rgba(0,255,0,0.1); color: #4ade80; border: 1px solid #4ade80;">
            <?= htmlspecialchars($_SESSION['mensaje']) ?>
        </div>
        <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>

    <div class="search_container">
        <div class="search_block">
            <div class="search_row" style="justify-content: flex-end;">
                <!-- Botón Agregar Nuevo -->
                <form action="form_contacto.php" method="post" style="margin:0;">
                    <input type="hidden" name="id_entidad" value="<?= $id_entidad ?>">
                    <input type="hidden" name="tipo" value="<?= $tipo ?>">
                    <button class="btn-primary" type="submit">
                        <img class="svg_lite" src="/cfl_402/assets/svg/plus_circle.svg" alt="Nuevo">
                        Nuevo Contacto
                    </button>
                </form>
            </div>
        </div>
    </div>

    <main class="main_alumnos">
    <?php if ($stmt_contactos->rowCount() > 0): ?>
        <table class="info_table">
            <thead>
                <tr class="table_header">
                    <th class="text-left">Nombre Completo</th>
                    <th>Parentesco</th>
                    <th>Teléfono</th>
                    <th>DNI</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php while($registro = $stmt_contactos->fetch()): ?>
                <tr>
                    <td class="text-left"><strong><?= htmlspecialchars($registro['nombre']) ?></strong> <?= htmlspecialchars($registro['apellido']) ?></td>
                    <td><?= htmlspecialchars($registro['parentesco']) ?></td>
                    <td><?= htmlspecialchars($registro['telefono']) ?></td>            
                    <td><?= htmlspecialchars($registro['dni']) ?></td>            
                    <td class="td_actions">
                        <div class="acciones_wrapper">
                            <form action="modificar_contacto.php" method="post" class="enlinea">
                                <?= getCSRFTokenField() ?>
                                <input type="hidden" name="id_contacto" value="<?= $registro['id_contacto_alumno'] ?>">                            
                                <button type="submit" class="submit-button">
                                    <img class="svg_lite" src="/cfl_402/assets/svg/edit-pencil.svg" title="Modificar">
                                </button>
                            </form>
                            
                            <form action="eliminar_contacto.php" method="post" class="enlinea confirm-delete">
                                <?= getCSRFTokenField() ?>
                                <input type="hidden" name="id_contacto" value="<?= $registro['id_contacto_alumno'] ?>">                      
                                <button type="submit" class="submit-button" onclick="return confirm('¿Estás seguro de eliminar este contacto?');">
                                    <img class="svg_lite" src="/cfl_402/assets/svg/trash-can.svg" title="Eliminar">
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align: center; color: var(--text-muted);">No hay contactos activos registrados para este usuario.</p>
    <?php endif; ?>
    </main>

    <div style="text-align: center; margin-top: 30px;">
        <a href="../<?= $individuo ?>/index.php" style="color: var(--accent); text-decoration: none; font-weight: bold;">
            &larr; Volver a la lista de <?= ucfirst($individuo) ?>
        </a>
    </div>

    <br><br>

</body>
</html>