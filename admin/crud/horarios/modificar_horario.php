<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XXXX</title>
</head>
<body>
    <?php
    require_once "conexion.php";
    if ($_SERVER["REQUEST_METHOD"]=="POST"){
        $conexion=conectar();
        $id_horario=$_POST["id_horario"];
        
        $texto="SELECT * FROM horarios WHERE id_horario=$id_horario";
        $consulta=$conexion->prepare($texto);
        // $consulta->bindParam(' :id_horario',$id_horario);
        $consulta->execute();
        $horario=$consulta->fetch();
        // var_dump($horario['dia_semana']);
        // exit();
        $id_curso=$_POST["id_curso"];
        // $id_curso=1;
          echo "ID_HORARIO:",$id_horario;
        if ($horario){
            $arreglo=["Lunes"=> "", "Martes"=> "", "Miércoles"=> "", "Jueves"=> "", "Viernes"=> "", "Sábado"=> ""];
            $arreglo[$horario["dia_semana"]]="selected";
            echo "<h2>Modificar Horario</h2>
        <form action='procesar_horarios.php' method='POST'>
            <input type='hidden' name='id_horario' value  ='$id_horario'>
                <select name='dia_semana'>
                    <option value='Lunes' $arreglo[Lunes]>Lunes</option>
                    <option value='Martes' $arreglo[Martes]>Martes</option>
                    <option value='Miércoles' $arreglo[Miércoles]>Miercoles</option>
                    <option value='Jueves' $arreglo[Jueves]>Jueves</option>
                    <option value='Viernes' $arreglo[Viernes]>Viernes</option>
                    <option value='Sábado' $arreglo[Sábado]>Sabado</option>
                </select>
            <input type='time' name='hora_inicio' placeholpder='hora_inicio' value='$horario[hora_inicio]'>
            <input type='time' name='hora_fin' placeholpder='hora_fin' value='$horario[hora_fin]'>
            <input type='submit'>
        </form>";
        }
    }else {
        echo "error";
        echo "<p>$_SERVER[REQUEST_METHOD]</p>";
    }
    ?>

    
</body>
</html>