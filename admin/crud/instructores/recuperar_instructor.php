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
    <title>Recuperar</title>
</head>
<body>
    <?php
    if ($_SERVER["REQUEST_METHOD"]=="POST"){
        if (isset($_POST["id_instructor"])) {
            try {
                
                $id_instructor=$_POST["id_instructor"];
                $consulta=$conn->prepare("UPDATE instructores SET activo=1 WHERE id_instructor=:id_instructor");
                $consulta->execute([$id_instructor]);
                if ($consulta->rowCount()>0){
                    echo "<p class='correcto'>Instructor recuperado correctamente</p>";
                    echo "<a href='index.php'>Volver al listado de instructores</a>";
                } else {
                    echo "<p class='error'>No hay instructores</p>";
                    echo "<a href='index.php'>Volver al listado de instructores</a>";
                }
            
            } catch (PDOException $e) {
                echo "<p class='error'>Error al recuperar el instructor: ".$e->getMessage()."</p>";
                echo "<a href='index.php'>Volver al listado de instructores</a>";
            }
        } 
        $consulta=$conn->query("SELECT * FROM instructores WHERE activo=0");
        echo "
        <h2>Instructores eliminados</h2>
        <table>
            <thead>
                <tr>
                    <th>nombre</th>
                    <th>apellido</th>
                    <th>dni</th>
                    <th>telefono</th>
                    <th>correo</th>
                    <th>recuperar</th>
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
                        <form action='recuperar_instructor.php' method='POST' class='enlinea'>
                            <input type='hidden' name='id_instructor' value=$registro[id_instructor]>
                            <input type='submit' value='RECUPERAR ♻️'>
                        </form>
                    </td>
                </tr>";
            } "</tbody></table>";

    } else {
        echo "<p class='error'>solicitud no válida</p>";
    }
    ?>
</body>
</html>