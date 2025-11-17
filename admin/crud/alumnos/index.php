<?php
// Cargar path.php
require_once dirname(__DIR__, 2) . '/../config/path.php';

// Dependencias
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/include/header.php';

// Seguridad
requireLogin();

// Conexión
$conn = conectar();
echo "<br><br><br><br><br><br>";
echo "
<table>
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>DNI</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody> 
        <tr>
            <td>DATO 1</td>
            <td>DATO 2</td>
            <td>DATO 2</td>
            <td>
                <form action='../contacto/listar_contactos.php' method='post'>
                    <input type='hidden' name='id_entidad' value='5'>                            
                    <input type='hidden' name='tipo' value='alumno'>
                    <input type='submit' value='Contacto'>
                </form>
            </td>
        </tr>
    </tbody>
</table>
";

?>
