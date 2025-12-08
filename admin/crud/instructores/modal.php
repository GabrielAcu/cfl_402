<!-- Modal para Nuevo/Editar Instructor -->
<div id="modalInstructor" class="modal">
    <div class="modal-content">
        <span class="cerrar">&times;</span>
        <h2 id="modalTitulo">Nuevo Instructor</h2>

        <form class="new-form" action="agregar_instructor.php" method="POST" id="formInstructor">
            <input type="hidden" name="id_instructor" id="id_instructor_edit">

            <h3>Información Personal</h3>
            
            <div class="fila">
                <div class="campo">
                    <label for="nombre">Nombre *</label>
                    <input class="input-modify" type="text" name="nombre" id="nombre" required>
                </div>
                <div class="campo">
                    <label for="apellido">Apellido *</label>
                    <input class="input-modify" type="text" name="apellido" id="apellido" required>
                </div>
            </div>

            <div class="fila">
                <div class="campo">
                    <label for="dni">DNI *</label>
                    <input class="input-modify" type="number" name="dni" id="dni" required>
                </div>
                <div class="campo">
                    <label for="fecha_nacimiento">Fecha de Nacimiento *</label>
                    <input class="input-modify" type="date" name="fecha_nacimiento" id="fecha_nacimiento" required>
                </div>
            </div>

            <h3>Información de Contacto</h3>

            <div class="fila">
                <div class="campo">
                    <label for="telefono">Teléfono *</label>
                    <input class="input-modify" type="text" name="telefono" id="telefono" required>
                </div>
                <div class="campo">
                    <label for="correo">Correo Electrónico *</label>
                    <input class="input-modify" type="email" name="correo" id="correo" required>
                </div>
            </div>

            <h3>Dirección</h3>

            <div class="fila">
                <div class="campo">
                    <label for="direccion">Dirección *</label>
                    <input class="input-modify" type="text" name="direccion" id="direccion" required>
                </div>
                <div class="campo">
                    <label for="localidad">Localidad *</label>
                    <input class="input-modify" type="text" name="localidad" id="localidad" required>
                </div>
            </div>

            <div class="fila">
                <div class="campo">
                    <label for="cp">Código Postal *</label>
                    <input class="input-modify" type="text" name="cp" id="cp" maxlength="8" required>
                </div>
            </div>

            <h3>Información del Vehículo</h3>

            <div class="fila">
                <div class="campo">
                    <label for="vehiculo">Vehículo *</label>
                    <input class="input-modify" type="text" name="vehiculo" id="vehiculo" required>
                </div>
                <div class="campo">
                    <label for="patente">Patente *</label>
                    <input class="input-modify" type="text" name="patente" id="patente" maxlength="10" required>
                </div>
            </div>

            <div class="fila">
                <div class="campo">
                    <label for="observaciones">Observaciones</label>
                    <textarea class="input-modify" name="observaciones" id="observaciones" rows="3"></textarea>
                </div>
            </div>

            <div class="modal-buttons">
                <button type="button" class="btn-cancel" onclick="cerrarModal()">Cancelar</button>
                <button type="submit" class="btn-primary" id="btnGuardar">Guardar</button>
            </div>
        </form>
    </div>
</div>
