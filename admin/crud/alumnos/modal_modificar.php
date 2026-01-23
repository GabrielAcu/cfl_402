<?php
// modal_modificar.php: Modal para editar datos de un alumno
?>
<div id="modalModificarAlumno" class="modal">
    <div class="modal-content">
        <span class="cerrar" id="cerrarModificar">&times;</span>
        <h2>Modificar Alumno</h2>

        <form action="procesar_modificacion.php" method="POST" id="formModificarAlumno" class="new-form">
            <?php
            require_once dirname(__DIR__, 3) . '/config/path.php';
            require_once BASE_PATH . '/config/csrf.php';
            echo getCSRFTokenField();
            ?>
            
            <input type="hidden" name="id_alumno" id="mod_id_alumno">

            <div class="fila">
                <div class="campo">
                    <label for="mod_nombre">Nombre</label>
                    <input class="input-modify" type="text" name="nombre" id="mod_nombre" required>
                </div>
                <div class="campo">
                    <label for="mod_apellido">Apellido</label>
                    <input class="input-modify" type="text" name="apellido" id="mod_apellido" required>
                </div>
            </div>

            <div class="fila">
                <div class="campo">
                    <label for="mod_dni">DNI</label>
                    <input class="input-modify" type="text" name="dni" id="mod_dni" required>
                </div>
                <div class="campo">
                    <label for="mod_telefono">Teléfono</label>
                    <input class="input-modify" type="text" name="telefono" id="mod_telefono" required>
                </div>
            </div>

            <div class="fila">
                <div class="campo">
                    <label for="mod_correo">Email</label>
                    <input class="input-modify" type="email" name="correo" id="mod_correo" required>
                </div>
                <div class="campo">
                    <label for="mod_fecha_nacimiento">Fecha Nacimiento</label>
                    <input class="input-modify" type="date" name="fecha_nacimiento" id="mod_fecha_nacimiento" required>
                </div>
            </div>

            <div class="fila">
                <div class="campo">
                    <label for="mod_direccion">Dirección</label>
                    <input class="input-modify" type="text" name="direccion" id="mod_direccion" required>
                </div>
                <div class="campo">
                    <label for="mod_localidad">Localidad</label>
                    <input class="input-modify" type="text" name="localidad" id="mod_localidad" required>
                </div>
                <!-- CP -->
                <div class="campo" style="max-width: 100px;">
                    <label for="mod_cp">CP</label>
                    <input class="input-modify" type="text" name="cp" id="mod_cp" required>
                </div>
            </div>

            <div class="fila">
                <div class="campo">
                    <label for="mod_vehiculo">Vehículo</label>
                    <input class="input-modify" type="text" name="vehiculo" id="mod_vehiculo">
                </div>
                <div class="campo">
                    <label for="mod_patente">Patente</label>
                    <input class="input-modify" type="text" name="patente" id="mod_patente">
                </div>
            </div>

            <div class="campo">
                <label for="mod_observaciones">Observaciones</label>
                <textarea class="input-modify" name="observaciones" id="mod_observaciones" rows="3"></textarea>
            </div>

            <div class="form_bottom">
                <input type="submit" value="Guardar Cambios" class="btn-submit">
            </div>
        </form>
    </div>
</div>
