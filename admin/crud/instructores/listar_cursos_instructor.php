<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cursos del instructor</title>
</head>
<body>
    <?php
        require_once "conexion.php";
        if ($_SERVER["REQUEST_METHOD"]=="POST"){
            $id_curso=$_POST["id_curso"];

            $conexion=conectar();
            try {
                $consulta=$conexion->prepare("SELECT * FROM cursos WHERE id_instructor=id_instructor");
            }
        }
    ?>
</body>
</html> 
