// Modal para ver detalles del Instructor

document.addEventListener("DOMContentLoaded", () => {
    const modalVer = document.getElementById("modalVerInstructor");
    const cerrarVer = document.getElementById("cerrarVerInstructor");
    const contenidoVer = document.getElementById("contenidoInstructor");

    // Si no existen, no hacemos nada aun.
    if (!modalVer) return;

    // Delegación para botones dinamicos
    document.body.addEventListener("click", e => {
        const btn = e.target.closest(".btnVerInstructor");
        if (btn) {
            const id = btn.dataset.id;
            console.log("Ver Instructor ID:", id);

            fetch(`cargar_instructor.php?id=${id}`)
                .then(r => r.json())
                .then(data => {
                    const i = data; // Asumimos que cargar_instructor devuelve el objeto directo o {instructor: ...}
                    // Adaptar segun respuesta de cargar_instructor.php

                    // Si data tiene error
                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    // Renderizar
                    const instructor = data.instructor || data; // Fallback

                    let html = `
                        <div class="modal-header">
                            <h2>${instructor.nombre} ${instructor.apellido}</h2>
                        </div>

                        <div class="detalle-grid">
                            
                            <div class="detalle-item">
                                <span class="detalle-label">DNI</span>
                                <span class="detalle-valor">${instructor.dni || '-'}</span>
                            </div>

                            <div class="detalle-item">
                                <span class="detalle-label">Teléfono</span>
                                <span class="detalle-valor">${instructor.telefono || '-'}</span>
                            </div>

                            <div class="detalle-item full-width">
                                <span class="detalle-label">Correo</span>
                                <span class="detalle-valor">${instructor.correo || '-'}</span>
                            </div>
                            
                            <!-- Fila 2: Ubicación -->
                            <div class="detalle-item">
                                <span class="detalle-label">Dirección</span>
                                <span class="detalle-valor">${instructor.direccion || '-'}</span>
                            </div>
                            <div class="detalle-item">
                                <span class="detalle-label">Localidad</span>
                                <span class="detalle-valor">${instructor.localidad || '-'} (CP: ${instructor.cp || '-'})</span>
                            </div>

                            <!-- Fila 3: Vehículo -->
                             <div class="detalle-item">
                                <span class="detalle-label">Vehículo</span>
                                <span class="detalle-valor">${instructor.vehiculo || 'No asignado'}</span>
                            </div>
                             <div class="detalle-item">
                                <span class="detalle-label">Patente</span>
                                <span class="detalle-valor">${instructor.patente || '-'}</span>
                            </div>

                            <!-- Fila 4: Observaciones -->
                            <div class="detalle-item full-width">
                                <span class="detalle-label">Observaciones</span>
                                <span class="detalle-valor">${instructor.observaciones || '-'}</span>
                            </div>
                        </div>
                    `;

                    contenidoVer.innerHTML = html;
                    modalVer.style.display = "flex"; // Flex por el CSS nuevo
                })
                .catch(err => console.error("Error cargando instructor:", err));
        }
    });

    if (cerrarVer) {
        cerrarVer.onclick = () => modalVer.style.display = "none";
    }

    window.onclick = (e) => {
        if (e.target === modalVer) modalVer.style.display = "none";
    };
});
