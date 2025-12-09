<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <link rel="stylesheet" href="modal.css">
</body>
</html>

<div id="modalAlumno" class="modal">
    <div class="modal-content">
    <span class="cerrar">&times;</span>
    <h2>Nuevo Usuario</h2>

    <form class="new-form"  id="formCurso" action="crear.php" method="POST">
            <?php
            require_once dirname(__DIR__, 3) . '/config/path.php';
            require_once BASE_PATH . '/config/csrf.php';
            echo getCSRFTokenField();
            ?>

            <h3> Información Del Usuario </h3> 

        <div class='fila'>
            <div class="campo">
                <label for="nombre-usuario"> Nombre de Usuario: </label>
                <input class="input-modify" type="text" name="nombre" id="nombre-usuario" placeholder="Nombre" required>
            </div>
            <div class="campo">
                <label for="contrasena-usuario"> Contraseña De Usuario: </label>
                <input class="input-modify" type="password" name="contrasenia" id="contrasena-usuario" placeholder="Contraseña.." required>
            </div>
            <div class="campo">
                <label for="contrasena-confirmar"> Confirmar Contraseña </label>
                <input class="input-modify" type="password" name="contrasenia-conf" id="contrasena-confirmar" placeholder="Confirmar.." required>
            </div>

            <div class="campo">
                <label for="rol-usuario"> Rol De Usuario: </label>
                <select name="rol" id="rol-usuario" required>
                    <option value="0"> SuperAdministrador</option>
                    <option value="1"> Administrador </option>
                    <option value="2"> Instructor </option>

                </select>
            </div>
        </div>
        
        
        <div class='form_bottom'>

            

            <input type="submit" value="Guardar" class="btn-submit">
        </div>
        </div>
       
    </form> 
</div>