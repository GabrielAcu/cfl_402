const modalfaq = document.getElementById("modalFaq");
const cocodriloFAQ = document.getElementById("btnAbrirFaq");
const cerrarFAQ = document.querySelector(".cerrar");

// Abrir el modal al hacer clic en el botón
cocodriloFAQ.onclick = () => (modalfaq.style.display = "block");

// Cerrar el modal al hacer clic en la "x"
cerrarFAQ.onclick = () => {
  modalfaq.style.display = "none";
};

// Cerrar el modal al hacer clic fuera del contenido del modal

window.onclick = (e) => {
  if (e.target === modalfaq) {
    modalfaq.style.display = "none";
    // modalfaq.style.animation = "fadeOut 1s ease";
  }
};