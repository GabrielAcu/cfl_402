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
                    // Construimos el HTML Estructurado y Profesional
                    let html = `
                        <div class="modal-header">
                            <h2>${a.nombre} ${a.apellido}</h2>
                            <!-- Cerrar movido al header por CSS o mantenido fuera -->
                        </div>

                        <div class="detalle-grid">
                            
                            <div class="detalle-item">
                                <span class="detalle-label">DNI</span>
                                <span class="detalle-valor">${a.dni || '-'}</span>
                            </div>

                            <div class="detalle-item">
                                <span class="detalle-label">Fecha de Nacimiento</span>
                                <span class="detalle-valor">${a.fecha_nacimiento || '-'}</span>
                            </div>

                            <div class="detalle-item">
                                <span class="detalle-label">Teléfono</span>
                                <span class="detalle-valor">${a.telefono || '-'}</span>
                            </div>

                             <div class="detalle-item">
                                <span class="detalle-label">Correo</span>
                                <span class="detalle-valor">${a.correo || '-'}</span>
                            </div>

                            <div class="detalle-item full-width">
                                <span class="detalle-label">Dirección</span>
                                <span class="detalle-valor">${a.direccion || '-'}</span>
                            </div>

                            <div class="detalle-item">
                                <span class="detalle-label">Localidad</span>
                                <span class="detalle-valor">${a.localidad || '-'}</span>
                            </div>

                            <div class="detalle-item">
                                <span class="detalle-label">Código Postal</span>
                                <span class="detalle-valor">${a.cp || '-'}</span>
                            </div>

                            <div class="detalle-item">
                                <span class="detalle-label">Vehículo</span>
                                <span class="detalle-valor">${a.vehiculo || 'No'}</span>
                            </div>

                            <div class="detalle-item">
                                <span class="detalle-label">Patente</span>
                                <span class="detalle-valor">${a.patente || '-'}</span>
                            </div>

                            <div class="detalle-item full-width">
                                <span class="detalle-label">Observaciones</span>
                                <span class="detalle-valor">${a.observaciones || 'Sin observaciones'}</span>
                            </div>

                        </div>
                    `;

                    contenidoAlumno.innerHTML = html;
                    modalAlumno.style.display = "block";
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

