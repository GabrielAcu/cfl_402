const modalVer = document.getElementById("modalVerCurso");
const cerrarVer = document.getElementById("cerrarVerCurso");
const contenido = document.getElementById("contenidoCurso");

document.querySelectorAll(".btnModificarAlumno").forEach(btn => {
    btn.addEventListener("click", function () {

        const id = this.dataset.id;
        console.log("ID RECIBIDO →", id);

        fetch("cargar_datos.php?id_alumno=" + id)
            .then(r => r.json())
            .then(data => {

                const a = data.alumno;   // ← IMPORTANTE
                console.log("ALUMNO:", a);

                // Construimos el formulario
                let html = `
                    <form action="procesar_modificacion.php" method="POST">

                        <input type="hidden" name="id_alumno" value="${a.id_alumno}">

                            <h2>Modificar Alumno: ${a.nombre} ${a.apellido} </h2>

                        <div class="fila">
                            <div class="campo">
                                <label>Nombre:</label>
                                <input class="input-modify" type="text" name="nombre" value="${a.nombre}">
                            </div>

                            <div class="campo">
                                <label>Apellido:</label>
                                <input class="input-modify" type="text" name="apellido" value="${a.apellido}">
                            </div>
                        </div>

                        <div class="fila">
                            <div class="campo">
                                <label>DNI:</label>
                                <input class="input-modify" type="number" name="dni" value="${a.dni}">
                            </div>

                            <div class="campo">
                                <label>Email:</label>
                                <input class="input-modify" type="text" name="correo" value="${a.correo}">
                            </div>
                        </div>

                        <div class="fila">
                            <div class="campo">
                                <label>Teléfono:</label>
                                <input class="input-modify" type="text" name="telefono" value="${a.telefono}">
                            </div>

                            <div class="campo">
                                <label>Fecha Nacimiento:</label>
                                <input class="input-modify" type="date" name="fecha_nacimiento" value="${a.fecha_nacimiento}">
                            </div>
                        </div>

                        <div class="fila">
                            <div class="campo">
                                <label>Domicilio:</label>
                                <input class="input-modify" type="text" name="direccion" value="${a.direccion}">
                            </div>

                            <div class="campo">
                                <label>Localidad:</label>
                                <input class="input-modify" type="text" name="localidad" value="${a.localidad}">
                            </div>
                        </div>

                        <div class="fila">
                            <div class="campo">
                                <label>Código Postal:</label>
                                <input class="input-modify" type="text" name="cp" value="${a.cp}">
                            </div>
                        </div>

                        <div class="fila">
                            <div class="campo">
                                <label>Vehículo:</label>
                                <input class="input-modify" type="text" name="vehiculo" value="${a.vehiculo}">
                            </div>

                            <div class="campo">
                                <label>Patente:</label>
                                <input class="input-modify" type="text" name="patente" value="${a.patente}">
                            </div>
                        </div>

                        <div class="fila">
                            <div class="campo">
                                <label>Observaciones:</label>
                                <textarea class="input-modify" name="observaciones">${a.observaciones}</textarea>
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

