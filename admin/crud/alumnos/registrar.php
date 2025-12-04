<?php
require_once dirname(__DIR__, 3) . '/config/path.php';


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
                <textarea class="input-modify" name="observaciones" id="observaciones-alumno" placeholder="Observaciones">

                </textarea>
            </div>
        
        </div> 
        
        <div class='form_bottom'>

            <button class='boton_volver'> <a class='cancel_link' href='index.php'> Cancelar </a> </button>

            <button class='boton_enviar' type='submit'> Registrar </button>
        </div>
       
            </form> 

        </main>
    
    </body>
</html>