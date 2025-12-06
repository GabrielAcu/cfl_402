const modalVer = document.getElementById("modalVerCurso");
const cerrarVer = document.getElementById("cerrarVerCurso");
const contenido = document.getElementById("contenidoCurso");

// Delegación de eventos
document.addEventListener("click", async (e) => {

    const btn = e.target.closest(".btnVerCurso");
    if (!btn) return;  // no clickeó un botón válido

    const id = btn.dataset.id;

    const info = await fetch("cargar_curso.php?id=" + id)
        .then(res => res.json());

    let html = `
        <p><strong>Código:</strong> ${info.curso.codigo}</p>
        <p><strong>Nombre:</strong> ${info.curso.nombre_curso}</p>
        <p><strong>Descripción:</strong> ${info.curso.descripcion}</p>
        <p><strong>Cupo:</strong> ${info.curso.cupo}</p>
        <p><strong>Instructor:</strong> ${info.curso.ape_ins}, ${info.curso.nom_ins}</p>
        <p><strong>Turno:</strong> ${info.curso.turno_desc}</p>
        <h3>Horarios</h3>
        <ul>
    `;

    info.horarios.forEach(h => {
        html += `<li>${h.dia}: ${h.hora_desde} - ${h.hora_hasta}</li>`;
    });

    html += "</ul>";

    contenido.innerHTML = html;
    modalVer.style.display = "block";
});

// Cerrar modal
cerrarVer.onclick = () => modalVer.style.display = "none";
window.onclick = (e) => {
    if (e.target === modalVer) modalVer.style.display = "none";
};
