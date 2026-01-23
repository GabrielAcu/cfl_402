document.addEventListener("DOMContentLoaded", () => {
    // Si tienes modalDetalles.php para curso, asegúrate que el ID sea modalVerCurso
    // En alumnos usaste modalVerAlumno, instructores modalVerInstructor.
    // Usaremos modalVerCurso aquí.
    const modalVer = document.getElementById("modalVerCurso");
    const cerrarVer = document.getElementById("cerrarVerCurso");
    const contenidoVer = document.getElementById("contenidoCurso");

    if (!modalVer) return;

    // Delegación
    document.body.addEventListener("click", e => {
        const btn = e.target.closest(".btnVerCurso");
        if (btn) {
            const id = btn.dataset.id;
            console.log("Ver Curso ID:", id);

            fetch(`cargar_curso.php?id=${id}`)
                .then(r => r.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    const c = data.curso;
                    const h = data.horarios; // Array de horarios

                    // Formatear horarios
                    let horariosHtml = '';
                    if (h && h.length > 0) {
                        horariosHtml = h.map(hor =>
                            `<div><strong>${hor.dia}:</strong> ${hor.hora_desde} a ${hor.hora_hasta}</div>`
                        ).join('');
                    } else {
                        horariosHtml = 'Sin horarios asignados';
                    }

                    let html = `
                        <div class="modal-header">
                            <h2>${c.nombre_curso} (${c.codigo})</h2>
                        </div>

                        <div class="detalle-grid">
                            
                            <div class="detalle-item">
                                <span class="detalle-label">Turno</span>
                                <span class="detalle-valor">${c.turno_desc || '-'}</span>
                            </div>

                            <div class="detalle-item">
                                <span class="detalle-label">Cupo</span>
                                <span class="detalle-valor">${c.cupo || '-'}</span>
                            </div>

                            <div class="detalle-item full-width">
                                <span class="detalle-label">Instructor</span>
                                <span class="detalle-valor">${c.ape_ins ? (c.ape_ins + ', ' + c.nom_ins) : 'Sin asignar'}</span>
                            </div>

                            <div class="detalle-item full-width">
                                <span class="detalle-label">Descripción</span>
                                <span class="detalle-valor">${c.descripcion || '-'}</span>
                            </div>

                            <div class="detalle-item full-width">
                                <span class="detalle-label">Horarios</span>
                                <div class="detalle-valor" style="background: rgba(255,255,255,0.05);">
                                    ${horariosHtml}
                                </div>
                            </div>

                        </div>
                    `;

                    contenidoVer.innerHTML = html;
                    modalVer.style.display = "flex";
                })
                .catch(err => console.error("Error cargando curso:", err));
        }
    });

    if (cerrarVer) {
        cerrarVer.onclick = () => modalVer.style.display = "none";
    }

    window.onclick = (e) => {
        if (e.target === modalVer) modalVer.style.display = "none";
    };
});