<?php
require_once dirname(__DIR__, 2) . '/../config/path.php';


require_once 'layouts.php';
require_once BASE_PATH . '/include/header.php';
require_once BASE_PATH . '/config/conexion.php';
$conn = conectar();

?>
<main class='modify-alumno'>

    <link rel="stylesheet" href="alumnos.css">

   <form class="new-form" action="crear.php" method="POST">
        
        <h2>Nuevo Alumno</h2>


        <h3> Información Personal </h3> 
    <div class='fila'>
        <div class="campo">
            <label for="nombre-alumno"> Nombre de Alumno: </label>
            <input class="input-modify" type="text" name="nombre" id="dni-alumno" placeholder="Nombre">
        </div>
        <div class="campo">
            <label for="apellido-alumno"> Apellido de Alumno: </label>
            <input class="input-modify" type="text" name="apellido" id="dni-alumno" placeholder="Apellido" required>
        </div>
    </div>
    
    <div class='fila'>
        <div class="campo">
            <label for="dni-alumno"> DNI de Alumno: </label>
            <input class="input-modify" type="number"  id="dni-alumno" name="dni" placeholder="DNI" required>
        </div>
    

        <div class="campo">
            <label for="email-alumno"> Email de Alumno: </label>
            <input class="input-modify" type="email" id="email-alumno" name="email" placeholder="Email" required>
        </div>
    </div>



    <div class='fila'>
        <div class="campo">
            <label for="telefono-alumno"> Télefono de Alumno: </label>
            <input class="input-modify" type="text" id="telefono-alumno" name="telefono" placeholder="Teléfono" required>
        </div>
    

        <div class="campo">
            <label for="fecha-alumno"> Fecha De Nacimiento: </label>
            <input class="input-modify" type="date" id="fecha-alumno" name="nacimiento" placeholder="Fecha de Nacimiento" required>
        </div>
    </div>

    <div class='fila'>
        <div class="campo">
            <label for="domicilio-alumno"> Domicilio: </label>
            <input class="input-modify" type="text" id="domicilio-alumno" name="domicilio" placeholder="Domicilio" required>
        </div>
    

        <div class="campo">
            <label for="localidad-alumno"> Localidad: </label>
            <input class="input-modify" type="text" id="localidad-alumno" name="localidad" placeholder="Localidad" required>
        </div>
    </div>

    <div class='fila'>
        <div class="campo">
            <label for="postal-alumno"> Código Postal: </label>
            <input class="input-modify" type="text" id="postal-alumno" name="postal" placeholder="Código Postal" required>
        </div>
    </div>

    <div class='fila'>
        <div class="campo">
            <label for="auto-alumno"> Modelo de Auto (si posee): </label>
            <input class="input-modify" type="text" id="auto-alumno" name="autos" placeholder="Modelo de Auto" >
        </div>
    

        <div class="campo">
            <label for="patente-alumno"> Patente: </label>
            <input class="input-modify" type="text" id="patente-alumno" name="patente" placeholder="Patente" >
        </div>
    </div>

    <div class='fila'>
        <div class="campo">
            <label for="observaciones-alumno"> Observaciones: </label>
            <!-- <input class="input-modify" type="text" id="observaciones-alumno" name="observaciones" placeholder="Teléfono" > -->
            <textarea class="input-modify" name="observaciones" id="observaciones-alumno" placeholder="Observaciones">

            </textarea>
        </div>
    

       
    </div>

    

    
    <div class='form_bottom'>

        <button class='boton_volver'> <a class='cancel_link' href='/crud-alumnos/crud_alumnos.php'> Cancelar </a> </button>

        <button class='boton_enviar' type='submit'> Registrar </button>
    </div>

        
    </form> 

    </main>
<!-- // <hr>

                    <div class='cs'>
                         <label class='switch' for='activo'> Activo </label>
                         <input class='input-modify' type='checkbox' name='activo' checked>
                         <span class='slider'></span>
                        <span> Activo </span>
                    </div class='campoo'>  -->

    
