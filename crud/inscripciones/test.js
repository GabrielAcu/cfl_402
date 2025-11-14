document.addEventListener("DOMContentLoaded", () => {
  const modal = document.getElementById("modal");
  const detalle = document.getElementById("detalle-modal");
  const cerrar = document.getElementById("cerrar-modal");

  document.querySelectorAll("a.icon.view").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      const tr = e.target.closest("tr");
      detalle.innerHTML = `
          <p><strong>Nombre:</strong> ${tr.cells[0].textContent}</p>
          <p><strong>Curso:</strong> ${tr.cells[1].textContent}</p>
          <p><strong>Fecha:</strong> ${tr.cells[2].textContent}</p>
          <p><strong>Observaciones:</strong> ${tr.dataset.observaciones}</p>
          <p><strong>ID Alumno:</strong> ${tr.dataset.idAlumno}</p>
          <p><strong>ID Curso:</strong> ${tr.dataset.idCurso}</p>
        `;
      modal.style.display = "block";
    });
  });

  cerrar.addEventListener("click", () => (modal.style.display = "none"));

  window.addEventListener("click", (e) => {
    if (e.target == modal) modal.style.display = "none";
  });
});
