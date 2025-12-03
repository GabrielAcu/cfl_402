<?php
// 1. Configuración y Auth
require_once dirname(__DIR__, 2) . '/../config/path.php';
require_once BASE_PATH . '/auth/check.php';
requireLogin();

// if (!isAdmin()) {
//     header('Location: /cfl_402/index.php');
//     exit();
// }

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

// 3. MOSTRAR MENSAJES (Si hubo error de DNI o éxito al guardar)
if (isset($_SESSION['mensaje'])) {
    $clase_alerta = str_contains($_SESSION['mensaje'], 'Error') ? 'color: red;' : 'color: green;';
    echo "<div style='padding: 10px; border: 1px solid #ccc; margin-bottom: 10px; $clase_alerta font-weight: bold;'>
            " . htmlspecialchars($_SESSION['mensaje']) . "
          </div>";
    unset($_SESSION['mensaje']); // Borrar mensaje tras mostrarlo
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

echo "<h2>Contactos de: {$nombre_mostrar}</h2><hr>";

// 5. Botón Agregar Nuevo
echo "
<form action='form_contacto.php' method='post'>
    <input type='hidden' name='id_entidad' value='{$id_entidad}'>
    <input type='hidden' name='tipo' value='{$tipo}'>
    <input type='submit' value='Agregar Contacto Nuevo'>
</form>
<br>
";

// 6. Listado de Contactos
if ($stmt_contactos->rowCount() > 0) {
    echo "<table border='1' cellpadding='10' style='border-collapse: collapse; width: 100%;'>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Parentesco</th>
                    <th>Teléfono</th>
                    <th>DNI</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>";

    while($registro = $stmt_contactos->fetch()) {
        echo "<tr>
                <td>{$registro['nombre']}</td>
                <td>{$registro['apellido']}</td>
                <td>{$registro['parentesco']}</td>
                <td>{$registro['telefono']}</td>            
                <td>{$registro['dni']}</td>            
                <td>
                    <div style='display:flex; gap:5px;'>
                        <form action='modificar_contacto.php' method='post'>
                            <input type='hidden' name='id_contacto' value='{$registro['id_contacto_alumno']}'>                            
                            <input type='submit' value='Modificar'>
                        </form>
                        
                        <form action='eliminar_contacto.php' method='post'>
                            <input type='hidden' name='id_contacto' value='{$registro['id_contacto_alumno']}'>                      
                            <input type='submit' value='Eliminar' onclick='return confirm(\"¿Estás seguro de eliminar este contacto?\");'>
                        </form>
                    </div>
                </td>
              </tr>";
    }
    echo "</tbody></table>";
} else {
    echo "<p>No hay contactos activos registrados.</p>";
}

echo "<br><br><a href='../{$individuo}/index.php'>&larr; Volver a la lista general</a><br><br>";
?>