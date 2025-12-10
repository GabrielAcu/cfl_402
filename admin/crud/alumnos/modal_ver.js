const modalAlumno = document.getElementById("modalVerAlumno");
const cerrarAlumno = document.getElementById("cerrarVerAlumno");
const contenidoAlumno = document.getElementById("contenidoAlumno");

// Delegación de eventos
document.querySelectorAll(".btnVerCurso").forEach(btn => {
    btn.addEventListener("click", function () {

        const id = this.dataset.id;
        console.log("ID RECIBIDO →", id);

        fetch("cargar_datos.php?id_alumno=" + id)
            .then(r => r.json())
            .then(data => {

                const a = data.alumno;   // ← IMPORTANTE
                console.log("ALUMNO:", a);

                // Obtener token CSRF y construir formulario
                Promise.all([
                    fetch('../get_csrf_token.php').then(r => r.json())
                ]).then(([csrfData]) => {
                    const csrfToken = csrfData.token;
                    
                    // Construimos el formulario
                    let html = `
                        
                            <input type="hidden" name="csrf_token" value="${csrfToken}">
                            <input type="hidden" name="id_alumno" value="${a.id_alumno}">

                            <h2>Información Del Alumno: ${a.nombre} ${a.apellido} </h2>

                        <div class="fila">
                            <div class="campo">
                                Nombre: ${a.nombre}
                            </div>

                            <div class="campo">
                                Apellido: ${a.apellido}
                            </div>
                        </div>

                        <div class="fila">
                            <div class="campo">
                               DNI: ${a.dni}
                            </div>

                            <div class="campo">
                               Correo: ${a.nombre}
                            </div>
                        </div>

                        <div class="fila">
                            <div class="campo">
                                Télefono: ${a.telefono}
                            </div>

                            <div class="campo">
                               Fecha de Nacimiento: ${a.fecha_nacimiento}
                            </div>
                        </div>

                        <div class="fila">
                            <div class="campo">
                               Dirección: ${a.direccion}
                            </div>

                            <div class="campo">
                                Localidad: ${a.localidad}
                            </div>
                        </div>

                        <div class="fila">
                            <div class="campo">
                                Código Postal: ${a.cp}
                            </div>
                        </div>

                        <div class="fila">
                            <div class="campo">
                                Vehículo: ${a.vehiculo}
                            </div>

                            <div class="campo">
                                Patente: ${a.patente}
                            </div>
                        </div>

                        <div class="fila">
                            <div class="campo">
                                Observaciones: ${a.observaciones}
                            </div>
                        </div>

                    
                       
                    `;

                    contenidoAlumno.innerHTML = html;
                    modalAlumno.style.display = "block"; // ← AQUÍ SE ABRE EL MODAL
                })
                .catch(e => console.error("ERROR CSRF:", e));
            })
            .catch(e => console.error("ERROR FETCH:", e));
    });
});

// Cerrar modal
cerrarAlumno.onclick = () => modalAlumno.style.display = "none";

window.onclick = (e) => {
    if (e.target === modalAlumno) modalAlumno.style.display = "none";
};

