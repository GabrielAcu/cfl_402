const modalVer = document.getElementById("modalVerCurso");
const cerrarVer = document.getElementById("cerrarVerCurso");
const contenido = document.getElementById("contenidoCurso");

document.querySelectorAll(".btnModificarAlumno").forEach(btn => {
    btn.addEventListener("click", function () {

        const id = this.dataset.id;
        console.log("ID RECIBIDO →", id);

        // Obtener token CSRF y datos del usuario
        Promise.all([
            fetch('../get_csrf_token.php').then(r => r.json()),
            fetch(`cargar_datos.php?id=${id}`).then(r => r.json())
        ]).then(([csrfData, userData]) => {
            const csrfToken = csrfData.token;
            const a = userData.usuario;   // ← IMPORTANTE
            console.log("USUARIO:", a);

            // Construimos el formulario
            let html = `
                <form action="procesar_modificacion.php" method="POST">
                    <input type="hidden" name="csrf_token" value="${csrfToken}">
                    <input type="hidden" name="id" value="${a.id}">

                    <h2>Modificar Usuario: ${a.nombre} </h2>

                    <div class="fila">
                        <div class="campo">
                            <label for="nombre-usuario"> Nombre de Usuario: </label>
                            <input class="input-modify" type="text" name="nombre" id="nombre-usuario" value="${a.nombre}" placeholder="Nombre" required>
                        </div>

                        <div class="campo">
                            <label for="contrasena-usuario"> Contraseña De Usuario: </label>
                            <input class="input-modify" type="password" name="contrasenia" id="contrasena-usuario" placeholder="Dejar vacío para mantener la actual">
                        </div>

                        <div class="campo">
                            <label for="contrasena-confirmar"> Confirmar Contraseña </label>
                <input class="input-modify" type="password" name="contrasenia-conf" id="contrasena-confirmar" placeholder="Confirmar.." required>
                        </div>

                        <div class="campo">
                            <label for="rol-usuario"> Rol De Usuario: </label>
                            <select name="rol" id="rol-usuario" required>
                                <option value="0" ${a.rol == 0 ? 'selected' : ''}>SuperAdministrador</option>
                                <option value="1" ${a.rol == 1 ? 'selected' : ''}>Administrador</option>
                                <option value="2" ${a.rol == 2 ? 'selected' : ''}>Instructor</option>
                            </select>
                        </div>
                    </div>

                    <div class="form_bottom">
                        <button class="boton_enviar" type="submit">Guardar Cambios</button>
                    </div>

                </form>
            `;

            contenido.innerHTML = html;
            modalVer.style.display = "block"; // ← AQUÍ SE ABRE EL MODAL
        })
        .catch(e => console.error("ERROR FETCH:", e));
    });
});

// Cerrar modal
cerrarVer.onclick = () => modalVer.style.display = "none";

window.onclick = (e) => {
    if (e.target === modalVer) modalVer.style.display = "none";
};
