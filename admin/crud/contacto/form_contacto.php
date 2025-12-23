<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Contactos</title>
    <link rel="stylesheet" href="contactos2.css">
</head>
<body>
    
</body>
</html>
<?php
session_start();
// 3. Autenticación
require_once dirname(__DIR__, 2) . '/../config/path.php';
require_once BASE_PATH . '/auth/check.php';
requireLogin();

if (!isAdmin() && !isSuperAdmin()) {
    header('Location: /cfl_402/index.php');
    exit();
}

$id_entidad = $_POST['id_entidad'] ?? $_SESSION['entidad_id'] ?? null;
$tipo = $_POST['tipo'] ?? $_SESSION['tipo'] ?? null;
$mensaje_session = $_SESSION['mensaje_de_session'] ?? null;

// echo($id_entidad)."<br>";
// echo($tipo)."<br>";
// echo($mensaje_session);
// exit();

if ($tipo == null || $id_entidad == null) {    
    header("Location: ../../index.php");
    exit();
}


// echo "Agregar contacto<br><br>";

require_once BASE_PATH . '/config/csrf.php';

echo "
<form action='procesar_contacto.php' method='post' id='modalContacto' class='form-contacto'>
    " . getCSRFTokenField() . "


    <div class='form-wrapper'>
        <div class='form-scroll'>

            <h3> Nuevo Contacto </h3>  

            <div class='fila'>
                <div class='campo'>
                    <label for='nombre-contacto'> Nombre de contacto: </label>
                    <input class='input-modify' id='nombre-contacto' type='text' name='nombre' placeholder='Nombre' required>
                </div>
            
                <div class='campo'>
                    <label for='apellido-contacto' > Apellido de contacto: </label>
                    <input class='input-modify' id='apellido-contacto' type='text' name='apellido' placeholder='Apellido' required>
                </div
            </div>

            <div class='fila'>
                <div class='campo'>
                    <label for='dni-contacto'> DNI de contacto: </label>
                    <input class='input-modify' id='dni-contacto' type='number' name='dni' placeholder='DNI' required>
                </div>
                <div class='campo'>
                    <label for='telefono-contacto'> Teléfono de contacto: </label>
                    <input class='input-modify' id='telefono-contacto' type='number' name='telefono' placeholder='Telefeno' required>
                </div>
            </div>

            <div class='fila'>
                <div class='campo'>
                    <label for='correo-contacto'> Correo de contacto: </label>
                    <input class='input-modify' id='correo-contacto' type='email' name='correo' placeholder='Correo' required>
                </div>
                <div class='campo'>
                    <label for='direccion-contacto'> Dirección de contacto: </label>
                    <input class='input-modify' id='direccion-contacto' type='adress' name='direccion' placeholder='Dirección' required>
                </div>        
            </div>

            <div class='fila'>
                <div class='campo'>
                    <label for='localidad-contacto'> Localidad de Contacto: </label>
                    <input class='input-modify' id='localidad-contacto' type='text' name='localidad' placeholder='Localidad' required>
                </div>

                <div class='campo'>
                    <label for='cp-contacto'> Código Postal de contacto: </label>
                    <input class='input-modify' id='cp-contacto' type='text' name='cp' placeholder='Código Postal' required>
                </div>

            </div>

            <div class='fila'>
                <div class='campo'>
                    <label for='parentesco-contacto'> Parentesco de contacto: </label>
                    <input class='input-modify' 'parentesco-contacto' type='text' name='parentesco' placeholder='Parentesco'required>
                </div>

                <div class='campo'>
                    <label for='observaciones-contacto'> Observaciones de Contacto: </label>
                    <textarea class='input-modify' id='observaciones-contacto' name='observaciones' placeholder='Observaciones'></textarea>
                </div>    
                </div>

            <div class='fila'>
                <div class='campo'>
                    <label for='entidad-contacto'> Entidad de Contacto: </label>
                    <input class='input-modify' id='entidad-contacto' type='number' name='id_entidad' value='{$id_entidad}' readonly>
                </div>
                <div class='campo'>
                    <label for='tipo-contacto'>Tipo de Contacto: </label>
                    <input  class='input-modify' id='tipo-contacto' type='text' name='tipo' value='{$tipo}' readonly>
                </div>
            </div>

            <input class='btn-primary' type='submit' value='Enviar'>
        </div>

        

    </div>
       

    
</form>
";

$individuo = $tipo.'s';
if($individuo == 'instructors'){
    $individuo = 'instructores';
}
echo "<br><hr>";
echo "
<form action='listar_contactos.php' method='post'>
    <input type='hidden' name='id_entidad' value='{$id_entidad}' readonly>
    <input type='hidden' name='tipo' value='{$tipo}' readonly>
    <input class='btnCancel' type='submit' value='Volver a lista de contactos'>
</form><br><br>
";
echo "<br><br>";


if (isset($mensaje_session)) {
    echo $mensaje_session."<br>";
    $id_entidad=$_SESSION['entidad_id'];
    $tipo = $_SESSION['tipo'];
    unset($_SESSION['entidad_id']);
    unset($_SESSION['tipo']);
    unset($_SESSION['mensaje_de_session']);

}

if (!$tipo || !$id_entidad) {
    die('faltan parametros');
}



?>


<script src="modal_nuevo.js">

</script>