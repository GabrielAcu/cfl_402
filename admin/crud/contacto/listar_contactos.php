<?php
// Cargar path.php
require_once dirname(__DIR__, 2) . '/../config/path.php';
require_once BASE_PATH . '/auth/check.php';
// 3. Autenticación
requireLogin();

if (!isAdmin()) {
    header('Location: /cfl_402/index.php');
    exit();
}

// Dependencias
require_once BASE_PATH . '/config/conexion.php';

$id_entidad = $_POST['id_entidad'] ?? null;
$tipo = $_POST['tipo'] ?? null;

if ($tipo == null || $id_entidad == null) {    
    header("Location: ../../index.php");
    exit();
}

echo "
<form action='form_contacto.php' method='post'>
    <input type='hidden' name='id_entidad' value='{$id_entidad}' readonly>
    <input type='hidden' name='tipo' value='{$tipo}' readonly>
    <input type='submit' value='Agregar Contacto Nuevo'>
</form>
<br><br>
";

$individuo = $tipo.'s';
$id_individuo = null;
if($individuo == 'instructors'){
    $id_individuo = 'id_instructor';
    $individuo = 'instructores';
} else {
    $id_individuo = 'id_alumno';
}

$conexion = conectar();

$consulta_contactos = "SELECT * FROM contactos WHERE entidad_id = $id_entidad AND tipo = '$tipo'";
$consulta_entidad = "SELECT * FROM $individuo WHERE $id_individuo = $id_entidad";

$contactos = $conexion->query($consulta_contactos);
$entidad = $conexion->query($consulta_entidad);

echo "Contactos de {$entidad->fetch()['nombre']} <br><hr>";

if ($contactos->rowCount() > 0) {
    require_once 'listar_contactos.php';
} else {
    echo "No hay contactos<br>";
}

if ($contactos->rowCount() > 0) {
    // var_dump($alumnos->fetch());
    while($registro = $contactos->fetch()) {
        if ($registro['activo']){
            echo "
            <table>
            <thead>
            <tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Parentesco</th>
            <th>Telefono</th>
            <th>Acciones</th>
            </tr>
            </thead>
            <tbody>";
            echo "
            <tr>
            <td>{$registro['nombre']}</td>
            <td>{$registro['apellido']}</td>
            <td>{$registro['parentesco']}</td>
            <td>{$registro['telefono']}</td>            
            <td>
                <form action='modificar_contacto.php' method='post'>
                <input type='hidden' name='id_contacto' value={$registro['id_contacto']}>                            
                <input type='submit' value='Modificar'>
                </form>
                <form action='eliminar_contacto.php' method='post'>
                <input type='hidden' name='id_contacto' value={$registro['id_contacto']}>                            
                <input type='submit' value='Eliminar'>
                </form>
            </td>
            </tr>
            ";
        }
    }
    echo "
    </tbody>
    </table>
    ";
}

echo "<a href='../{$individuo}/index.php'>Volver a la lista</a><br><br>";