const modalInstructor = document.getElementById("modalVerAlumno"); // Reusamos el mismo ID de modal si es generico, o debemos revisar modal.php
// Revisando modal.php de instructores, el ID suele ser modalNuevo o similar.
// Vamos a asumir que usamos el mismo modal.php generico o uno especifico.
// Verificaremos modal.php en el siguiente paso, por ahora escribo el codigo logico.

// Ajuste: El modal en instructores/modal.php tiene ID "modalInstructor"? Lo verificare.
// Si no, creare uno dinamico.
// Por seguridad, usaré la clase .modal si es único, o ids especificos.

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

            fetch(`cargar_instructor.php?id_instructor=${id}`)
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
                            
                            <!-- Agregar más campos si existen en la DB (direccion, fecha_nac, etc) -->
                            <!-- Por defecto en instructores solemos tener menos datos que en alumnos -->
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