</body>
</html>

<?php

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {

//     $nombre      = $_POST["nombre"]; 
//     $apellido    = $_POST["apellido"];
//     $dni         = $_POST["dni"];
//     $telefono    = $_POST["telefono"];
//     $correo      = $_POST["email"];
//     $nacimiento  = $_POST["nacimiento"];
//     $domicilio   = $_POST["domicilio"];
//     $localidad   = $_POST["localidad"];
//     $postal      = $_POST["postal"];
//     $autos       = $_POST["autos"];
//     $patente     = $_POST["patente"];
//     $observaciones = $_POST["observaciones"];
//     $activo      = "1";

//     if (empty($nombre)) {
//         fallido("Sin Nombre");
//     } elseif (strlen(string: $nombre) > 50) {
//         fallido("El Nombre supera el límite de caractéres");
//     } elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $nombre)) { 
//         fallido("El nombre solo puede tener letras y espacios");
//     } elseif (empty($apellido)) {
//         fallido("Sin Apellido");
//     } elseif (strlen($apellido) > 50) {
//         fallido("El Apellido supera el límite de caractéres");
//     } elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/u', $apellido)) { 
//         fallido("El apellido solo puede tener letras y espacios");
//     } elseif (empty($dni)) {
//         fallido("Sin DNI");
//     } elseif (empty($telefono)) {
//         fallido("Sin Télefono");
//     } elseif (empty($correo)) {
//         fallido("Sin Email");
//     } elseif (empty($nacimiento)) {
//         fallido("Sin Fecha de Nacimiento");
//     } elseif (empty($domicilio)) {
//         fallido("Sin Domicilio");
//     } elseif (empty($localidad)) {
//         fallido("El Domicilio no tiene localidad");
//     } elseif (empty($postal)) {
//         fallido("Sin Código Postal");
//     } else {

//         try {

           
//             $sql = "INSERT INTO alumnos (
//                         nombre, apellido, dni, fecha_nacimiento,
//                         telefono, correo, direccion, localidad, cp,
//                         activo, vehiculo, patente, observaciones
//                     ) VALUES (
//                         :nombre, :apellido, :dni, :nacimiento,
//                         :telefono, :correo, :direccion, :localidad, :cp,
//                         :activo, :autos, :patente, :observaciones
//                     )";

//             $consulta = $conexion->prepare($sql);

//             $consulta->execute([
//                 ':nombre'        => $nombre,
//                 ':apellido'      => $apellido,
//                 ':dni'           => $dni,
//                 ':nacimiento'    => $nacimiento,
//                 ':telefono'      => $telefono,
//                 ':correo'        => $correo,
//                 ':direccion'     => $domicilio,
//                 ':localidad'     => $localidad,
//                 ':cp'            => $postal,
//                 ':activo'        => $activo,
//                 ':autos'         => $autos,
//                 ':patente'       => $patente,
//                 ':observaciones' => $observaciones
//             ]);

//             echo "
//             <div class='exitoso'>
//                 <div class='titulo-exitoso'>Registro Exitoso</div>
//                 <div class='motivo'></div>
//             </div>
//             <a href='crud_alumnos.php'>Volver al Listado de Alumnos</a>";

//         } catch (PDOException $e) {

//             if ($e->getCode() == 23000) {
//                 fallido("El DNI ya existe");
//             } elseif ($e->getCode() == '42S22') {
//                 fallido("El campo 'autos' no existe en tu tabla");
//             } else {
//                 echo "Ocurrió un error al insertar los datos: " . $e->getMessage();
//             }
//         }
//     }

// } else { 
//     echo "<h1 class='error'>Aha pillín!!!</h1>"; 
//     echo "<p>{$_SERVER['REQUEST_METHOD']}</p>";
// }

?>



<?php

 
    // header('Location: index.php');
    // exit;

?>

