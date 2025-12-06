<?php

// Cargar path.php
require_once dirname(__DIR__, 3) . '/config/path.php';


// Dependencias
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/include/header.php';

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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="instructores.css">
    <title>Crud Instructores</title>
</head>
<body class="light">
    <h1>Instructor</h1>
    <h2>Nuevo Instructor</h2>
    <form action="agregar_instructor.php" method="POST">
        <input type="text" name="nombre" placeholder="nombre">
        <input type="text" name="apellido" placeholder="apellido">
        <input type="number" name="dni"placeholder="dni">
        <input type="text" name="telefono"placeholder="telefono">
        <input type="text" name="correo" placeholder="correo">
        <input type="submit">
    </form>
    <hr>Listado de Instructores<hr>
    <td>
        <form action='recuperar_instructor.php' method='POST' class='enlinea'>
            <input type='hidden' name='id' value=$registro[id_instructor]>
            <input type='submit' value='Instructores eliminados 🗑️'>
            
        </form>
    <?php
    $consulta=$conn->query("SELECT * FROM instructores WHERE activo=1");
    if ($consulta->rowCount()>0){
        echo "<table>
                <thead>
                    <tr>
                        <th>nombre</th>
                        <th>apellido</th>
                        <th>dni</th>
                        <th>telefono</th>
                        <th>correo</th>
                        <td>
                            <th>acciones</th>
                            <th>contactos</th>
                            <th>cursos</th>
                            
                        </td>
                    </tr>
                </thead>
                <tbody>";
                while ($registro=$consulta->fetch()){
                    echo "
                    <tr>
                        <td>$registro[nombre]</td>
                        <td>$registro[apellido]</td>
                        <td>$registro[dni]</td>
                        <td>$registro[telefono]</td>
                        <td>$registro[correo]</td>
                        <td>
                            <form action='modificar_instructor.php' method='POST' class='enlinea'>
                                <input type='hidden' name='id' value=$registro[id_instructor]>
                                <input type='submit' value='MODIFICAR ✏️'>
                            </form>
                        </td>
                        <td>
                            <form action='eliminar_instructor.php' method='POST' class='enlinea'>
                                <input type='hidden' name='id'value=$registro[id_instructor]>
                                <input type='hidden' name='id_instructor' value=$registro[id_instructor]>
                                <input type='submit' value='ELIMINAR ❌'>
                            </form>
                        </td>
                        <td>
                            <form action='../contacto/listar_contactos.php' method='POST' class='enlinea'>
                                <input type='hidden' name='id_entidad' value=$registro[id_instructor]>
                                <input type='hidden' name='tipo' value='instructor'>
                                <input type='submit' value='CONTACTOS 📇'>
                            </form>
                        </td>
                        <td>
                            <form action='../cursos/index.php' method='POST' class='enlinea'>
                                <input type='hidden' name='id_instructor' value=$registro[id_instructor]>
                                <input type='submit' value='CURSOS 📚'>
                            </form>
                        </td>
                        
                    </tr>";
        } "</tbody></table>";
    }else {
        echo "<p>Aún no existen Instructores</p>";
    }
    ?>
</body>
</html>
