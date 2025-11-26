  <?php
    include_once __DIR__ . '/../../../config/conexion.php';
    $conn = conectar();
    if ($_SERVER["REQUEST_METHOD"]=="POST"){ // verificar que el método de solicitud sea POST
        $conn=conectar(); 
        $id_alumno=$_POST["id_alumno"]; // obtener el id_alumno enviado desde el formulario
        // texto de la consulta SQL con marcador de posición
        $texto="SELECT * FROM alumnos WHERE id_alumno=:id_alumno"; 
        $consulta=$conn->prepare($texto); // preparar la consulta
        $consulta->bindParam(':id_alumno',$id_alumno); // vincular el parámetro
        $consulta->execute(); // ejecutar la consulta

        $alumno=$consulta->fetch(); // obtener el registro del alumno
        if ($alumno){ // si el alumno existe, mostrar el formulario de modificación con los datos actuales
            echo "

    <link rel='stylesheet' href='alumnos.css'>


            <main class='modify-alumno'>


                <h2>Modificar Alumno: $alumno[nombre] $alumno[apellido] </h2>
                <form class='new-form' action='../../crud/alumnos/procesar_modificacion.php' method='POST'>

                    

                    <h3> Información Personal </h3> 

                    <input class='input-modify' type='hidden' name='id_alumno' value=$id_alumno'>

                   <div class='fila'>
                        <div class='campoo'>
                            <label for='nombre-alumno'> Nombre de Alumno: </label>
                            <input class='input-modify' type='text' name='nombre' id='dni-alumno' placeholder='Nombre' value=$alumno[nombre]>
                        </div>
                        <div class='campoo'>
                            <label for='apellido-alumno'> Apellido de Alumno: </label>
                            <input class='input-modify' type='text' name='apellido' id='dni-alumno' placeholder='Apellido'  value=$alumno[apellido]>
                        </div>
                    </div>
                    
                    <div class='fila'>
                        <div class='campoo'>
                            <label for='dni-alumno'> DNI de Alumno: </label>
                            <input class='input-modify' type='number'  id='dni-alumno' name='dni' placeholder='DNI'  value=$alumno[dni]>
                        </div>
                    

                        <div class='campoo'>
                            <label for='email-alumno'> Email de Alumno: </label>
                            <input class='input-modify' type='text' id='email-alumno' name='email' placeholder='Teléfono'  value=$alumno[correo]>
                        </div>
                    </div>



                    <div class='fila'>
                        <div class='campoo'>
                            <label for='telefono-alumno'> Télefono de Alumno: </label>
                            <input class='input-modify' type='text' id='telefono-alumno' name='telefono' placeholder='Teléfono'  value=$alumno[telefono]>
                        </div>
                    

                        <div class='campoo'>
                            <label for='fecha-alumno'> Fecha De Nacimiento: </label>
                            <input class='input-modify' type='date' id='fecha-alumno' name='nacimiento' placeholder='Teléfono'  value=$alumno[fecha_nacimiento]>
                        </div>
                    </div>

                    <div class='fila'>
                        <div class='campoo'>
                            <label for='domicilio-alumno'> Domicilio: </label>
                            <input class='input-modify' type='text' id='domicilio-alumno' name='domicilio' placeholder='Teléfono'  value=$alumno[direccion]>
                        </div>
                    

                        <div class='campoo'>
                            <label for='localidad-alumno'> Localidad: </label>
                            <input class='input-modify' type='text' id='localidad-alumno' name='localidad' placeholder='Teléfono'  value=$alumno[localidad]>
                        </div>
                    </div>

                    <div class='fila'>
                        <div class='campoo'>
                            <label for='postal-alumno'> Código Postal: </label>
                            <input class='input-modify' type='text' id='postal-alumno' name='postal' placeholder='Teléfono'  value=$alumno[cp]>
                        </div>
                    </div>

                    <div class='fila'>
                        <div class'campoo'>
                            <label for='auto-alumno'> Modelo de Auto (si posee): </label>
                            <input class='input-modify' type='text' id='auto-alumno' name='autos' placeholder='Teléfono'  value=$alumno[vehiculo]>
                        </div>
                    

                        <div class='campoo'>
                            <label for='patente-alumno'> Patente: </label>
                            <input class='input-modify' type='text' id='patente-alumno' name='patente' placeholder='Teléfono'  value=$alumno[patente]>
                        </div>
                    </div>

                    <div class='fila'>
                        <div class='campoo'>
                            <label for='observaciones-alumno'> Observaciones: </label>
                            <input class='input-modify' 'type='text' id='observaciones-alumno' name='observaciones' placeholder='Teléfono'  value=$alumno[observaciones]>
                        </div>
                    

                    
                    </div>

                    <div class='form_bottom'>

                        <button class='boton_volver'> <a class='cancel_link' href='/cfl_402/cruds/crud_alumnos/crud_alumnos.php'> Cancelar </a> </button>

                        <button class='boton_enviar' type='submit'> Guardar Cambios </button>
                    </div>

                </form>;
            
            

            </main>'
            
            </body>
            </html>"; // cuando se envíe el formulario, los datos se enviarán a procesar_modificacion.php mediante el método POST
        } else { // si el alumno no existe, mostrar mensaje de error
            echo "<p class='error'>El alumno no existe</p>";
        }
    } else { // si no es método POST, mostrar mensaje de error
        echo "<h1 class='error'>Aha pillín!!!</h1>";
        echo "<p>$_SERVER[REQUEST_METHOD]</p>";
    }


        

?>


<!-- // <hr>

                    // <div class='cs'>
                    //      <label class='switch' for='activo'> Activo </label>
                    //      <input class='input-modify' type='checkbox' name='activo' checked>
                    //      <span class='slider'></span>
                    //     <span> Activo </span>
                    // </div class='campoo'>  --> 

