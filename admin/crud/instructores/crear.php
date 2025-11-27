<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Instructor</title>
</head>
<body>
    <?php
    require_once "conexion.php";
if ($_SERVER["REQUEST_METHOD"]=="POST"){ // verificar que el método de solicitud sea POST
    $nombre=$_POST["nombre"]; 
    $apellido=$_POST["apellido"];
    $dni=$_POST["dni"];
    $telefono=$_POST["telefono"];
    $correo=$_POST["email"];
    $nacimiento=$_POST["nacimiento"];
    $domicilio=$_POST["domicilio"];
    $localidad=$_POST["localidad"];
    $postal=$_POST["postal"];
    $autos=$_POST["autos"];
    $patente=$_POST["patente"];
    $observaciones=$_POST["observaciones"];
    $activo= "1";

    // function fallido($motivo){
    //     echo"<div class='fallido'>
    //         <div class='titulo-fallido'> Registro Fallido </div>

    //         <div class='motivo'> $motivo  </div>
    //         </div>";
    // }
    if (empty($nombre)) {
        fallido("Sin Nombre");
    }
    elseif (strlen($nombre) > 50) {
        fallido("El Nombre supera el límite de caractéres");
    }
    elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $nombre)) { 
        fallido("el nombre solo puede tener letras y espacios ");
    }
    elseif (empty($apellido)) {
        fallido("Sin Apellido");
    }
    elseif (strlen($apellido) > 50) {
        fallido("El Apellido supera el límite de caractéres");
    }
    elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $apellido)) { 
        fallido("el Apellido solo puede tener letras y espacios ");
    }
    elseif (empty($dni)) {
        fallido("Sin DNI");
    }
    elseif (empty($telefono)) {
        fallido("Sin Télefono");
    }
    elseif (empty($correo)) {
        fallido("Sin Email");
    }
    elseif (empty($telefono)) {
        fallido("Sin Télefono");
    }
    elseif (empty($nacimiento)) {
        fallido("Sin Fecha de Nacimiento");
    }
    elseif (empty($domicilio)) {
        fallido("Sin Domicilio");
    }
    elseif (empty($localidad)) {
        fallido("El Domicilio no tiene localidad");
    }
    elseif (empty($postal)) {
        fallido("Sin Código Postal");
    }
    
    else {
    try {
     
            
        
        // texto de la consulta SQL con marcadores de posición
        $sql="INSERT INTO instructores (nombre, apellido, dni,fecha_nacimiento, telefono, correo, direccion, localidad, cp, activo, vehiculo, patente, observaciones) 
        VALUES (:nombre, :apellido, :dni, :nacimiento, :correo, :telefono, :direccion, :localidad, :cp , :activo, :autos, :patente, :observaciones)";
        
        $consulta=$conexion->prepare($sql); 
        $consulta->execute([':nombre'=>$nombre,':apellido'=>$apellido,':dni'=>$dni,':nacimiento' =>$nacimiento, ':correo' =>$correo, ':telefono'=>$telefono, ':direccion' =>$domicilio, ':localidad' => $localidad, ':cp' => $postal, ':activo'=> $activo, ':autos' =>$autos, ':patente' =>$patente, ':observaciones'=>$observaciones]);
        echo"<div class='exitoso'>
            <div class='titulo-exitoso'> Registro Exitoso </div>

            <div class='motivo'>  </div>

             <a href='crud_instructores.php'>Volver al Listado de Instructores</a>
            </div>
            
           ";
    } catch (PDOException $e) {
        if ($e->getCode()==23000){ // código de error para violación de clave única (DNI repetido)
           fallido("el Dni Ya Existe");
        }
        elseif ($e->getCode()=='42S22') {
            fallido("El Campo 'autos' no existe en tu tabla ");
        }
         else {
            echo "Ocurrió un error al insertar los datos: ". $e->getMessage(); // mensaje de error genérico
        }    
    }
    }
} else { // si no es método POST, mostrar mensaje de error
    echo "<h1 class='error'>Aha pillín!!!</h1>"; 
    echo "<p>$_SERVER[REQUEST_METHOD]</p>";
}

    ?>
</body>
</html>
    ?>
</body>
</html> 