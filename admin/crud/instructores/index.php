<?php
// Cargar path.php
require_once dirname(__DIR__, 3) . '/config/path.php';
// Dependencias
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/config/csrf.php';

// 3. Autenticación
requireLogin();
// Si no es admin ni superadmin, afuera del panel
if (!isAdmin() && !isSuperAdmin()) {
    header('Location: /cfl_402/index.php');
    exit();
}
// Conexión
$conn = conectar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/global.css?v=3.5">
    <link rel="stylesheet" href="instructores.css?v=3.5">
    <link rel="stylesheet" href="modal.css?v=3.5">
    <title>Instructores - CFL 402</title>
</head>
<body class="main_instructores_body">
    <?php require_once BASE_PATH . '/include/header.php'; ?>
    
    <h1>Instructores</h1>
    
    <!-- Mensajes de éxito/error -->
    <?php if (isset($_GET['ok'])): ?>
        <div class="mensaje-exito">
            <?php
            $mensajes = [
                '1' => 'Instructor creado correctamente',
                'modificado' => 'Instructor modificado correctamente',
                'eliminado' => 'Instructor eliminado correctamente'
            ];
            echo htmlspecialchars($mensajes[$_GET['ok']] ?? 'Operación realizada correctamente');
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="mensaje-error">
            <?php
            $errores = [
                'campos_vacios' => 'Por favor complete todos los campos requeridos',
                'email_invalido' => 'El correo electrónico no es válido',
                'dni_invalido' => 'El DNI no es válido',
                'dni_duplicado' => 'El DNI ya existe en la base de datos',
                'id_invalido' => 'ID de instructor inválido',
                'sin_cambios' => 'No se realizaron cambios',
                'no_encontrado' => 'Instructor no encontrado',
                'curso_asociado' => 'No se puede eliminar el instructor porque está asociado a un curso',
                'error_db' => 'Error en la base de datos'
            ];
            echo htmlspecialchars($errores[$_GET['error']] ?? 'Ocurrió un error');
            ?>
        </div>
    <?php endif; ?>

    <div class="acciones-superiores">
        <button id="btnAbrirModal" class="btn-primary">
            <img class="svg_lite" src="/cfl_402/assets/svg/plus_circle.svg" alt="Nuevo">
            Nuevo Instructor
        </button>
        <form action='recuperar_instructor.php' method='POST' class='enlinea'>
            <button type='submit' class='btn-secondary'>Instructores Eliminados 🗑️</button>
        </form>
    </div>

    <?php include 'modal.php'; ?>
    <?php include 'modalDetalles.php'; ?>

    <!-- <hr> Eliminado HR para limpieza visual -->
    <h2>Listado de Instructores</h2>
    
    <?php
    $consulta = $conn->query("SELECT * FROM instructores WHERE activo=1 ORDER BY apellido, nombre");
    if ($consulta->rowCount() > 0) {
        echo "<main class='main_instructores'>
                <table class='info_table'>
                <thead>
                    <tr>
                        <th>Ver</th>
                        <th>Instructor</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Datos Extra</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>";
        
        while ($registro = $consulta->fetch()) {
            echo "
                    <tr>
                        <td> 
                            <button class='btnVerInstructor' data-id='" . htmlspecialchars($registro['id_instructor']) . "'>
                                <img class='svg_lite' src='/cfl_402/assets/svg/eye.svg' title='Ver Detalles'>
                            </button>
                        </td>
                        <td class='text-left'><strong>" . htmlspecialchars($registro['apellido']) . "</strong>, " . htmlspecialchars($registro['nombre']) . "</td>
                        <td>" . htmlspecialchars($registro['telefono']) . "</td>
                        <td>" . htmlspecialchars($registro['correo']) . "</td>
                        
                        <td class='td_actions'>
                            <div class='acciones_wrapper'>
                                <form action='../contacto/listar_contactos.php' method='POST' class='enlinea'>
                                    <input type='hidden' name='id_entidad' value='" . htmlspecialchars($registro['id_instructor']) . "'>
                                    <input type='hidden' name='tipo' value='instructor'>
                                    <button type='submit' class='submit-button'>
                                        <img class='svg_lite' src='/cfl_402/assets/svg/contact-card.svg' title='Gestionar Contactos'>
                                    </button>
                                </form>

                                <form action='listar_cursos_instructor.php' method='POST' class='enlinea'>
                                    <input type='hidden' name='id_instructor' value='" . htmlspecialchars($registro['id_instructor']) . "'>
                                    <button type='submit' class='submit-button'>
                                        <img class='svg_lite' src='/cfl_402/assets/svg/graduation-cap.svg' title='Cursos Asignados'>
                                    </button>
                                </form>
                            </div>
                        </td>

                        <td class='td_actions2'>
                            <div class='acciones_wrapper'>
                                <button class='btnModificarInstructor' data-id='" . htmlspecialchars($registro['id_instructor']) . "'>
                                    <img class='svg_lite' src='/cfl_402/assets/svg/edit-pencil.svg' title='Modificar Datos'>
                                </button>
            
                                <form action='eliminar_instructor.php' method='POST' class='enlinea' onsubmit='return confirm(\"¿Está seguro de eliminar este instructor?\");'>
                                    " . getCSRFTokenField() . "
                                    <input type='hidden' name='id_instructor' value='" . htmlspecialchars($registro['id_instructor']) . "'>
                                    <button type='submit' class='submit-button' title='Eliminar'>
                                        <img src='/cfl_402/assets/svg/trash-can.svg' class='svg_lite'>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>";
        }
        
        echo "</tbody></table></main>";
    } else {
        echo "<p>Aún no existen Instructores</p>";
    }
    ?>
    
    <script src="modalNuevoInstructor.js"></script>
    <script src="modalEditar.js"></script>
    <script src="modalVer.js"></script>
</body>
</html>
