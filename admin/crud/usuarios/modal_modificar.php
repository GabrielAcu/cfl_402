<div id="modalModificarUsuario" class="modal">
    <div class="modal-content">
        <span class="cerrar" id="cerrarModificar">&times;</span>
        <h2>Modificar Usuario</h2>

        <form action="procesar_modificacion.php" method="POST" id="formModificarUsuario">
            <?php
            require_once dirname(__DIR__, 3) . '/config/path.php';
            require_once BASE_PATH . '/config/csrf.php';
            echo getCSRFTokenField();
            ?>
            
            <input type="hidden" name="id" id="mod_id">

            <div class="fila">
                <div class="campo">
                    <label for="mod_nombre">Nombre de Usuario</label>
                    <input type="text" name="nombre" id="mod_nombre" required>
                </div>
            </div>

            <div class="fila">
                <div class="campo">
                    <label for="mod_rol">Rol</label>
                    <select name="rol" id="mod_rol" required>
                        <option value="0">SuperAdministrador</option>
                        <option value="1">Administrador</option>
                        <option value="2">Instructor</option>
                    </select>
                </div>
            </div>

            <hr style="margin: 20px 0; border: 0; border-top: 1px solid #ccc;">
            <p style="font-size: 0.9em; color: #666; margin-bottom: 10px;">Dejar en blanco para mantener la contraseña actual.</p>

            <div class="fila">
                <div class="campo">
                    <label for="mod_contrasenia">Nueva Contraseña</label>
                    <input type="password" name="contrasenia" id="mod_contrasenia" placeholder="Nueva contraseña (opcional)">
                </div>
                <div class="campo">
                    <label for="mod_contrasenia_conf">Confirmar Contraseña</label>
                    <input type="password" name="contrasenia-conf" id="mod_contrasenia_conf" placeholder="Repetir contraseña">
                </div>
            </div>

            <div class="modal-buttons">
                <button type="button" class="btn-cancel" onclick="document.getElementById('modalModificarUsuario').style.display='none'">Cancelar</button>
                <button type="submit" class="btn-submit">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>
