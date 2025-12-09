document.querySelectorAll(".btnModificarInstructor").forEach((btn) => {
  btn.addEventListener("click", () => {
    const id = btn.dataset.id;

    // Cargar datos vía fetch
    fetch(`cargar_instructor.php?id=${id}`)
      .then((r) => {
        if (!r.ok) {
          throw new Error('Error al cargar instructor');
        }
        return r.json();
      })
      .then((data) => {
        if (data.error) {
          alert('Error: ' + data.error);
          return;
        }

        // Completar el modal con los datos
        document.getElementById("id_instructor_edit").value = data.id_instructor;
        document.getElementById("nombre").value = data.nombre || '';
        document.getElementById("apellido").value = data.apellido || '';
        document.getElementById("dni").value = data.dni || '';
        document.getElementById("fecha_nacimiento").value = data.fecha_nacimiento || '';
        document.getElementById("telefono").value = data.telefono || '';
        document.getElementById("correo").value = data.correo || '';
        document.getElementById("direccion").value = data.direccion || '';
        document.getElementById("localidad").value = data.localidad || '';
        document.getElementById("cp").value = data.cp || '';
        document.getElementById("vehiculo").value = data.vehiculo || '';
        document.getElementById("patente").value = data.patente || '';
        document.getElementById("observaciones").value = data.observaciones || '';

        // Cambiar título y acción del formulario
        document.getElementById("modalTitulo").textContent = "Modificar Instructor";
        document.getElementById("formInstructor").action = "procesar_modificacion_instructor.php";
        document.getElementById("btnGuardar").textContent = "Guardar Cambios";

        // Abrir modal
        document.getElementById("modalInstructor").style.display = "block";
      })
      .catch((error) => {
        console.error('Error:', error);
        alert('Error al cargar los datos del instructor');
      });
  });
});

// Función para cerrar modal y resetear formulario
function cerrarModal() {
  const modal = document.getElementById("modalInstructor");
  modal.style.display = "none";
  
  // Resetear formulario
  const form = document.getElementById("formInstructor");
  form.reset();
  document.getElementById("id_instructor_edit").value = '';
  document.getElementById("modalTitulo").textContent = "Nuevo Instructor";
  form.action = "agregar_instructor.php";
  document.getElementById("btnGuardar").textContent = "Guardar";
}
