const modal = document.getElementById("modalInstructor");
const boton = document.getElementById("btnAbrirModal");
const cerrar = document.querySelector(".cerrar");

// Abrir el modal al hacer clic en el botón
if (boton) {
  boton.onclick = () => {
    // Resetear formulario para nuevo instructor
    const form = document.getElementById("formInstructor");
    form.reset();
    document.getElementById("id_instructor_edit").value = '';
    document.getElementById("modalTitulo").textContent = "Nuevo Instructor";
    form.action = "agregar_instructor.php";
    document.getElementById("btnGuardar").textContent = "Guardar";
    modal.style.display = "block";
  };
}

// Cerrar el modal al hacer clic en la "x"
if (cerrar) {
  cerrar.onclick = () => {
    cerrarModal();
  };
}

// Cerrar el modal al hacer clic fuera del contenido del modal
window.onclick = (e) => {
  if (e.target === modal) {
    cerrarModal();
  }
};
