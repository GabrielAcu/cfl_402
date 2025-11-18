<?php

// Cargar path.php
require_once dirname(__DIR__, 2) . '/../config/path.php';


// Dependencias
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/include/header.php';

// 3. Autenticación
requireLogin();

if (!isAdmin()) {
    header('Location: cfl_402_ciro/cfl_402/index.php');
    exit();
}

// Conexión
$conn = conectar();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
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
                        <input type='hidden' name='id_entidad' value='1'>                            
                        <input type='hidden' name='tipo' value='instructor'>
                        <input type='submit' value='Contacto'>
                    </form>
                </td>
            </tr>
        </tbody>
    </table>
    ";
    ?>
</body>
</html>