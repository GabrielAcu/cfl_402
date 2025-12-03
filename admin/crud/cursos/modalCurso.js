const modal = document.getElementById("modalCurso");
const btn = document.getElementById("btnAbrirModal");
const cerrar = document.querySelector(".cerrar");

// Abrir el modal al hacer clic en el botón
btn.onclick = () => (modal.style.display = "block");

// Cerrar el modal al hacer clic en la "x"
cerrar.onclick = () => {
  modal.style.display = "none";
};

// Cerrar el modal al hacer clic fuera del contenido del modal

window.onclick = (e) => {
  if (e.target === modal) {
    modal.style.display = "none";
  }
};
