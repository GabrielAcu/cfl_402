<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="estilos.css">
    <title>Document</title>
</head>
<body>
    <?php
    require_once "conexion.php"; // incluir el archivo de conexión a la base de datos
    if ($_SERVER["REQUEST_METHOD"] == "POST") { // verificar que el método de solicitud sea POST
        $id_curso = $_POST['id_curso']; // obtener el id_curso enviado desde el formulario
        $codigo = $_POST['codigo']; // obtener los demás datos enviados desde el formulario
        $nombre_curso = $_POST['nombre_curso'];
        $descripcion = $_POST['descripcion'];
        $cupo = $_POST['cupo'];
        $id_instructor = $_POST["instructor"];
        $id_turno= $_POST["turno"];

        $conexion = conectar(); // establecer la conexión
        try {
        // preparar la consulta de actualización con marcadores de posición
        // en este caso, usamos signos de interrogación como marcadores
        $consulta = $conexion->prepare("UPDATE cursos SET codigo = ?, nombre_curso = ?, descripcion = ?, cupo = ?, id_instructor=?, id_turno=? WHERE id_curso = ?");
        // ejecutar la consulta pasando un array con los valores a actualizar
        // el orden de los valores en el array debe coincidir con el orden de los marcadores en la consulta
        $consulta->execute([$codigo, $nombre_curso, $descripcion, $cupo, $id_instructor, $id_turno, $id_curso]);

        if ($consulta->rowCount() > 0) { // si se modificó al menos una fila
            // si los datos a guardar en la modificación son iguales a los que ya estaban, 
            // MySQL se da cuenta que en realidad no hubo cambios y rowCount() devuelve 0
            echo "<p class='correcto'>curso modificado correctamente.</p>"; // mensaje de éxito
            echo "<a href='index.php'>Volver al Listado de cursos</a>"; // enlace para volver al listado
        } else {
            echo "<p class='error'>No se realizaron cambios.</p>"; // mensaje si no se modificaron datos
            echo "<a href='index.php'>Volver al Listado de cursos</a>"; // enlace para volver al listado
        }
        } catch (Exception $e) {
            echo "<p class='error'>Error al modificar el curso: " . $e->getMessage() . "</p>"; 
        }
    } else {
        echo "<p class='error'>Solicitud inválida.</p>"; // mensaje de error si no es método POST
    }
    ?>
</body>
</html>