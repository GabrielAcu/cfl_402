<?php

// Cargar path.php
require_once dirname(__DIR__, 2) . '/../config/path.php';


// Dependencias
require_once BASE_PATH . '/config/conexion.php';
require_once BASE_PATH . '/auth/check.php';
require_once BASE_PATH . '/include/header.php';

// 3. Autenticación
requireLogin();

// Conexión
$conn = conectar();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar</title>
</head>
<body>
    <?php
    if ($_SERVER["REQUEST_METHOD"]=="POST"){
        $id_instructor=$_POST["id_instructor"];

        $consulta=$conn->query("SELECT * FROM instructores WHERE activo=0");
        echo "
        <h2>Instructores eliminados</h2>
        <table class='info_table'>
                <thead>
                    <tr class='table_header'>
                        <th class='table_th'>nombre</th>
                        <th class='table_th'>apellido</th>
                        <th class='table_th'>dni</th>
                        <th class='table_th'>telefono</th>
                        <th class='table_th'>correo</th>
                        <th class='table_th'>acciones</th>
                        <th  colspan='2' class='table_th_final'>Datos</th>
                    
                    </tr>
            <tbody>";
            while ($registro=$consulta->fetch()){
                echo "
                <tr>
                    <td class='td_name'>$registro[nombre]</td>
                        <td class='td_name2'>$registro[apellido]</td>
                        <td class='td_data'>$registro[dni]</td>
                        <td  class='td_data'>$registro[telefono]</td>
                        <td  class='td_data'>$registro[correo]</td>
                    <td class='td_actions'>
                        <form action='recuperar_instructor.php' method='POST' class='enlinea'>
                            <input type='hidden' name='id_instructor' value=$registro[id_instructor]>
                            <input type='submit' value='RECUPERAR ♻️'>
                        </form>
                    </td>
                </tr>";
            } "</tbody></table>";
        try {
            $consulta=$conn->prepare("UPDATE instructores SET activo=1 WHERE id_instructor=:id_instructor");
            $consulta->execute([$id_instructor]);
            if ($consulta->rowCount()>0){
                echo "<p class='correcto'>Instructor recuperado correctamente</p>";
                echo "<a href='index.php'>Volver al listado de instructores</a>";
            } else {
                echo "<p class='error'>El instructor no existe</p>";
                echo "<a href='index.php'>Volver al listado de instructores</a>";
            }
        } catch (PDOException $e) {
            echo "<p class='error'>Error al recuperar el instructor: ".$e->getMessage()."</p>";
            echo "<a href='index.php'>Volver al listado de instructores</a>";
        }
    } else {
        echo "<p class='error'>solicitud no válida</p>";
    }
    ?>
</body>
</html>